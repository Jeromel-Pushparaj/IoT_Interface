import React, { useState, useEffect, useContext } from 'react';
import { Flex, Badge } from '@radix-ui/themes';
import { Wifi, WifiOff } from 'lucide-react';
import mqttService from '@/services/mqttService.js'; // Adjust the import path as necessary
import api from '@/api.js'; // Adjust the import path as necessary

function DeviceStatus(props) {
  const [status, setStatus] = useState('offline');
  const deviceId = props.id;

useEffect(() => {
  // Connect only once when component mounts
  if(!mqttService.isConnected()) {
    mqttService.connect();
  }

  // Subscribe to MQTT topic
  mqttService.subscribe(`device/${deviceId}/status`, (incomingMessage) => {
    console.log('Received MQTT message:', incomingMessage);
    const normalized = incomingMessage.toLowerCase();

    if (normalized !== 'online' && normalized !== 'offline') {
      console.error('Invalid status message:', normalized);
      return;
    }

    // Update state
    setStatus(normalized);
    console.log(`Device ${deviceId} status changed to:`, normalized);

    // Call API immediately on status change
    api.post(`/api/device/update`, {
      device_id: String(deviceId),
      status: normalized
    })
    .then((response) => {
      console.log('Device status updated:', response.data);
    })
    .catch((error) => {
      console.error('Error updating device status:', error);
    });
  });

  return () => {
    // Disconnect only when component unmounts
    mqttService.disconnect();
  };
}, [deviceId]); // Re-run only if deviceId changes

  const getStatusIcon = (status) => {
    return status === 'online' ? <Wifi size={16} /> : <WifiOff size={16} />;
  };



  return (
    <Flex align="center" gap="2">
        {getStatusIcon(status)}
            <Badge 
                color={status === 'online' ? 'green' : 'red'}
                variant="soft"
            >
            {status}
            </Badge>
    </Flex>
  );
}

export default DeviceStatus;