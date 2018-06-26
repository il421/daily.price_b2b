'use strict';
(function () {
  window.load = function (url, onLoad) {
    var xhr = new XMLHttpRequest();

    xhr.addEventListener('load', onLoad);

    xhr.addEventListener('error', function() {
      alert('Something\'s went wrong');
      });

    xhr.addEventListener('timeout', function() {
        alert('Time\'s up!');
      });

    xhr.open('GET', url);
    xhr.send();
  }
})()
