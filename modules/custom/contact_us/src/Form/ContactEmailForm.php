<?php

namespace Drupal\contact_us\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\CssCommand;

/**
 * Class FormLogin
 * @package Drupal\contact_us\Form
 */
class ContactEmailForm extends FormBase {

  /**
   * @return string
   */
  public function getFormId() {
    return 'Feedback';
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   * @return array
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $query = \Drupal::database()->select('contact_us', 'cu');
    $query->fields('cu', ['id','title','email','text']);
    $query->orderBy('id', 'DESC');
    $contacts = $query->execute()->fetchAll();

    $id = \Drupal::request()->query->get('id');

    $rows['name'] = [];

    foreach ($contacts as $contact) {
      $attributes = $contact->id;

      if($id==$attributes) {

        $rows['name'] = [
          'id' => $contact->id,
          'title' => $contact->title,
          'email' => $contact->email,
          'text' => $contact->text,
        ];
      }
    }

    \Drupal::state()->setMultiple($rows['name']);

    $header = [
      'author' => t('id'),
      'title' => t('title'),
      'email' => t('e-mail'),
      'text' => t('text'),
    ];

    $form['table_pager'] = [
      '#header' => $header,
      '#type' => 'table',
      '#rows' => $rows,
      '#empty' => t('there is no record!=('),
    ];

    $form['text'] = [
      '#type' => 'textarea',
      '#title' => t('text'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('submit'),
    ];

    return $form;

  }

  /**
  * @param array $form
  * @param FormStateInterface $form_state
  */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $keys = [
      'id',
      'title',
      'email',
      'text',
    ];

    $answer = $form_state->getValue('text');

    $output = \Drupal::state()->getMultiple($keys);

    $query = \Drupal::database();
    $query->update('contact_us')
      ->condition('id', $output['id'])
      ->fields([
        'status' => True,
      ])
      ->execute();

    $account_name = user_load_by_mail($output['email'])->getAccountName();

    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $to = $output['email'];

    $mailManager = \Drupal::service('plugin.manager.mail');
    $module = 'contact_us';
    $key = 'key_contact';

    $params['title'] = $account_name;

    $params['body'] = [
      'name' => $params['title'],
      'topic_question' => $output['title'],
      'question' => $output['text'],
      'answer' => $answer,
    ];

    \Drupal::state()->delete('id');
    \Drupal::state()->delete('title');
    \Drupal::state()->delete('email');
    \Drupal::state()->delete('text');


    $send = TRUE;

    $mail = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);

    // processing the result.
    if (!$mail) {
      drupal_set_message(t('There was a problem sending your message and it was not sent.'), 'error');
    } else {
      drupal_set_message(t('Your message has been sent.'));
      $form_state->setRedirect('contact_us.table');
    }
  }
}