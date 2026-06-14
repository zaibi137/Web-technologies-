# Opal Haven - Admin Panel Guide

Complete admin dashboard for managing the Opal Haven hotel booking system.

## Access

**URL**: `/admin/login.html`

**Test Credentials**:
- Email: `admin@opalhaven.com`
- Password: `password123`

---

## Pages Overview

### 1. Login (`admin/login.html`)

- Email and password authentication
- Auto-redirect if already logged in
- Test credentials displayed on login page
- Token stored in localStorage

**Features:**
- Form validation
- Error messages
- Loading states
- Remember login (via localStorage)

---

### 2. Dashboard (`admin/dashboard.html`)

**Central admin hub showing key metrics and recent activity.**

**Stats Cards:**
- Total Hotels
- Total Rooms
- Total Bookings
- Total Revenue

**Booking Status Breakdown:**
- Pending count
- Confirmed count
- Cancelled count
- Completed count

**Recent Bookings Table:**
- Last 10 bookings with guest name, hotel, room, dates
- Total price and current status
- Quick link to view all bookings

**Data Source:** `GET /api/admin/dashboard`

---

### 3. Hotels (`admin/hotels.html`)

**Manage all hotel properties.**

**Features:**
- View all hotels in a table
- Filter by city, country, rating
- Add new hotel (modal form)
- Edit existing hotels
- Delete hotels
- Toggle active/inactive status

**Table Columns:**
- Hotel name
- City
- Country
- Star rating
- Number of rooms
- Active/Inactive status

**Modal Form Fields:**
- Hotel name (required)
- City
- Country
- Address
- Description
- Star rating (1-5)
- Amenities (comma-separated list)
- Status (Active/Inactive)

**API Endpoints:**
- `GET /api/hotels` - List hotels
- `POST /api/hotels` - Create hotel
- `PUT /api/hotels/{id}` - Update hotel
- `DELETE /api/hotels/{id}` - Delete hotel

---

### 4. Rooms (`admin/rooms.html`)

**Manage hotel rooms.**

**Features:**
- View all rooms in a table
- Filter by hotel (dropdown)
- Add new room (modal form)
- Edit existing rooms
- Delete rooms
- Toggle availability status

**Table Columns:**
- Room name
- Hotel name
- Room type (Deluxe, Suite, Standard, etc.)
- Price per night
- Max guests
- Available/Not available status

**Modal Form Fields:**
- Hotel (dropdown, required)
- Room name (required)
- Room type (e.g., Deluxe, Suite)
- Price per night (required)
- Max guests (required)
- Amenities (comma-separated)
- Description
- Available/Not available status

**API Endpoints:**
- `GET /api/hotels/{hotel_id}/rooms` - List rooms by hotel
- `POST /api/rooms` - Create room
- `PUT /api/rooms/{id}` - Update room
- `DELETE /api/rooms/{id}` - Delete room

---

### 5. Bookings (`admin/bookings.html`)

**Manage customer bookings.**

**Features:**
- View all bookings in a table
- Filter by status (Pending, Confirmed, Cancelled, Completed)
- Search by guest name
- View booking details in modal
- Update booking status

**Table Columns:**
- Booking ID
- Guest name
- Hotel name
- Room name
- Check-in date
- Check-out date
- Total price
- Status badge (with color: yellow=pending, green=confirmed, red=cancelled, gray=completed)

**Details Modal:**
- Full booking information
- Guest contact details
- Hotel and room details
- Dates and guest count
- Total price
- Special requests
- Status dropdown to change status

**API Endpoints:**
- `GET /api/bookings` - List all bookings
- `GET /api/bookings/{id}` - Get booking details
- `PATCH /api/bookings/{id}/status` - Update booking status

---

### 6. Users (`admin/users.html`)

**Manage user accounts.**

**Features:**
- View all users in a table
- Search by name or email
- View user details in modal
- Change user role (User/Admin)
- Toggle user active/inactive status

**Table Columns:**
- User name
- Email address
- Role badge (User/Admin)
- Phone number
- Total bookings count
- Status badge (Active/Inactive)

**Details Modal:**
- User profile information
- Account creation date
- Total number of bookings
- Role selector (User/Admin)
- Status selector (Active/Inactive)
- Update button to save changes

**API Endpoints:**
- `GET /api/admin/users` - List all users
- `GET /api/admin/users/{id}` - Get user details
- `PATCH /api/admin/users/{id}/role` - Update role
- `PATCH /api/admin/users/{id}/toggle-active` - Toggle active status

---

### 7. Reviews (`admin/reviews.html`)

**Manage customer reviews.**

**Features:**
- View all reviews in a table
- Filter by hotel
- Filter by rating (1-5 stars)
- View review details
- Toggle review visibility
- Delete reviews

**Table Columns:**
- Hotel name
- Reviewer name
- Rating (displayed as stars)
- Comment preview (truncated)
- Visibility status (Visible/Hidden)
- Action buttons

