/**
 * OPAL HAVEN - Admin Panel
 * admin/js/admin.js — Shared utilities (API, auth, UI helpers)
 *
 * Fixed issues:
 *  - GET cache is now cleared after any mutating request (POST/PUT/PATCH/DELETE)
 *  - 403 no longer blindly redirects to login (only 401 does)
 *  - apiRequest signature normalised: always (endpoint, optionsObject)
 *  - initializeAdminHeader() uses correct response shape
 */

const API_BASE_URL = 'http://localhost:8000/api';

// In-memory GET cache — keyed by "METHOD:endpoint"
const apiCache = new Map();

// Global loading overlay counter
let activeApiRequests = 0;
let loadingOverlayEl  = null;

const API_REQUEST_TIMEOUT = 10000; // 10 s

// ─── Token helpers ────────────────────────────────────────────────────────────

function getToken()  { return localStorage.getItem('adminToken'); }
function setToken(t) { localStorage.setItem('adminToken', t); }
function clearToken(){ localStorage.removeItem('adminToken'); }

function authHeaders() {
    return {
        'Authorization': `Bearer ${getToken()}`,
        'Content-Type':  'application/json',
        'Accept':        'application/json',
    };
}

/**
 * Redirect to login if no token is present.
 * Returns true when authenticated, false when redirecting.
 */
function checkAdminAuth() {
    if (!getToken()) {
        window.location.href = 'login.html';
        return false;
    }
    return true;
}

// ─── Core API request ─────────────────────────────────────────────────────────

/**
 * @param {string} endpoint   e.g. '/hotels' or '/hotels/1'
 * @param {object} [options]  fetch options + optional `forceRefresh: true`
 */
async function apiRequest(endpoint, options = {}) {
    const method   = (options.method || 'GET').toUpperCase();
    const cacheKey = `GET:${endpoint}`;

    // Serve from cache for GET requests (unless forceRefresh)
    if (method === 'GET' && !options.forceRefresh && apiCache.has(cacheKey)) {
        return apiCache.get(cacheKey);
    }

    const url        = `${API_BASE_URL}${endpoint}`;
    const controller = new AbortController();
    const timeoutId  = setTimeout(() => controller.abort(), API_REQUEST_TIMEOUT);

    activeApiRequests++;
    updateLoadingOverlay();

    try {
        const response = await fetch(url, {
            ...options,
            method,
            headers: { ...authHeaders(), ...(options.headers || {}) },
            signal:  controller.signal,
        });

        clearTimeout(timeoutId);

        const contentType = response.headers.get('content-type') || '';
        const data = contentType.includes('application/json')
            ? await response.json()
            : { message: await response.text() };

        console.log(`[API] ${method} ${endpoint} → ${response.status}`, data);

        // 401 = token expired / missing → force re-login
        if (response.status === 401) {
            clearToken();
            window.location.href = 'login.html';
            return null;
        }

        // 403 = authenticated but not allowed — show error, do NOT redirect
        if (response.status === 403) {
            showAlert(data.message || 'You do not have permission for this action.', 'danger');
            return null;
        }

        if (!response.ok) {
            handleApiError(data, response.status);
            return null;
        }

        // Cache successful GET responses
        if (method === 'GET') {
            apiCache.set(cacheKey, data);
        } else {
            // Invalidate related GET cache entries after any mutation
            invalidateCache(endpoint);
        }

        return data;

    } catch (err) {
        clearTimeout(timeoutId);
        if (err.name === 'AbortError') {
            showAlert('Request timed out. Is your backend server running on port 8000?', 'warning');
        } else {
            console.error('[API Error]', { url, err });
            showAlert(`Network error: ${err.message || 'Please check your connection.'}`, 'danger');
        }
        return null;
    } finally {
        activeApiRequests--;
        updateLoadingOverlay();
    }
}

/**
 * Clear all cache entries whose key starts with "GET:/…base…"
 * e.g. deleting /hotels/3 invalidates GET:/hotels and GET:/hotels/3
 */
