import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import { BrowserRouter } from 'react-router-dom'
import './index.css'
import App from './App.jsx'
import { Theme, ThemePanel } from "@radix-ui/themes";

createRoot(document.getElementById('root')).render(
  <StrictMode>
    <BrowserRouter>
    <Theme accentColor="crimson" grayColor="sand" radius="large" scaling="95%" appearance='dark'>
      <App />
    </Theme>
    </BrowserRouter>
  </StrictMode>,
)
