import {Switch} from '@radix-ui/themes';
import React, { useEffect } from 'react';
import mqttService from '@/services/mqttService.js'; // Adjust the import path as necessary

function Toggle({ deviceId }) {
  const checked = false; // Default state, can be managed via state if needed
  const handleToggle = (checked) => {
    mqttService.publish(`device/${deviceId}/toggle`, checked ? 'on' : 'off');
    console.log(`Toggle status for device ${deviceId}:`, checked);
  };

  useEffect(() => {
    if(!mqttService.isConnected()) {
      mqttService.connect();
    }
    // Subscribe to the toggle status topic if needed
    mqttService.subscribe(`device/${deviceId}/toggle/status`, (message) => {
      const normalized = message.toLowerCase();
      if (normalized === 'on' || normalized === 'off') {
        console.log(`Received toggle status for device ${deviceId}:`, normalized);
        // Update local state or UI as needed
      } else {
        console.error('Invalid toggle status message:', normalized);
      }
    }); 
  }, [deviceId]);

  return (
    <div className='m-l-5 m-t-10'>
    <Switch
      radius='small'
      onCheckedChange={onCheckedChange => {
        mqttService.publish(`device/${deviceId}/toggle`, onCheckedChange ? 'on' : 'off');
        console.log(`Toggle status for device ${deviceId}:`, onCheckedChange);
      }}
    >
    </Switch>
  </div>
  );
}

export default Toggle;