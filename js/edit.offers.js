'use strict';

(function () {
  const editFormOffers = document.querySelector('.edit-form-offers__form');
  const btnEdit = document.querySelectorAll('.btn-edit');
  const btnClose = document.querySelector('.btn-close');
  const btnDel = document.querySelectorAll('.btn-delete');

  const prodId = document.querySelector('.edit-form-offers__id');
  const prodeName = document.querySelector('.edit-form-offers__name')
  const offerDate = document.querySelector('.edit-form-offers__date');
  const oldPrice = document.querySelector('.edit-form-offers__old');
  const newPrice = document.querySelector('.edit-form-offers__new');
  const upDatedrice = document.querySelector('.edit-form-offers__updated');
  const offerQty = document.querySelector('.edit-form-offers__qty');

  const isCloseModal = () => {
    editFormOffers.reset();
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


  // Open and fill an offer form
  btnEdit.forEach((item) => {
    item.addEventListener('click', (evt) => {
      const prodIDThis = evt.path[2].cells[0].innerText;
      const prodNameThis = evt.path[2].cells[1].innerText + ', ' + evt.path[2].cells[2].innerText + ', ' + evt.path[2].cells[3].innerText;
      const bestDateThis = evt.path[2].cells[4].innerText;
      const oldPriceThis = evt.path[2].cells[5].innerText;
      const newPriceThis = evt.path[2].cells[6].innerText;
      const updatedPriceThis = evt.path[2].cells[6].innerText;
      const offerQtyThis = evt.path[2].cells[8].innerText;

      prodId.value = prodIDThis;
      prodeName.value = prodNameThis;
      offerDate.value = bestDateThis;
      oldPrice.value = oldPriceThis;
      newPrice.value = newPriceThis;
      upDatedrice.value = updatedPriceThis;
      offerQty.value = offerQtyThis;
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
