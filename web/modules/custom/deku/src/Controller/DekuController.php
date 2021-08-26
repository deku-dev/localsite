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

    return $build;
  }

}
