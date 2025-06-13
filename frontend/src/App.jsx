import './App.css'
import React from 'react'
import { Routes, Route } from 'react-router-dom';
import DashboardPage from './app/dashboard/page';
import LoginPage from './app/login/page';
function App() {
  return (
    <>
    <Routes>
      <Route path='/' element={ <DashboardPage/>}/>
      <Route path='/login' element={ <LoginPage /> }/>
    </Routes>
    </>
  )
}

export default App
