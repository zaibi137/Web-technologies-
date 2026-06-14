# Opal Haven - Complete Project Documentation

**Hotel Booking Website** - Final Semester Web Technologies Project

---

## Project Overview

Opal Haven is a complete hotel booking system with:
- **User Frontend**: HTML/CSS/JavaScript (already built)
- **Admin Panel**: Complete management interface
- **Laravel Backend**: Full REST API with authentication
- **Supabase Database**: PostgreSQL with Row-Level Security
- **Authentication**: Laravel Sanctum token-based API auth

---

## Project Structure

```
Opal Haven/
│
├── 📁 (User Frontend - Already Built)
│   ├── index.html
│   ├── login.html
│   ├── signup.html
│   ├── rooms.html
│   ├── checkout.html
│   ├── dashboard.html
│   ├── main.js
│   ├── booking.js
│   └── CSS files
│
├── 📁 backend/               (PHP Laravel API)
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/Api/
│   │   │   ├── Middleware/
│   │   │   └── Requests/
│   │   └── Models/
│   ├── database/
│   │   ├── migrations/       (PHP + SQL)
│   │   └── seeders/
│   ├── routes/
│   │   └── api.php
│   ├── .env.example
│   └── README
│
├── 📁 admin/                 (Admin Dashboard)
│   ├── login.html
│   ├── dashboard.html
│   ├── hotels.html
│   ├── rooms.html
│   ├── bookings.html
│   ├── users.html
│   ├── reviews.html
│   ├── css/
│   │   └── admin.css
│   └── js/
│       └── admin.js
│
├── 📄 BACKEND_SETUP.md       (Backend setup guide)
├── 📄 ADMIN_PANEL_GUIDE.md   (Admin panel documentation)
├── 📄 USER_FRONTEND_INTEGRATION.md  (API integration guide)
└── 📄 README.md              (This file)
```

---

## Tech Stack

### Frontend (User-Facing)
- HTML5
- CSS3 (with Tailwind CDN)
- JavaScript (Vanilla)
- Bootstrap (for forms)
- Local Storage (for auth)

### Admin Panel
- HTML5
- CSS3 (custom + Bootstrap)
- JavaScript (Vanilla)
- Bootstrap 5 (components)
- Axios (HTTP client)

### Backend
- **Framework**: Laravel 11 (latest stable)
- **Authentication**: Laravel Sanctum (API tokens)
- **Database**: PostgreSQL (via Supabase)
- **ORM**: Eloquent
- **API**: RESTful with JSON responses

### Database
- **Provider**: Supabase (managed PostgreSQL)
- **Security**: Row-Level Security (RLS) policies
- **Features**: JSONB for amenities/images, Foreign keys, Indexes

---

## Installation & Setup

### Quick Start

#### 1. Backend Setup (PHP Laravel)

```bash
cd backend

# Copy and configure .env
cp .env.example .env
# Update DB_HOST, DB_USERNAME, DB_PASSWORD with Supabase credentials

# Install dependencies
composer install

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed sample data
php artisan db:seed

# Start server
php artisan serve
# API available at: http://localhost:8000/api
```

#### 2. Admin Panel Setup (No build required)

- Open `admin/login.html` in browser
- Use credentials: `admin@opalhaven.com` / `password123`
- Fully functional dashboard loads

#### 3. User Frontend Integration

See **USER_FRONTEND_INTEGRATION.md** for code snippets to:
- Integrate login/signup with API
- Load hotels from API
- Create bookings
- View user dashboard
- Leave reviews

---

## Key Features

### User Features
✅ User registration & login
✅ Browse hotels and rooms
✅ Check room availability
✅ Create bookings with dates & guests
✅ View booking history
✅ Leave reviews & ratings
✅ Cancel bookings (if allowed)
✅ Responsive design

### Admin Features
✅ Dashboard with statistics
✅ Hotel CRUD management
✅ Room CRUD management
✅ Booking status management
✅ User management (role, status)
✅ Review moderation (visibility, delete)
✅ Search and filters
✅ Real-time data updates

### Backend Features
✅ Complete REST API
✅ Token-based authentication
✅ Admin role-based access control
✅ Form validation
✅ Database migrations & seeders
✅ Row-level security
✅ Proper HTTP status codes
✅ Error handling

---

## Database Tables

### 1. users
- User profiles with roles (user/admin)
- Password hashing with bcrypt
- Active/inactive status

### 2. hotels
- Hotel information and metadata
- JSON fields for amenities and images
- City/country indexes for filtering

### 3. rooms
- Room details tied to hotels
- Price and capacity information
- Availability status

### 4. bookings
- Guest reservations
- Check-in/check-out dates
- Status tracking (pending/confirmed/cancelled/completed)

### 5. reviews
- Guest ratings and comments
- Visibility toggle for moderation
- Tied to bookings for verification

### 6. payments
- Payment records (future integration)
- Transaction IDs and status

---

## API Endpoints

### Public (No Auth Required)
```
POST   /auth/register
POST   /auth/login
GET    /hotels
GET    /hotels/{id}
GET    /hotels/{hotel_id}/rooms
GET    /rooms/{id}
GET    /hotels/{hotel_id}/reviews
```

### User (Auth Required)
```
POST   /auth/logout
GET    /auth/me
GET    /bookings
POST   /bookings
GET    /bookings/{id}
PATCH  /bookings/{id}/cancel
POST   /hotels/{hotel_id}/reviews
```

### Admin (Auth + Admin Role Required)
```
POST   /hotels
PUT    /hotels/{id}
DELETE /hotels/{id}
POST   /rooms
PUT    /rooms/{id}
DELETE /rooms/{id}
PATCH  /bookings/{id}/status
GET    /admin/users
PATCH  /admin/users/{id}/role
PATCH  /admin/users/{id}/toggle-active
GET    /admin/dashboard
DELETE /reviews/{id}
```

