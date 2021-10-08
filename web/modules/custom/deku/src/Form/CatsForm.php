<?php

namespace Drupal\deku\Form;

use Drupal;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\file\Entity\File;

/**
 * Form add cats to database.
 */
class CatsForm extends ConfigFormBase {

  /**
   * State form (error or complete)
   *
   * @var array
   */
  public $stateForm = 'noerror';

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return "deku_form";
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['cats_name'] = [
      '#type' => 'textfield',
      '#title' => $this
        ->t("Your cat's name:"),
      '#description' => $this->t("Min 2 and max 32 characters"),
      '#attributes' => [
        'min-length' => '2',
        'max-length' => '32',
      ],
      '#ajax' => [
        'callback' => '::validateForm',
        'event' => 'change',
      ],
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Your email:'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::validateForm',
        'event' => 'input',
      ],
      '#decription' => $this->t("Email is allowed for example: example@mail.com
      "),
      '#validators' => [
        'email',
      ],
      '#filters' => [
        'lowercase',
      ],
    ];
    $form['image_cats'] = [
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
    ];
    $form['add_cat'] = [
      '#type' => 'submit',
      '#value' => $this
        ->t('Add cat'),
      '#ajax' => [
        'callback' => '::setMessage',
        'event' => 'click',
      ],
    ];
    $form['label-error'] = [
      '#markup' => '<span id="messenger-label"></span>',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): AjaxResponse {
    $response = new AjaxResponse();
    $form_len = strlen($form_state->getValue('cats_name'));
    $message = [
      "",
    ];
    if ($form_len < 3) {
      $message = [
        'The cat name is too short. Please enter a valid cat name',
        "error",
      ];

    }
    elseif ($form_len > 32) {
      $message = [
        'The cat name is too long. Please enter a valid cat name',
        "error",
      ];
    }
    elseif (!filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
      $message = [
        "The email is not valid. Please enter a valid email",
        "error",
      ];
    }
    elseif ($form_state->hasValue('image_cats')) {
      $message = [
        "The cat image required",
        "error",
      ];
    }
    $response->addCommand(new HtmlCommand("#messenger-label", "<span class='text-danger'>" . $message[0] . "</span>"));
    return $response;
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $connection = Drupal::service('database');
    $event_image = $form_state->getValue('image_cats');
    $file = File::load(reset($event_image));
    $file->setPermanent();
    $file->save();
    $result = $connection->insert('deku')
      ->fields([
        'cats_name' => $form_state->getValue("cats_name"),
        'email' => $form_state->getValue('email'),
        'created' => Drupal::time()->getRequestTime(),
        'image_url' => $file->getFilename(),
      ])
      ->execute();
    if ($result) {
      $message = 'The cat name has been saved';
    }
    else {
      $message = "Error, please repeat";
    }
    $this
      ->messenger()
      ->addStatus($message);
    $this->stateForm = [
      "success", $message, "status",
    ];
  }

  /**
   * Set Message response to form.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Response to form send.
   */
  public function setMessage(): AjaxResponse {

    $response = new AjaxResponse();
    if ($this->stateForm[0] == 'noerror') {
      $response->addCommand(new HtmlCommand('.highlighted .section', ""));
    }
    else {
      $this
        ->messenger()
        ->addError($this->stateForm[1]);
    }
    return $response;

  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return [
      'deku.settings',
    ];
  }

}
