import React from 'react';
import { Card, Flex, Text, Link, Box } from '@radix-ui/themes';
import { Power } from 'lucide-react';
function LiveData() {
  return (
      <Box style={{ backgroundColor: 'black', padding: '1rem', borderRadius: '8px', color: 'white' }} className='h-20'>
        <Text size="1" color="gray">
          No data available
        </Text>
      </Box>
  );
}

export default LiveData;