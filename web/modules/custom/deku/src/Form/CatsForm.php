<?php

namespace Drupal\deku\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class CatsForm extends ConfigFormBase{
  
  /**
   * {@inheritdoc}
   */
  public function getFormId(){
    return "deku_form";
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('deku.settings');
    // Create a $form API array.
    $form['cats_name'] = array(
      '#type' => 'textfield',
      '#title' => $this
        ->t("Your cat's name:"),
      '#description' => $this->t("Min 2 and max 32 characters"),
      '#default_value'=> $config->get("deku.source_text")
    );
    $form['add_cat'] = array(
      '#type' => 'submit',
      '#value' => $this
        ->t('Add cat'),
    );
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $form_len = strlen($form_state->getValue('cats_name'));
    if ($form_len < 3) {
      $form_state->setErrorByName('cats_name', $this->t('The cat name is too short. Please enter a valid cat name'));
    }elseif ($form_len > 32) {
      $form_state->setErrorByName('cats_name', $this->t('The cat name is too long. Please enter a valid cat name'));
    }
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('deku.settings');
    $config->set('deku.source_text', $form_state->getValue('cats_name'));
    $config->save();
    $this
    ->messenger()
    ->addStatus($this
    ->t('The cat name has been saved'));
  }
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'deku.settings',
    ];
  }
}