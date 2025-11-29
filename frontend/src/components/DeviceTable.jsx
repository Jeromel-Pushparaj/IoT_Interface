import DeviceStatus from '@components/DeviceStatus';
import { useState, useEffect } from 'react';
import {
  Card,
  Flex,
  Text,
  Table,
  Button
} from '@radix-ui/themes';
import {
  Pencil
} from 'lucide-react';
import DeleteButton from './ui/DeleteButton';
import api from '@/api';

function DeviceTable() {
  const [devices, setDevices] = useState([]);

  useEffect(() => {
    api.get('/api/devices')
      .then((response) => {
        if (response.data) {
          setDevices(response.data);
        } else {
          console.error('No devices found in response:', response.data);
        }
      })
      .catch((error) => {
        console.error('Error fetching devices:', error);
      });
  }, []);

  const handleDleteSuccess = (id) => {
    //update the state without refetching whole list
    setDevices((prev) => prev.filter((d) => d._id.$oid !== id));
    console.log("hhhh");
  }
  return (
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
                    <Flex align="center" gap={5}>
                      <Button gap="2" variant='surface'>
                        <Pencil size={16} />
                      </Button>
                      <DeleteButton id={device._id.$oid} onDeleteSuccess={handleDleteSuccess} />
                    </Flex>
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
