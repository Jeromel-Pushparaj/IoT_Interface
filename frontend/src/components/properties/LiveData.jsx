import React, { useRef, useEffect } from 'react';
import { Box, Text } from '@radix-ui/themes';
import { useMqttSubscription } from '@/hooks/useMqttSubscription';

function LiveData({ deviceId }) {
  const topic = `device/${deviceId}/livedata`;
  const liveData = useMqttSubscription(topic);
  const dataBoxRef = useRef(null);

  // Scroll to bottom on new message
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
        color: 'lime',
        overflowY: 'auto',
        height: '8rem',
        fontFamily: 'monospace',
        lineHeight: '1.4',
      }}
    >
      {liveData.length === 0 ? (
        <Text size="1" color="gray">No data available</Text>
      ) : (
        liveData.map((msg, idx) => (
          <div key={idx} style={{ opacity: 0, animation: 'fadeIn 0.4s forwards' }}>
            {msg}
          </div>
        ))
      )}
      <style>{`
        @keyframes fadeIn { to { opacity: 1; } }
      `}</style>
    </Box>
  );
}

export default LiveData;
