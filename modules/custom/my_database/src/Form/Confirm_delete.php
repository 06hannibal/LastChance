<?php

namespace Drupal\my_database\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
/**
 * Defines a confirmation form for deleting my_database module data.
 */
class Confirm_delete extends ConfirmFormBase
{
    /**
     * The ID of the item to delete.
     *
     * @var string
     */
    protected $id;

    /**
     * {@inheritdoc}.
     */
    public function getFormId()
    {
        return 'my_string_delete_form';
    }

    /**
     * {@inheritdoc}
     */
    public function getQuestion() {
        //the question to display to the user.
        return t('Do you want to delete %id?', array('%id' => $this->id));
    }

    /**
     * {@inheritdoc}
     */
    public function getCancelUrl() {
        //this needs to be a valid route otherwise the cancel link won't appear
        return new Url('my_database.mypage');
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription() {
        //a brief desccription
        return t('Only do this if you are sure!');
    }

    /**
     * {@inheritdoc}
     */
    public function getConfirmText() {
        return $this->t('Delete it Now!');
    }


    /**
     * {@inheritdoc}
     */
    public function getCancelText() {
        return $this->t('Cancel');
    }

    /**
     * {@inheritdoc}
     *
     * @param int $id
     *   (optional) The ID of the item to be deleted.
     */
    public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
        $this->id = $id;
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        if (isset($_GET['id'])) {
            $query = \Drupal::database()->delete('my_database');
            $query->condition('id', $_GET['id']);
            $query->execute();
            drupal_set_message("succesfully deleted");
            $form_state->setRedirect('my_database.mypage');
        }
    }
}