<?php

namespace Drupal\contact_us\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class FormLogin
 * @package Drupal\contact_us\Form
 */
class ContactUsForm extends FormBase {

  /**
   * @return string
   */
  public function getFormId() {
    return 'contact us';
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   * @return array
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => t('title'),
      '#required' => TRUE,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => t('e-mail'),
      '#required' => TRUE,
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

    $query = \Drupal::database();

    $query->insert('contact_us')

      ->fields([
        'title' => $form_state->getValue('title'),
        'email' => $form_state->getValue('email'),
        'text' => $form_state->getValue('text'),
        'status' => 0,
      ])

      ->execute();

    drupal_set_message("Thank you for your application.");
  }
}