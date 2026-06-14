/**
 * OPAL HAVEN - Global Hotel Booking System
 * main.js - Central Logic Hub
 * * This file manages:
 * - Hotel data registry (HOTEL_DATA)
 * - UI initialization across all pages
 * - Universal booking button handler
 * - Dashboard and checkout logic
 * - Authentication state management
 */

const STORAGE_USERS = 'users';
const STORAGE_BOOKINGS = 'bookings';
const STORAGE_CURRENT_USER = 'currentUser';

// Backend API (admin) - used to sync user activity to admin panel
const API_BASE_URL = 'http://localhost:8000/api';

async function apiUserRequest(endpoint, options = {}) {
  const url = `${API_BASE_URL}${endpoint}`;
  const headers = options.headers || {};
  const authHeaders = getAuthHeaders();
  try {
    const resp = await fetch(url, {
      ...options,
      headers: { 'Content-Type': 'application/json', Accept: 'application/json', ...authHeaders, ...headers },
    });
    const contentType = resp.headers.get('content-type') || '';
    let data;
    if (contentType.includes('application/json')) data = await resp.json();
    else data = { message: await resp.text() };
    if (!resp.ok) throw { status: resp.status, data };
    return data;
  } catch (err) {
    console.warn('API user request failed', endpoint, err);
    return null;
  }
}

/**
 * HOTEL_DATA: The Single Source of Truth
 * All room information is defined here and used across the entire site.
 */
const HOTEL_DATA = [
  {
    id: 101,
    name: 'Regal Penthouse Suite',
    price: 520,
    image: 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80',
    description: 'A light-filled penthouse with vaulted ceilings, private balcony, and city skyline views.',
    category: 'luxury'
  },
  {
    id: 102,
    name: 'Signature Ocean Room',
    price: 420,
    image: 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
    description: 'A coastal-inspired suite with ocean views, plush finishes, and a romantic ambience.',
    category: 'premium'
  },
  {
    id: 103,
    name: 'Executive Garden Loft',
    price: 360,
    image: 'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=1200&q=80',
    description: 'A tranquil loft with garden-facing windows, elegant textures, and premium comfort.',
    category: 'deluxe'
  },
  {
    id: 104,
    name: 'Imperial Suite',
    price: 680,
    image: 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=1200&q=80',
    description: 'A majestic suite with private lounge, panoramic windows, and bespoke service touches.',
    category: 'luxury'
  },
  {
    id: 105,
    name: 'Chamber Deluxe',
    price: 300,
    image: 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
    description: 'A polished room with refined details, rain shower, and modern luxury amenities.',
    category: 'standard'
  },
  {
    id: 106,
    name: 'Luxe Harmony Suite',
    price: 470,
    image: 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&q=80',
    description: 'A serene suite with designer furnishings, mood lighting, and spacious comfort.',
    category: 'premium'
  }
];

// ============================================================================
// STORAGE MANAGEMENT
// ============================================================================

function getUsers() {
  return JSON.parse(localStorage.getItem(STORAGE_USERS) || '[]');
}

function saveUsers(users) {
  localStorage.setItem(STORAGE_USERS, JSON.stringify(users));
}

function getBookings() {
  return JSON.parse(localStorage.getItem(STORAGE_BOOKINGS) || '[]');
}

function saveBookings(bookings) {
  localStorage.setItem(STORAGE_BOOKINGS, JSON.stringify(bookings));
}

function getCurrentUser() {
  return JSON.parse(localStorage.getItem(STORAGE_CURRENT_USER) || 'null');
}

function setCurrentUser(user) {
  localStorage.setItem(STORAGE_CURRENT_USER, JSON.stringify(user));
}

function clearCurrentUser() {
  localStorage.removeItem(STORAGE_CURRENT_USER);
}

function getAuthHeaders() {
  const currentUser = getCurrentUser();
  if (!currentUser || !currentUser.token) return {};
  return { Authorization: `Bearer ${currentUser.token}` };
}

