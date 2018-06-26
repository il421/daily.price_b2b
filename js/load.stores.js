'use strict';

(function () {
  const editFormStoresTitle = document.querySelector('.edit-form-stores__title');
  const editFormStoresForm = document.querySelector('.edit-form-stores__form');
  const btnSubmit = document.querySelector('.btn-submit');
  const btnAdd = document.querySelector('.btn-add');
  const btnEdit = document.querySelectorAll('.btn-edit');
  const btnClose = document.querySelector('.btn-close');
  const btnDel = document.querySelectorAll('.btn-delete');

  const storeId = document.querySelector('.edit-form-stores__id');
  const storeName = document.querySelector('.edit-form-stores__name');
  const storeAddress = document.querySelector('.edit-form-stores__address');
  const storeSuburd = document.querySelector('.edit-form-stores__suburb');
  const storeCity = document.querySelector('.edit-form-stores__city')
  const storeZip = document.querySelector('.edit-form-stores__zip');
  const storePhone = document.querySelector('.edit-form-stores__phone');
  const storeLat = document.querySelector('.edit-form-stores__lat');
  const storeLong = document.querySelector('.edit-form-stores__long');

  const isCloseModal = () => {
    editFormStoresForm.reset();
    window.utils.openAndFillDialog(editFormStoresTitle, '', editFormStoresForm, '', btnSubmit,'btn--save');
  };

  // Confirm deleting
  btnDel.forEach((item) => {
    item.addEventListener('click', (evt) => {
      const confirmAns = confirm('Do you realy want to delete?');
      if (!confirmAns) {
        evt.preventDefault();
      }
    });
  });

  // Open and fill for new stores
  btnAdd.addEventListener('click', () => {
    window.utils.openAndFillDialog(editFormStoresTitle, 'Add a new store', editFormStoresForm, 'server_files/add.new.store.php', btnSubmit, 'btn--save');
  });

  // Update stores
  btnEdit.forEach((item) => {
    item.addEventListener('click', (evt) => {
      const idPath = evt.path[2].cells['0'].innerText;

      const xhr = new XMLHttpRequest();
      xhr.addEventListener('load', () => {
        const dataValues = JSON.parse(xhr.responseText);

        storeId.value = dataValues[0];
        storeName.value = dataValues[1];
        storeAddress.value = dataValues[2];
        storeSuburd.value = dataValues[3];
        storeCity.value = dataValues[4];
        storeZip.value = dataValues[5];
        storePhone.value = dataValues[6];
        storeLat.value = dataValues[7];
        storeLong.value = dataValues[8];

        window.myLatlng.lat = dataValues[7];
        window.myLatlng.long = dataValues[8];

        window.utils.openAndFillDialog(editFormStoresTitle, 'Edit the store', editFormStoresForm, 'server_files/edit.stores.php', btnSubmit,'btn--save');
      });

      xhr.open('GET', 'server_files/get.stores.data.php?ID=' + idPath);
      xhr.send();
    });
  });

  // Close and fill off modal dialog
  btnClose.addEventListener('click', isCloseModal);

  document.addEventListener('keydown', (evt) => {
    if (window.utils.isDisactiavateEvent(evt)) {
      isCloseModal();
    }
  });
})()
