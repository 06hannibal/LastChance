<?php

namespace Drupal\registration_menu\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;


/**
 * Class FormLogin
 * @package Drupal\registration_form\Form
 */
class FormLogin extends FormBase {

  /**
   * @return string
   */
  public function getFormId() {
    return 'form login';
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   * @return array
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['name'] = [
      '#type' => 'textfield',
      '#required' => true,
      '#title' => t('name'),
    ];

    $form['pass'] = [
      '#type' => 'password',
      '#required' => TRUE,
      '#title' => t('password'),
      '#size' => 25,
    ];

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Log in'),
    );

    return $form;
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $name = $form_state->getValue('name');
    $pass = $form_state->getValue('pass');

    $uid = \Drupal::service('user.auth')->authenticate($name, $pass);
    $user = User::load($uid);
    user_login_finalize($user);

    drupal_set_message(t("login to the system."), 'status');
    $form_state->setRedirect('<front>');
  }
}