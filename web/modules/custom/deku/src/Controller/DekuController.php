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
  public function addCatsPage() {

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
  public function listCats(){
    $database = \Drupal::database();
    $query = $database->query("SELECT cats_name,email, image_url, created FROM {deku}");
    $rows = $query->fetchAll();
    $build['table'] = [
      '#type'=> 'table',
      '#caption' => $this->t('Cats list.'),
      '#header'=>[$this->t('Cat name'), $this->t('Email'), $this->t('Created'), $this->t('Image')],
      '#rows'=>$rows
    ];
    return $build;
  }

}