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
    if (!topic) return;

    console.log(`📡 [useMqttSubscription] Subscribing to ${topic}`);

    const unsubscribe = mqttService.subscribe(topic, (message) => {
      setMessages((prev) => [...prev, message]);
      if (onMessage) {
        try {
          onMessage(message);
        } catch (err) {
          console.error(`⚠️ Error in onMessage callback for ${topic}:`, err);
        }
      }
    });

    return () => {
      console.log(`🛑 [useMqttSubscription] Unsubscribing from ${topic}`);
      unsubscribe();
    };
  }, [topic]);

  return messages;
}
