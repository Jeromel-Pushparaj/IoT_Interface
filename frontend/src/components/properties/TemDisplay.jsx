import { Card, Flex, Text } from '@radix-ui/themes';
import { Thermometer } from 'lucide-react';
import React from 'react';

function TempDisplay({ device }) {
  return (
              <Card style={{ padding: '1.5rem' }}>
                <Flex direction="column" gap="3">
                  <Flex align="center" justify="between">
                    <Text size="3" weight="medium">{device.name}</Text>
                    <Thermometer size={20} />
                  </Flex>
                  <Text size="5" weight="bold">
                    25°C
                  </Text>
                  <Text size="2" color="gray">
                    Humidity: 74%
                  </Text>
                  <Text size="1" color="gray">
                    Last update: {device.updated_at}
                  </Text>
                </Flex>
              </Card>

  );
}

export default TempDisplay;