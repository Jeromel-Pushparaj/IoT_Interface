import React, { useState } from 'react';
import {
  Flex,
  Text,
  Card,
  Table,
  Badge,
  Container,
  Grid
} from '@radix-ui/themes';
import {
  Activity,
  Thermometer,
  Lightbulb,
  Power,
} from 'lucide-react';
import DeviceStatus from '@/components/DeviceStatus.jsx';
import DeviceProperties from '@/components/DeviceProperties.jsx';
import { useDevices } from '@/hooks/useDevices.js';

const IoTDashboard = () => {
  const { data: devices, isLoading, isError } = useDevices();

  const [sensorData] = useState({
    temperature: 22.5,
    humidity: 65,
    pressure: 1013.25,
    lastUpdate: new Date().toLocaleTimeString()
  });

  const getDeviceIcon = (type) => {
    switch (type) {
      case 'Temperature': return <Thermometer size={20} />;
      case 'Lighting': return <Lightbulb size={20} />;
      case 'Security': return <Activity size={20} />;
      default: return <Power size={20} />;
    }
  };

  if (isLoading) return <div>Loading devices...</div>;
  if (isError) return <div>Error fetching devices.</div>;

  return (
    <>
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
                Feature coming soon
              </Text>
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
                  <Table.ColumnHeaderCell>Properties</Table.ColumnHeaderCell>
                </Table.Row>
              </Table.Header>

              <Table.Body>
                {devices && devices.map((device) => (
                  <Table.Row key={device.device_id}>
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
                      <DeviceStatus id={device._id.$oid} deviceId={device.device_id} />
                    </Table.Cell>
                    <Table.Cell>
                      <DeviceProperties properties={device.properties} />
                    </Table.Cell>
                    <Table.Cell>
                    </Table.Cell>
                  </Table.Row>
                ))}
              </Table.Body>
            </Table.Root>
          </Flex>
        </Card>
      </Container>
    </>
  );
};

export default IoTDashboard;
