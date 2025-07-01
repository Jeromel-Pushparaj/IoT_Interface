import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import { BrowserRouter } from 'react-router-dom'
import './index.css'
import App from './App.jsx'
import { 
  Theme, 
  Box,
  Flex,
 } from "@radix-ui/themes";
import Background from './components/background.jsx';
import AppHeader from '@/components/header.jsx';


createRoot(document.getElementById('root')).render(
  <StrictMode>
    <BrowserRouter>
    <Theme panelBackground="translucent" accentColor="indigo" grayColor="sand" radius="large" scaling="95%" appearance='dark'>
      <Background />
      
      <Box className="absolute left-0 right-0 min-h-screen ">
        <Flex>
         <Box style={{ flex: 1 }}>
           <Box 
              style={{ 
                backgroundColor: 'transparent', 
                padding: '1rem 2rem'
              }}
            >
              <AppHeader />
            </Box>
            <App />
          </Box>
        </Flex>
      </Box>
    </Theme>
    </BrowserRouter>
  </StrictMode>,
)
