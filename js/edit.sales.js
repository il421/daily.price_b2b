'use strict';

(function () {

  const formEditSalesTitle = document.querySelector('.sell-product-form__title');
  const editFormSales = document.querySelector('.sell-product-form__form');
  const btnSell = document.querySelectorAll('.btn-sell');
  const btnSbm = document.querySelector('.btn-submit--sell');
  const btnClose = document.querySelector('.btn-close--sell');

  const prodId = document.querySelector('.sell-product-form__id');
  const prodName = document.querySelector('.sell-product-form__name')
  const prodDate = document.querySelector('.sell-product-form__date');
  const prodQty = document.querySelector('.sell-product-form__qty');
  const sellQty = document.querySelector('.sell-product-form__sell-qty');

  const isCloseModal = () => {
    editFormSales.reset();
    window.utils.openAndFillDialog(formEditSalesTitle, '', editFormSales, '', btnSbm, 'btn--save');
  };


  // Open and fill an offer form
  btnSell.forEach((item) => {
    item.addEventListener('click', (evt) => {
      const prodIDThis = evt.path[2].cells[0].innerText;
      const prodNameThis = evt.path[2].cells[1].innerText + ', ' + evt.path[2].cells[2].innerText + ' - ' + evt.path[2].cells[3].innerText;
      const bestDateThis = evt.path[2].cells[4].innerText;
      const prodQtyThis = evt.path[2].cells[5].innerText;

      prodId.value = prodIDThis;
      prodName.value = prodNameThis;
      prodDate.value = bestDateThis;
      prodQty.value = prodQtyThis;
      sellQty.max = prodQtyThis;
      sellQty.min = 1;

      window.utils.openAndFillDialog(formEditSalesTitle, 'Selling', editFormSales, 'server_files/edit.sales.php', btnSbm, 'btn--save');

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
