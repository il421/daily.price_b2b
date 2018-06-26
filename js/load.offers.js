'use strict';

(function () {

  const addFormOffers = document.querySelector('.add-form-offers__form');
  const offerProduct = addFormOffers.querySelector('.add-form-offers__product p');

  const btnOffer = document.querySelectorAll('.btn-offer');
  const btnClose = addFormOffers.querySelector('.btn-close--offer');

  const offerDate = addFormOffers.querySelector('.add-form-offers__date');
  const totalQty = addFormOffers.querySelector('.add-form-offers__total-qty');
  const offerQty = addFormOffers.querySelector('.add-form-offers__offer-qty');
  const prodId = addFormOffers.querySelector('.add-form-offers__id');
  const productDateAll = document.querySelectorAll('.operate-table__date');
  let systemDate = new Date();

  const isCloseModal = () => {
    addFormOffers.reset();
    offerProduct.innerText = '';
  };

  // Open and fill an offer form
  btnOffer.forEach((item) => {
    item.addEventListener('click', (evt) => {
      // Check expire date and block offer
      const offerDateThis = evt.path[2].cells[4].innerText;
      if (window.utils.formatDate(systemDate) >= offerDateThis) item.disabled = true;

      const prodQtyThis = evt.path[2].cells[5].innerText;
      const offerQtyThis = evt.path[2].cells[6].innerText;
      const prodIdThis = evt.path[2].cells[0].innerText;
      const prodNameThis = evt.path[2].cells[2].innerText;
      const prodPriceThis = evt.path[2].cells[3].innerText;
      const offerProductName = prodNameThis + ' - ' + prodPriceThis;

      offerDate.value = offerDateThis;
      totalQty.value = prodQtyThis;
      prodId.value = prodIdThis;
      offerQty.max = prodQtyThis - offerQtyThis;
      offerProduct.innerText = offerProductName;

      // Limit number of offers for one products__title
      if (+prodQtyThis <= +offerQtyThis) {
        item.disabled = true;
      }
    });
  });

  // Close and fill off modal dialog
  btnClose.addEventListener('click', isCloseModal);

  document.addEventListener('keydown', (evt) => {
    if (window.utils.isDisactiavateEvent(evt)) {
      isCloseModal();
    }
  });

  // Monitor best berofe date
  productDateAll.forEach((item) => {
    window.utils.formatDate(systemDate) >= item.innerText? item.style.color = 'red':item.style.color = '#7ED321';
  });
})()
