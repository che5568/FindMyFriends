<?php
include_once('includes/connect.php');
include_once('includes/logincheck.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Profile_Page</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets\css\user_profile.css">
</head>
<style>
  #map {
    height: 400px;
  }
</style>

<body>
  <div class="container">
    <?php
    include_once('includes/navbar_profile.php');
    ?>

    <div id="map"></div>

  </div>
  <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <script>
    var map = L.map('map').setView([0, 0], 2); // Center the map at [0, 0] with zoom level 2
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    $(document).ready(function() {
      // Fetch data from the AJAX API and display markers on the map
      var friendMarkers = {}; // Object to store markers by friend ID

      function getFriendsLocations() {
        $.ajax({
          method: 'POST',
          url: 'ajax_api.php',
          data: {
            action: 'getFriendsLocations'
          },
          dataType: 'json',
          success: function(data) {
            // Create a new set of friend IDs from the data
            let currentIds = new Set(data.map(item => item.id));

            // Remove markers for friends no longer present
            Object.keys(friendMarkers).forEach(id => {
              if (!currentIds.has(id)) {
                map.removeLayer(friendMarkers[id]);
                delete friendMarkers[id];
              }
            });

            // Update existing markers or create new ones
            data.forEach(function(item) {
              if (friendMarkers[item.id]) {
                // If marker exists, update its position
                friendMarkers[item.id].setLatLng([item.latitude, item.longitude]);
                friendMarkers[item.id].getPopup().setContent("<b>" + item.name + "</b>");
              } else {
                // Create a new marker and add it to the map
                var marker = L.marker([item.latitude, item.longitude], {
                  title: item.name
                }).addTo(map);
                marker.bindPopup("<b>" + item.name + "</b>").openPopup();
                friendMarkers[item.id] = marker; // Store the marker with id as key
              }
            });
          },
          error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
          }
        });
      }

      setInterval(getFriendsLocations, 2000);

      function sendLocation() {
        function getLocation() {
          if (navigator.geolocation) {
            console.log("Attempting to get user's location...");
            navigator.geolocation.getCurrentPosition(showPosition, showError);
          } else {
            console.log("Geolocation is not supported by this browser.");
          }
        }

        function showPosition(position) {
          const latitude = position.coords.latitude;
          const longitude = position.coords.longitude;
          console.log(`Position obtained: (${latitude}, ${longitude})`);

          // Send latitude and longitude to ajax_api.php using jQuery AJAX
          $.ajax({
            type: "POST",
            url: "ajax_api.php",
            data: {
              action: 'sendFriendsLocations',
              latitude: latitude,
              longitude: longitude
            },
            success: function(response) {
              console.log("Server response:", response);
            },
            error: function(xhr, status, error) {
              console.log("AJAX error:", status, error);
            }
          });
        }

        function showError(error) {
          console.error(`Geolocation error: ${error.message}`);
        }

        // getLocation();
        setInterval(getLocation, 2000);
      }


      sendLocation();
    });
  </script>
</body>

</html>