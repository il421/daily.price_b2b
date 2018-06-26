'use strict';
(function () {
  const userPaginator = document.querySelectorAll('.page-link');
  const windowLocation = window.location.href;

  window.utils.isPaginate(userPaginator, windowLocation);

})()
