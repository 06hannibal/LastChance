<?php

/**
 * @file
 * Contains \Drupal\clock_block\Plugin\Block\ClockBlock.
 */

namespace Drupal\clock_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a custom_block.
 *
 * @Block(
 *   id = "clock_block",
 *   admin_label = @Translation("Clock Block"),
 * )
 */
class ClockBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $build[] = [
      '#theme' => 'clock_block',
    ];

    $build['#attached']['library'][] = 'clock_block/clock_block';

    return $build;
  }

}