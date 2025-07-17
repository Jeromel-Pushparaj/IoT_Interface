import React, { useEffect, useRef, useState } from 'react';
import { Card, Flex, Text, Link, Box } from '@radix-ui/themes';
import mqttService from '@/services/mqttService';
function LiveData({ deviceId }) {

  const [liveData, setLiveData] = useState([]);
  const dataBoxRef = useRef(null);

useEffect(() => {
  if (!mqttService.isConnected()) {
    mqttService.connect();
  }

  const topic = `device/${deviceId}/livedata`;

  const handleMessage = (message) => {
    setLiveData(prevData => [...prevData, message]);
    console.log(`Received live data for device ${deviceId}:`, message);
  };

  mqttService.subscribe(topic, handleMessage);

  return () => {
    mqttService.unsubscribe(topic, handleMessage);
  };
}, [deviceId]);
  // Smooth scroll to bottom when new message comes
  useEffect(() => {
    if (dataBoxRef.current) {
      dataBoxRef.current.scrollTo({
        top: dataBoxRef.current.scrollHeight,
        behavior: 'smooth',
      });
    }
  }, [liveData]);

  return (
    <Box
      ref={dataBoxRef}
      style={{
        backgroundColor: 'black',
        padding: '1rem',
        borderRadius: '8px',
        color: 'lime', // terminal green text
        overflowY: 'auto',
        height: '4rem', // taller for smooth flow
        fontFamily: 'monospace',
        lineHeight: '1.4',
        transition: 'all 0.3s ease', // subtle animation
      }}
    >
      {liveData.length === 0 ? (
        <Text size="1" color="gray">
          No data available
        </Text>
      ) : (
        liveData.map((msg, index) => (
          <div
            key={index}
            style={{
              opacity: 0,
              animation: 'fadeIn 0.4s forwards',
              animationDelay: `${index * 0.05}s`,
            }}
          >
            {msg}
          </div>
        ))
      )}

      <style>{`
        @keyframes fadeIn {
          to { opacity: 1; }
        }
      `}</style>
    </Box>
  );
}

export default LiveData;