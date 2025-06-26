import './App.css'
import React from 'react'
import { Routes, Route } from 'react-router-dom';
import DashboardPage from './app/dashboard/page';
import LoginPage from './app/login/page';
import SingupPage from './app/signup/page';
import DashboardTest from './app/home/dashboard';
function App() {
  return (
    <>
    <Routes>
      <Route path='/' element={ <DashboardPage/>}/>
      <Route path='/login' element={ <LoginPage /> }/>
      <Route path='/signup' element={ <SingupPage /> }/>
      <Route path='/test' element={ <DashboardTest /> }/>
    </Routes>
    </>
  )
}

export default App
