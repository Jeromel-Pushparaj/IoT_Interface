import './App.css'
import React, { useEffect } from 'react'
import { Routes, Route, useNavigate } from 'react-router-dom';
import { isTokenExpired, logoutAndRedirect } from './utils/auth'; // Adjust the import path as necessary
import DashboardPage from './app/dashboard/page';
import LoginPage from './app/login/page';
import SingupPage from './app/signup/page';
import DashboardTest from './app/home/dashboard';
import DevicePage from './app/device/page';
import AddDevice from './app/device/AddDevice';
import ControlPage from './app/control/page'; // Import the ControlPage component

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
      <Route path='/' element={ <DashboardPage/>}/>
      
      // Routes for Devices
      <Route path='/device' element={ <DevicePage/>}/> 
      <Route path='/device/add' element={ <AddDevice/> }/>

      //Routes for Controll page
      <Route path='/control' element={ <ControlPage/> }/>
      <Route path='/control/:deviceId' element={ <ControlPage/> }/>

      <Route path='/login' element={ <LoginPage /> }/>
      <Route path='/signup' element={ <SingupPage /> }/>
      <Route path='/test' element={ <DashboardTest /> }/>
    </Routes>
    </>
  )
}

export default App
