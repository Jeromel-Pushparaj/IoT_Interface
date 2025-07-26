import axios from "axios";
import { API_CONFIG } from "@config/api";

const api = axios.create({
    baseURL: API_CONFIG.development.BASE_URL,
    timeout: API_CONFIG.TIMEOUT,
});

//Intercept requests to add the Authorization header
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem("token");
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

api.interceptors.response.use(
    (res) => res,
    (err) => {
        if (err.response && err.response.status === 401) {
            localStorage.removeItem("token");
            console.error("Unauthorized access - redirecting to login");
            window.location.href = "/login";
        }
        return Promise.reject(err);
    }
);

export default api;
