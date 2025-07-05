import React, { useState, useEffect, useContext } from 'react';
import { Flex, Badge } from '@radix-ui/themes';
import { Wifi, WifiOff } from 'lucide-react';
import mqttService from '@/services/mqttService.js'; // Adjust the import path as necessary

function DeviceStatus(props) {
  const [status, setStatus] = useState('offline');

  const deviceId = props.id;
  useEffect(() => {
    mqttService.connect();
    mqttService.subscribeToDevice(`device/${deviceId}/status`, (message) => {
      console.log('Received MQTT message:', message);
      setStatus(message);
    });

    return () => {
      mqttService.disconnect();
    };
  }, [mqttService]);

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