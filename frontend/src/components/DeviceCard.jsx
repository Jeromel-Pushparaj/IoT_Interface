import React, { useEffect, useState } from 'react';
import { Card, Flex, Text, Link, Separator } from '@radix-ui/themes';
import { Power } from 'lucide-react';
import Toggle from './properties/Toggle';
import BrightnessSlider from './properties/BrightnessSlider';
import TempDisplay from './properties/TemDisplay';
import LiveData from './properties/LiveData';
import api from '@/api.js';

function DeviceCard({ device, onDelete, onEdit }) {
  const [status, setStatus] = useState('offline')
  const handleDelete = () => {
    if (window.confirm(`Are you sure you want to delete the device ${device.name}?`)) {
      onDelete(device.device_id);
    }
  };

  const handleEdit = () => {
    onEdit(device);
  };
  const deviceId = device.device_id;
  if (!deviceId) {
    console.error('Device ID is missing:', device);
    return null; // or handle the error as needed
  }
  const id = device._id.$oid;
  useEffect(() => {
    // Fetch initial status from API
    api.get(`/api/devices/${id}`)
      .then((response) => {
        const statusDb = response.data.status;
        console.log(statusDb);
        setStatus(statusDb);
      })
      .catch((error) => {
        console.error('Error getting the status of the Device:', error);
      });
  }, [deviceId]);
  const deviceProperties = device.properties || [];

  return (
    <Card style={{ padding: '1.5rem', marginBottom: '1rem' }}>
      <Flex direction="column" gap="3">
        <Flex align="center" justify="between">
          <Link href={`/device/${device.device_id}`} style={{ textDecoration: 'none' }}>
            <Text size="3" weight="medium">{device.name}</Text>
          </Link>
          <Power size={20} />
        </Flex>
        <Text size="2" color="gray">
          {device.type}
        </Text>
        {/* Render each property */}
        {deviceProperties.map((prop, idx) => (
          <div gap="2" key={idx}>
            {prop.uiType === 'toggle' && (
              <>
                <Text size="2">{prop.name}: {prop.value}</Text>
                <Toggle deviceId={deviceId} disable={status == 'offline'} />
              </>

            )}
            {prop.uiType === 'slider' && (

              <>
                <Text size="2">{prop.name}: {prop.value}</Text>
                <BrightnessSlider deviceId={deviceId} disable={status == 'offline'} min={prop.minValue} max={prop.maxValue} />
              </>
            )}
            {prop.uiType === 'temp_display' && (
              <>
                <Text size="2">{prop.name}: {prop.value}</Text>
                <TempDisplay value={prop.value} disable={status == 'offline'} />
              </>
            )}

            {prop.uiType === 'live_data' && (
              <>
                <Text size="2">{prop.name}: {prop.value}</Text>
                <LiveData deviceId={deviceId} disable={status == 'offline'} />
              </>
            )}
            {/* Display property name and value */}
          </div>
        ))}
        {status == 'offline' &&
          <Text size="1" color="gray">
            Last update: {device.updated_at}
          </Text>
        }
      </Flex>
    </Card>
  );
}

export default DeviceCard;
