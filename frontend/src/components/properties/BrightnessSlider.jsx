import { Slider,  Tooltip } from '@radix-ui/themes';
import { useState, useEffect } from 'react';
import mqttService from '@/services/mqttService.js'; // Adjust the import path as necessary

function BrightnessSlider({deviceId}) {
  const [brightness, setBrightness] = useState(50);
  const [showTooltip, setShowTooltip] = useState(false);

  // Handlers for pointer events
  const handlePointerDown = () => setShowTooltip(true);
  const handlePointerUp = () => setShowTooltip(false);

  useEffect(() => {
    if(!mqttService.isConnected()) {
      mqttService.connect();
    }
},[deviceId]);
  return (
    <div
      onPointerDown={handlePointerDown}
      onPointerUp={handlePointerUp}
      onPointerLeave={handlePointerUp}
      style={{ width: '100%' }}
    >
      <Tooltip open={showTooltip} content={`${brightness}%`} placement="top">
        <Slider
          value={[brightness]}
          radius="small"
          max={100}
          min={0}
          step={1}
          onValueChange={(value) => {
            setBrightness(value[0]);
            // Here you can add code to send the brightness value to the device
            mqttService.publish(`device/${deviceId}/brightness`, value[0].toString());
          }}
        />
      </Tooltip>
    </div>
  );
}

export default BrightnessSlider;