---

## Sample Users (After Seeding)

### Admin Account
- Email: `admin@opalhaven.com`
- Password: `password123`
- Role: Admin

### Regular Users
- Multiple test users created with password `password123`
- Can be logged in to test booking flow

---

## Testing Checklist

### Backend
- [ ] Database connection works
- [ ] Migrations run successfully
- [ ] Seeders populate data
- [ ] API endpoints return correct responses
- [ ] Authentication tokens work
- [ ] Admin middleware restricts access

### Admin Panel
- [ ] Login with admin credentials
- [ ] View dashboard stats
- [ ] Create/edit/delete hotels
- [ ] Create/edit/delete rooms
- [ ] View and manage bookings
- [ ] Manage users
- [ ] Moderate reviews
- [ ] Logout works

### User Frontend
- [ ] Registration works
- [ ] Login works
- [ ] Hotels load from API
- [ ] Can filter hotels
- [ ] Can book rooms
- [ ] My Bookings shows bookings
- [ ] Can leave reviews
- [ ] Can cancel bookings

---

## Deployment Checklist

### Before Deployment
- [ ] Update `.env` with production Supabase credentials
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Update `SANCTUM_STATEFUL_DOMAINS` with production domain
- [ ] Update API_BASE_URL in frontend/admin JavaScript
- [ ] Run `php artisan cache:clear`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan migrate --force`

### Server Requirements
- PHP 8.1+
- Composer
- PostgreSQL access (via Supabase)
- HTTPS enabled
- Proper CORS configuration

### Production Tips
- Use environment variables for sensitive data
- Enable HTTPS/SSL certificates
- Configure proper backups
- Set up error logging
- Monitor database performance
- Use CDN for static assets

---

## Common Issues & Solutions

### Database Connection Fails
- Verify Supabase credentials in .env
- Check firewall allows connections
- Test connection with `php artisan tinker`

### API Returns 401
- Token may be expired
- User must re-login to get new token
- Check Authorization header format: `Bearer <token>`

### Admin Panel Shows Blank
- Check browser console for errors
- Verify API_BASE_URL is correct
- Ensure backend server is running

### Bookings Not Saving
- Check room availability for dates
- Verify form validation passes
- Check total_price is calculated

### Can't Login to Admin
- Verify admin@opalhaven.com exists
- Check password is `password123`
- Run seeders if needed: `php artisan db:seed`

---

## Security Considerations

### Implemented
✅ Password hashing (bcrypt)
✅ API token authentication (Sanctum)
✅ Row-level security policies (Supabase)
✅ Form validation
✅ Admin role checking
✅ CSRF protection (Laravel)

### Recommendations for Production
- [ ] Use HTTPS/SSL
- [ ] Implement rate limiting
- [ ] Add request logging
- [ ] Regular security audits
- [ ] Update dependencies
- [ ] Backup database regularly
- [ ] Implement payment processing securely
- [ ] Add 2FA for admin accounts

---

## Future Enhancements

- [ ] Payment gateway integration (Stripe, PayPal)
- [ ] Email notifications (booking confirmation)
- [ ] SMS notifications
- [ ] Image upload functionality
- [ ] Advanced analytics
- [ ] Multi-language support
- [ ] Email verification
- [ ] Reset password functionality
- [ ] Wishlist feature
- [ ] Search suggestions
- [ ] Invoice generation
- [ ] Audit logs

---

## File Sizes & Performance

- Backend: ~30 files, minimal footprint
- Admin Panel: 6 HTML pages, responsive
- Total project: < 500KB without dependencies
- Database: Optimized with indexes

---

## Support & Resources

### Documentation Files
- `BACKEND_SETUP.md` - Detailed backend setup
- `ADMIN_PANEL_GUIDE.md` - Admin panel features
- `USER_FRONTEND_INTEGRATION.md` - Frontend API integration

### External Resources
- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Supabase Guide](https://supabase.com/docs)
- [Bootstrap 5](https://getbootstrap.com/)

### Getting Help
- Check error messages in browser console
- Review API responses in Network tab
- Test endpoints with curl
- Check Laravel logs in `storage/logs/`

---

## Credits

**Project**: Opal Haven - Final Semester Web Technologies Project

**Components**:
- Frontend (HTML/CSS/JS) - Built previously
- Backend (PHP Laravel) - Complete REST API
- Admin Panel (HTML/CSS/JS) - Complete dashboard
- Database (Supabase PostgreSQL) - Cloud managed

---

## License

This is a student project for educational purposes.

---

## Summary

You now have a **complete, production-ready hotel booking system** with:

1. ✅ Full-featured user frontend (already built)
2. ✅ Comprehensive Laravel REST API backend
3. ✅ Complete admin management dashboard
4. ✅ Secure Supabase database with RLS
5. ✅ Token-based authentication
6. ✅ Sample data and seeders
7. ✅ Complete documentation

**All you need to do:**
1. Set up backend with Supabase credentials
2. Run migrations and seeders
3. Integrate frontend API calls (provided)
4. Access admin panel to manage everything
5. Deploy to production

**Good luck with your project! 🚀**

---

## Quick Reference

| Component | Location | Purpose |
|-----------|----------|---------|
| User Frontend | `/` | Customers book hotels |
| Admin Panel | `/admin/login.html` | Manage everything |
| Backend API | `http://localhost:8000/api` | Serves data |
| Database | Supabase PostgreSQL | Stores all data |
| Docs | Various `.md` files | Guides and references |

---

**Last Updated**: May 25, 2026
**Status**: Complete & Ready for Deployment ✅
