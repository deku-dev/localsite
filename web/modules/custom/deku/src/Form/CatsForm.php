<?php

namespace Drupal\deku\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class CatsForm extends FormBase{
  
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

    // Create a $form API array.
    $form['cats_name'] = array(
      '#type' => 'tel',
      '#title' => $this
        ->t("Your cat's name:"),
      '#description' => $this->t("Min 2 and max 32 characters")
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

  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('deku.settings');
    $config->set('deku.source_text', $form_state->getValue('source_text'));
    $config->set('deku.page_title', $form_state->getValue('page_title'));
    $config->save();
    return parent::submitForm($form, $form_state);
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