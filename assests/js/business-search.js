document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('businessSearchForm');
    const resultsContainer = document.getElementById('businessResults');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const noResultsMessage = document.getElementById('noResults');
    
    // Get user's location (you might have this from login/dashboard)
    let userLat = null;
    let userLng = null;
    
    // Check if we already have location in sessionStorage
    if (sessionStorage.getItem('userLocation')) {
        const location = JSON.parse(sessionStorage.getItem('userLocation'));
        userLat = location.lat;
        userLng = location.lng;
    } else {
        // Request location if not available
        getLocation();
    }
    
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!userLat || !userLng) {
            alert('Please enable location services to search nearby businesses');
            getLocation();
            return;
        }
        
        const category = document.getElementById('businessCategory').value;
        const radius = document.getElementById('searchRadius').value;
        
        searchBusinesses(userLat, userLng, radius, category);
    });
    
    function searchBusinesses(lat, lng, radius, category) {
        loadingIndicator.style.display = 'block';
        resultsContainer.innerHTML = '';
        noResultsMessage.style.display = 'none';
        
        fetch(`/business/search.php?lat=${lat}&lng=${lng}&radius=${radius}&category=${category}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                loadingIndicator.style.display = 'none';
                
                if (!data.success || data.data.length === 0) {
                    noResultsMessage.style.display = 'block';
                    return;
                }
                
                displayBusinesses(data.data);
            })
            .catch(error => {
                loadingIndicator.style.display = 'none';
                console.error('Error:', error);
                alert('Failed to load businesses. Please try again.');
            });
    }
    
    function displayBusinesses(businesses) {
        resultsContainer.innerHTML = businesses.map(business => `
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">${business.name}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">${business.category}</h6>
                        <p class="card-text">${business.address}</p>
                        <p class="text-muted">${business.distance.toFixed(1)} km away</p>
                        <a href="/orders/create.php?business_id=${business.id}" class="btn btn-sm btn-primary">Place Order</a>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                position => {
                    userLat = position.coords.latitude;
                    userLng = position.coords.longitude;
                    sessionStorage.setItem('userLocation', JSON.stringify({
                        lat: userLat,
                        lng: userLng
                    }));
                },
                error => {
                    console.error('Geolocation error:', error);
                    alert('Could not get your location. Using default location.');
                    // Fallback to a default location if needed
                    userLat = 14.5995; // Example: Manila coordinates
                    userLng = 120.9842;
                }
            );
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
    
    // Initial load with default parameters if we have location
    if (userLat && userLng) {
        searchBusinesses(userLat, userLng, 10, 'all');
    }
});