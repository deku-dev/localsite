/**
 * @file
 * Global utilities.
 *
 */
(function ($, Drupal) {

  'use strict';
  // Delete or edit entry id set
  $(".js-modal__show").on("click", function () {
    let modalId = document.querySelector(this.getAttribute('data-bs-target') + ' .js-hidden-id');
    console.log(this.getAttribute('data-bs-target'));
    modalId.value = this.getAttribute('data-cat-id');
    let imageField = this.getAttribute('data-image-field');
    if (imageField == "") {
      document.getElementById('edit-delete-img').hidden = true;
    }else{
      document.getElementById('edit-delete-img').hidden = false;
    }
    if (this.hasAttribute('data-image-field') && imageField != "") {
      document.getElementById('image-js-thumb').innerHTML = "<img width='50px' height='50px' src='/sites/default/files/cats_images/"+ this.getAttribute('data-image-field') +"'>";
    }
  });
  // Delete some entry list create
  $("#js-delete-some").on("click", function () {
    let listElem = document.querySelectorAll(".js-delete-list:checked");
    let listId = "";
    for (const elemData in listElem) {
      if (Object.hasOwnProperty.call(listElem, elemData)) {
        const elemCheckbox = listElem[elemData];
        ;
        listId += elemCheckbox.getAttribute('data-cat-id') + ",";
        console.log(listId);
      }
      let modalId = document.querySelector('#deleteModal .js-hidden-id');
        modalId.value = listId;
    }

  })
  Drupal.behaviors.bootstrap_barrio_subtheme = {
    attach: function (context, settings) {
      // Add behaviors to elements
    }
  };

})(jQuery, Drupal);
