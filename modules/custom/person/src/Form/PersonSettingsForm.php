<?php
/**
 * @file
 * Contains \Drupal\person\Form\PersonSettingsForm.
 */

namespace Drupal\person\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ContentEntityExampleSettingsForm.
 *
 * @package Drupal\dictionary\Form
 *
 * @ingroup dictionary
 */
class PersonSettingsForm extends FormBase {
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'person_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Empty implementation of the abstract submit class.
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['person_settings']['#markup'] = 'Settings form for Person. Manage field settings here.';
    return $form;
  }

}