async function ensureBackendToken() {
  const currentUser = getCurrentUser();
  if (!currentUser || currentUser.token) return currentUser;

  const localUser = findUserByEmail(currentUser.email);
  if (!localUser || !localUser.password) return currentUser;

  const loginResponse = await apiUserRequest('/auth/login', {
    method: 'POST',
    body: JSON.stringify({ email: localUser.email, password: localUser.password }),
  });

  if (loginResponse && loginResponse.token && loginResponse.user) {
    const updatedUser = {
      id: loginResponse.user.id,
      name: loginResponse.user.name,
      email: loginResponse.user.email,
      token: loginResponse.token,
    };
    setCurrentUser(updatedUser);
    return updatedUser;
  }

  return currentUser;
}

// === CHANGED FOR FIX: Fetches all database rooms to map by textual name match ===
async function findBackendRoom(localRoom) {
  try {
    const roomsResp = await apiUserRequest('/hotels/8/rooms');
    const backendRooms = Array.isArray(roomsResp?.data) ? roomsResp.data : Array.isArray(roomsResp) ? roomsResp : [];
    
    if (!backendRooms.length) return { hotelId: null, roomId: null };

    let match = backendRooms.find(r => r.name.toLowerCase().trim() === localRoom.name.toLowerCase().trim());
    
    if (!match) {
      match = backendRooms.find(r => r.name.toLowerCase().includes(localRoom.name.toLowerCase()));
    }
    
    if (match) {
      return { hotelId: match.hotel_id, roomId: match.id };
    }
  } catch (err) {
    console.warn("Error inside findBackendRoom mapping logic:", err);
  }
  return { hotelId: null, roomId: null };
}

// === CHANGED FOR FIX: Builds direct payload format needed by BookingController.php ===
async function syncBookingToBackend(booking) {
  console.log('[SYNC] Starting:', booking.roomName);
  const userWithToken = await ensureBackendToken();
  if (!userWithToken || !userWithToken.token) {
    return { success: false, message: 'No backend auth token available.' };
  }

  const hotelRoom = await findBackendRoom({
    name: booking.roomName
  });

  if (!hotelRoom.roomId) {
    return { success: false, message: 'Could not map room to backend hotel/room.' };
  }

  const numericGuests = parseInt(booking.guests) || 1;

  const response = await apiUserRequest('/bookings', {
    method: 'POST',
    body: JSON.stringify({
      hotel_id: hotelRoom.hotelId,
      room_id: hotelRoom.roomId,
      check_in_date: booking.checkIn,
      check_out_date: booking.checkOut,
      guests: numericGuests,
    }),
  });

  const returnedBooking = response?.booking || response;

  if (!returnedBooking || !returnedBooking.id) {
    return { success: false, message: 'Backend booking creation failed.' };
  }

  const allBookings = getBookings();
  const idx = allBookings.findIndex(b => b.bookingId === booking.bookingId);
  if (idx !== -1) {
    allBookings[idx].backendId = returnedBooking.id;
    allBookings[idx].status = returnedBooking.status || allBookings[idx].status;
    saveBookings(allBookings);
  }

  return { success: true, booking: returnedBooking };
}

// ============================================================================
// UTILITY FUNCTIONS
// ============================================================================

function findUserByEmail(email) {
  return getUsers().find(user => user.email.toLowerCase() === email.toLowerCase());
}

function getRoomById(id) {
  return HOTEL_DATA.find(room => Number(room.id) === Number(id));
}

function formatCurrency(value) {
  return 'Rs ' + new Intl.NumberFormat('en-PK').format(value);
}

function dateDifference(start, end) {
  const startDate = new Date(start);
  const endDate = new Date(end);
  if (isNaN(startDate.valueOf()) || isNaN(endDate.valueOf())) return 0;
  const diff = endDate - startDate;
  return diff > 0 ? Math.ceil(diff / (1000 * 60 * 60 * 24)) : 0;
}

function getUrlParam(key) {
  return new URLSearchParams(window.location.search).get(key);
}

function generateBookingId() {
  return `booking_${Date.now()}_${Math.floor(Math.random() * 1000)}`;
}

// ============================================================================
// UNIVERSAL BOOKING BUTTON HANDLER
// ============================================================================

function initBookingButtons() {
  const bookingButtons = document.querySelectorAll('.book-now-btn');
  bookingButtons.forEach(button => {
    button.addEventListener('click', (e) => {
      e.preventDefault();
      const roomId = button.getAttribute('data-id');
      if (roomId) {
        window.location.href = `checkout.html?roomId=${roomId}`;
      }
    });
  });
}

