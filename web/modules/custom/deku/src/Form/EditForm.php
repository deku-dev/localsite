<?php

namespace Drupal\deku\Form;

use Drupal;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\file\Entity\File;

/**
 * Form to edit or delete entry in database.
 */
class EditForm extends ConfigFormBase {

  /**
   * Get form id.
   */
  public function getFormId(): string {
    return 'deku-form__edit';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['edit_id'] = [
      '#type' => 'hidden',
      '#prefix' => '<div class="modal-body">',
      '#attributes' => [
        'class' => [
          'js-hidden-id',
        ],
      ],
    ];
    $form['cats_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t("Your cat's name:"),
      '#description' => $this->t('Min 2 and max 32 characters'),
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
      '#title' => $this->t('Your email'),
      '#ajax' => [
        'callback' => '::validateForm',
        'event' => 'change',
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
    ];
    $form['image_thumb'] = [
      '#markup' => '<div id="image-js-thumb"></div>',
    ];
    $form['delete_img'] = [
      '#type' => 'button',
      '#value' => 'Delete Image',
      '#ajax' => [
        'callback' => '::deleteImage',
        'event' => 'click',
      ],
      '#attributes' => [
        'class' => ['btn', 'btn-danger', 'w-25', 'my-3'],
      ],
    ];
    $form['error-label'] = [
      '#markup' => '<span id="error-edit" class="text-danger label-alert"></span>',
      '#suffix' => '</div>',
    ];
    $form['cancel-modal'] = [
      '#markup' => '<button type="button" class="btn btn-secondary mx-2" data-bs-dismiss="modal">Cancel</button>',
      '#prefix' => '<div class="modal-footer">',
    ];
    $form['edit_cat'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#suffix' => '</div>',
    ];
    return $form;
  }

  /**
   * Validate form.
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
    $response->addCommand(new HtmlCommand("#error-edit", "<span class='text-danger'>" . $message[0] . "</span>"));
    return $response;
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $connection = Drupal::service('database');
    // Save image to filesystem and get filename.
    $image_edit = $form_state->getValue('image_cats');
    $file = File::load(reset($image_edit));
    $file->setPermanent();
    $file->save();
    $filename = $file->getFilename();
    if ($form_state->getValue('delete_image')) {
      $filename = "";
    }
    $valueList = [
      'cats_name' => $form_state->getValue('cats_name'),
      'email' => $form_state->getValue('email'),
      'image_url' => $filename,
    ];
    foreach ($valueList as $field => $value) {
      if (empty($value)) {
        continue;
      }
      $connection->update('deku')
        ->fields([
          $field => $value,
        ])
        ->condition('id', $form_state->getValue('edit_id'))
        ->execute();
    }

  }

  /**
   * Delete field image from database.
   */
  public function deleteImage(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $database = Drupal::service('database');
    $id = $form_state->getValue('edit_id');
    $database->update('deku')
      ->fields([
        'image_url' => "",
      ])
      ->condition('id', $id)
      ->execute();
    $response->addCommand(new HtmlCommand('#image-js-thumb', ''));
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
