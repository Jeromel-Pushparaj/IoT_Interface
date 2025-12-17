import "./App.css";
import React, { useEffect } from "react";
import { Routes, Route, useNavigate } from "react-router-dom";
import { isTokenExpired, logoutAndRedirect } from "@utils/auth";
import DashboardPage from "@app/dashboard/DashboardPage";
import LoginPage from "@app/login/LoginPage";
import SignupPage from "@app/signup/SignupPage";
import HomePage from "@app/home/HomePage";
import DevicePage from "@app/device/DevicePage";
import AddDevice from "@app/device/AddDevice";
import ControlPage from "@app/control/ControlPage";

function App() {
  const navigate = useNavigate();

  useEffect(() => {
    if (isTokenExpired()) {
      logoutAndRedirect(navigate);
    }
  }, []);

  return (
    <>
      <Routes>
        <Route path="/" element={<DashboardPage />} />
        {/* // Routes for Devices */}
        <Route path="/device" element={<DevicePage />} />
        <Route path="/device/add" element={<AddDevice />} />
        {/* //Routes for Controll page */}
        <Route path="/control" element={<ControlPage />} />
        <Route path="/control/:deviceId" element={<ControlPage />} />
        <Route path="/login" element={<LoginPage />} />
        <Route path="/signup" element={<SignupPage />} />
        <Route path="/test" element={<HomePage />} />
      </Routes>
    </>
  );
}

export default App;