// ============================================================================
// NAVIGATION RENDERING
// ============================================================================

function renderNavigation() {
  const currentUser = getCurrentUser();
  const welcomeEl = document.getElementById('nav-user-welcome');
  const loginLink = document.getElementById('login-link');
  const signupLink = document.getElementById('signup-link');
  const dashboardLink = document.getElementById('dashboard-link');
  const logoutButton = document.getElementById('logout-button');

  if (!welcomeEl || !loginLink || !signupLink || !dashboardLink || !logoutButton) return;

  if (currentUser) {
    welcomeEl.textContent = `Welcome, ${currentUser.name}`;
    welcomeEl.classList.remove('hidden');
    dashboardLink.classList.remove('hidden');
    logoutButton.classList.remove('hidden');
    loginLink.classList.add('hidden');
    signupLink.classList.add('hidden');
  } else {
    welcomeEl.classList.add('hidden');
    dashboardLink.classList.add('hidden');
    logoutButton.classList.add('hidden');
    loginLink.classList.remove('hidden');
    signupLink.classList.remove('hidden');
  }
}

function attachLogoutHandler() {
  const logoutButton = document.getElementById('logout-button');
  if (!logoutButton) return;
  logoutButton.addEventListener('click', () => {
    clearCurrentUser();
    window.location.href = 'index.html';
  });
}

// ============================================================================
// ROOM CARD TEMPLATE
// ============================================================================

function buildRoomCard(room) {
  return `
    <article class="group overflow-hidden rounded-[2rem] border border-slate-700/70 bg-slate-950/80 shadow-2xl shadow-slate-950/20 transition hover:-translate-y-1">
      <img src="${room.image}" alt="${room.name}" class="h-72 w-full object-cover transition duration-500 group-hover:scale-105" />
      <div class="p-6">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h3 class="text-2xl font-semibold text-white">${room.name}</h3>
            <p class="mt-3 text-slate-400">${room.description}</p>
          </div>
          <div class="rounded-3xl bg-amber-300/10 px-4 py-3 text-right text-sm text-amber-200 ring-1 ring-amber-200/20 min-w-max">
            <span class="block text-lg font-semibold text-amber-100 whitespace-nowrap">${formatCurrency(room.price)}</span>
            <span class="text-slate-300 whitespace-nowrap">per night</span>
          </div>
        </div>
        <div class="mt-6 flex flex-wrap gap-3">
          <button type="button" data-id="${room.id}" class="book-now-btn inline-flex items-center justify-center rounded-full bg-amber-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-amber-300">Book Now</button>
          <button type="button" data-id="${room.id}" class="book-now-btn text-sm font-medium text-slate-300 transition hover:text-white">View Details</button>
        </div>
      </div>
    </article>
  `;
}

// ============================================================================
// PAGE INITIALIZERS
// ============================================================================

function initIndexPage() {
  const featuredContainer = document.getElementById('featured-rooms');
  const heroSearch = document.getElementById('hero-search');
  const homeSearch = document.getElementById('home-search');
  const checkInInput = document.getElementById('hero-checkin');
  const checkOutInput = document.getElementById('hero-checkout');
  const guestsInput = document.getElementById('hero-guests');
  const homeCheckInInput = document.getElementById('home-checkin');
  const homeCheckOutInput = document.getElementById('home-checkout');
  const homeGuestsInput = document.getElementById('home-guests');

  const handleSearchClick = (checkinEl, checkoutEl, guestsEl) => {
    const params = new URLSearchParams({
      checkin: checkinEl?.value || '',
      checkout: checkoutEl?.value || '',
      guests: guestsEl?.value || ''
    });
    window.location.href = `rooms.html?${params.toString()}`;
  };

  if (featuredContainer) {
    featuredContainer.innerHTML = HOTEL_DATA.slice(0, 3).map(buildRoomCard).join('');
    initBookingButtons();
  }

  if (heroSearch) {
    heroSearch.addEventListener('click', () => handleSearchClick(checkInInput, checkOutInput, guestsInput));
  }

  if (homeSearch) {
    homeSearch.addEventListener('click', () => handleSearchClick(homeCheckInInput, homeCheckOutInput, homeGuestsInput));
  }
}

