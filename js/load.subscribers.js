'use strict';

(function() {
  const btnDel = document.querySelectorAll('.btn-delete');

  btnDel.forEach((item) => {
    item.addEventListener('click', (evt) => {
      const confirmAns = confirm('Do you realy want to delete?');
      if (!confirmAns) {
        evt.preventDefault();
      }
    });
  });
})()
