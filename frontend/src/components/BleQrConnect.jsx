import React, { useRef, useState } from 'react';
import '@radix-ui/themes/styles.css';
import {
    Box,
    Flex,
    Text,
    Button,
    Card,
    Container,
    Separator,
    TextField,
} from '@radix-ui/themes';

// Import the Html5Qrcode library (install via npm or use CDN in index.html)
// npm: npm install html5-qrcode
import { Html5Qrcode } from 'html5-qrcode';

const BleQrConnect = () => {
  const [qrResult, setQrResult] = useState('Scan a QR code...');
  const [qrData, setQrData] = useState(null);
  const [isConnected, setIsConnected] = useState(false);
  const uidRef = useRef();
  const passRef = useRef();
  const html5QrCodeRef = useRef(null);

  const handleStartScan = async () => {
    const qrCodeRegionId = 'reader';
    html5QrCodeRef.current = new Html5Qrcode(qrCodeRegionId);

    const devices = await Html5Qrcode.getCameras();
    if (devices && devices.length) {
      const cameraId = devices[0].id;

      html5QrCodeRef.current.start(
        cameraId,
        { fps: 10, qrbox: 250 },
        (decodedText) => handleScanSuccess(decodedText),
        (error) => console.warn(`QR Scan error: ${error}`)
      );
    }
  };

  const handleScanSuccess = (decodedText) => {
    console.log('✅ QR Code detected:', decodedText);
    setQrResult(`QR: ${decodedText}`);

    try {
      const parsed = JSON.parse(decodedText);
      if (parsed.name && parsed.serviceUUID && parsed.characteristicUUID) {
        setQrData(parsed);
        html5QrCodeRef.current.stop();
        console.log('Parsed QR data:', parsed);
      } else {
        setQrResult('❌ Invalid QR format');
      }
    } catch (err) {
      setQrResult('❌ QR not in JSON format');
      console.error(err);
    }
  };

  const handleConnectBLE = async () => {
    try {
      const device = await navigator.bluetooth.requestDevice({
        filters: [{ name: qrData.name }],
        optionalServices: [qrData.serviceUUID]
      });

      const server = await device.gatt.connect();
      const service = await server.getPrimaryService(qrData.serviceUUID);
      const characteristic = await service.getCharacteristic(qrData.characteristicUUID);

      window.bleCharacteristic = characteristic; // Store globally to use in send

      alert(`✅ Connected to ${qrData.name}`);
      setIsConnected(true);
    } catch (error) {
      console.error('BLE connection error:', error);
      alert('❌ BLE connection failed');
    }
  };

  const handleSend = async () => {
    const uid = 'uid ' + uidRef.current.value;
    const pass = 'pass ' + passRef.current.value;

    if (!window.bleCharacteristic) {
      alert('❌ Not connected!');
      return;
    }

    try {
      const encoder = new TextEncoder();
      await window.bleCharacteristic.writeValue(encoder.encode(uid));
      await window.bleCharacteristic.writeValue(encoder.encode(pass));
      console.log('📤 Sent:', uid);
      console.log('📤 Sent:', pass);
    } catch (error) {
      console.error('Send failed:', error);
    }
  };

  return (
    <Box maxWidth="1600px" align="center">
    <Card size='5' align="center">
    <div style={{ padding: 20 }}>
      <Text size='5' weight='bold' align='center'>BLE Connect via QR</Text> 

      <div id="reader" style={{ width: 400 }}></div>
      <Box maxWidth="250px" margin="auto" padding="10px" color="black" border="1px solid #ccc" borderRadius="10px">
        <Text size='3' weight='bold' align='center'>QR Result</Text>
        <Separator size='2' />
        <p>{qrResult}</p>
      </Box>

      <Button onClick={handleStartScan}>Start QR Scan</Button>{' '}
      <Button onClick={handleConnectBLE} disabled={!qrData}>Connect BLE</Button>

      <br /><br />
      <TextField.Root variant="surface" placeholder="Enter wifi UID" size="3" ref={uidRef} disabled={!isConnected} />
      <br />
      <TextField.Root variant="surface" placeholder="Enter wifi password" size="3" ref={passRef} disabled={!isConnected} />
      <br />
      <Button onClick={handleSend} disabled={!isConnected}>Send</Button>
    </div>
    </Card>
    </Box>  
  );
};

export default BleQrConnect;
