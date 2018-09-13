<?php

namespace Drupal\my_database\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 *  form.
 */
class EditForm extends FormBase{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'editform';
    }

     /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        if (isset($_GET['id'])) {
            $query = \Drupal::database()->select('my_database', 'md');
            $query->fields('md', ['id', 'number', 'teaser', 'text']);
            $query->condition('id', $_GET['id']);
            $record = $query->execute()->fetchAssoc();
        }
        $form['number'] = array(
            '#type' => 'textfield',
            '#required' => TRUE,
            '#placeholder' => 'number',
            '#default_value' => $record['number'],

        );
        $form['teaser'] = array(
            '#type' => 'textfield',
            '#required' => TRUE,
            '#placeholder' => 'teaser',
            '#default_value' => $record['teaser'],
        );

        $form['text'] = array(
            '#type' => 'textarea',
            '#required' => TRUE,
            '#placeholder' => 'text',
            '#default_value' => $record['text'],
        );
        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => t('Update'),
            '#name' => $_GET['id'],
        );
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        if (!is_numeric($form_state->getValue('number'))) {
            $form_state->setErrorByName('number', $this->t('Not number in number field !'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        if ($form_state->getValue('submit') == 'Update') {

            $query = \Drupal::database();
            $query->update('my_database')
                ->fields([
                    'number' => $form_state->getValue('number'),
                    'teaser' => $form_state->getValue('teaser'),
                    'text' => $form_state->getValue('text'),
                ])
                ->condition('id', $_GET['id'])
                ->execute();
            drupal_set_message("succesfully updated");
        } else {

            $query = \Drupal::database();
            $query->insert('my_database')
                ->fields([
                    'number' => $form_state->getValue('number'),
                    'teaser' => $form_state->getValue('teaser'),
                    'text' => $form_state->getValue('text'),
                ])
                ->execute();
            drupal_set_message("succesfully added");
        }
    }
}