'use strict';

(function () {
  const editFormProductsTitle = document.querySelector('.edit-form-products__title');
  const editFormProductsForm = document.querySelector('.edit-form-products__form');
  const btnSubmit = document.querySelector('.btn-submit');
  const btnAdd = document.querySelector('.btn-add');
  const btnEdit = document.querySelectorAll('.btn-edit');
  const btnClose = document.querySelector('.btn-close');
  const btnDel = document.querySelectorAll('.btn-delete');

  const prodId = document.querySelector('.edit-form-products__id');
  const prodCat = document.querySelector('.edit-form-products__cat');
  const prodSubcat = document.querySelector('.edit-form-products__subcat');
  const prodName = document.querySelector('.edit-form-products__name');
  const prodBrand = document.querySelector('.edit-form-products__brand')
  const prodUnit = document.querySelector('.edit-form-products__unit');
  const prodPrice = document.querySelector('.edit-form-products__price');
  const prodPhoto = document.querySelector('.edit-form-products__photo');
  const prodPreview = document.querySelector('.edit-form-products__preview');

  // Confirm deleting
  btnDel.forEach((item) => {
    item.addEventListener('click', (evt) => {
      const confirmAns = confirm('Do you realy want to delete?');
      if (!confirmAns) {
        evt.preventDefault();
      }
    });
  });

  // Open and fill for new products
  btnAdd.addEventListener('click', () => {
    window.utils.openAndFillDialog(editFormProductsTitle, 'Add a new category', editFormProductsForm, 'server_files/add.new.cat.php', btnSubmit, 'btn--save');
  });

  // Update products
  btnEdit.forEach((item) => {
    item.addEventListener('click', (evt) => {
      const idPath = evt.path[2].cells['0'].innerText;

      const xhr = new XMLHttpRequest();
      xhr.addEventListener('load', () => {
        const dataValues = JSON.parse(xhr.responseText);

        prodId.value = dataValues[0];
        prodCat.value = dataValues[1];
        prodSubcat.value = dataValues[2];
        prodBrand.value = dataValues[3];
        prodName.value = dataValues[4];
        prodUnit.value = dataValues[5];
        prodPrice.value = dataValues[6];

        prodPreview.style.backgroundImage = 'url(' + dataValues[7] + ')';

        window.utils.openAndFillDialog(editFormProductsTitle, 'Edit the category', editFormProductsForm, 'server_files/edit.cat.php', btnSubmit, 'btn--save');
      });

      xhr.open('GET', 'server_files/get.cat.data.php?ID=' + idPath);
      xhr.send();
    });
  });

  // Close and fill off modal dialog
  btnClose.addEventListener('click', () => {
    editFormProductsForm.reset();
    prodPreview.style.backgroundImage = 'url(img/img.png)';
    window.utils.openAndFillDialog(editFormProductsTitle, '', editFormProductsForm, '', btnSubmit,'btn--save');
  });

  document.addEventListener('keydown', (evt) => {
    if (window.utils.isDisactiavateEvent(evt)) {
      editFormProductsForm.reset();
      prodPreview.style.backgroundImage = 'url(img/img.png)';
      window.utils.openAndFillDialog(editFormProductsTitle, '', editFormProductsForm, '', btnSubmit,'btn--save');
        }
  });

  prodPhoto.addEventListener('change', (evt) => {
    prodPreview.style.backgroundImage = 'url(' + URL.createObjectURL(evt.target.files[0]) + ')';
  });

})()
