<?php

namespace Drupal\registration_menu\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

/**
 * @property  userAuth
 */
class FormRegistration extends FormBase {

  /**
   * @return string
   */
  public function getFormId() {
    return 'form registration';
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   * @return array|void
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['name'] = [
      '#type' => 'textfield',
      '#required' => true,
      '#title' => t('name'),
    ];

    $form['surname'] = [
      '#type' => 'textfield',
      '#required' => true,
      '#title' => t('surname'),
    ];

    $form['email'] = [
      '#type' => 'email',
      '#required' => TRUE,
      '#title' => t('e-mail'),
    ];

    $form['pass'] = [
      '#type' => 'password',
      '#required' => TRUE,
      '#title' => t('password'),
      '#size' => 25,
    ];

    $form['pass2'] = [
      '#type' => 'password',
      '#required' => TRUE,
      '#title' => t('confirm password'),
      '#size' => 25,
    ];

    $form['gender'] = [
      '#type' => 'radios',
      '#options' => [
        'Male' => t('Male'),
        'Female' => t('Female'),
      ],
      '#title' => t('Gender'),
      '#required' => true,
    ];

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Sign up'),
    );

    return $form;
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $pass = $form_state->getValue('pass');
    $pass2 = $form_state->getValue('pass2');

    if ($pass!=$pass2) {
      $form_state->setErrorByName('pass', $this->t('Your password does not match.'));
    }

    if(strlen($pass) < 6 ) {
      $form_state->setErrorByName('pass', $this->t('Password length must be at least 6 characters.'));
    }

    if(!preg_match('@[A-Z]@', $pass)) {
      $form_state->setErrorByName('pass', $this->t('The password must have a capital letter.'));
    }

  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $name = $form_state->getValue('name');
    $surname = $form_state->getValue('surname');
    $email = $form_state->getValue('email');
    $pass = $form_state->getValue('pass');
    $location = $form_state->getValue('location');
    $gender = $form_state->getValue('gender');

    $user = User::create([
      'type' => 'user',
      'name' => $name,
      'field_surname' => $surname,
      'mail' => $email,
      'init' => $email,
      'pass' => $pass,
      'field_location' => $location,
      'field_gender' => $gender,
    ]);
    $user->activate();
    $user->save();

    drupal_set_message(t("user saved."), 'status');
    $form_state->setRedirect('<front>');

  }
}