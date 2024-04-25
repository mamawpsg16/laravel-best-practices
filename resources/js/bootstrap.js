/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
import { useAuthStore } from './stores/authStore';
window.axios = axios;

const handleLogout = function() {
  localStorage.removeItem('authenticated');
  useAuthStore().logout();
  window.location.href = "/login";
}

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
// axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.baseURL = 'http://laravel-best-practices.org'
window.axios.interceptors.response.use(
  function(response) {
      // Call was successful, don't do anything special.
      return response;
  },
  function (error) {
  switch (error.response.status) {
      case 401 && localStorage.getItem('authenticated'): // Not logged in
      case 419: 
        handleLogout()
        break;
      case 403: // Forbidden
        window.location.href = "/forbidden";
        break;
 
      case 503: // Down for maintenance
          // Bounce the user to the login screen with a redirect back
          // window.location.reload();
          break;
      case 500:
          // alert('Oops, something went wrong!  The team have been notified.');
          break;
      default:
          // Allow individual requests to handle other errors
          return Promise.reject(error);
  }
});

const csrf_token = document.head.querySelector('meta[name="csrf-token"]');
if (csrf_token) {
  axios.defaults.headers.common["X-CSRF-TOKEN"] = csrf_token.content;
}
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
