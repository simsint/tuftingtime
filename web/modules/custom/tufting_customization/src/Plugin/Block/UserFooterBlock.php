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
    $sales_telephone = theme_get_setting('sales_telephone', $theme);
    $copyright = theme_get_setting('copyright', $theme);

    /*$query = \Drupal::entityTypeManager()->getStorage('block_content')->getQuery();
    $query->condition('type', 'social_links'); // The block type machine name.
    $query->condition('field_machine_name', 'footer_social_links'); // The field and value to look for.
    $query->range(0, 1); // We only want one result.
    $query->accessCheck(FALSE);
    $block_ids = $query->execute();

    if (!empty($block_ids)) {
      $block_id = array_shift($block_ids);
      $block_content = \Drupal::entityTypeManager()->getStorage('block_content')->load($block_id);

      if ($block_content) {
        $user_links = $block_content;
      }
    }*/

    //dump($user_links);exit;

    $data = [
      'copyright' => $copyright,
      'user_links' => $user_links,
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
