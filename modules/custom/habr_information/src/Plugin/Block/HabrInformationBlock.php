<?php

/**
 * @file
 * Contains \Drupal\habr_information\Plugin\Block\HabrInformationBlock.
 */

namespace Drupal\habr_information\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a custom_block.
 *
 * @Block(
 *   id = "habr_information",
 *   admin_label = @Translation("Habr Information"),
 * )
 */
class HabrInformationBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $url = file_get_contents('https://habr.com/rss/hubs/') or die(t("file does not load"));

    $xmlelement=simplexml_load_string($url) or die("Error: Cannot create object");
    $general_information = [];
    $rows = [];

    foreach ($xmlelement as $elements) {

      $general_information[] = [
        'title' => $elements->title->__toString(),
        'link' => $elements->link->__toString(),
        'description' => $elements->description->__toString(),
        'language' => $elements->language->__toString(),
        'managingEditor' => $elements->managingEditor->__toString(),
        'generator' => $elements->generator->__toString(),
        'pubDate' => $elements->pubDate->__toString(),
        'link_image' => $elements->image->link->__toString(),
        'url_image' => $elements->image->url->__toString(),
        'title_image' => $elements->image->title->__toString(),
      ];


      $i = count($elements->item);

        foreach ($elements->item as $items) {

          if ($i-->10) {

            $rows[] = [
              'title' => $items->title->__toString(),
              'link' => $items->link->__toString(),
            ];
          }
        }
    }

    $build[] = [
      '#theme' => 'habr_information',
      '#general_information' => $general_information,
      '#rows' => $rows,
    ];

    $build['#attached']['library'][] = 'habr_information/habr_information';

    return $build;

  }

}