import DeviceLayout from "@/components/device-layout";
import React,{useState, useEffect} from "react";
import DeviceCard from "@/components/DeviceCard";
import { Grid, Link } from "@radix-ui/themes";
import api from "@/api.js"; // Adjust the import path as necessary
function DevicePage() {
  const [devices, setDevices] = useState([]);

  useEffect(() => {
  api.get('/api/device/list')
  .then((response) => {
    if (response.data) {
      setDevices(response.data);
    } else {
      console.error('No devices found in response:', response.data);
    }
  })
  .catch((error) => {
    console.error('Error fetching devices:', error);
  });
}, []);

  return (
    <DeviceLayout page={"Device Management"}>
      {/* Add more device management components or features here */}
            {/* Placeholder for device management content */}

            {/* <Grid columns="3" gap="4" style={{ marginBottom: '2rem' }}>
              {devices.map((device) => (
            <DeviceCard
            key={device.device_id}
              device={device}
              onDelete={(deviceId) => {
                console.log(`Delete device with ID: ${device.device_id}`);
              }}
              onEdit={(device) => {
                console.log(`Edit device:`, device);
              }}
            />
              ))}
</Grid>*/}
     </DeviceLayout> 
  );
}

export default DevicePage;