function invalidateCache(endpoint) {
    // Strip trailing ID to get the collection key  e.g. /hotels/3 → /hotels
    const base = endpoint.replace(/\/\d+$/, '');
    for (const key of apiCache.keys()) {
        if (key.startsWith('GET:' + base) || key === 'GET:' + endpoint) {
            apiCache.delete(key);
        }
    }
    // Also always clear dashboard cache after any change
    apiCache.delete('GET:/admin/dashboard');
}

// ─── Loading overlay ──────────────────────────────────────────────────────────

function ensureLoadingOverlay() {
    if (loadingOverlayEl) return loadingOverlayEl;
    loadingOverlayEl = document.createElement('div');
    loadingOverlayEl.id = 'admin-loading-overlay';
    Object.assign(loadingOverlayEl.style, {
        position: 'fixed', inset: '0', zIndex: '2000',
        display: 'none', alignItems: 'center', justifyContent: 'center',
        background: 'rgba(15,23,42,0.75)', color: '#fff',
        backdropFilter: 'blur(4px)',
    });
    loadingOverlayEl.innerHTML = `
        <div style="text-align:center;">
            <div class="spinner-border text-light" style="width:3rem;height:3rem;" role="status"></div>
            <div style="margin-top:1rem;font-weight:600;">Loading…</div>
        </div>`;
    document.body.appendChild(loadingOverlayEl);
    return loadingOverlayEl;
}

function updateLoadingOverlay() {
    const el = ensureLoadingOverlay();
    el.style.display = activeApiRequests > 0 ? 'flex' : 'none';
}

// ─── Error handler ────────────────────────────────────────────────────────────

function handleApiError(error, status) {
    let msg = 'An unexpected error occurred.';
    if (status === 422 && error.errors) {
        msg = Object.values(error.errors).flat()[0];
    } else if (status === 422) {
        msg = error.message || 'Validation failed.';
    } else if (status === 404) {
        msg = 'Resource not found.';
    } else if (status === 500) {
        msg = 'Server error — check the Laravel log.';
    } else if (error.message) {
        msg = error.message;
    }
    showAlert(msg, 'danger');
}

// ─── Alert helper ─────────────────────────────────────────────────────────────

function showAlert(message, type = 'info') {
    let container = document.getElementById('alertContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'alertContainer';
        document.body.insertAdjacentElement('afterbegin', container);
    }
    container.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
    setTimeout(() => {
        const a = container.querySelector('.alert');
        if (a) a.remove();
    }, 5000);
}

// ─── Formatting helpers ───────────────────────────────────────────────────────

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount || 0);
}

function formatDate(dateString) {
    if (!dateString) return '—';
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric', month: 'short', day: 'numeric',
    });
}

function getStatusBadge(status) {
    const map = {
        pending:   '<span class="badge bg-warning text-dark">Pending</span>',
        confirmed: '<span class="badge bg-success">Confirmed</span>',
        cancelled: '<span class="badge bg-danger">Cancelled</span>',
        completed: '<span class="badge bg-secondary">Completed</span>',
    };
    return map[status] || `<span class="badge bg-light text-dark">${status || '—'}</span>`;
}

// ─── Logout ───────────────────────────────────────────────────────────────────

async function logout() {
    try {
        await apiRequest('/auth/logout', { method: 'POST' });
    } catch (_) { /* ignore */ }
    clearToken();
    apiCache.clear();
    window.location.href = 'login.html';
}

// ─── Admin header (name) ──────────────────────────────────────────────────────

async function initializeAdminHeader() {
    const el = document.getElementById('admin-name');
    if (!el) return;

    try {
        const res = await apiRequest('/auth/me');
        // Response shape: { user: { name: "…" } }
        const name = res?.user?.name || res?.name;
        if (name) el.textContent = name;
    } catch (_) {
        el.textContent = 'Admin';
    }
}
