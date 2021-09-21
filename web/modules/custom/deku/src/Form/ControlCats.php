<?php

namespace Drupal\deku\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

class ControlCats extends ConfigFormBase{


  /**
   * {@inheritdoc}
   */
  public function getFormId(){
    return 'deku-form__edit';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state){
    $form['edit_name'] = array(
      '#type'=>'textfield',
      '#title' => $this->t('Your cat\'s name:'),
      '#description' => $this->t('Min 2 and max 32 characters'),
      '#attributes' => array(
        'min-length' => '2',
        'max-length' => '32'
      )
    );
    $form['edit_image'] = array(
      '#type' => 'managed_file',
      '#title' => $this->t('Image cats'),
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#upload_location' => 'public://cats_images/',
      '#required' => TRUE,
    );
    $form['edit_email'] = array(
      '#type' => 'email',
      '#title' => $this->t('Your email'),
      '#ajax' => [
        'callback'=> '::validateEmail'
      ],
    );
    $form['edit_cat'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#ajax' => [
        'callback' => '::editCat'
      ]
    );
    $form['delete_cat'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Delete'),
      '#ajax' => [
        'callback' => '::deleteCat'
      ]
    );
  }


  public function deleteCat(array &$form, FormStateInterface $form_state){
    $response = new AjaxResponse();
  }

  public function editCat(array &$form, FormStateInterface $form_state){
    $response = new AjaxResponse();
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state){

  }

  public function validateEmailAjax(array &$form, FormStateInterface $form_state){
    $response = new AjaxResponse();
    if ($this->stateForm == "noerror") {
      $response->addCommand(new HtmlCommand('.modal-body .label-alert', ""));
    }else {
      $response->addCommand(new HtmlCommand('.modal-body .label-alert', "Email is invalid. Please enter a valid email"));
    }
    return $response;
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state){

  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(){
    return [
      'deku.settings',
    ];
  }
}