import React, { useState } from 'react';
import { 
  Box, 
  Flex, 
  Text, 
  Button,
  Badge,
  DropdownMenu,
  IconButton
} from '@radix-ui/themes';
import { 
  ChevronDown,
  Github,
  ExternalLink,
  Palette,
  FileText,
  Book,
  Settings,
  Layers,
  Code
} from 'lucide-react';

const AppHeader = () => {
  const [activeItem, setActiveItem] = useState('dashboard');

  const leftNavItems = [
    { id: 'dashboard', label: 'Dashboard', hasDropdown: false },
    { id: 'devices', label: 'Devices', hasDropdown: false },
    { id: 'control', label: 'Control', hasDropdown: false, hasBadge: true },
    { id: 'icons', label: 'Profile', hasDropdown: false },
  ];

  const rightNavItems = [
    { id: 'documentation', label: 'Documentation', icon: Book },
    { id: 'custom-devices', label: 'Custom Devices', icon: Palette },
    { id: 'blog', label: 'Blog', icon: FileText }
  ];

  return (
    <header className="transparent border-b border-slate-700/50">
      <div className="max-w-15xl mx-auto px-4">
        <Flex align="center" justify="between" className="h-14">
          {/* Left Side - Logo and Main Navigation */}
          <Flex align="center" gap="6">
            {/* Logo */}
            <Flex align="center" gap="3">
              <div className="flex items-center space-x-2">
                <div className="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                  <Layers size={18} className="text-white" />
                </div>
                <Text size="3" weight="bold" className="text-white">
                  IoT Interface
                </Text>
              </div>
              
              {/* WorkOS Badge */}
              {/* <Flex align="center" gap="2" className="ml-2">
                <div className="w-6 h-6 bg-orange-500 rounded-sm flex items-center justify-center">
                  <Code size={12} className="text-white" />
                </div>
                <Text size="2" className="text-slate-300">
                  Made by WorkOS
                </Text>
              </Flex>*/}
            </Flex> 

          </Flex>
          <Flex align="center">
            {/* Main Navigation */}
            <nav className="hidden md:flex">
              <Flex align="center" gap="5">
                {leftNavItems.map((item) => (
                  <div key={item.id} className="relative">
                    <Button
                      variant={activeItem === item.id ? "solid" : "ghost"}
                      size="2"
                      color="gray"
                      radius='full'
                      className={`px-3 py-2 text-sm font-medium transition-colors ${
                        activeItem === item.id 
                          ? 'text-white bg-white' 
                          : 'text-slate-300 hover:text-white hover:bg-slate-800/50'
                      }`}
                      onClick={() => setActiveItem(item.id)}
                    highContrast>
                      <Flex align="center" gap="1">
                        {item.label}
                        {item.hasBadge && (
                          <Badge 
                            size="1" 
                            color='blue'
                            className="ml-1 bg-gray text-gray text-xs px-1.5 py-0.5 rounded-full"
                          >
                            New
                          </Badge>
                        )}
                        {item.hasDropdown && (
                          <ChevronDown size={14} className="ml-1" />
                        )}
                      </Flex>
                    </Button>
                  </div>
                ))}
              </Flex>
            </nav>

</Flex>
          {/* Right Side Navigation */}
          <Flex align="center" gap="5">
            {rightNavItems.map((item) => {
              const Icon = item.icon;
              return (
                <Button
                  key={item.id}
                  variant="ghost"
                  size="2"
                  className="px-3 py-2 text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800/50 transition-colors hidden sm:flex"
                >
                  <Flex align="center" gap="2">
                    <Icon size={16} />
                    {item.label}
                  </Flex>
                </Button>
              );
            })}

            {/* GitHub Icon */}
            <IconButton
              variant="ghost"
              size="2"
              className="text-slate-300 hover:text-white hover:bg-slate-800/50 transition-colors ml-2"
            >
              <Github size={18} />
            </IconButton>

            {/* Mobile Menu Button */}
            <div className="md:hidden ml-2">
              <DropdownMenu.Root>
                <DropdownMenu.Trigger asChild>
                  <IconButton
                    variant="ghost"
                    size="2"
                    className="text-slate-300 hover:text-white hover:bg-slate-800/50"
                  >
                    <Settings size={18} />
                  </IconButton>
                </DropdownMenu.Trigger>
                <DropdownMenu.Content className="bg-slate-800 border-slate-700">
                  {leftNavItems.map((item) => (
                    <DropdownMenu.Item 
                      key={item.id}
                      className="text-slate-300 hover:text-white hover:bg-slate-700"
                      onClick={() => setActiveItem(item.id)}
                    >
                      {item.label}
                    </DropdownMenu.Item>
                  ))}
                  <DropdownMenu.Separator className="bg-slate-700" />
                  {rightNavItems.map((item) => {
                    const Icon = item.icon;
                    return (
                      <DropdownMenu.Item 
                        key={item.id}
                        className="text-slate-300 hover:text-white hover:bg-slate-700"
                      >
                        <Flex align="center" gap="2">
                          <Icon size={16} />
                          {item.label}
                        </Flex>
                      </DropdownMenu.Item>
                    );
                  })}
                </DropdownMenu.Content>
              </DropdownMenu.Root>
            </div>
          </Flex>
        </Flex>
      </div>
    </header>
  );
};

export default AppHeader;