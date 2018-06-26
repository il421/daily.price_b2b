'use strict';

function initMap() {
  window.myLatlng = {
    lat: -36.84866974,
    lng: 174.76463845
  };

  const map = new google.maps.Map(document.getElementById('map'), {
    zoom: 18,
    center: window.myLatlng
    // styles: [
    //         {
    //           featureType: 'poi.business',
    //           stylers: [
    //             {
    //               visibility: 'off'
    //             }
    //           ]
    //         },
    //         {
    //           featureType: 'poi.medical',
    //           stylers: [
    //             {
    //               visibility: 'off'
    //             }
    //           ]
    //         },
    //         {
    //           featureType: 'poi.place_of_worship',
    //           stylers: [
    //             {
    //               visibility: 'off'
    //             }
    //           ]
    //         },
    //         {
    //           featureType: 'poi.school',
    //           stylers: [
    //             {
    //               visibility: 'off'
    //             }
    //           ]
    //         },
    //         {
    //             featureType: 'poi.sports_complex',
    //             stylers: [
    //               {
    //                 visibility: 'off'
    //               }
    //             ]
    //         },
    //         {
    //           featureType: 'road',
    //           elementType: 'labels.icon',
    //           stylers: [
    //             {
    //               visibility: 'off'
    //             }
    //           ]
    //         },
    //         {
    //           featureType: 'transit',
    //           stylers: [
    //             {
    //               visibility: 'off'
    //             }
    //           ]
    //     }
    //   ]
  });


  map.addListener('click', function(evt) {
    const latField = document.querySelector('.edit-form-stores__lat');
    const longField = document.querySelector('.edit-form-stores__long');
    const latitude = evt.latLng.lat();
    const longitude = evt.latLng.lng();

    latField.value = latitude;
    longField.value = longitude;
  });
}
