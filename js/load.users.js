'use strict';

(function () {
  const editFormUserTitle = document.querySelector('.edit-form-users__title');
  const editFormUserForm = document.querySelector('.edit-form-users__form');
  const btnSubmit = document.querySelector('.btn-submit');

  const userId = document.querySelector('.edit-form-users__id');
  const userEmail = document.querySelector('.edit-form-users__email');
  const userFirst = document.querySelector('.edit-form-users__first');
  const userLast = document.querySelector('.edit-form-users__last');
  const userPhone = document.querySelector('.edit-form-users__phone')
  const userActive = document.querySelector('.edit-form-users__active');
  const userPwd = document.querySelector('.edit-form-users__pwd');

  const isCloseModal = () => {
    editFormUserForm.reset();
    window.utils.openAndFillDialog(editFormUserTitle, '', editFormUserForm, '', btnSubmit,'btn--save');
  };

  const renderForm = (data) => {
    userId.value = data[0];
    userEmail.value = data[1];
    userFirst.value = data[2];
    userLast.value = data[3];
    userPhone.value = data[4];

    switch (data[5]) { // check activation
      case 'basic':
        document.querySelector('#user_role1').checked = true;
      break;
      case 'staff':
        document.querySelector('#user_role2').checked = true;
      break;
      case 'admin':
        document.querySelector('#user_role3').checked = true;
      break;
    }

    data[6] == 1 ? userActive.checked = true : userActive.checked = false; // check role
  };

  window.operateForm = (add, edit, close, del) => {
    // New users
    add.addEventListener('click', () => {
      window.utils.openAndFillDialog(editFormUserTitle, 'Add a new user', editFormUserForm, 'server_files/add.new.user.php', btnSubmit, 'btn--save');
      userPwd.required = true;
    });

    // Update users
    edit.forEach((item) => {
      item.addEventListener('click', (evt) => {
        const idPath = evt.path[2].cells['0'].innerText;
        const xhr = new XMLHttpRequest();

        xhr.addEventListener('load', () => {
          const dataValues = JSON.parse(xhr.responseText);
          renderForm(dataValues);
          window.utils.openAndFillDialog(editFormUserTitle, 'Edit the user', editFormUserForm, 'server_files/edit.users.php', btnSubmit,'btn--save');
          userPwd.required = false;
        });

        xhr.open('GET', 'server_files/get.users.data.php?ID=' + idPath);
        xhr.send();
      });
    });

    // Close and fill off modal dialog
    close.addEventListener('click', isCloseModal);

    // Confirm deleting
    del.forEach((item) => {
      item.addEventListener('click', (evt) => {
        const confirmAns = confirm('Do you realy want to delete?');
        if (!confirmAns) {
          evt.preventDefault();
        }
      });
    });

    document.addEventListener('keydown', (evt) => {
      if (window.utils.isDisactiavateEvent(evt)) {
        isCloseModal();
      }
    });
  }
})()
