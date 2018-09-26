<?php

namespace Drupal\load_content\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;

use Drupal\Core\Controller\ControllerBase;

class LoadContentController extends ControllerBase{

  public function loadContent($nid) {

    # Get NID render
    $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
    $node_view = render(\Drupal::entityTypeManager()->getViewBuilder('node')->view($node, 'full'));

    # New response
    $response = new AjaxResponse();

    # Commands Ajax
    $selector = '.load-content';
    $response->addCommand(new ReplaceCommand($selector, $node_view, $settings = NULL));

    # Return response
    return $response;

  }

}