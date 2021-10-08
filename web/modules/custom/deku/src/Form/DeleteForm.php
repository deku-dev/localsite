<?php

namespace Drupal\deku\Form;

use Drupal;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Delete entry cats form.
 */
class DeleteForm extends ConfigFormBase {

  /**
   * Get form id.
   */
  public function getFormId(): string {
    return 'deku-form__delete';
  }

  /**
   * Build form delete.
   *
   * @param array $form
   *   Form object.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state array.
   *
   * @return array
   *   Form delete entry
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['delete_id'] = [
      '#type' => 'hidden',
      '#attributes' => [
        'class' => [
          'js-hidden-id',
        ],
      ],
    ];
    $form['delete'] = [
      '#type' => 'submit',
      '#value' => $this->t('Delete'),
      '#attributes' => [
        'class' => [
          'btn-danger',
        ],
      ],
    ];
    return $form;
  }

  /**
   * Submit function to form.
   *
   * @param array $form
   *   Form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   */
  public function submitForm(array &$form, FormStateInterface $form_state): AjaxResponse {
    $respAjax = new AjaxResponse();
    $conn = Drupal::database();
    $id_entry = $form_state->getValue('delete_id');
    foreach (explode(",", $id_entry) as $id_key) {
      $conn->delete('deku')
        ->condition('id', $id_key)
        ->execute();
    }

    return $respAjax;
  }

  /**
   * {@inheritDoc}
   */
  protected function getEditableConfigNames(): array {
    return [
      'deku.settings',
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

}
