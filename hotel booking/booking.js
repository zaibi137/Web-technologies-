function initializeCheckoutPage() {
  authGuard();

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
        removeBooking(bookingId);
        refreshBookings();
      });
    });
  }

  refreshBookings();
}

function goToCheckout(roomId) {
  window.location.href = `checkout.html?roomId=${roomId}`;
}
