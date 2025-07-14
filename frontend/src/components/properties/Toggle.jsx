import {Switch} from '@radix-ui/themes';
import React from 'react';

function Toggle({ checked, onCheckedChange }) {
  return (
    <div className='m-l-5 m-t-10'>
    <Switch
      checked={checked}
      radius='small'
      onCheckedChange={onCheckedChange}
    >
    </Switch>
</div>
  );
}

export default Toggle;