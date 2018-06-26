'use strict';

(function () {
  const addFormProductsTitle = document.querySelector('.add-form-products__title');
  const addFormProductsForm = document.querySelector('.add-form-products__form');
  const btnSubmit = document.querySelector('.btn-submit--products');
  const btnAdd = document.querySelector('.btn-add');
  const btnClose = document.querySelector('.btn-close--products');

  const prodId = document.querySelector('.add-form-products__id');
  const prodSelect = document.querySelector('.add-form-products__select');
  const prodPrice = document.querySelector('.add-form-products__price');

  const isCloseModal = () => {
    addFormProductsForm.reset();
    window.utils.openAndFillDialog(addFormProductsTitle, '', addFormProductsForm, '', btnSubmit,'btn--save');
  };

  // Open and fill for new stores
  btnAdd.addEventListener('click', () => {
    window.utils.openAndFillDialog(addFormProductsTitle, 'Add products', addFormProductsForm, 'server_files/add.new.prod.php', btnSubmit, 'btn--save');
  });

  prodSelect.addEventListener('change', (evt) => {
    const selectId = evt.target.selectedOptions['0'].attributes['0'].value;
    const selectPrice = evt.target.selectedOptions['0'].attributes['1'].value;

    prodId.value = selectId;
    prodPrice.value = '$' + selectPrice;
  });

  // Close and fill off modal dialog
  btnClose.addEventListener('click', isCloseModal);

  document.addEventListener('keydown', (evt) => {
    if (window.utils.isDisactiavateEvent(evt)) {
      isCloseModal();
    }
  });
})()
