# Opal Haven - User Frontend API Integration Guide

> This guide provides code snippets to integrate your existing user frontend with the Laravel backend API.

## Table of Contents
1. [Setup](#setup)
2. [Authentication](#authentication)
3. [Hotels & Rooms](#hotels--rooms)
4. [Bookings](#bookings)
5. [Reviews](#reviews)
6. [Error Handling](#error-handling)

---

## Setup

Add these constants to your `main.js` file:

```javascript
/**
 * API Configuration
 */
const API_BASE_URL = 'http://localhost:8000/api';

/**
 * Get stored user token
 */
function getToken() {
    return localStorage.getItem('userToken');
}

/**
 * Store user token
 */
function setToken(token) {
    localStorage.setItem('userToken', token);
}

/**
 * Get current user
 */
function getCurrentUser() {
    const userStr = localStorage.getItem('currentUser');
    return userStr ? JSON.parse(userStr) : null;
}

/**
 * Store current user
 */
function setCurrentUser(user) {
    localStorage.setItem('currentUser', JSON.stringify(user));
}

/**
 * Clear auth data
 */
function clearAuthData() {
    localStorage.removeItem('userToken');
    localStorage.removeItem('currentUser');
}

/**
 * Get authorization headers
 */
function authHeaders() {
    const token = getToken();
    return {
        'Authorization': token ? `Bearer ${token}` : '',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    };
}

/**
 * Make API request
 */
async function apiRequest(endpoint, options = {}) {
    const url = `${API_BASE_URL}${endpoint}`;
    const headers = authHeaders();

    try {
        const response = await fetch(url, {
            ...options,
            headers: { ...headers, ...options.headers },
        });

        if (response.status === 401) {
            // Token expired - redirect to login
            clearAuthData();
            window.location.href = 'login.html';
            return null;
        }

        const data = await response.json();

        if (!response.ok) {
            handleApiError(data, response.status);
            return null;
        }

        return data;
    } catch (error) {
        console.error('API Error:', error);
        showAlert('Network error. Please try again.', 'danger');
        return null;
    }
}

/**
 * Handle API errors
 */
function handleApiError(error, status) {
    let message = 'An error occurred';

    if (status === 422) {
        // Validation errors
        if (error.errors) {
            message = Object.values(error.errors)[0][0];
        }
    } else if (status === 404) {
        message = 'Not found';
    } else if (error.message) {
        message = error.message;
    }

    showAlert(message, 'danger');
}
```

---

## Authentication

### 1. User Registration

Replace your existing signup form handler:

```javascript
async function handleSignup(e) {
    e.preventDefault();

    const name = document.getElementById('signup-name').value;
    const email = document.getElementById('signup-email').value;
    const password = document.getElementById('signup-password').value;
    const passwordConfirm = document.getElementById('signup-password-confirm').value;

    if (password !== passwordConfirm) {
        showAlert('Passwords do not match', 'danger');
        return;
    }

    const result = await apiRequest('/auth/register', {
        method: 'POST',
        body: JSON.stringify({
            name: name,
            email: email,
            password: password,
            password_confirmation: passwordConfirm,
        }),
    });

    if (result) {
        setToken(result.token);
        setCurrentUser(result.user);
        showAlert('Registration successful! Redirecting...', 'success');
        setTimeout(() => {
            window.location.href = 'index.html';
        }, 1500);
    }
}
```

### 2. User Login

Replace your existing login form handler:

```javascript
async function handleLogin(e) {
    e.preventDefault();

    const email = document.getElementById('login-email').value;
    const password = document.getElementById('login-password').value;

    const result = await apiRequest('/auth/login', {
        method: 'POST',
        body: JSON.stringify({
            email: email,
            password: password,
        }),
    });

    if (result) {
        setToken(result.token);
        setCurrentUser(result.user);
        showAlert('Login successful! Redirecting...', 'success');
        setTimeout(() => {
            window.location.href = 'index.html';
        }, 1500);
    }
}
```

### 3. Check Authentication & Update UI

Add this to your navigation initialization:

```javascript
function initializeNavigation() {
    const token = getToken();
    const user = getCurrentUser();

    const loginLink = document.getElementById('login-link');
    const signupLink = document.getElementById('signup-link');
    const logoutButton = document.getElementById('logout-button');
    const dashboardLink = document.getElementById('dashboard-link');
    const navUserWelcome = document.getElementById('nav-user-welcome');

    if (token && user) {
        // User is logged in
        if (loginLink) loginLink.style.display = 'none';
        if (signupLink) signupLink.style.display = 'none';
        if (logoutButton) {
            logoutButton.style.display = 'inline-block';
            logoutButton.onclick = handleLogout;
        }
        if (dashboardLink) dashboardLink.classList.remove('hidden');
        if (navUserWelcome) {
            navUserWelcome.classList.remove('hidden');
            navUserWelcome.textContent = `Welcome, ${user.name}!`;
        }
    } else {
        // User is not logged in
        if (loginLink) loginLink.style.display = 'inline-block';
        if (signupLink) signupLink.style.display = 'inline-block';
        if (logoutButton) logoutButton.style.display = 'none';
        if (dashboardLink) dashboardLink.classList.add('hidden');
        if (navUserWelcome) navUserWelcome.classList.add('hidden');
    }
}

async function handleLogout() {
    const result = await apiRequest('/auth/logout', {
        method: 'POST',
    });

    if (result) {
        clearAuthData();
        showAlert('Logged out successfully', 'success');
        setTimeout(() => {
            window.location.href = 'index.html';
        }, 1000);
    }
}

// Call on page load
document.addEventListener('DOMContentLoaded', initializeNavigation);
```

---

## Hotels & Rooms

### 1. Load Hotels with Filters

Replace your existing hotels loading logic:

```javascript
async function loadHotels(filters = {}) {
    let endpoint = '/hotels?';

    // Build query parameters
    const params = [];
    if (filters.city) params.push(`city=${encodeURIComponent(filters.city)}`);
    if (filters.country) params.push(`country=${encodeURIComponent(filters.country)}`);
    if (filters.star_rating) params.push(`star_rating=${filters.star_rating}`);
    if (filters.check_in) params.push(`check_in=${filters.check_in}`);
    if (filters.check_out) params.push(`check_out=${filters.check_out}`);

    endpoint += params.join('&');

    const data = await apiRequest(endpoint);
    if (!data || !data.data) return;

    displayHotels(data.data);
}

function displayHotels(hotels) {
    const container = document.getElementById('hotels-container');
    container.innerHTML = '';

    hotels.forEach(hotel => {
        const hotelHTML = `
            <div class="hotel-card">
                <div class="hotel-image">
                    <img src="${hotel.images[0] || 'https://via.placeholder.com/300'}" alt="${hotel.name}" />
                </div>
                <div class="hotel-details">
                    <h3>${hotel.name}</h3>
                    <p class="hotel-location">${hotel.city}, ${hotel.country}</p>
                    <p class="hotel-rating">⭐ ${hotel.star_rating}/5</p>
                    <p class="hotel-description">${hotel.description}</p>
                    <button class="btn btn-primary" onclick="viewHotelDetails(${hotel.id})">View Rooms</button>
                </div>
            </div>
        `;
        container.innerHTML += hotelHTML;
    });
}
```

### 2. Load Hotel Details & Rooms

```javascript
async function loadHotelRooms(hotelId) {
    const data = await apiRequest(`/hotels/${hotelId}`);
    if (!data) return;

    const roomsData = await apiRequest(`/hotels/${hotelId}/rooms`);
    if (!roomsData || !roomsData.data) return;

    displayHotelDetails(data, roomsData.data);
}

function displayHotelDetails(hotel, rooms) {
    const container = document.getElementById('hotel-details-container');
    
    let roomsHTML = rooms.map(room => `
        <div class="room-card">
            <img src="${room.images[0] || 'https://via.placeholder.com/300'}" alt="${room.name}" />
            <h4>${room.name}</h4>
            <p class="room-type">${room.room_type}</p>
            <p class="room-description">${room.description}</p>
            <p class="room-amenities">Amenities: ${(room.amenities || []).join(', ')}</p>
            <div class="room-footer">
                <span class="room-price">${formatCurrency(room.price_per_night)}/night</span>
                <span class="room-guests">Max ${room.max_guests} guests</span>
                <button class="btn btn-primary" onclick="bookRoom(${room.id}, ${hotel.id})">Book Now</button>
            </div>
        </div>
    `).join('');

    container.innerHTML = `
        <div class="hotel-header">
            <h1>${hotel.name}</h1>
            <p>${hotel.address}, ${hotel.city}, ${hotel.country}</p>
        </div>
        <div class="rooms-grid">
            ${roomsHTML}
        </div>
    `;
}
```

### 3. Check Room Availability

```javascript
async function checkRoomAvailability(roomId, checkIn, checkOut) {
    const result = await apiRequest(
        `/rooms/${roomId}/availability?check_in=${checkIn}&check_out=${checkOut}`
    );

    if (result) {
        return result.available;
    }
    return false;
}
```

---

## Bookings

### 1. Create Booking

Replace your existing booking form handler:

```javascript
async function submitBooking(e) {
    e.preventDefault();

    const token = getToken();
    if (!token) {
        showAlert('Please login to make a booking', 'warning');
        window.location.href = 'login.html';
        return;
    }

    const roomId = document.getElementById('room-id').value;
    const hotelId = document.getElementById('hotel-id').value;
    const checkIn = document.getElementById('checkin-date').value;
    const checkOut = document.getElementById('checkout-date').value;
    const guests = document.getElementById('guest-count').value;
    const specialRequests = document.getElementById('special-requests').value;

    // Check availability first
    const available = await checkRoomAvailability(roomId, checkIn, checkOut);
    if (!available) {
        showAlert('Room is not available for selected dates', 'danger');
        return;
    }

    const result = await apiRequest('/bookings', {
        method: 'POST',
        body: JSON.stringify({
            room_id: parseInt(roomId),
            hotel_id: parseInt(hotelId),
            check_in_date: checkIn,
            check_out_date: checkOut,
            guests: parseInt(guests),
            special_requests: specialRequests,
        }),
    });

    if (result) {
        showAlert('Booking created successfully!', 'success');
        // Redirect to dashboard or booking confirmation
        setTimeout(() => {
            window.location.href = 'dashboard.html';
        }, 1500);
    }
}
```

### 2. Load User Bookings (Dashboard)

```javascript
async function loadUserBookings() {
    const token = getToken();
    if (!token) {
        window.location.href = 'login.html';
        return;
    }

    const data = await apiRequest('/bookings');
    if (!data || !data.data) return;

    displayUserBookings(data.data);
}

function displayUserBookings(bookings) {
    const container = document.getElementById('bookings-container');
    container.innerHTML = '';

    if (bookings.length === 0) {
        container.innerHTML = '<p class="text-muted">No bookings yet</p>';
        return;
    }

    bookings.forEach(booking => {
        const statusBadge = `
            <span class="badge badge-${getStatusColor(booking.status)}">
                ${booking.status.charAt(0).toUpperCase() + booking.status.slice(1)}
            </span>
        `;

        const bookingHTML = `
            <div class="booking-card">
                <div class="booking-header">
                    <h4>${booking.hotel.name}</h4>
                    ${statusBadge}
                </div>
                <div class="booking-details">
                    <p><strong>Room:</strong> ${booking.room.name}</p>
                    <p><strong>Check-in:</strong> ${formatDate(booking.check_in_date)}</p>
                    <p><strong>Check-out:</strong> ${formatDate(booking.check_out_date)}</p>
                    <p><strong>Guests:</strong> ${booking.guests}</p>
                    <p><strong>Total:</strong> ${formatCurrency(booking.total_price)}</p>
                    ${booking.special_requests ? `<p><strong>Requests:</strong> ${booking.special_requests}</p>` : ''}
                </div>
                <div class="booking-actions">
                    ${booking.status !== 'completed' && booking.status !== 'cancelled' ? 
                        `<button class="btn btn-danger" onclick="cancelBooking(${booking.id})">Cancel Booking</button>` : 
                        ''}
                    ${booking.status === 'completed' ? 
                        `<button class="btn btn-primary" onclick="leaveReview(${booking.hotel_id}, ${booking.id})">Leave Review</button>` : 
                        ''}
                </div>
            </div>
        `;

        container.innerHTML += bookingHTML;
    });
}

function getStatusColor(status) {
    const colors = {
        'pending': 'warning',
        'confirmed': 'success',
        'cancelled': 'danger',
        'completed': 'secondary',
    };
    return colors[status] || 'secondary';
}
```

### 3. Cancel Booking

```javascript
async function cancelBooking(bookingId) {
    if (!confirm('Are you sure you want to cancel this booking?')) return;

    const result = await apiRequest(`/bookings/${bookingId}/cancel`, {
        method: 'PATCH',
    });

    if (result) {
        showAlert('Booking cancelled successfully', 'success');
        loadUserBookings(); // Reload bookings
    }
}
```

---

## Reviews

### 1. Leave Review

```javascript
async function submitReview(hotelId, bookingId = null) {
    const rating = document.getElementById('review-rating').value;
    const comment = document.getElementById('review-comment').value;

    const token = getToken();
    if (!token) {
        showAlert('Please login to leave a review', 'warning');
        return;
    }

    const result = await apiRequest(`/hotels/${hotelId}/reviews`, {
        method: 'POST',
        body: JSON.stringify({
            hotel_id: hotelId,
            booking_id: bookingId,
            rating: parseInt(rating),
            comment: comment,
        }),
    });

    if (result) {
        showAlert('Review submitted successfully!', 'success');
        document.getElementById('review-form').reset();
        loadHotelReviews(hotelId); // Reload reviews
    }
}
```

### 2. Load Hotel Reviews

```javascript
async function loadHotelReviews(hotelId) {
    const data = await apiRequest(`/hotels/${hotelId}/reviews`);
    if (!data || !data.data) return;

    displayHotelReviews(data.data);
}

function displayHotelReviews(reviews) {
    const container = document.getElementById('reviews-container');
    container.innerHTML = '';

    if (reviews.length === 0) {
        container.innerHTML = '<p class="text-muted">No reviews yet</p>';
        return;
    }

    reviews.forEach(review => {
        const stars = '⭐'.repeat(review.rating);
        const reviewHTML = `
            <div class="review-card">
                <div class="review-header">
                    <strong>${review.user.name}</strong>
                    <span class="review-date">${formatDate(review.created_at)}</span>
                </div>
                <div class="review-rating">${stars} (${review.rating}/5)</div>
                <p class="review-comment">${review.comment}</p>
            </div>
        `;
        container.innerHTML += reviewHTML;
    });
}
```

---

## Error Handling

### Display Errors to Users

```javascript
function showAlert(message, type = 'info') {
    // Using Bootstrap alerts
    const alertHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    let alertContainer = document.getElementById('alertContainer');
    if (!alertContainer) {
        alertContainer = document.createElement('div');
        alertContainer.id = 'alertContainer';
        document.body.insertBefore(alertContainer, document.body.firstChild);
    }

    alertContainer.innerHTML = alertHTML;

    // Auto-remove after 4 seconds
    setTimeout(() => {
        const alert = alertContainer.querySelector('.alert');
        if (alert) alert.remove();
    }, 4000);
}
```

### Redirect on 401 Unauthorized

The `apiRequest` function automatically handles 401 errors by clearing auth data and redirecting to login.

---

## Utility Functions

Add these utility functions to your `main.js`:

```javascript
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(amount);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
}
```

---

## Testing the Integration

1. **Test Registration:**
   - Go to signup.html
   - Fill in credentials
   - Verify token is stored in localStorage

2. **Test Login:**
   - Go to login.html
   - Use existing user credentials
   - Verify you're redirected to home page

3. **Test Hotel Listing:**
   - Load hotels page
   - Verify hotels load from API
   - Test filtering by city/rating

4. **Test Booking:**
   - Click "Book Now" on a room
   - Fill in booking details
   - Submit and verify it appears in dashboard

5. **Test User Dashboard:**
   - Click "My Bookings" in navigation
   - Verify your bookings load
   - Test cancel booking functionality

6. **Test Reviews:**
   - On completed booking, click "Leave Review"
   - Submit review and verify it appears

---

## API Response Examples

### Register Response:
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "user",
    "is_active": true,
    "created_at": "2024-01-01T12:00:00Z"
  },
  "token": "1|abc123token..."
}
```

### Login Response:
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "user",
    "is_active": true
  },
  "token": "1|abc123token..."
}
```

### Bookings Response:
```json
{
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "room_id": 5,
      "hotel_id": 2,
      "check_in_date": "2024-02-15",
      "check_out_date": "2024-02-20",
      "guests": 2,
      "total_price": 750.00,
      "status": "confirmed",
      "special_requests": null,
      "user": { "id": 1, "name": "John Doe", "email": "john@example.com" },
      "room": { "id": 5, "name": "Deluxe Suite", "price_per_night": 150 },
      "hotel": { "id": 2, "name": "Coastal Dreams", "city": "Miami" },
      "created_at": "2024-01-15T10:30:00Z"
    }
  ],
  "links": { "first": "...", "last": "...", "next": "..." },
  "meta": { "current_page": 1, "total": 5 }
}
```

---

## Troubleshooting

| Issue | Solution |
|-------|----------|
| 401 Unauthorized | Token expired or invalid. User will be redirected to login. |
| 422 Validation Error | Check form inputs. Error message will be displayed. |
| 404 Not Found | Resource doesn't exist. Verify the ID is correct. |
| CORS Error | Backend CORS must be configured to allow your frontend domain. |
| Network Error | Check if backend is running and API_BASE_URL is correct. |

---

## Support

For API documentation, see the complete API spec in `routes/api.php`.

For admin panel documentation, see the admin folder README.

Good luck with your project! 🚀
