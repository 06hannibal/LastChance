<?php

namespace Drupal\menu_element\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Component\Serialization\Json;
use Symfony\Component\HttpFoundation\JsonResponse;

class MenuDropDownController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function dropdown() {

    $query = \Drupal::database()->select('node_field_data', 'nfd');
    $query->Join('node__field_link_node', 'nfln', 'nfd.nid = nfln.entity_id');
    $query->Join('node__field_image_menu', 'nfim', 'nfd.nid = nfim.entity_id');
    $query->Join('file_managed', 'fm', 'fm.fid = nfim.field_image_menu_target_id');
    $query->fields('nfd', ['nid','title']);
    $query->fields('fm', ['uri']);
    $query->orderBy('nfd.created', 'DESC');
    $nodes = $query->execute()->fetchAll();

    $rows = [];

    foreach ($nodes as $node) {

      $title = [
        'title' => $node->title,
      ];

      if(!empty($node->nid)) {
        $link_node = Link::createFromRoute($title, 'entity.node.canonical', ['node' => $node->nid,]);
        $url_node = $link_node->getUrl()->toString();
      } else {
        $url_node = '';
      }

      if (!empty($node->uri)) {
        $url = file_create_url($node->uri);
        $uri = Link::fromTextAndUrl($title, Url::fromUri($url));
        $url_imege_node = $uri->getUrl()->toString();
      } else {
        $url_imege_node = "";
      }

      $rows[] = [
        'url_node' => $url_node,
        'img' => $url_imege_node,
        'title' => $node->title,
      ];
    }

    $json_response= new JsonResponse($rows);

    return $json_response;

  }


}