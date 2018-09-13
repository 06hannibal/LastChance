<?php

namespace Drupal\registration_menu\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
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

    $form['system_messages'] = [
      '#markup' => '<div id="form-login-system-messages"></div>',
      '#weight' => -100,
    ];

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Log in'),
      '#ajax' => [
        'callback' => '::saveLogin',
        'event' => 'click',
        'progress' => [
          'type' => 'throbber',
        ],
      ],
    );

    return $form;
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   * @return AjaxResponse
   */
  public function saveLogin(array &$form, FormStateInterface $form_state) {

    $login_response = new AjaxResponse();

    $name = $form_state->getValue('name');
    $pass = $form_state->getValue('pass');

    $uid = \Drupal::service('user.auth')->authenticate($name, $pass);

    if($uid==true) {

      $user = User::load($uid);
      user_login_finalize($user);

      $login_response->addCommand(new RedirectCommand(Url::fromRoute('<front>')->toString()));

      $selector = '.odd';
      $css = [
        'background' => '#00bc8c',
      ];
      drupal_set_message(t("login to the system."), 'status');

    } else {

      $selector = '.odd';
      $css = [
        'background' => '#00bc8c',
      ];
      drupal_set_message(t("Invalid login or password."), 'error');
    }

    $message = [
      '#theme' => 'status_messages',
      '#message_list' => drupal_get_messages(),
      '#status_headings' => [
        'status' => t('Status message'),
        'error' => t('Error message'),
      ],
    ];

    $messages = \Drupal::service('renderer')->render($message);
    $login_response->addCommand(new HtmlCommand('#form-login-system-messages', $messages));
    $login_response->addCommand(new CssCommand($selector, $css));

    return $login_response;
  }


    /**
   * @param array $form
   * @param FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }
}