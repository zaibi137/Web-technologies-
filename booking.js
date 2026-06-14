function initializeCheckoutPage() {
  authGuard();

  const API_BASE_URL = 'http://localhost:8000/api';
  async function apiUserRequest(endpoint, options = {}) {
    const url = `${API_BASE_URL}${endpoint}`;
    try {
      const resp = await fetch(url, { ...options, headers: { 'Content-Type': 'application/json', Accept: 'application/json', ...(options.headers||{}) } });
      const ct = resp.headers.get('content-type') || '';
      const data = ct.includes('application/json') ? await resp.json() : { message: await resp.text() };
      if (!resp.ok) throw { status: resp.status, data };
      return data;
    } catch (e) {
      console.warn('booking api error', e);
      return null;
    }
  }

  const roomSelect = document.getElementById('room-select');
  const guestName = document.getElementById('guest-name');
  const guestEmail = document.getElementById('guest-email');
  const checkinDate = document.getElementById('checkin-date');
  const checkoutDate = document.getElementById('checkout-date');
  const guestCount = document.getElementById('guest-count');
  const bookingSummary = document.getElementById('booking-summary');
  const bookingForm = document.getElementById('booking-form');
  const roomTitle = document.getElementById('selected-room-title');

  ROOM_DATA.forEach(room => {
    const option = document.createElement('option');
    option.value = room.id;
    option.textContent = `${room.name} — ${formatCurrency(room.price)} / night`;
    roomSelect.appendChild(option);
  });

  const currentUser = getCurrentUser();
  if (currentUser) {
    guestName.value = currentUser.name;
    guestEmail.value = currentUser.email;
  }

  const selectedRoomId = getQueryParam('roomId');
  if (selectedRoomId) {
    const matchingRoom = ROOM_DATA.find(room => room.id === Number(selectedRoomId));
    if (matchingRoom) {
      roomSelect.value = matchingRoom.id;
      roomTitle.textContent = matchingRoom.name;
    }
  }

  function refreshSummary() {
    const room = getRoomById(roomSelect.value);
    if (room) {
      roomTitle.textContent = room.name;
      updateBookingSummary(checkinDate.value, checkoutDate.value, room.price, bookingSummary);
    }
  }

  roomSelect.addEventListener('change', refreshSummary);
  checkinDate.addEventListener('change', refreshSummary);
  checkoutDate.addEventListener('change', refreshSummary);

  bookingForm.addEventListener('submit', event => {
    event.preventDefault();
    const room = getRoomById(roomSelect.value);
    const nights = dateDifference(checkinDate.value, checkoutDate.value);
    if (!guestName.value.trim() || !guestEmail.value.trim() || !checkinDate.value || !checkoutDate.value || nights <= 0) {
      alert('Please fill all fields and choose valid dates.');
      return;
    }

    const currentUser = getCurrentUser();
    if (!currentUser) {
      alert('Please log in to continue.');
      window.location.href = 'login.html';
      return;
    }

    const booking = {
      id: Date.now(),
      userId: currentUser.id,
      guestName: guestName.value.trim(),
      guestEmail: guestEmail.value.trim(),
      roomId: room.id,
      roomName: room.name,
      checkIn: checkinDate.value,
      checkOut: checkoutDate.value,
      guests: guestCount.value,
      totalPrice: room.price * nights,
      createdAt: new Date().toISOString()
    };

    addBooking(booking);
    // attempt to sync with backend
    (async () => {
      try {
        const currentUser = getCurrentUser();
        const token = currentUser && currentUser.token;
        // try to find backend hotel/room mapping similar to main.js approach
        const hotelsResp = await apiUserRequest('/hotels');
        let hotelId = null;
        let roomId = null;
        if (hotelsResp && Array.isArray(hotelsResp.data ? hotelsResp.data : hotelsResp)) {
          const hotels = hotelsResp.data || hotelsResp;
          for (const h of hotels) {
            const roomsResp = await apiUserRequest(`/hotels/${h.id}/rooms`);
            const rooms = roomsResp && (roomsResp.data || roomsResp) ? (roomsResp.data || roomsResp) : [];
            const match = rooms.find(r => (r.name || '').toLowerCase().includes((room.name || '').toLowerCase()));
            if (match) { hotelId = h.id; roomId = match.id; break; }
          }
          if (!hotelId && hotels.length) {
            hotelId = hotels[0].id;
            const roomsResp = await apiUserRequest(`/hotels/${hotelId}/rooms`);
            const rooms = roomsResp && (roomsResp.data || roomsResp) ? (roomsResp.data || roomsResp) : [];
            if (rooms.length) roomId = rooms[0].id;
          }
        }

        if (hotelId && roomId && token) {
          const resp = await apiUserRequest('/bookings', {
            method: 'POST',
            headers: { Authorization: `Bearer ${token}` },
            body: JSON.stringify({ hotel_id: hotelId, room_id: roomId, check_in_date: checkinDate.value, check_out_date: checkoutDate.value, guests: Number(guestCount.value || 1) })
          });
          if (resp && resp.booking && resp.booking.id) {
            // store backend id on the local booking
            const all = getBookings();
            const idx = all.findIndex(b => b.id === booking.id);
            if (idx !== -1) { all[idx].backendId = resp.booking.id; saveBookings(all); }
          }
        }
      } catch (e) { console.warn('sync failed', e); }
    })();

    window.location.href = 'dashboard.html';
  });

  refreshSummary();
}

