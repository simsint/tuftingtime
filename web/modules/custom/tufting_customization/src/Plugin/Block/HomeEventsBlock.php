<?php

declare(strict_types=1);

namespace Drupal\tufting_customization\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;
use Drupal\node\Entity\Node;
use Drupal\Core\Link;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides an user footer block.
 */
#[Block(
  id: 'tufting_customization_home_events',
  admin_label: new TranslatableMarkup('Home Branch Events Gallery Block'),
  category: new TranslatableMarkup('Custom'),
)]
final class HomeEventsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {

     $items = [];

    $query = \Drupal::entityQuery('node')
      ->condition('type', 'branches')
      ->condition('status', 1)
      ->sort('created', 'DESC')
      ->accessCheck(TRUE);

    $nids = $query->execute();
    $nodes = Node::loadMultiple($nids);

    foreach ($nodes as $node) {

      $image_url = null;
      $image_alt = '';

      if (!$node->get('field_gallery_image')->isEmpty()) {
        $media_entity = $node->get('field_gallery_image')->entity;
        if ($media_entity && $media_entity->hasField('field_media_image') && !$media_entity->get('field_media_image')->isEmpty()) {
            $media_file = $media_entity->get('field_media_image')->entity;
            if ($media_file) {
                $image_url = $media_file->createFileUrl(FALSE);
                $image_alt = $media_entity->get('field_media_image')->alt;
            }
        }
      }
      $video_url = null;
      $play_str = null;
      if ($node->hasField('field_video_file') && !$node->get('field_video_file')->isEmpty()) {
        $fid = $node->get('field_video_file')->target_id;
        $file = \Drupal::service('entity_type.manager')->getStorage('file')->load($fid);

        if ($file) {
          $video_url = \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri());
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
        }
      }

      $items[] = [
        'branch_name' => $node->getTitle(),
        'branch_address' => $node->get('field_address')->value,
        'image_url' => $image_url,
        'image_alt' => $image_alt,
        'video_url' => $video_url,
        'play_str' => $play_str,
        'nid' => $node->id(),
      ];
    }


    $data = [
      'items' => $items,

    ];

    $build = [
      '#theme' => 'home_events_template',
      '#data' => $data,
      '#attached' => [
        'library' => [
          'tufting_customization/events_home_styles'
        ],
      ],
      '#cache' => [
        'max-age' => 0,
      ],
    ];

    //dump($build);exit;

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account): AccessResult {
    // @todo Evaluate the access condition here.
    return AccessResult::allowedIf(TRUE);
  }


  /**
   * Builds a renderable array from a menu tree with debugging.
   */
  protected function buildMenuLinks(array $tree): array {

    $build = [];
    foreach ($tree as $element) {
      if (!$element->access->isAllowed()) {
        continue;
      }

      $link = $element->link;
      $build_item = [
        '#type' => 'link',
        '#title' => $link->getTitle(),
        '#url' => $link->getUrlObject(),
        '#attributes' => $link->getPluginDefinition()['options']['attributes'] ?? [],
      ];

      if ($element->hasChildren) {
        $build_item['#below'] = $this->buildMenuLinks($element->subtree);
      }

      $build[] = $build_item;
    }

    return $build;
  }

}
