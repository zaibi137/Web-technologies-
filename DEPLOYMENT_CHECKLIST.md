# DEPLOYMENT_CHECKLIST.md - Final Setup & Deployment

## Pre-Deployment Checklist

### 1. Backend Setup ✅

**Supabase Configuration:**
- [ ] Create Supabase project
- [ ] Copy host, database name, password
- [ ] Note your Anon Key
- [ ] Enable PostgreSQL

**Laravel Setup:**
```bash
cd backend
cp .env.example .env
```

**Update .env with Supabase details:**
```env
DB_CONNECTION=pgsql
DB_HOST=xxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=xxxxx
```

**Install & Configure:**
```bash
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

### 2. Admin Panel Configuration ✅

- [ ] Open `admin/login.html` in browser
- [ ] Verify login works with `admin@opalhaven.com` / `password123`
- [ ] Test all admin pages load correctly
- [ ] Verify API calls work from admin panel

### 3. User Frontend Integration ✅

**Update main.js:**
```javascript
const API_BASE_URL = 'http://localhost:8000/api'; // Update for production
```

**Add auth functions** (see USER_FRONTEND_INTEGRATION.md):
- [ ] getToken()
- [ ] setToken()
- [ ] apiRequest()
- [ ] handleApiError()

**Integrate pages:**
- [ ] login.html - Add handleLogin()
- [ ] signup.html - Add handleSignup()
- [ ] rooms.html - Add loadHotels()
- [ ] checkout.html - Add submitBooking()
- [ ] dashboard.html - Add loadUserBookings()

### 4. Testing ✅

**Backend Tests:**
```bash
curl http://localhost:8000/api/hotels
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@opalhaven.com","password":"password123"}'
```

**Admin Panel Tests:**
- [ ] Login/Logout works
- [ ] Dashboard loads stats
- [ ] Can create hotel
- [ ] Can create room
- [ ] Can view bookings
- [ ] Can manage users
- [ ] Can moderate reviews

**User Frontend Tests:**
- [ ] Registration works
- [ ] Login works
- [ ] Can see hotels
- [ ] Can book room
- [ ] Can view bookings
- [ ] Can leave review

---

## Production Deployment

### Step 1: Update Environment

**For Production Server:**

```bash
# SSH into server
ssh user@your-domain.com

# Update .env
nano /path/to/backend/.env
```

**Critical .env Changes:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Use production Supabase credentials
DB_HOST=xxx.supabase.co
DB_USERNAME=postgres
DB_PASSWORD=xxxxx

# Update SANCTUM for your domain
SANCTUM_STATEFUL_DOMAINS=your-domain.com,www.your-domain.com
SESSION_DOMAIN=your-domain.com
```

### Step 2: Clear Cache

```bash
cd /path/to/backend

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Regenerate optimized cache
php artisan config:cache
php artisan route:cache
```

### Step 3: Update Frontend URLs

**In admin/js/admin.js:**
```javascript
const API_BASE_URL = 'https://your-domain.com/api';
```

**In user frontend main.js:**
```javascript
const API_BASE_URL = 'https://your-domain.com/api';
```

### Step 4: Run Migrations

```bash
php artisan migrate --force
```

### Step 5: Update Web Server Config

**For Apache (if using):**
- Point document root to `public/` folder
- Enable mod_rewrite
- Set up HTTPS with SSL certificate

**For Nginx:**
```nginx
server {
    listen 443 ssl http2;
    server_name your-domain.com;
    root /path/to/backend/public;

    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### Step 6: Test in Production

```bash
# Test API endpoint
curl https://your-domain.com/api/hotels

# Test admin login
curl -X POST https://your-domain.com/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@opalhaven.com","password":"password123"}'
```

### Step 7: Set Up Monitoring

- [ ] Enable error logging
- [ ] Set up automated backups
- [ ] Monitor server resources
- [ ] Track API response times
- [ ] Log security events

---

## Post-Deployment

### Immediate Actions
- [ ] Test all user flows end-to-end
- [ ] Verify admin panel works
- [ ] Check email notifications (if implemented)
- [ ] Test payment processing (if integrated)

### Security Hardening
- [ ] Update admin password
- [ ] Enable HTTPS redirects
- [ ] Configure CORS properly
- [ ] Set up rate limiting
- [ ] Enable request logging

### Monitoring Setup
- [ ] Error tracking (Sentry, etc.)
- [ ] Performance monitoring (New Relic, etc.)
- [ ] Log aggregation (ELK Stack, etc.)
- [ ] Database backups (automated)
- [ ] Security scanning

### Maintenance Tasks
- [ ] Regular security updates
- [ ] Database optimization
- [ ] Clean up old logs
- [ ] Update dependencies monthly
- [ ] Review access logs

---

## Rollback Procedure (If Issues)

**If deployment fails:**

```bash
# Restore from backup
git reset --hard PREVIOUS_COMMIT

