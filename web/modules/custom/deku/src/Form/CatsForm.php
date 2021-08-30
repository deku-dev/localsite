<?php

namespace Drupal\deku\Form;



use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

class CatsForm extends ConfigFormBase{
  
  public $stateForm;
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
      '#default_value'=> $config->get("deku.source_text"),
      '#attributes' => array('min-length' => '2'),
    );
    $form['email'] = array(
      '#type' => 'email',
      '#title' => $this->t('Your email:'),
      '#default_value'=> $config->get("deku.email"),
      '#required' => TRUE,
      '#ajax' => [
        'callback'=>'::validateEmailAjax',
        'event' => 'input',
      ],
      '#decription' => $this->t("Email is allowed for example: example@mail.com
      "),
    );

    $form['image_cats'] = [
      '#type' => 'managed_file',
      '#title' => t('Image cats'),
      '#upload_validators' => [
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#upload_location' => 'public://',
      '#required' => TRUE,
    ];

    $form['add_cat'] = array(
      '#type' => 'submit',
      '#value' => $this
        ->t('Add cat'),
      '#ajax' => [
        'callback' => '::setMessage',
      ],
    );
    return $form;
  }
  public function validateEmailAjax(array &$form, FormStateInterface $form_state){
    $response = new AjaxResponse();
    if ($this->stateForm == "noerror") {
      $response->addCommand(new HtmlCommand('.highlighted .section', ""));
    }else {
      $response->addCommand(new HtmlCommand('.highlighted .section',
      '<div data-drupal-messages-fallback="" class="hidden"></div><div class="alert-wrapper" data-drupal-messages><div role aria-label="'.$this->stateForm[0].' message" class="alert alert-dismissible fade show col-12 alert-'.$this->stateForm[0].'" role="'.$this->stateForm[2].'"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' . $this->stateForm[1] . '</div></div>'));
    }
    return $response;
  }
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $form_len = strlen($form_state->getValue('cats_name'));

    if (!filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
      $message = 'The email is not valid. Please enter a valid email';
      $form_state->setErrorByName('email', $this->t($message));
      $this->stateForm = [
        'danger', $message, "alert", "email"
      ];
    }elseif ($form_len < 3) {
      $message = 'The cat name is too short. Please enter a valid cat name';
      $form_state->setErrorByName('cats_name', $this->t($message));
      $this->stateForm = [
        'danger', $message, "alert", "cats_name"
      ];
    }elseif ($form_len > 32) {
      $message = 'The cat name is too long. Please enter a valid cat name';
      $form_state->setErrorByName('cats_name', $this->t($message));
      $this->stateForm = [
        'danger', $message, "alert", "cats_name"
      ];
    }else {
      $this->stateForm [
        "noerror"
      ];
    }
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $config = $this->config('deku.settings');
    $config->set('deku.source_text', $form_state->getValue('cats_name'));
    $config->set('deku.email', $form_state->getValue('email'));
    $config->save();
    $message = 'The cat name has been saved';
    $this
    ->messenger()
    ->addStatus($this
    ->t($message));
    $this->stateForm = [
      "success", $message,"status"
    ];
  }
  public function setMessage(array $form, FormStateInterface $form_state) {

    $response = new AjaxResponse();
    if ($this->stateForm[0] == 'noerror') {
      $response->addCommand(new HtmlCommand('.highlighted .section', ""));
    }else {
      $response->addCommand(
      new HtmlCommand(
        '.highlighted .section',
        '<div data-drupal-messages-fallback="" class="hidden"></div><div class="alert-wrapper" data-drupal-messages><div role aria-label="'.$this->stateForm[0].' message" class="alert alert-dismissible fade show col-12 alert-'.$this->stateForm[0].'" role="'.$this->stateForm[2].'"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' . $this->stateForm[1] . '</div></div>'),
      );
    }
    return $response;

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