**API Endpoints:**
- `GET /api/hotels/{hotel_id}/reviews` - List reviews by hotel
- `PATCH /api/reviews/{id}/toggle-visibility` - Toggle visibility
- `DELETE /api/reviews/{id}` - Delete review

---

## Shared Features

### Navigation Sidebar

Present on all pages (except login):

- **Opal Haven** branding header
- **Dashboard** link with icon
- **Hotels** link with icon
- **Rooms** link with icon
- **Bookings** link with icon
- **Users** link with icon
- **Reviews** link with icon
- **Logout** button at bottom

Active page is highlighted with blue background.

### Top Bar

Present on all pages (except login):

- Page title (Dashboard, Hotels, etc.)
- Admin name (fetched from API)
- Responsive design

### Alert Messages

All pages show:
- Success messages (green) after actions
- Error messages (red) for failures
- Auto-dismiss after 4 seconds
- Manual dismiss with close button

### Authentication

All pages:
- Check for valid admin token on load
- Redirect to login if token missing or expired
- Handle 401/403 errors by clearing token and redirecting

---

## File Structure

```
admin/
├── login.html           # Login page
├── dashboard.html       # Dashboard / stats
├── hotels.html          # Hotel management
├── rooms.html           # Room management
├── bookings.html        # Booking management
├── users.html           # User management
├── reviews.html         # Review management
├── css/
│   └── admin.css        # Shared styling
└── js/
    └── admin.js         # Shared utilities & auth
```

---

## Shared JavaScript (admin/js/admin.js)

**Constants:**
- `API_BASE_URL` - Points to Laravel API (http://localhost:8000/api)

**Functions:**
- `getToken()` - Retrieve stored auth token
- `setToken(token)` - Store auth token
- `clearToken()` - Remove token (logout)
- `authHeaders()` - Get auth headers for requests
- `checkAdminAuth()` - Verify admin is authenticated
- `apiRequest(endpoint, options)` - Make API requests with auth
- `handleApiError(error, status)` - Display error messages
- `showAlert(message, type)` - Show alert to admin
- `formatCurrency(amount)` - Format as USD currency
- `formatDate(dateString)` - Format date as "Jan 01, 2024"
- `getStatusBadge(status)` - Get HTML badge for status
- `logout()` - Logout admin

---

## CSS Styling (admin/css/admin.css)

**Design:**
- Dark sidebar (#2c3e50)
- Light content area (#f5f5f5)
- Purple accent color (#667eea)
- Bootstrap 5 based
- Responsive design (mobile-friendly)

**Components:**
- Stat cards with icons and hover effects
- Tables with hover rows
- Modal dialogs for forms
- Alert messages
- Status badges (green/yellow/red/gray)
- Forms with validation styling

---

## Common Actions

### Adding a Hotel

1. Click "Add Hotel" button
2. Fill in form:
   - Hotel name
   - City, country, address
   - Description
   - Star rating
   - Amenities
3. Click "Save Hotel"
4. Hotel appears in table

### Editing a Room

1. Click "Edit" button on room row
2. Modal opens with pre-filled data
3. Update fields
4. Click "Save Room"
5. Changes are applied

### Changing Booking Status

1. Click "Details" on booking
2. Modal opens with full info
3. Select new status from dropdown
4. Click "Update Status"
5. Status is updated and table refreshes

### Managing Users

1. Search users by name/email
2. Click "Details" on user
3. Change role or status
4. Click "Update"
5. Changes applied

### Deleting with Confirmation

All delete actions show a confirmation dialog first.

---

## Data Validation

### Backend Validation

Form requests validate:
- Required fields
- Email format
- Numeric ranges
- Array structures
- Unique values

Error messages display to admin with specific issues.

### Frontend Validation

- HTML5 form validation
- Required field indicators
- Type validation (email, number, etc.)

---

## Error Handling

**Common Errors:**

| Status | Message | Solution |
|--------|---------|----------|
| 401 | Token expired | Admin redirected to login |
| 403 | Unauthorized | Verify admin privileges |
| 404 | Not found | Resource doesn't exist |
| 422 | Validation failed | Check form field errors |
| 500 | Server error | Contact support |

---

## Browser Compatibility

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

---

## Security Features

- Token-based authentication
- Admin role verification
- Row-level security policies
- HTTPS recommended for production
- Secure password hashing (Laravel)
- CSRF protection (Laravel Sanctum)

---

## Tips & Best Practices

1. **Always confirm before deleting** - Dialogs prevent accidents
2. **Check status carefully** - Different statuses have different permissions
3. **Use search** - Quickly find specific hotels or users
4. **Review recent bookings** - Dashboard shows latest 10 automatically
5. **Filter by status** - Easier to manage bookings by status
6. **Verify hotel rooms** - Check room count when listing hotels
7. **Monitor revenue** - Dashboard shows total revenue from completed bookings

---

## Support

For API documentation, see: `BACKEND_SETUP.md`

For frontend integration guide, see: `USER_FRONTEND_INTEGRATION.md`

---

Good luck managing your Opal Haven! 🏨
