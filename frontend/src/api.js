import axios from "axios";

const api = axios.create({
  baseURL: "http://192.168.1.12:8080",
  timeout: 10000,
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
        if  (err.response && err.response.status === 401) {
          localStorage.removeItem('token');
            // Handle unauthorized access, e.g., redirect to login
            console.error("Unauthorized access - redirecting to login");
            window.location.href = "/login"; // Adjust the path as needed
        }
        return Promise.reject(err);
    }
)

export default api;