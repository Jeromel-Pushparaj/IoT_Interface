import React from 'react';
import { Text } from '@radix-ui/themes';
import { 
  ToggleLeft, 
  SlidersHorizontal,
  RadioTower

 } from 'lucide-react';
function DeviceProperties({ properties }) {
  return (
  <>
  <div className='flex items-center gap-2'>
      {properties.map((prop, idx) => (
        <div className='' key={idx}>
          { (prop.name == "Power") && <ToggleLeft /> } { (prop.name) == "Brightness" && <SlidersHorizontal /> }{ (prop.name) == "Data" && <RadioTower />}
        </div>
      ))}
      </div>
  </>
  );
}

export default DeviceProperties;