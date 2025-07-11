import {Switch} from '@radix-ui/themes';
import React from 'react';

function Toggle({ checked, onCheckedChange }) {
  return (
    <Switch
      checked={checked}
      radius='small'
      onCheckedChange={onCheckedChange}
    >
      <span
        style={{
          position: 'absolute',
          top: '2px',
          left: checked ? '26px' : '2px',
          width: '21px',
          height: '21px',
          borderRadius: '50%',
          backgroundColor: '#fff',
          transition: 'left 0.2s ease-in-out',
        }}
      />
    </Switch>
  );
}

export default Toggle;