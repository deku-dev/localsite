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
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('Hello! You can add here a photo of your cat.'),
    ];
    $build['form_cats'] = \Drupal::formBuilder()->getForm('Drupal\deku\Form\CatsForm');

    return $build;
  }


  public function content() {
    return array(
        '#theme' => 'cats-template',
        '#cats_list' => $test/* result query */,
    );
}

}
