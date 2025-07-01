import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import { BrowserRouter } from 'react-router-dom'
import './index.css'
import App from './App.jsx'
import { Theme, ThemePanel } from "@radix-ui/themes";
import Background from './components/background.jsx';

createRoot(document.getElementById('root')).render(
  <StrictMode>
    <BrowserRouter>
    <Theme panelBackground="translucent" accentColor="indigo" grayColor="sand" radius="large" scaling="95%" appearance='dark'>
      <Background />
      <App />
    </Theme>
    </BrowserRouter>
  </StrictMode>,
)
