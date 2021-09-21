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
   * Function render list all cats
   *
   * @return array
   */
  private function getCatsData(): array {
    $database = \Drupal::database();
    $query = $database->query("SELECT cats_name,email, image_url, created FROM {deku}");
    return $query->fetchAll();
  }



  public function buildList(): array {
    $form_edit = \Drupal::formBuilder()->getForm('Drupal\deku\Form\ControlCats');
    return array(
        '#theme' => 'cats-template',
        '#cats_list' => $this->getCatsData(),
        '#form_edit' => $form_edit
    );
}

}