function initRoomsPage() {
  const searchInput = document.getElementById('search-query');
  const priceSelect = document.getElementById('price-filter');
  const roomsGrid = document.getElementById('rooms-grid');

  function filterRooms() {
    const searchValue = searchInput.value.trim().toLowerCase();
    const priceValue = priceSelect.value;
    const filtered = HOTEL_DATA.filter(room => {
      const text = `${room.name} ${room.description}`.toLowerCase();
      const matchesSearch = !searchValue || text.includes(searchValue);
      const matchesPrice = priceValue === 'all' || room.price <= Number(priceValue);
      return matchesSearch && matchesPrice;
    });
    roomsGrid.innerHTML = filtered.map(buildRoomCard).join('');
    initBookingButtons();
  }

  if (searchInput) searchInput.addEventListener('input', filterRooms);
  if (priceSelect) priceSelect.addEventListener('change', filterRooms);

  const queryParams = new URLSearchParams(window.location.search);
  const checkin = queryParams.get('checkin');
  const checkout = queryParams.get('checkout');
  const guests = queryParams.get('guests');

  if (checkin || checkout || guests) {
    const banner = document.createElement('div');
    banner.className = 'mb-8 rounded-[2rem] border border-amber-300/20 bg-amber-300/10 p-6 text-amber-100 ring-1 ring-amber-200/20';
    banner.innerHTML = `
      <p class="text-sm uppercase tracking-[0.32em]">Search details</p>
      <p class="mt-3 text-base text-slate-100">Check-in: <strong>${checkin || '—'}</strong> · Check-out: <strong>${checkout || '—'}</strong> · Guests: <strong>${guests || '1'}</strong></p>
    `;
    const main = document.querySelector('main');
    if (main) main.prepend(banner);
  }

  filterRooms();
}

function initCheckoutPage() {
  const roomId = getUrlParam('roomId');
  const room = getRoomById(roomId);
  const roomImage = document.getElementById('checkout-room-image');
  const roomTitle = document.getElementById('checkout-room-title');
  const roomDescription = document.getElementById('checkout-room-description');
  const roomPrice = document.getElementById('checkout-room-price');
  const bookingSummary = document.getElementById('booking-summary');
  const checkinInput = document.getElementById('checkin-date');
  const checkoutInput = document.getElementById('checkout-date');
  const guestsInput = document.getElementById('guest-count');
  const bookingForm = document.getElementById('booking-form');
  const invalidMessage = document.getElementById('checkout-invalid');

  if (!room) {
    if (invalidMessage) invalidMessage.classList.remove('hidden');
    return;
  }

  if (roomImage) roomImage.src = room.image;
  if (roomTitle) roomTitle.textContent = room.name;
  if (roomDescription) roomDescription.textContent = room.description;
  if (roomPrice) roomPrice.textContent = formatCurrency(room.price);

  function updateSummary() {
    const nights = dateDifference(checkinInput.value, checkoutInput.value);
    const total = nights * room.price;
    if (bookingSummary) {
      bookingSummary.innerHTML = `
        <div class="rounded-[2rem] border border-slate-700/60 bg-slate-950/80 p-6 shadow-2xl shadow-slate-950/20 overflow-hidden">
          <img src="${room.image}" alt="${room.name}" class="w-full h-48 object-cover rounded-[1rem] mb-6 shadow-lg shadow-slate-950/40" />
          <h2 class="text-xl font-semibold text-amber-200">Booking summary</h2>
          <div class="mt-5 space-y-4 text-slate-300">
            <div class="flex items-center justify-between"><span>Room</span><strong class="text-right text-white">${room.name}</strong></div>
            <div class="flex items-center justify-between"><span>Check-in</span><strong>${checkinInput.value || '—'}</strong></div>
            <div class="flex items-center justify-between"><span>Check-out</span><strong>${checkoutInput.value || '—'}</strong></div>
            <div class="flex items-center justify-between"><span>Nights</span><strong>${nights}</strong></div>
            <div class="flex items-center justify-between"><span>Rate</span><strong>${formatCurrency(room.price)}</strong></div>
            <div class="border-t border-slate-700 pt-4 text-base font-semibold text-white flex items-center justify-between"><span>Total</span><strong class="text-amber-200">${formatCurrency(total)}</strong></div>
          </div>
        </div>
      `;
    }
  }

  checkinInput.addEventListener('change', updateSummary);
  checkoutInput.addEventListener('change', updateSummary);
  updateSummary();

  if (bookingForm) {
    // 🌟 EXACT SOLUTION FIX: Made this event listener callback async so the database sync completes first
    bookingForm.addEventListener('submit', async event => {
      event.preventDefault();
      const currentUser = getCurrentUser();
      if (!currentUser) {
        window.location.href = 'login.html';
        return;
      }

      const checkin = checkinInput.value;
      const checkout = checkoutInput.value;
      const nights = dateDifference(checkin, checkout);
      if (!checkin || !checkout || nights <= 0) {
        alert('Please select a valid check-in and check-out date.');
        return;
      }

      const bookings = getBookings();
      const newBooking = {
        bookingId: generateBookingId(),
        userId: currentUser.id,
        roomId: room.id,
        roomName: room.name,
        checkIn: checkin,
        checkOut: checkout,
        totalPrice: room.price * nights,
        guests: guestsInput.value,
        status: 'pending',
        backendId: null,
        createdAt: new Date().toISOString(),
      };

      // 🌟 The script will now halt and verify completion of the Laravel API operation before redirection
      try {
        const syncResult = await syncBookingToBackend(newBooking);
        if (!syncResult.success) {
          console.warn('Backend sync warning:', syncResult.message);
        }
      } catch (e) {
        console.warn('Failed to sync booking to backend', e);
      }

      bookings.push(newBooking);
      saveBookings(bookings);
      window.location.href = 'dashboard.html';
    });
  }
}

