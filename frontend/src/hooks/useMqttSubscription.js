import { useEffect, useState } from 'react';
import mqttService from '@/services/mqttService';

/**
 * useMqttSubscription
 * Automatically subscribes to an MQTT topic and gives you live messages
 * 
 * @param {string} topic - The MQTT topic to subscribe to
 * @param {(msg: string) => void} [onMessage] - Optional callback when message arrives
 * @returns {string[]} messages - Array of all received messages for this topic
 */
export function useMqttSubscription(topic, onMessage) {
  const [messages, setMessages] = useState([]);

  useEffect(() => {
    if (!mqttService.isConnected()) {
      console.log(`⏳ Waiting for MQTT to connect before subscribing to ${topic}`);
      return;
    }
    if (!topic) return;

    console.log(`📡 [useMqttSubscription] Subscribing to ${topic}`);


    let lastMessage = null;

    const handleMessage = (msg) => {
      if (msg !== lastMessage) {
        setMessages((prev) => [...prev, msg]);
        lastMessage = msg;
      }
      if (onMessage) onMessage(msg);
    };

    const unsubscribe = mqttService.subscribe(topic, handleMessage);

    return () => {
      console.log(`🛑 [useMqttSubscription] Unsubscribing from ${topic}`);
      unsubscribe();
    };
  }, [topic]);

  return messages;
}
