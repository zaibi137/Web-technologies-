# Opal Haven Backend - Setup Guide

A complete Laravel + Supabase backend for the Opal Haven hotel booking system.

## Prerequisites

- PHP 8.1+
- Composer
- PostgreSQL (via Supabase)
- Node.js & npm (optional, for frontend assets)

## Installation

### 1. Clone/Create Project

```bash
cd backend
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

Update the `.env` file with your Supabase credentials:

```env
APP_NAME="Opal Haven"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=pgsql
DB_HOST=YOUR_SUPABASE_HOST.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=YOUR_SUPABASE_PASSWORD

# Supabase
SUPABASE_URL=https://YOUR_SUPABASE_HOST.supabase.co
SUPABASE_KEY=YOUR_ANON_KEY

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,localhost:8000
SANCTUM_ENCRYPT_COOKIES=false
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Run Seeders

```bash
php artisan db:seed
```

This will create:
- 1 admin user: `admin@opalhaven.com` / `password123`
- 9 regular users with test data
- 5 hotels with 3 rooms each
- Sample bookings and reviews

### 7. Start Development Server

```bash
php artisan serve
```

The API will be available at: `http://localhost:8000/api`

---

## Database Schema

### Users Table
- id, name, email, password, role (user/admin), phone, avatar, is_active
- Indexes: email, role

### Hotels Table
- id, name, slug, description, address, city, country, star_rating, amenities (JSON), images (JSON), is_active
- Indexes: city, country, is_active

### Rooms Table
- id, hotel_id (FK), room_type, name, description, price_per_night, max_guests, amenities (JSON), images (JSON), is_available
- Indexes: hotel_id, is_available

### Bookings Table
- id, user_id (FK), room_id (FK), hotel_id (FK), check_in_date, check_out_date, guests, total_price, status, special_requests
- Indexes: user_id, room_id, hotel_id, status, check_in_date

### Reviews Table
- id, user_id (FK), hotel_id (FK), booking_id (FK), rating, comment, is_visible
- Indexes: hotel_id, user_id, is_visible

### Payments Table
- id, booking_id (FK), amount, payment_method, transaction_id, status, paid_at
- Indexes: booking_id, status

---

## API Routes

### Authentication
- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - Login user
- `POST /api/auth/logout` - Logout user (requires auth)
- `GET /api/auth/me` - Get current user (requires auth)

### Hotels (Public Read, Admin Write)
- `GET /api/hotels` - List all hotels with filters
- `GET /api/hotels/{id}` - Get hotel details
- `POST /api/hotels` - Create hotel (admin only)
- `PUT /api/hotels/{id}` - Update hotel (admin only)
- `DELETE /api/hotels/{id}` - Delete hotel (admin only)

### Rooms
- `GET /api/hotels/{hotel_id}/rooms` - List rooms by hotel
- `GET /api/rooms/{id}` - Get room details
- `GET /api/rooms/{id}/availability` - Check availability
- `POST /api/rooms` - Create room (admin only)
- `PUT /api/rooms/{id}` - Update room (admin only)
- `DELETE /api/rooms/{id}` - Delete room (admin only)

### Bookings
- `GET /api/bookings` - List bookings (users see own, admins see all)
- `POST /api/bookings` - Create booking
- `GET /api/bookings/{id}` - Get booking details
- `PATCH /api/bookings/{id}/cancel` - Cancel booking
- `PATCH /api/bookings/{id}/status` - Update status (admin only)

### Reviews
- `GET /api/hotels/{hotel_id}/reviews` - List reviews by hotel
- `POST /api/hotels/{hotel_id}/reviews` - Create review
- `DELETE /api/reviews/{id}` - Delete review (admin only)
- `PATCH /api/reviews/{id}/toggle-visibility` - Toggle visibility (admin only)

### Admin Routes
- `GET /api/admin/users` - List all users (admin only)
- `GET /api/admin/users/{id}` - Get user details (admin only)
- `PATCH /api/admin/users/{id}/role` - Update role (admin only)
- `PATCH /api/admin/users/{id}/toggle-active` - Toggle active status (admin only)
- `GET /api/admin/dashboard` - Dashboard stats (admin only)

---

## Middleware

### auth:sanctum
Requires a valid Sanctum token in the `Authorization` header:
```
Authorization: Bearer <token>
```

### admin
Checks if the authenticated user has `role = 'admin'`. Returns 403 if not.

---

## Testing the API

### Register User
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Login User
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### Get Hotels
```bash
curl -X GET http://localhost:8000/api/hotels
```

### Get Current User (with token)
```bash
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Create Booking (with token)
```bash
curl -X POST http://localhost:8000/api/bookings \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "room_id": 1,
    "hotel_id": 1,
    "check_in_date": "2024-02-15",
    "check_out_date": "2024-02-20",
    "guests": 2,
    "special_requests": "Early check-in please"
  }'
