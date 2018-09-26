<?php

/**
 * @file
 * Contains \Drupal\menu_element\Plugin\Block\MenuElementBlock.
 */

namespace Drupal\menu_element\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides a custom_block.
 *
 * @Block(
 *   id = "menu_element",
 *   admin_label = @Translation("Menu Element"),
 * )
 */
class MenuElementBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $query_main_link = \Drupal::database()->select('node_field_data', 'nfd');
    $query_main_link->leftJoin('node__field_link_node', 'nfln', 'nfd.nid = nfln.entity_id');
    $query_main_link->leftJoin('node__field_image_menu', 'nfim', 'nfd.nid = nfim.entity_id');
    $query_main_link->leftJoin('file_managed', 'fm', 'fm.fid = nfim.field_image_menu_target_id');
    $query_main_link->isNull('nfln.entity_id');
    $query_main_link->range(0, 1);
    $query_main_link->fields('nfd', ['nid','title']);
    $query_main_link->fields('fm', ['uri']);
    $main_links = $query_main_link->execute()->fetchAll();

    $rows_main = [];

    foreach ($main_links as $main_link) {

      $title_main_link = [
        'title' => $main_link->title,
      ];

      if(!empty($main_link->nid)) {
        $link_node_main = Link::createFromRoute($title_main_link, 'entity.node.canonical', ['node' => $main_link->nid,]);
        $url_node_main = $link_node_main->getUrl()->toString();
      } else {
        $url_node_main = '';
      }

      if (!empty($main_link->uri)) {
        $url_main = file_create_url($main_link->uri);
        $uri_main = Link::fromTextAndUrl($main_link, Url::fromUri($url_main));
        $url_imege_node_main = $uri_main->getUrl()->toString();
      } else {
        $url_imege_node_main = "";
      }

      $rows_main[$url_imege_node_main] = [
        'url_node' => $url_node_main,
        'title' => $title_main_link,
      ];
    }

    $build[] = [
      '#theme' => 'menu_element',
      '#rows_main' => $rows_main,
    ];

    $build['#attached']['library'][] = 'menu_element/menu_element';

    return $build;
  }
}