<?php

namespace Drupal\contact_us\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides route responses for the Example module.
 */
class ContactUsTable extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function feedback() {

    $header = [
      ['data' => $this->t('question №'),'field' => 'id', 'sort' => 'DESC'],
      ['data' => $this->t('title')],
      ['data' => $this->t('mail')],
      ['data' => $this->t('text')],
      ['data' => $this->t('link-form')],
    ];

    $query = \Drupal::database()->select('contact_us', 'cu');
    $query->fields('cu', ['id','title','email','text','status']);
    $table_sort = $query->extend('Drupal\Core\Database\Query\TableSortExtender')
->orderByHeader($header);
    $pager = $table_sort->extend('Drupal\Core\Database\Query\PagerSelectExtender')
      ->limit(10);
    $contacts = $pager->execute()->fetchAll();

    $link = 'Answer';

    $rows = [];

    foreach ($contacts as $contact) {

      $status = $contact->status;

      $contact_id = $contact->id;

      $ulr = Url::fromRoute('contact_us.emailform',['id' => $contact_id]);

      if (!empty($contact_id && $status==false)) {
        $uri = Link::fromTextAndUrl(t($link), $ulr);
      } else {
        $uri = t('Response sent');
      }

      $rows[] = [
        'id' => $contact->id,
        'title' => $contact->title,
        'email' => $contact->email,
        'text' => $contact->text,
        'id_form' => $uri,
      ];

    }

    $build['config_table'] = [
      '#header' => $header,
      '#type' => 'table',
      '#rows' => $rows,
      '#markup' => t('No records found!'),
    ];

    $build['pager'] = [
    '#type' => 'pager'
    ];

    return $build;

  }
}