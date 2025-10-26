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
use Drupal\Core\Link;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\FileUrlGeneratorInterface;

/**
 * Provides an user header block.
 */
#[Block(
  id: 'tufting_customization_user_header',
  admin_label: new TranslatableMarkup('User Header'),
  category: new TranslatableMarkup('Custom'),
)]
final class UserHeaderBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $site_name = \Drupal::config('system.site')->get('name');
    // Get the menu link tree service.
    $menu_link_tree = \Drupal::service('menu.link_tree');
    $parameters = new MenuTreeParameters();
    $parameters->setMinDepth(1);
    $parameters->setMaxDepth(NULL);
    $parameters->onlyEnabledLinks();
    $tree = $menu_link_tree->load('main', $parameters);

    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];
    $tree = $menu_link_tree->transform($tree, $manipulators);
    $user_links = $this->buildMenuLinks($tree);
    //dump($user_links);exit;

    $query = \Drupal::entityTypeManager()->getStorage('block_content')->getQuery();
    $query->condition('type', 'hero_banner'); // The block type machine name.
    $query->condition('field_machine_name', 'hero_banner'); // The field and value to look for.
    $query->range(0, 1); // We only want one result.
    $query->accessCheck(FALSE);
    $block_ids = $query->execute();

    if (!empty($block_ids)) {
      $block_id = array_shift($block_ids);
      $block_content = \Drupal::entityTypeManager()->getStorage('block_content')->load($block_id);

      if ($block_content->hasField('field_image') && !$block_content->get('field_image')->isEmpty()) {
          $fid = $block_content->get('field_image')->target_id;
          $file = \Drupal::entityTypeManager()->getStorage('file')->load($fid);

          if ($file) {
              $uri = $file->getFileUri();
              $logo_image_url = \Drupal::service('file_url_generator')->generateAbsoluteString($uri);
          }
      }

    }



    $theme = \Drupal::theme()->getActiveTheme()->getName();
    $logo = theme_get_setting('logo.url', $theme);
    $sales_telephone = theme_get_setting('sales_telephone', $theme);
    $sales_email = theme_get_setting('sales_email', $theme);
    $sales_whatsapp = theme_get_setting('sales_whatsapp', $theme);

    $data = [
      'site_name' => $site_name,
      'user_links' => $user_links,
      'logo' => $logo,
      'sales_telephone' => $sales_telephone,
      'sales_email' => $sales_email,
      'sales_whatsapp' => $sales_whatsapp,
      'logo_image_url' => $logo_image_url,
    ];

    $build = [
      '#theme' => 'portal_header_template',
      '#data' => $data,
      '#label' => $this->t($this->configuration['label']),
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
