<?php

namespace Drupal\deku\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a modal window
 *
 * @Block(
 *   id = "deku-modal__block",
 *   admin_label = @Translation("Modal Block"),
 *   category = @Traslation("Modal")
 * )
 */


class ModalBlock extends BlockBase{
  /**
   * @{inheritdoc}
   */
  public function build(){
    return [
      "#markup"=> $this->t("Modal Block")
    ];
  }
}