function initializeDashboardPage() {
  authGuard();

  const currentUser = getCurrentUser();
  const bookingList = document.getElementById('booking-list');

  function renderBooking(booking) {
    return `
      <article class="glass-panel rounded-[2rem] border border-slate-700/50 p-8 shadow-2xl shadow-slate-950/20">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
          <div class="space-y-4">
            <p class="text-sm uppercase tracking-[0.32em] text-amber-200">${booking.roomName}</p>
            <h2 class="text-2xl font-semibold text-white">${booking.guestName}</h2>
            <p class="text-slate-400">${booking.guests} · ${booking.checkIn} to ${booking.checkOut}</p>
          </div>
          <div class="space-y-3 text-right">
            <p class="text-sm text-slate-400">Total paid</p>
            <p class="text-3xl font-semibold text-amber-200">${formatCurrency(booking.totalPrice)}</p>
          </div>
        </div>
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
          <div class="rounded-3xl bg-slate-900/80 p-5 text-slate-300">Booking ID: ${booking.id}</div>
          <div class="rounded-3xl bg-slate-900/80 p-5 text-slate-300">Email: ${booking.guestEmail || '—'}</div>
          <div class="rounded-3xl bg-slate-900/80 p-5 text-slate-300">Created: ${new Date(booking.createdAt).toLocaleDateString()}</div>
          <button data-id="${booking.id}" class="cancel-booking-button rounded-3xl bg-amber-400 px-5 py-4 font-semibold text-slate-950 transition hover:bg-amber-300">Cancel booking</button>
        </div>
      </article>
    `;
  }

  function refreshBookings() {
    const bookings = getBookingsByUser(currentUser.id);
    if (!bookings.length) {
      bookingList.innerHTML = `<div class="rounded-[2rem] border border-slate-700/50 bg-slate-950/80 p-12 text-center text-slate-300 shadow-2xl shadow-slate-950/20"><p class="text-xl font-semibold text-white">No active bookings yet.</p><p class="mt-3">Start a reservation on the Rooms page and it will appear here instantly.</p></div>`;
      return;
    }

    bookingList.innerHTML = bookings.map(renderBooking).join('');
    bookingList.querySelectorAll('.cancel-booking-button').forEach(button => {
      button.addEventListener('click', () => {
        const bookingId = Number(button.getAttribute('data-id'));
        const all = getBookings();
        const booking = all.find(b => b.id === bookingId);
        const currentUser = getCurrentUser();
        (async () => {
          try {
            if (booking && booking.backendId && currentUser && currentUser.token) {
              await apiUserRequest(`/bookings/${booking.backendId}/cancel`, {
                method: 'PATCH',
                headers: { Authorization: `Bearer ${currentUser.token}` }
              });
            }
          } catch (e) { console.warn('backend cancel failed', e); }
          removeBooking(bookingId);
          refreshBookings();
        })();
      });
    });
  }

  refreshBookings();
}

function goToCheckout(roomId) {
  window.location.href = `checkout.html?roomId=${roomId}`;
}
