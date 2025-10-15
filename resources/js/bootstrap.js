import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-with'] = 'XMLHttpRequest';

// Esta línea le dice a Axios que envíe automáticamente las cookies
// (como la de sesión y la de CSRF) en cada petición a la API.
// Esto es lo que soluciona el error 401 Unauthorized.
window.axios.defaults.withCredentials = true;