function initDashboardPage() {
  const currentUser = getCurrentUser();
  if (!currentUser) {
    window.location.href = 'login.html';
    return;
  }

  const dashboardBookings = document.getElementById('dashboard-bookings');
  const dashboardMessage = document.getElementById('dashboard-message');
  const availableRoomsContainer = document.getElementById('available-rooms');

  if (availableRoomsContainer) {
    availableRoomsContainer.innerHTML = HOTEL_DATA.map(buildRoomCard).join('');
    initBookingButtons();
  }

  function renderBookings() {
    const allBookings = getBookings();
    const userBookings = allBookings.filter(booking => booking.userId === currentUser.id);

    if (!dashboardBookings) return;
    if (!userBookings.length) {
      if (dashboardMessage) dashboardMessage.classList.remove('hidden');
      dashboardBookings.innerHTML = '';
      return;
    }

    if (dashboardMessage) dashboardMessage.classList.add('hidden');

    dashboardBookings.innerHTML = userBookings.map(booking => {
      const room = getRoomById(booking.roomId);
      return `
        <article class="rounded-[2rem] border border-slate-700/60 bg-slate-950/80 p-6 shadow-2xl shadow-slate-950/20">
          <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
              <p class="text-sm uppercase tracking-[0.3em] text-amber-200">${room ? room.name : 'Reserved Room'}</p>
              <h3 class="mt-3 text-2xl font-semibold text-white">${booking.roomName || (room ? room.name : 'Room Booking')}</h3>
              <p class="mt-2 text-slate-400">${booking.checkIn} → ${booking.checkOut} · ${booking.guests} guest(s)</p>
            </div>
            <div class="space-y-3 text-right">
              <p class="text-sm text-slate-400">Total paid</p>
              <p class="text-3xl font-semibold text-amber-200">${formatCurrency(booking.totalPrice)}</p>
            </div>
          </div>
          <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-3xl bg-slate-900/80 p-4 text-slate-300">Booking ID: ${booking.bookingId}</div>
            <div class="rounded-3xl bg-slate-900/80 p-4 text-slate-300">Room ID: ${booking.roomId}</div>
            <div class="rounded-3xl bg-slate-900/80 p-4 text-slate-300">Created: ${new Date(booking.createdAt).toLocaleDateString()}</div>
            <button type="button" data-booking-id="${booking.bookingId}" class="cancel-booking-button rounded-3xl bg-amber-400 px-4 py-4 text-sm font-semibold text-slate-950 transition hover:bg-amber-300">Cancel Booking</button>
          </div>
        </article>
      `;
    }).join('');

    document.querySelectorAll('.cancel-booking-button').forEach(button => {
      button.addEventListener('click', () => {
        const bookingId = button.getAttribute('data-booking-id');
        const all = getBookings();
        const booking = all.find(b => b.bookingId === bookingId);
        const currentUser = getCurrentUser();

        (async () => {
          try {
            if (booking && booking.backendId && currentUser && currentUser.token) {
              await apiUserRequest(`/bookings/${booking.backendId}/cancel`, {
                method: 'PATCH',
                headers: { Authorization: `Bearer ${currentUser.token}` },
              });
            }
          } catch (e) {
            console.warn('Failed to cancel backend booking', e);
          } finally {
            const updatedBookings = getBookings().filter(b => b.bookingId !== bookingId);
            saveBookings(updatedBookings);
            renderBookings();
          }
        })();
      });
    });
  }

  renderBookings();
}

