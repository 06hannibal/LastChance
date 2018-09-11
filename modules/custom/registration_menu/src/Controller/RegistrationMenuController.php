<?php

namespace Drupal\registration_menu\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the Example module.
 */
class RegistrationMenuController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function menu() {

    $form_login = \Drupal::formBuilder()->getForm('Drupal\registration_menu\Form\FormLogin');

    $form_registration = \Drupal::formBuilder()->getForm('Drupal\registration_menu\Form\FormRegistration');


    $build[] = [
      '#theme' => 'registration_menu',
      '#form_login' => $form_login,
      '#form_registration' => $form_registration,
    ];

    $build['#attached']['library'][] = 'registration_menu/registration_menu';

    $build['#cache']['max-age'] = 0;

    return $build;

  }
}