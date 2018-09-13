<?php
 
namespace Drupal\my_database\Form;
 
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
 
/**
 *  form.
 */
class AddForm extends FormBase
{
 
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'addform';
    }
 
    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
       
        $form['number'] = array(
            '#type' => 'textfield',
            '#required' => TRUE,
            '#placeholder' => 'number',
            '#default_value' => '',
 
        );
        $form['teaser'] = array(
            '#type' => 'textfield',
            '#required' => TRUE,
            '#placeholder' => 'teaser',
            '#default_value' => '',
        );
 
        $form['text'] = array(
            '#type' => 'textarea',
            '#required' => TRUE,
            '#placeholder' => 'text',
            '#default_value' => '',
        );
        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => t('Submit'),
            '#name' => '',
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