<?php

namespace Drupal\tufting_customization\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountSwitcherInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Theme\ThemeManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class TuftingTimeController extends ControllerBase {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The account switcher.
   *
   * @var \Drupal\Core\Session\AccountSwitcherInterface
   */
  protected $accountSwitcher;

  /**
   * The theme manager.
   *
   * @var \Drupal\Core\Theme\ThemeManagerInterface
   */
  protected $themeManager;

  /**
   * Constructs an AutoLoginController object.
   */
  public function __construct(
    AccountProxyInterface $current_user,
    EntityTypeManagerInterface $entity_type_manager,
    AccountSwitcherInterface $account_switcher,
    ThemeManagerInterface $theme_manager
  ) {
    $this->currentUser = $current_user;
    $this->entityTypeManager = $entity_type_manager;
    $this->accountSwitcher = $account_switcher;
    $this->themeManager = $theme_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('entity_type.manager'),
      $container->get('account_switcher'),
      $container->get('theme.manager')
    );
  }

  public function branchGallery($nid) {

    $branch_nid = $nid;

    $branch_node = Node::load($branch_nid);
    //dump($branch_node->get('field_phone')->getlistedItems());
    //exit;
    $branch_name = $branch_node->getTitle();
    $branch_address = $branch_node->get('field_address')->value;
    $branch_map_url = $branch_node->get('field_map_url')->value;
    $branch_phone = $branch_node->get('field_phone');
    $branch_email = $branch_node->get('field_email');
    $branch_whatsapp = $branch_node->get('field_whatsapp_link')->value;
    $branch_info = [
      'name' => $branch_name,
      'address' => $branch_address,
      'map_url' => $branch_map_url,
      'phone' => $branch_phone,
      'email' => $branch_email,
      'whatsapp' => $branch_whatsapp,
    ];

    $query = \Drupal::entityQuery('node')
      ->condition('type', 'branch_gallery')
      ->condition('status', 1)
      ->condition('field_branch', $branch_nid)
      ->sort('created', 'DESC')
      ->accessCheck(TRUE);

    $nids = $query->execute();
   // dump($nids);exit;
    $nodes = Node::loadMultiple($nids);
    $entity_type_manager = \Drupal::entityTypeManager();
    $file_url_generator = \Drupal::service('file_url_generator');
    $media_storage = $entity_type_manager->getStorage('media');
    $image_urls = [];
    $video_urls = [];
    foreach ($nodes as $node) {

      $field_name = 'field_video_file';
      if ($node->hasField($field_name) && !$node->get($field_name)->isEmpty()) {
        $file_storage = \Drupal::entityTypeManager()->getStorage('file');
        $play_str = '';
          if ($node->hasField('field_autoplay_video') && !$node->get('field_autoplay_video')->isEmpty() && $node->get('field_autoplay_video')->value == 1) {
            $play_str .= 'autoplay ';
          }
          if ($node->hasField('field_loop_video') && !$node->get('field_loop_video')->isEmpty() && $node->get('field_loop_video')->value == 1) {
            $play_str .= 'loop ';
          }
          if ($node->hasField('field_mute_video') && !$node->get('field_mute_video')->isEmpty() && $node->get('field_mute_video')->value == 1) {
            $play_str .= 'muted ';
          }
        foreach ($node->get($field_name) as $item) {
            $fid = $item->target_id;
            $file = $file_storage->load($fid);
            if ($file) {
                $video_urls[] = [
                  'video_mime' => $file->getMimeType() ?: 'video/mp4',
                  'play_str' => $play_str,
                  'video_url' => $file_url_generator->generateAbsoluteString($file->getFileUri()),
                ];
            }
        }
      }





      $gallery_field_name = 'field_gallery_image';
      $media_image_field_name = 'field_media_image';
      if ($node->hasField($gallery_field_name) && !$node->get($gallery_field_name)->isEmpty()) {
          foreach ($node->get($gallery_field_name) as $media_ref_item) {
            $media = $media_ref_item->entity;
            if ($media && $media->hasField($media_image_field_name) && !$media->get($media_image_field_name)->isEmpty()) {
                $image_field_item = $media->get($media_image_field_name)->first();
                $media_file = $image_field_item->entity;
                if ($media_file) {
                    $image_urls[] = $media_file->createFileUrl(FALSE);
                }
            }
        }


      }


    }


    $data['videos'] = $video_urls;
    $data['images'] = $image_urls;
    $data['branch_info'] = $branch_info;

    //dump($data);exit;

      return [
        '#theme' => 'branch_gallery_page',
        '#data' => $data,
        '#attached' => [
          'library' => [
            'tufting_customization/branch_gallery_styles'
          ],
        ],
      ];


  }

  public function publicationList() {
    $items = [];

    $query = \Drupal::entityQuery('node')
      ->condition('type', 'publicati')
      ->condition('status', 1)
      ->sort('created', 'DESC')
      ->accessCheck(TRUE);

    $nids = $query->execute();
    $nodes = Node::loadMultiple($nids);

    foreach ($nodes as $node) {
      $pdf_url = null;
      if (!$node->get('field_pdf')->isEmpty()) {
        $file = $node->get('field_pdf')->entity;
        if ($file) {
          $pdf_url = $file->createFileUrl(FALSE);
        }
      }

      $image_url = null;
      $image_alt = '';

      if (!$node->get('field_featured_image')->isEmpty()) {
        $media_entity = $node->get('field_featured_image')->entity;
        if ($media_entity && $media_entity->hasField('field_media_image') && !$media_entity->get('field_media_image')->isEmpty()) {
            $media_file = $media_entity->get('field_media_image')->entity;
            if ($media_file) {
                $image_url = $media_file->createFileUrl(FALSE);
                $image_alt = $media_entity->get('field_media_image')->alt;
            }
        }
      }

      $items[] = [
        'title' => $node->getTitle(),
        'pdf_url' => $pdf_url,
        'image_url' => $image_url,
        'image_alt' => $image_alt,
        'link' => $node->toUrl()->toString(),
      ];
    }

    $data['publications'] = $items;

    $lectures = [];

    $lectures_query = \Drupal::entityQuery('node')
      ->condition('type', 'lectures')
      ->condition('status', 1)
      ->sort('created', 'DESC')
      ->accessCheck(TRUE);

    $lectures_nids = $lectures_query->execute();
    $lectures_nodes = Node::loadMultiple($lectures_nids);

    foreach ($lectures_nodes as $lectures_node) {

      $image_url = null;
      $image_alt = '';

      if (!$lectures_node->get('field_video_thumbnail')->isEmpty()) {
        $media_entity = $lectures_node->get('field_video_thumbnail')->entity;
        if ($media_entity && $media_entity->hasField('field_media_image') && !$media_entity->get('field_media_image')->isEmpty()) {
            $media_file = $media_entity->get('field_media_image')->entity;
            if ($media_file) {
                $image_url = $media_file->createFileUrl(FALSE);
                $image_alt = $media_entity->get('field_media_image')->alt;
            }
        }
      }

      $field_name = 'field_video_file';

      if ($lectures_node->hasField($field_name) && !$lectures_node->get($field_name)->isEmpty()) {
        $fid = $lectures_node->get($field_name)->target_id;
        $file = \Drupal::entityTypeManager()->getStorage('file')->load($fid);

        if ($file) {
          $file_url_generator = \Drupal::service('file_url_generator');
          $video_url = $file_url_generator->generateAbsoluteString($file->getFileUri());

          $play_str = '';
          if ($lectures_node->hasField('field_autoplay_video') && !$lectures_node->get('field_autoplay_video')->isEmpty() && $lectures_node->get('field_autoplay_video')->value == 1) {
            $play_str .= 'autoplay ';
          }
          if ($lectures_node->hasField('field_loop_video') && !$lectures_node->get('field_loop_video')->isEmpty() && $lectures_node->get('field_loop_video')->value == 1) {
            $play_str .= 'loop ';
          }
          if ($lectures_node->hasField('field_mute_video') && !$lectures_node->get('field_mute_video')->isEmpty() && $lectures_node->get('field_mute_video')->value == 1) {
            $play_str .= 'muted ';
          }

        }
      }

      $lectures[] = [
        'title' => $lectures_node->getTitle(),
        'video_url' => $video_url,
        'video_mime' => $file->getMimeType() ?: 'video/mp4',
        'play_str' => $play_str,
        'image_url' => $image_url,
        'image_alt' => $image_alt,
        'nid' => $lectures_node->id(),
      ];
    }
    $data['lectures'] = $lectures;
    //dump($data);exit;

    // Return a render array using a custom theme hook defined in auto_login.module.
    return [
      '#theme' => 'publication_list_page',
      '#data' => $data,
      '#attached' => [
        'library' => [
          'sdact_payment/publications'
        ],
      ],
      '#cache' => [
        'contexts' => ['user.roles:anonymous'],
      ],
    ];
  }


}
