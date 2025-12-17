import DeviceLayout from "@/components/DeviceLayout";
import React from "react";
import DeviceCard from "@/components/DeviceCard.tsx";
import { Grid } from "@radix-ui/themes";
import { useDevices } from "@/hooks/useDevices.js";

function ControlPage() {
  const { data: devices, isLoading, isError } = useDevices();

  if (isLoading) return <DeviceLayout page={"Device Control"}><div>Loading devices...</div></DeviceLayout>;
  if (isError) return <DeviceLayout page={"Device Control"}><div>Error fetching devices.</div></DeviceLayout>;


  return (
    <DeviceLayout page={"Device Control"}>
      {/* Add more device management components or features here */}
      {/* Placeholder for device management content */}

      <Grid columns="3" gap="4" style={{ marginBottom: '2rem' }}>
        {devices && devices.map((device) => (
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
      </Grid>
    </DeviceLayout>
  );
}
export default ControlPage;
