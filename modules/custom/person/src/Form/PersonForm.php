<?php
/**
 * @file
 * Contains \Drupal\person\Form\PersonForm.
 */

namespace Drupal\person\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the person entity edit forms.
 *
 * @ingroup person
 */
class PersonForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\person\Entity\Person */
    $form = parent::buildForm($form, $form_state);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    // Redirect to term list after save.
    $form_state->setRedirect('entity.person.collection');
    $entity = $this->getEntity();
    $entity->save();
  }

}