# Restore database
# Contact Supabase support or restore from backup

# Restart services
php artisan serve
```

---

## Common Deployment Issues

### Issue: 503 Service Unavailable
**Solution:**
```bash
php artisan migrate --force
php artisan cache:clear
php artisan config:clear
```

### Issue: Database Connection Error
**Solution:**
- Verify Supabase IP whitelist
- Check firewall rules
- Test connection: `php artisan tinker`

### Issue: CORS Errors
**Solution:**
Update `SANCTUM_STATEFUL_DOMAINS` in .env:
```env
SANCTUM_STATEFUL_DOMAINS=your-domain.com,www.your-domain.com
```

### Issue: Admin Panel Blank
**Solution:**
- Update `API_BASE_URL` in admin/js/admin.js
- Check browser console for errors
- Verify HTTPS certificates

### Issue: Slow Response Times
**Solution:**
```bash
php artisan config:cache
php artisan route:cache
```

---

## Performance Optimization

### Database Optimization
```bash
# Analyze queries
php artisan tinker
> DB::statement('ANALYZE;')
```

### Cache Configuration
```env
CACHE_DRIVER=redis  # Better than file for production
```

### Asset Optimization
```bash
# Minify frontend files
npm install
npm run production
```

---

## Backup Strategy

### Daily Backups
```bash
# Supabase: Automated backups every 24 hours
# Configuration: Settings > Database > Backups

# Application Files: Version control (Git)
git push to remote repository
```

### Restore from Backup
```bash
# Database: Contact Supabase or restore point-in-time
# Files: git revert or restore from backup
```

---

## Scaling Considerations

### When to Scale
- [ ] More than 1000 daily active users
- [ ] Response time > 2 seconds
- [ ] Database CPU > 80%

### Scaling Steps
1. Upgrade Supabase tier
2. Add caching layer (Redis)
3. Set up CDN for static files
4. Load balance API servers
5. Separate read/write database

---

## Monitoring Checklist

### Daily
- [ ] Error logs - any critical errors?
- [ ] Performance - response times normal?
- [ ] Database - no locks or slow queries?

### Weekly
- [ ] Backup success - confirmed?
- [ ] Security - any suspicious activity?
- [ ] Users - sign up trends normal?

### Monthly
- [ ] Disk usage - room to grow?
- [ ] Database size - within limits?
- [ ] Dependencies - any security updates?
- [ ] Cost - within budget?

---

## Quick Deployment Commands

```bash
# SSH to server
ssh user@your-domain.com

# Navigate to app
cd /path/to/backend

# Update code
git pull origin main

# Install new dependencies
composer install

# Run migrations
php artisan migrate --force

# Clear caches
php artisan cache:clear
php artisan config:cache

# Restart PHP
sudo systemctl restart php8.1-fpm

# Check status
php artisan health
```

---

## Contact & Support

**During Deployment Issues:**
1. Check error logs: `storage/logs/laravel.log`
2. Review API responses in browser DevTools
3. Verify database connection
4. Check server resources (disk, memory, CPU)
5. Consult Laravel and Supabase documentation

**Key Contacts:**
- Supabase Support: https://supabase.com/support
- Laravel Docs: https://laravel.com/docs
- Your Hosting Provider Support

---

## Deployment Complete Checklist

- [ ] Environment configured
- [ ] Migrations run
- [ ] Seeders loaded
- [ ] Frontend URLs updated
- [ ] Admin panel tested
- [ ] User flows tested
- [ ] HTTPS enabled
- [ ] Backups configured
- [ ] Monitoring active
- [ ] Team notified
- [ ] Documentation updated

---

**Status**: Ready for Production Deployment ✅

**Last Checked**: May 25, 2026

---

## Next Steps After Deployment

1. **Monitor closely** for first 48 hours
2. **Gather user feedback** and iterate
3. **Plan marketing** to drive user acquisition
4. **Set up analytics** to track usage
5. **Plan future enhancements** based on user needs

**You've built a complete, production-ready system! 🚀**
