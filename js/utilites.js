'use strict';
(function () {
  const ESCAPE_KEY_CODE = 27;
  window.utils = {
    isDisactiavateEvent: function (evt) {
      return evt.keyCode && evt.keyCode === ESCAPE_KEY_CODE;
    },

    openAndFillDialog: function (title, text, form, action, submit, icon) {
      title.innerHTML = text;
      form.action = action;
      submit.classList.toggle(icon);
    },

    filterTableByRole: function (arr, role) {
      const filteredArray = arr.filter((item) => {
          return item[5] == role;
        });
      return filteredArray;
    },

    formatDate: function (date) {
      let dd = date.getDate();
      if (dd < 10) dd = '0let' + dd;

      let mm = date.getMonth() + 1;
      if (mm < 10) mm = '0' + mm;

      let yyyy = date.getFullYear();
      if (yyyy < 10) yyyy = '0' + yyyy;

      return yyyy + '-' + mm + '-' + dd;
    },

    isPaginate: function (a, b) {
      if (window.location.search === '') {
        a[0].parentNode.classList.toggle('active');
      } else {
        a.forEach((item) => {
          if (b === item.href) {
            item.parentNode.classList.toggle('active');
          }
        })
      }
    }
  }
})()
