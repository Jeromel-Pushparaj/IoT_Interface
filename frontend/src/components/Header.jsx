import React, { useState } from 'react';
import { Flex, Text, Button, DropdownMenu, IconButton } from '@radix-ui/themes';
import { Layers, Settings } from 'lucide-react';
import { useNavigate } from 'react-router-dom';

const AppHeader = () => {
  const [activeItem, setActiveItem] = useState('dashboard');
  const navigate = useNavigate();
  const token = localStorage.getItem('token'); // Get token for conditional rendering

  function handleLogout() {
    localStorage.removeItem('token'); // Remove the token
    navigate('/login'); // Redirect to login
  }

  const navItems = [
    { id: 'dashboard', label: 'Dashboard', action: '/' },
    { id: 'devices', label: 'Devices', action: '/device' },
    { id: 'control', label: 'Control', action: '/control' },
  ];

  return (
    <header className="fixed top-5 left-1/2 -translate-x-1/2 z-50">
      <div className="px-4 py-2 rounded-full border border-slate-700/50 bg-slate-800/50 backdrop-blur-lg shadow-lg">
        <Flex align="center" justify="between" gap="6">
          {/* Logo and Name */}
          <Flex align="center" gap="3">
            <div className="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
              <Layers size={18} className="text-white" />
            </div>
            <Text size="3" weight="bold" className="text-white">
              IoT Interface
            </Text>
          </Flex>

          {/* Separator */}
          <div className="h-6 w-px bg-slate-600"></div>

          {/* Center Buttons and Profile Dropdown */}
          <nav>
            <Flex align="center" gap="4">
              {navItems.map((item) => (
                <Button
                  key={item.id}
                  variant={activeItem === item.id ? "soft" : "ghost"}
                  size="2"
                  color="gray"
                  radius="full"
                  className="transition-colors text-slate-300 hover:text-white"
                  onClick={() => {
                    setActiveItem(item.id);
                    navigate(item.action);
                  }}
                  highContrast={activeItem === item.id}
                >
                  {item.label}
                </Button>
              ))}

              {token && ( // Conditionally render Profile dropdown if token exists
                <DropdownMenu.Root>
                  <DropdownMenu.Trigger>
                    <IconButton
                      variant="ghost"
                      size="2"
                      color="gray"
                      radius="full"
                      className="text-slate-300 hover:text-white"
                    >
                      <Settings size={18} />
                    </IconButton>
                  </DropdownMenu.Trigger>
                  <DropdownMenu.Content>
                    <DropdownMenu.Item disabled>
                      Profile
                    </DropdownMenu.Item>
                    <DropdownMenu.Separator />
                    <DropdownMenu.Item onClick={handleLogout}>
                      Logout
                    </DropdownMenu.Item>
                  </DropdownMenu.Content>
                </DropdownMenu.Root>
              )}
            </Flex>
          </nav>
        </Flex>
      </div>
    </header>
  );
};

export default AppHeader;