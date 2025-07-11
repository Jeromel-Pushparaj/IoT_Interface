import React from 'react';
import { Card, Flex, Text, Switch } from '@radix-ui/themes';
import { Power } from 'lucide-react';
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
                    <Text size="3" weight="medium">{device.name}</Text>
                    <Power size={20} />
                  </Flex>
                  <Text size="2" color="gray">
                    {device.type}
                  </Text>
                  <Flex align="center" gap="2">
                    <Switch 
                    />
                    <Text size="2">
                    </Text>
                  </Flex>
                </Flex>
              </Card>
</>
  );
}

export default DeviceCard;