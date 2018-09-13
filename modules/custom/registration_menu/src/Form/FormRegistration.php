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

    $form['system_messages'] = [
      '#markup' => '<div id="form-registration-system-messages"></div>',
      '#weight' => -100,
    ];


    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Sign up'),
      '#ajax' => [
        'callback' => '::saveRegistration',
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
  public function saveRegistration(array &$form, FormStateInterface $form_state) {

    $name = $form_state->getValue('name');
    $surname = $form_state->getValue('surname');
    $email = $form_state->getValue('email');
    $pass = $form_state->getValue('pass');
    $pass2 = $form_state->getValue('pass2');
    $gender = $form_state->getValue('gender');

    $registration_response = new AjaxResponse();

    $query = \Drupal::database()->select('users_field_data', 'ufd');
    $is_exist = (bool)$query
      ->condition('ufd.name', $name)
      ->countQuery()
      ->execute()
      ->fetchField();

    if ($is_exist) {

      $selector_pass = '.odd';
      $css = [
        'background' => '#00bc8c',
      ];
      drupal_set_message(t("The name of such a user already exists."), 'error');

    } elseif ($pass!=$pass2) {

      $selector_pass = '.odd';
        $css = [
          'background' => '#00bc8c',
        ];
        drupal_set_message(t("Your password does not match."), 'error');

    } elseif (strlen($pass) < 6) {

      $selector_pass = '.odd';
        $css = [
          'background' => '#00bc8c',
        ];
        drupal_set_message(t("Password length must be at least 6 characters."), 'error');

    } elseif (!preg_match('@[A-Z]@', $pass)) {

      $selector_pass = '.odd';
        $css = [
          'background' => '#00bc8c',
        ];
        drupal_set_message(t("The password must have a capital letter."), 'error');

    } elseif (empty($gender)) {

      $selector_pass = '.odd';
      $css = [
        'background' => '#00bc8c',
      ];
      drupal_set_message(t("You have not chosen sex."), 'error');

    } else {

      $user = User::create([
        'type' => 'user',
        'name' => $name,
        'field_surname' => $surname,
        'mail' => $email,
        'init' => $email,
        'pass' => $pass,
        'field_gender' => $gender,
      ]);

      $user->activate();
      $user->save();

      $registration_response->addCommand(new RedirectCommand(Url::fromRoute('<front>')->toString()));

      $selector_pass = '.odd';
      $css = [
        'background' => '#00bc8c',
      ];
      drupal_set_message(t("user is successfully registered."), 'status');

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

    $registration_response->addCommand(new HtmlCommand('#form-registration-system-messages', $messages));
    $registration_response->addCommand(new CssCommand($selector_pass, $css));


    return $registration_response;
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }
}