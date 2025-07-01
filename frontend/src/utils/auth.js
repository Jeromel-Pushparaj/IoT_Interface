import { jwtDecode } from 'jwt-decode';

export function isTokenExpired() {
  const token = localStorage.getItem('token');
  if (!token) return true;

  try {
    const decoded = jwtDecode(token);
    const now = Date.now() / 1000;
    return decoded.exp < now;
  } catch (e) {
    return true;
  }
}

export function logoutAndRedirect(navigate) {
  localStorage.removeItem('token');
  navigate('/login');
}
