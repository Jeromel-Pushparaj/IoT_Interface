import React from 'react';
import { Card, Flex, Text, Link, Separator} from '@radix-ui/themes';
import { Power } from 'lucide-react';
import Toggle from './properties/Toggle';
import BrightnessSlider from './properties/BrightnessSlider';
import TempDisplay from './properties/TemDisplay';
import LiveData from './properties/LiveData';

function DeviceCard({ device, onDelete, onEdit }) {
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
              <Toggle deviceId={deviceId}/>
              </>

              )}
            {prop.uiType === 'slider' && (

              <>
            <Text size="2">{prop.name}: {prop.value}</Text>
              <BrightnessSlider deviceId={deviceId} />
              </>
            )}
            {prop.uiType === 'temp_display' && (
              <>
            <Text size="2">{prop.name}: {prop.value}</Text>
              <TempDisplay value={prop.value} />
              </>
            )}
            
            {prop.uiType === 'live_data' && (
            <>
            <Text size="2">{prop.name}: {prop.value}</Text>
              <LiveData  deviceId={deviceId}/>
            </>
            )}
            {/* Display property name and value */}
            </div>
        ))}
        <Text size="1" color="gray">
          Last update: {device.updated_at}
        </Text>
      </Flex>
    </Card>
  );
}

export default DeviceCard;