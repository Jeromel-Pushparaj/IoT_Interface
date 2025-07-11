import React from 'react';
import { Card, Flex, Text,  Link } from '@radix-ui/themes';
import { Power } from 'lucide-react';
import Toggle from './properties/Toggle'; // Adjust the import path as necessary
import BrightnessSlider from './properties/BrightnessSlider'; // Adjust the import path as necessary

function DeviceCard({ device, onDelete, onEdit }) {
  const handleDelete = () => {
    if (window.confirm(`Are you sure you want to delete the device ${device.name}?`)) {
      onDelete(device.device_id);
    }
  };

  const handleEdit = () => {
    onEdit(device);
  };

  return (
    <>
              {/* Toggle Button with Device Name */}
              <Card style={{ padding: '1.5rem' }}>
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
                  <Flex align="center" gap="2">
                    {device.ui_type === 'toggle' && (
                      <Toggle
                        checked={device.properties?.status || false}
                        onCheckedChange={(checked) => {
                          console.log(`Toggle status for device ${device.name}:`, checked);
                        }}
                      />
                    )}

                    {device.ui_type === 'slider' && (
                      <BrightnessSlider />
                    )}
                    <Text size="2">
                    </Text>
                  </Flex>
                </Flex>
              </Card>
</>
  );
}

export default DeviceCard;