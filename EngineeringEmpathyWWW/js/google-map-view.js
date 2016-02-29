var locations = [
  ['401 Quarry Road, Palo Alto, CA 94304', 37.438627, -122.170725, 1],
  ['201 San Antonio Circle, Suite 200, Mountain View, CA', 37.406509, -122.109083, 2],
  ['1100 S El Camino Real, Suite #1, San Mateo, CA', 37.556464, -122.319298, 3]
];

var map = new google.maps.Map(document.getElementById('map'), {
  zoom: 10,
  center: new google.maps.LatLng(37.425568, -122.173729),
  mapTypeId: google.maps.MapTypeId.ROADMAP
});

var infowindow = new google.maps.InfoWindow();

var marker, i;

for (i = 0; i < locations.length; i++) {  
  marker = new google.maps.Marker({
    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
    map: map
  });

  google.maps.event.addListener(marker, 'click', (function(marker, i) {
    return function() {
      infowindow.setContent(locations[i][0]);
      infowindow.open(map, marker);
    }
  })(marker, i));
}