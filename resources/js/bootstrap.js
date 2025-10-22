import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;

window.axios.interceptors.request.use(config => {
    // Attempt to get the token. You might need to adjust 'XSRF-TOKEN' if your cookie name differs.
    const token = document.cookie.split('; ').find(row => row.startsWith('XSRF-TOKEN='))?.split('=')[1];

    if (token) {
        config.headers['X-XSRF-TOKEN'] = decodeURIComponent(token);
    } else {
        console.warn('XSRF-TOKEN cookie not found. Ensure it is being set by the backend.');
    }

    return config;
});