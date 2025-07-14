// import React from 'react';
// import { Card, Flex, Text,  Link } from '@radix-ui/themes';
// import { Power } from 'lucide-react';
// import Toggle from './properties/Toggle'; // Adjust the import path as necessary
// import BrightnessSlider from './properties/BrightnessSlider'; // Adjust the import path as necessary
// import TempDisplay from './properties/TemDisplay'; // Adjust the import path as necessary
// import LiveData from './properties/LiveData';

// function DeviceCard({ device, onDelete, onEdit }) {
//   const handleDelete = () => {
//     if (window.confirm(`Are you sure you want to delete the device ${device.name}?`)) {
//       onDelete(device.device_id);
//     }
//   };

//   const handleEdit = () => {
//     onEdit(device);
//   };

//   const deviceProperties = device.properties || [];
//   deviceProperties.map((properties) => {
//   if(properties.ui_type === 'toggle' || properties.ui_type === 'slider') {
//   return (
//     <>
//               {/* Toggle Button with Device Name */}
//               <Card style={{ padding: '1.5rem' }}>
//                 <Flex direction="column" gap="3">
//                   <Flex align="center" justify="between">
                    
//                   <Link href={`/device/${device.device_id}`} style={{ textDecoration: 'none' }}>
//                     <Text size="3" weight="medium">{device.name}</Text>
//                   </Link>
//                     <Power size={20} />
//                   </Flex>
//                   <Text size="2" color="gray">
//                     {device.type}
//                   </Text>
//                   <Flex align="center" gap="2">
//                     {device.ui_type === 'toggle' && (
//                       <Toggle
//                         checked={device.properties?.status || false}
//                         onCheckedChange={(checked) => {
//                           console.log(`Toggle status for device ${device.name}:`, checked);
//                         }}
//                       />
//                     )}
//                     {device.ui_type === 'slider' && (
//                       <BrightnessSlider />
//                     )}
//                     <Text size="2">
//                     </Text>
//                   </Flex>
//                   <Text size="1" color="gray">
//                     Last update: {device.updated_at}
//                   </Text>
//                 </Flex>
//               </Card>
// </>
//   );
// }
// if(device.ui_type === 'temp_display'){
// return (
//   <TempDisplay
//     device={device}
//   />
// );
// }

// if(device.ui_type === 'live_data'){
// return (
//   <LiveData
//     device={device}
//   />
// );
// }
// });
// }

// export default DeviceCard;
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
              <Toggle
                checked={false}
                onCheckedChange={(checked) => {
                  console.log(`Toggle status for property ${prop.name}:`, checked);
                }}
                />
              </>

              )}
            {prop.uiType === 'slider' && (

              <>
            <Text size="2">{prop.name}: {prop.value}</Text>
              <BrightnessSlider value={prop.value} />
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
              <LiveData  />
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