```

---

## Row Level Security (RLS)

All Supabase tables have RLS enabled with policies:

- **Users**: Users can view/update their own profile. Admins can view/update all.
- **Hotels**: Everyone can view active hotels. Admins can manage all.
- **Rooms**: Everyone can view available rooms. Admins can manage all.
- **Bookings**: Users can view their own. Admins can view all.
- **Reviews**: Everyone can view visible reviews. Admins can manage.
- **Payments**: Users can view their own. Admins can manage all.

---

## Models & Relationships

### User
- `bookings()` - Has many bookings
- `reviews()` - Has many reviews
- `isAdmin()` - Check if user is admin
- `isActive()` - Check if user is active

### Hotel
- `rooms()` - Has many rooms
- `bookings()` - Has many bookings
- `reviews()` - Has many reviews
- Scopes: `active()`, `byCity()`, `byCountry()`, `byStarRating()`

### Room
- `hotel()` - Belongs to hotel
- `bookings()` - Has many bookings
- `isAvailable($checkIn, $checkOut)` - Check availability for dates
- Scopes: `available()`, `byHotel()`

### Booking
- `user()` - Belongs to user
- `room()` - Belongs to room
- `hotel()` - Belongs to hotel
- `payment()` - Has one payment
- `canCancel()` - Check if booking can be cancelled
- `calculateNights()` - Calculate number of nights
- Scopes: `byUser()`, `byStatus()`, `pending()`, `confirmed()`, `recent()`

### Review
- `user()` - Belongs to user
- `hotel()` - Belongs to hotel
- `booking()` - Belongs to booking
- Scopes: `visible()`, `byHotel()`, `byRating()`

### Payment
- `booking()` - Belongs to booking
- Scopes: `paid()`, `byStatus()`

---

## Form Request Validation

### RegisterRequest
- name: required, string, max 255
- email: required, email, unique
- password: required, min 8, confirmed

### LoginRequest
- email: required, email
- password: required

### StoreHotelRequest
- name: required, string, max 255
- description: nullable, string
- address: nullable, string
- city: nullable, string
- country: nullable, string
- star_rating: nullable, numeric, between 1-5
- amenities: nullable, array
- images: nullable, array
- is_active: boolean

### StoreRoomRequest
- hotel_id: required, exists
- room_type: nullable, string
- name: required, string, max 255
- description: nullable, string
- price_per_night: required, numeric, min 0
- max_guests: required, integer, min 1
- amenities: nullable, array
- images: nullable, array
- is_available: boolean

### StoreBookingRequest
- room_id: required, exists
- hotel_id: required, exists
- check_in_date: required, date, after_or_equal today
- check_out_date: required, date, after check_in
- guests: required, integer, min 1
- special_requests: nullable, string, max 1000

### StoreReviewRequest
- hotel_id: required, exists
- booking_id: nullable, exists
- rating: required, integer, between 1-5
- comment: nullable, string, max 1000

---

## Troubleshooting

### Database Connection Error
- Verify Supabase credentials in .env
- Check PostgreSQL is running
- Ensure firewall allows connection to Supabase

### Token Expired
- Make a new login request to get a fresh token

### 403 Unauthorized
- You don't have admin privileges for that action
- Login with an admin account

### 422 Validation Error
- Check form validation errors in the response
- Ensure all required fields are provided

### CORS Issues
- Add frontend URL to `SANCTUM_STATEFUL_DOMAINS`
- Or configure CORS middleware in `config/cors.php`

---

## File Structure

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/
│   │   │   ├── AuthController.php
│   │   │   ├── HotelController.php
│   │   │   ├── RoomController.php
│   │   │   ├── BookingController.php
│   │   │   ├── ReviewController.php
│   │   │   ├── UserController.php
│   │   │   └── DashboardController.php
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php
│   │   └── Requests/
│   │       ├── RegisterRequest.php
│   │       ├── LoginRequest.php
│   │       ├── StoreHotelRequest.php
│   │       ├── StoreRoomRequest.php
│   │       ├── StoreBookingRequest.php
│   │       └── StoreReviewRequest.php
│   └── Models/
│       ├── User.php
│       ├── Hotel.php
│       ├── Room.php
│       ├── Booking.php
│       ├── Review.php
│       └── Payment.php
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── api.php
└── .env
```

---

## Next Steps

1. **Update Frontend**: Integrate API calls using the provided integration guide
2. **Admin Panel**: Access admin panel at `/admin/login.html`
3. **Testing**: Test all endpoints with provided curl examples
4. **Deployment**: Deploy to production with proper security measures

---

## Support & Documentation

- Laravel Documentation: https://laravel.com/docs
- Sanctum Documentation: https://laravel.com/docs/sanctum
- Supabase Documentation: https://supabase.com/docs
- API Routes: See `routes/api.php`
- Integration Guide: See `USER_FRONTEND_INTEGRATION.md`

Good luck! 🚀
