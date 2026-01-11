import axios from 'axios';

// Request Interceptor
axios.interceptors.request.use(
    function (config) {
        // Get current outlet from localStorage
        const storedOutlet = localStorage.getItem('current_outlet');

        if (storedOutlet) {
            try {
                const outlet = JSON.parse(storedOutlet);

                // ✅ Add OutletID to request header
                config.headers['X-Outlet-ID'] = outlet.OutletID;

                console.log('Request with OutletID:', outlet.OutletID);
            } catch (e) {
                console.error('Failed to parse stored outlet', e);
            }
        }

        return config;
    },
    function (error) {
        return Promise.reject(error);
    }
);

// Response Interceptor (for error handling)
axios.interceptors.response.use(
    function (response) {
        return response;
    },
    function (error) {
        // If outlet not found or invalid
        if (error.response && error.response.status === 422) {
            const message = error.response.data.message;

            if (message && message.toLowerCase().includes('outlet')) {
                // Clear invalid outlet from localStorage
                localStorage.removeItem('current_outlet');

                // Redirect to outlet switcher
                if (window.vueApp && window.vueApp.$router) {
                    window.vueApp.$router.push('/outlet-switcher');
                }
            }
        }

        return Promise.reject(error);
    }
);

export default axios;
