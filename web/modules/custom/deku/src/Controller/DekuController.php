<?php

namespace Drupal\deku\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Deku routes.
 */
class DekuController extends ControllerBase {
  
  /**
   * Builds the response.
   */
  public function addCatsPage(): array {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('Hello! You can add here a photo of your cat.'),
    ];
    $build['form_cats'] = \Drupal::formBuilder()->getForm('Drupal\deku\Form\CatsForm');

    return $build;
  }

  /**
   * Function render list all cats.
   *
   * @return array
   *   Result query
   */
  private function getCatsData(): array {
    $database = \Drupal::database();
    $query = $database->query("SELECT id, cats_name,email, image_url, created FROM {deku}");
    return $query->fetchAll();
  }

  /**
   * Build all element from database.
   *
   * @return array
   *   Render twig template.
   */
  public function buildList(): array {
    $form_edit = \Drupal::formBuilder()->getForm('Drupal\deku\Form\EditForm');
    $form_delete = \Drupal::formBuilder()->getForm('Drupal\deku\Form\DeleteForm');
    return [
      '#theme' => 'cats-template',
      '#cats_list' => $this->getCatsData(),
      '#form_edit' => $form_edit,
      '#form_delete' => $form_delete,
    ];
  }

}
