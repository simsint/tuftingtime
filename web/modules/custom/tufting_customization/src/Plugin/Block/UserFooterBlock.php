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

/**
 * Provides an user footer block.
 */
#[Block(
  id: 'tufting_customization_user_footer',
  admin_label: new TranslatableMarkup('User Footer'),
  category: new TranslatableMarkup('Custom'),
)]
final class UserFooterBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $theme = \Drupal::theme()->getActiveTheme()->getName();
    $logo = theme_get_setting('logo.url', $theme);
    $sales_telephone = theme_get_setting('sales_telephone', $theme);
    $sales_email = theme_get_setting('sales_email', $theme);
    $sales_whatsapp = theme_get_setting('sales_whatsapp', $theme);
    $main_showroom = theme_get_setting('main_showroom', $theme);
    $main_showroom_address = theme_get_setting('main_showroom_address', $theme);
    $footer_title = theme_get_setting('footer_title', $theme);

    $menu_link_tree = \Drupal::service('menu.link_tree');
    $parameters = new MenuTreeParameters();
    $parameters->setMinDepth(1);
    $parameters->setMaxDepth(NULL);
    $parameters->onlyEnabledLinks();
    $tree = $menu_link_tree->load('footer', $parameters);

    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];
    $tree = $menu_link_tree->transform($tree, $manipulators);
    $user_links = $this->buildMenuLinks($tree);


    $menu_link_tree = \Drupal::service('menu.link_tree');
    $parameters = new MenuTreeParameters();
    $parameters->setMinDepth(1);
    $parameters->setMaxDepth(NULL);
    $parameters->onlyEnabledLinks();
    $tree = $menu_link_tree->load('footer-bottom', $parameters);

    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];
    $tree = $menu_link_tree->transform($tree, $manipulators);
    $footer_bottom_links = $this->buildMenuLinks($tree);

    $query = \Drupal::entityTypeManager()->getStorage('block_content')->getQuery();
    $query->condition('type', 'hero_banner'); // The block type machine name.
    $query->condition('field_machine_name', 'advertise_footer'); // The field and value to look for.
    $query->range(0, 1); // We only want one result.
    $query->accessCheck(FALSE);
    $block_ids = $query->execute();

    if (!empty($block_ids)) {
      $block_id = array_shift($block_ids);
      $block_content = \Drupal::entityTypeManager()->getStorage('block_content')->load($block_id);

      if ($block_content) {
          $variables['adv_title'] = $block_content->get('field_title')->value;
          $variables['adv_sub_title'] = $block_content->get('field_sub_title')->value;
          $variables['adv_link_text'] = $block_content->get('field_consultant_form')->value;
          $variables['adv_link_url'] = $block_content->get('field_consultant_form_link')->value;

          if ($block_content->hasField('field_image') && !$block_content->get('field_image')->isEmpty()) {
              $fid = $block_content->get('field_image')->target_id;
              $file = \Drupal::entityTypeManager()->getStorage('file')->load($fid);

              if ($file) {
                  $uri = $file->getFileUri();
                  $variables['adv_logo_image_url'] = \Drupal::service('file_url_generator')->generateAbsoluteString($uri);
              }
          }
      }
    }

    //dump($user_links);exit;

    $data = [
      'site_name' => $site_name,
      'user_links' => $user_links,
      'footer_bottom_links' => $footer_bottom_links,
      'logo' => $logo,
      'sales_telephone' => $sales_telephone,
      'sales_email' => $sales_email,
      'sales_whatsapp' => $sales_whatsapp,
      'logo_image_url' => $logo_image_url,
      'main_showroom' => $main_showroom,
      'main_showroom_address' => $main_showroom_address,
      'footer_title' => $footer_title,
      'advertise_footer' => $advertise_footer,
    ];

    $build = [
      '#theme' => 'portal_footer_template',
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
