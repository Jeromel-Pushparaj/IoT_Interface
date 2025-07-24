import React from 'react';
import { Text } from '@radix-ui/themes';
function DeviceProperties({ properties }) {
  return (
  <>
    <Text size="2" color="gray">
      {properties.map((prop, idx) => (
        <div key={idx}>
          <Text size="2">{prop.name}: {prop.dataType}</Text>
        </div>
      ))}
    </Text>
  </>
  );
}

export default DeviceProperties;