function initLoginPage() {
  const currentUser = getCurrentUser();
  if (currentUser) {
    window.location.href = 'index.html';
    return;
  }

  const loginForm = document.getElementById('login-form');
  const emailInput = document.getElementById('login-email');
  const passwordInput = document.getElementById('login-password');

  if (!loginForm) return;
  loginForm.addEventListener('submit', event => {
    event.preventDefault();
    const email = emailInput.value.trim().toLowerCase();
    const password = passwordInput.value;
    const user = findUserByEmail(email);
    (async () => {
      const resp = await apiUserRequest('/auth/login', {
        method: 'POST',
        body: JSON.stringify({ email, password }),
      });
      if (resp && resp.token && resp.user) {
        const serverUser = resp.user;
        const cuser = { id: serverUser.id, name: serverUser.name, email: serverUser.email, token: resp.token };
        setCurrentUser(cuser);
        const users = getUsers();
        if (!users.find(u => u.email === cuser.email)) {
          users.push({ id: cuser.id, name: cuser.name, email: cuser.email, password });
          saveUsers(users);
        }
        window.location.href = 'index.html';
        return;
      }

      if (!user || user.password !== password) {
        alert('Invalid email or password.');
        return;
      }

      setCurrentUser(user);
      window.location.href = 'index.html';
    })();
  });
}

function initSignupPage() {
  const currentUser = getCurrentUser();
  if (currentUser) {
    window.location.href = 'index.html';
    return;
  }

  const signupForm = document.getElementById('signup-form');
  const nameInput = document.getElementById('signup-name');
  const emailInput = document.getElementById('signup-email');
  const passwordInput = document.getElementById('signup-password');

  if (!signupForm) return;
  signupForm.addEventListener('submit', event => {
    event.preventDefault();
    const name = nameInput.value.trim();
    const email = emailInput.value.trim().toLowerCase();
    const password = passwordInput.value;

    if (!name || !email || !password) {
      alert('Please complete all fields to sign up.');
      return;
    }

    (async () => {
      const resp = await apiUserRequest('/auth/register', {
        method: 'POST',
        body: JSON.stringify({ name, email, password }),
      });
      if (resp && resp.token && resp.user) {
        const serverUser = resp.user;
        const cuser = { id: serverUser.id, name: serverUser.name, email: serverUser.email, token: resp.token };
        const users = getUsers();
        users.push({ id: cuser.id, name: cuser.name, email: cuser.email, password });
        saveUsers(users);
        setCurrentUser(cuser);
        window.location.href = 'index.html';
        return;
      }

      if (findUserByEmail(email)) {
        alert('That email is already registered. Please log in.');
        return;
      }

      const users = getUsers();
      const newUser = { id: Date.now(), name, email, password };
      users.push(newUser);
      saveUsers(users);
      setCurrentUser(newUser);
      window.location.href = 'index.html';
    })();
  });
}

// ============================================================================
// GLOBAL INITIALIZATION
// ============================================================================

function initApp() {
  renderNavigation();
  attachLogoutHandler();
  initBookingButtons();

  const page = window.location.pathname.split('/').pop();
  switch (page) {
    case 'index.html':
    case '':
      initIndexPage();
      break;
    case 'rooms.html':
      initRoomsPage();
      break;
    case 'checkout.html':
      initCheckoutPage();
      break;
    case 'dashboard.html':
      initDashboardPage();
      break;
    case 'login.html':
      initLoginPage();
      break;
    case 'signup.html':
      initSignupPage();
      break;
    default:
      break;
  }
}

document.addEventListener('DOMContentLoaded', initApp);