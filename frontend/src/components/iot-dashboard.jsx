import React, { useState } from 'react';
import { 
  Box, 
  Flex, 
  Text, 
  Button, 
  Card, 
  Table, 
  Badge, 
  Switch,
  Container,
  Heading,
  Grid
} from '@radix-ui/themes';
import { 
  Activity, 
  Thermometer, 
  Lightbulb, 
  Wifi, 
  WifiOff,
  Power,
  Settings
} from 'lucide-react';
import AppHeader from '@/components/header.jsx'; 
import Background from '@/components/background.jsx';

const IoTDashboard = () => {
  const [devices, setDevices] = useState([
    { id: 1, name: 'Smart Thermostat', type: 'Temperature', status: 'online', value: '22°C', isActive: true },
    { id: 2, name: 'Living Room Light', type: 'Lighting', status: 'online', value: '75%', isActive: false },
    { id: 3, name: 'Security Camera', type: 'Security', status: 'offline', value: 'Inactive', isActive: false },
    { id: 4, name: 'Smart Speaker', type: 'Audio', status: 'online', value: 'Playing', isActive: true },
    { id: 5, name: 'Garden Sprinkler', type: 'Irrigation', status: 'online', value: 'Scheduled', isActive: false }
  ]);

  const [sensorData] = useState({
    temperature: 22.5,
    humidity: 65,
    pressure: 1013.25,
    lastUpdate: new Date().toLocaleTimeString()
  });

  const toggleDevice = (deviceId) => {
    setDevices(devices.map(device => 
      device.id === deviceId 
        ? { ...device, isActive: !device.isActive }
        : device
    ));
  };

  const getStatusIcon = (status) => {
    return status === 'online' ? <Wifi size={16} /> : <WifiOff size={16} />;
  };

  const getDeviceIcon = (type) => {
    switch (type) {
      case 'Temperature': return <Thermometer size={20} />;
      case 'Lighting': return <Lightbulb size={20} />;
      case 'Security': return <Activity size={20} />;
      default: return <Power size={20} />;
    }
  };

  return (
    <>
    <Background />
    <Box className="absolute left-0 right-0 min-h-screen ">
      <Flex>
        {/* Sidebar */}
        {/* <Box 
          style={{ 
            width: '250px', 
            backgroundColor: 'transparent', 
            color: 'white',
            minHeight: '100vh',
            padding: '1rem'
          }}
        >
            <Card 
          style={{ 
            width: '250px', 
            color: 'white',
            minHeight: '85vh',
            padding: '1rem'
          }} >
          <Flex direction="column" gap="4">
            <Box style={{ padding: '1rem 0' }}>
              <Text size="4" weight="bold" style={{ color: 'white' }}>
                IoT Control Hub
              </Text>
            </Box>
            
            <Flex direction="column" gap="2">
              <Button variant="ghost" style={{ justifyContent: 'flex-start', color: 'white' }}>
                <Activity size={16} style={{ marginRight: '8px' }} />
                Dashboard
              </Button>
              <Button variant="ghost" style={{ justifyContent: 'flex-start', color: 'white' }}>
                <Power size={16} style={{ marginRight: '8px' }} />
                Devices
              </Button>
              <Button variant="ghost" style={{ justifyContent: 'flex-start', color: 'white' }}>
                <Settings size={16} style={{ marginRight: '8px' }} />
                Settings
              </Button>
            </Flex>
          </Flex>
          </Card>
        </Box> */}

        {/* Main Content */}
        <Box style={{ flex: 1 }}>
          {/* Header */}
          <Box 
            style={{ 
              backgroundColor: 'transparent', 
              padding: '1rem 2rem'
            }}
          >
            {/* <Heading size="6">IoT Device Dashboard</Heading> */}
            <AppHeader />
          </Box>

          {/* Content Area */}
          <Container size="4" style={{ padding: '2rem' }}>
            {/* Control Cards */}
            <Grid columns="3" gap="4" style={{ marginBottom: '2rem' }}>
              {/* Toggle Button with Device Name */}
              <Card style={{ padding: '1.5rem' }}>
                <Flex direction="column" gap="3">
                  <Flex align="center" justify="between">
                    <Text size="3" weight="medium">Device Control</Text>
                    <Power size={20} />
                  </Flex>
                  <Text size="2" color="gray">
                    Smart Thermostat
                  </Text>
                  <Flex align="center" gap="2">
                    <Switch 
                      checked={devices[0]?.isActive}
                      onCheckedChange={() => toggleDevice(1)}
                    />
                    <Text size="2">
                      {devices[0]?.isActive ? 'Active' : 'Inactive'}
                    </Text>
                  </Flex>
                </Flex>
              </Card>

              {/* Sensor Value */}
              <Card style={{ padding: '1.5rem' }}>
                <Flex direction="column" gap="3">
                  <Flex align="center" justify="between">
                    <Text size="3" weight="medium">Live Sensor Data</Text>
                    <Thermometer size={20} />
                  </Flex>
                  <Text size="5" weight="bold">
                    {sensorData.temperature}°C
                  </Text>
                  <Text size="2" color="gray">
                    Humidity: {sensorData.humidity}%
                  </Text>
                  <Text size="1" color="gray">
                    Last update: {sensorData.lastUpdate}
                  </Text>
                </Flex>
              </Card>

              {/* Device Statistics */}
              <Card style={{ padding: '1.5rem' }}>
                <Flex direction="column" gap="3">
                  <Flex align="center" justify="between">
                    <Text size="3" weight="medium">Device Statistics</Text>
                    <Activity size={20} />
                  </Flex>
                  <Flex direction="column" gap="2">
                    <Flex justify="between">
                      <Text size="2">Online Devices</Text>
                      <Badge color="green">4/5</Badge>
                    </Flex>
                    <Flex justify="between">
                      <Text size="2">Active Devices</Text>
                      <Badge color="blue">2/5</Badge>
                    </Flex>
                    <Flex justify="between">
                      <Text size="2">System Status</Text>
                      <Badge color="green">Healthy</Badge>
                    </Flex>
                  </Flex>
                </Flex>
              </Card>
            </Grid>

            {/* Device Table */}
            <Card style={{ padding: '1.5rem' }}>
              <Flex direction="column" gap="3">
                <Text size="4" weight="medium">
                  Device Management Table
                </Text>
                <Text size="2" color="gray">
                  Table containing device name, device status and its type
                </Text>
                
                <Table.Root>
                  <Table.Header>
                    <Table.Row>
                      <Table.ColumnHeaderCell>Device Name</Table.ColumnHeaderCell>
                      <Table.ColumnHeaderCell>Type</Table.ColumnHeaderCell>
                      <Table.ColumnHeaderCell>Status</Table.ColumnHeaderCell>
                      <Table.ColumnHeaderCell>Value</Table.ColumnHeaderCell>
                      <Table.ColumnHeaderCell>Control</Table.ColumnHeaderCell>
                    </Table.Row>
                  </Table.Header>

                  <Table.Body>
                    {devices.map((device) => (
                      <Table.Row key={device.id}>
                        <Table.RowHeaderCell>
                          <Flex align="center" gap="2">
                            {getDeviceIcon(device.type)}
                            <Text size="2" weight="medium">{device.name}</Text>
                          </Flex>
                        </Table.RowHeaderCell>
                        <Table.Cell>
                          <Text size="2">{device.type}</Text>
                        </Table.Cell>
                        <Table.Cell>
                          <Flex align="center" gap="2">
                            {getStatusIcon(device.status)}
                            <Badge 
                              color={device.status === 'online' ? 'green' : 'red'}
                              variant="soft"
                            >
                              {device.status}
                            </Badge>
                          </Flex>
                        </Table.Cell>
                        <Table.Cell>
                          <Text size="2">{device.value}</Text>
                        </Table.Cell>
                        <Table.Cell>
                          <Switch 
                            checked={device.isActive}
                            onCheckedChange={() => toggleDevice(device.id)}
                            disabled={device.status === 'offline'}
                          />
                        </Table.Cell>
                      </Table.Row>
                    ))}
                  </Table.Body>
                </Table.Root>
              </Flex>
            </Card>
          </Container>
        </Box>
      </Flex>
    </Box>
    </>
  );
};

export default IoTDashboard;