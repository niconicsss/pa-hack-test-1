// This script is for loading Google Maps and Places API
function initMap() {
    const userLocation = new google.maps.LatLng(10.289791482833758, 123.86146166142129); // Example coordinates
    const map = new google.maps.Map(document.getElementById('map'), {
        center: userLocation,
        zoom: 14
    });

    const service = new google.maps.places.PlacesService(map);

    const request = {
        location: userLocation,
        radius: 200000, // 200km radius from the user's location
        query: 'water supply store' // Example query to search for water-related businesses
    };

    service.textSearch(request, function(results, status) {
        if (status === google.maps.places.PlacesServiceStatus.OK) {
            results.forEach(place => {
                const marker = new google.maps.Marker({
                    position: place.geometry.location,
                    map: map,
                    title: place.name
                });

                const infowindow = new google.maps.InfoWindow({
                    content: `<strong>${place.name}</strong><br>${place.formatted_address}`
                });

                marker.addListener('click', function() {
                    infowindow.open(map, marker);
                });
            });
        } else {
            console.error('Google Places API Error:', status);
        }
    });
}
