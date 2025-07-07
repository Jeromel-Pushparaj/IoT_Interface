// mqttService.js
import mqtt from "mqtt";

const MQTT_BROKER_URL = "ws://192.168.1.12:15675/ws"; // Use RabbitMQ WebSocket port
const RECONNECT_PERIOD = 2000; // 2 seconds
const KEEPALIVE = 60; // seconds

let client = null;
const subscriptions = {}; // { topic: callback }

function connect() {
  if (client && client.connected) {
    console.log("✅ MQTT already connected");
    return;
  }

  console.log("🔌 Connecting to MQTT broker...");
  client = mqtt.connect(MQTT_BROKER_URL, {
    clientId: `react_client_${Math.random().toString(16).substr(2, 8)}`,
    keepalive: KEEPALIVE,
    reconnectPeriod: RECONNECT_PERIOD,
    username: "guest", // RabbitMQ default user
    password: "guest",
    clean: true,
  });

  client.on("connect", () => {
    console.log("✅ MQTT connected");
    if(client && client.connected) {
      resubscribeAll();
    }
  });

  client.on("reconnect", () => {
    console.log("🔄 Reconnecting to MQTT broker...");
  });

  client.on("offline", () => {
    console.warn("⚠ MQTT went offline");
  });

  client.on("error", (err) => {
    console.error("🚨 MQTT connection error:", err);
    client.end();
  });

  client.on("message", (topic, message) => {
    const payload = message.toString();
    console.log(`📩 Message received on ${topic}:`, payload);

    if (subscriptions[topic]) {
      subscriptions[topic].forEach((callback) => callback(payload));
    }
  });
}

function subscribe(topic, callback) {
  if (!client) connect();

  if (!subscriptions[topic]) {
    subscriptions[topic] = [];
    client.subscribe(topic, (err) => {
      if (err) {
        console.error(`❌ Failed to subscribe to ${topic}:`, err);
      } else {
        console.log(`📡 Subscribed to ${topic}`);
      }
    });
  }

  subscriptions[topic].push(callback);

  // Return unsubscribe function
  return () => unsubscribe(topic, callback);
}

function unsubscribe(topic, callback) {
  if (subscriptions[topic]) {
    subscriptions[topic] = subscriptions[topic].filter((cb) => cb !== callback);
    if (subscriptions[topic].length === 0) {
      client.unsubscribe(topic, (err) => {
        if (err) {
          console.error(`❌ Failed to unsubscribe from ${topic}:`, err);
        } else {
          console.log(`🛑 Unsubscribed from ${topic}`);
        }
      });
      delete subscriptions[topic];
    }
  }
}

function publish(topic, message) {
  if (client && client.connected) {
    client.publish(topic, message, { qos: 1 }, (err) => {
      if (err) {
        console.error(`❌ Failed to publish to ${topic}:`, err);
      } else {
        console.log(`📤 Published to ${topic}:`, message);
      }
    });
  } else {
    console.warn("⚠ Cannot publish, MQTT not connected");
  }
}

function resubscribeAll() {
  if (!client || !client.connected) {
    console.warn("⚠ Skipping resubscribeAll: client is not connected");
    return;
  }
  Object.keys(subscriptions).forEach((topic) => {
    client.subscribe(topic, (err) => {
      if (err) {
        console.error(`❌ Failed to re-subscribe to ${topic}:`, err);
      } else {
        console.log(`🔄 Re-subscribed to ${topic}`);
      }
    });
  });
}

function isConnected() {
  return client && client.connected;
}

function disconnect() {
  if (client) {
    client.end(() => {
      console.log("🔌 MQTT disconnected");
      client = null;
    });
  }
}

export default {
  connect,
  subscribe,
  unsubscribe,
  publish,
  disconnect,
  isConnected,
};
