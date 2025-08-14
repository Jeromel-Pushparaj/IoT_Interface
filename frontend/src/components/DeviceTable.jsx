import DeviceStatus from '@components/DeviceStatus';
import DeviceProperties from './DeviceProperties';
import {
  Card,
  Flex,
  Text,
  Table
} from '@radix-ui/themes';
import {
  Pencil,
  Trash

} from 'lucide-react';

function DeviceTable({devices}){
    return(
        <>
            {/* Device Table */}
            <Card style={{ padding: '1.5rem' }}>
              <Flex direction="column" gap="3">
                <Text size="4" weight="medium">
                  Device Management Table
                </Text>
                <Text size="2" color="gray">
                  Table containing device name, device status and its type
                </Text>
                
                <Table.Root>
                  <Table.Header>
                    <Table.Row>
                      <Table.ColumnHeaderCell>Device Name</Table.ColumnHeaderCell>
                      <Table.ColumnHeaderCell>Type</Table.ColumnHeaderCell>
                      <Table.ColumnHeaderCell>Status</Table.ColumnHeaderCell>
                      <Table.ColumnHeaderCell>Manage</Table.ColumnHeaderCell>
                    </Table.Row>
                  </Table.Header>

                  <Table.Body>
                    {devices.map((device) => (
                      <Table.Row key={device.device_id}>
                        <Table.RowHeaderCell>
                          <Flex align="center" gap="2">
                            <Text size="2" weight="medium">{device.name}</Text>
                          </Flex>
                        </Table.RowHeaderCell>
                        <Table.Cell>
                          <Text size="2">{device.type}</Text>
                        </Table.Cell>
                        <Table.Cell>
                        <DeviceStatus id={device._id.$oid} deviceId={device.device_id} />
                        </Table.Cell>

                        <Table.Cell>
                        <div className='flex item-center gam-4'>
                        <Pencil />
                        <Trash />
                        </div>
                        </Table.Cell>
                        <Table.Cell>
                        </Table.Cell>
                      </Table.Row>
                    ))}
                  </Table.Body>
                </Table.Root>
              </Flex>
            </Card> 
        </>
    );
}
export default DeviceTable;