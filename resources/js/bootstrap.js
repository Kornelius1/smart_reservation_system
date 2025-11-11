import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Setup CSRF token from meta tag
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Add request interceptor for JSON content type
window.axios.interceptors.request.use(function (config) {
    if (config.headers['Content-Type'] === 'application/json') {
        config.headers['Accept'] = 'application/json';
    }
    return config;
}, function (error) {
    return Promise.reject(error);
});

// Add response interceptor for error handling
window.axios.interceptors.response.use(
    function (response) {
        return response;
    },
    function (error) {
        if (error.response?.status === 422) {
            // Validation errors
            return Promise.reject(error);
        }
        
        if (error.response?.status === 419) {
            // CSRF token mismatch
            console.error('CSRF token mismatch. Please refresh the page.');
            window.location.reload();
            return;
        }
        
        return Promise.reject(error);
    }
);