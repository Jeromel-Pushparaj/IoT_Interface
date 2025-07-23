import React, { useState, useEffect, useContext } from 'react';
import { Flex, Badge } from '@radix-ui/themes';
import { Wifi, WifiOff } from 'lucide-react';
import mqttService from '@/services/mqttService.js'; // Adjust the import path as necessary
import api from '@/api.js'; // Adjust the import path as necessary
import { useMqttSubscription } from '@/hooks/useMqttSubscription';

function DeviceStatus({deviceId, id}) {
  const [status, setStatus] = useState('offline');

  useMqttSubscription(`device/${deviceId}/status`, (incomingMessage) => {
    console.log('📩 Received MQTT message:', incomingMessage);
    const normalized = incomingMessage.toLowerCase();

    if (normalized !== 'online' && normalized !== 'offline') {
      console.error('❌ Invalid status message:', normalized);
      return;
    }

    setStatus(normalized);
    console.log(`🔄 Device ${deviceId} status changed to:`, normalized);

    // Call API immediately on status change
    api.post(`/api/device/update`, {
      device_id: String(deviceId),
      status: normalized
    })
    .then((response) => {
      console.log('✅ Device status updated:', response.data);
    })
    .catch((error) => {
      console.error('❌ Error updating device status:', error);
    });
  });

  useEffect(() => {
    // Fetch initial status from API
    api.get(`/api/device/show/${id}`)
    .then((response) => {
      const statusDb = response.data.status;
      console.log(statusDb);
      setStatus(statusDb);
    })
    .catch((error) => {
      console.error('❌ Error getting the status of the Device:', error);
    });
  }, [deviceId]);

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