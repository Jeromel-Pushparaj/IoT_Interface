// mqttService.js
import mqtt from "mqtt";

const MQTT_BROKER_URL = "ws://192.168.1.12:15675/ws"; // RabbitMQ WebSocket
const RECONNECT_PERIOD = 2000; // ms
const KEEPALIVE = 60; // seconds

let client = null;
const subscriptions = {}; // { topic: Set(callbacks) }

function connect() {
  if (client && client.connected) {
    console.log("✅ MQTT already connected");
    return client;
  }

  console.log("🔌 Connecting to MQTT broker...");
  client = mqtt.connect(MQTT_BROKER_URL, {
    clientId: `react_client_${Math.random().toString(16).substr(2, 8)}`,
    keepalive: KEEPALIVE,
    reconnectPeriod: RECONNECT_PERIOD,
    username: "guest",
    password: "guest",
    clean: false, // keep session on reconnect
  });

  client.on("connect", () => {
    console.log("✅ MQTT connected");
    resubscribeAll();
  });

  client.on("reconnect", () => {
    console.log("🔄 Reconnecting to MQTT broker...");
  });

  client.on("offline", () => {
    console.warn("⚠ MQTT offline");
  });

  client.on("error", (err) => {
    console.error("❌ MQTT error:", err);
    client.end();
  });

  client.on("message", (topic, message) => {
    const payload = message.toString();
    console.log(`📩 Message received on ${topic}: ${payload}`);
    if (subscriptions[topic]) {
      subscriptions[topic].forEach((callback) => {
        try {
          callback(payload);
        } catch (err) {
          console.error(`⚠️ Error in callback for ${topic}:`, err);
        }
      });
    }
  });

  return client;
}

function subscribe(topic, callback) {
  connect();

  if (!subscriptions[topic]) {
    subscriptions[topic] = new Set();
    client.subscribe(topic, { qos: 1 }, (err) => {
      if (err) {
        console.error(`❌ Failed to subscribe to ${topic}:`, err);
      } else {
        console.log(`📡 Subscribed to ${topic}`);
      }
    });
  }

  if (!subscriptions[topic].has(callback)) {
    subscriptions[topic].add(callback);
    console.log(`✅ Callback added for ${topic}`);
  } else {
    console.log(`⚠️ Callback already registered for ${topic}`);
  }

  // Return an unsubscribe function for this callback
  return () => unsubscribe(topic, callback);
}

function unsubscribe(topic, callback) {
  if (!subscriptions[topic]) return;

  subscriptions[topic].delete(callback);
  console.log(`🗑 Removed callback for ${topic}`);

  if (subscriptions[topic].size === 0) {
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

function publish(topic, message) {
  if (client && client.connected) {
    client.publish(topic, message, { qos: 1 }, (err) => {
      if (err) {
        console.error(`❌ Failed to publish to ${topic}:`, err);
      } else {
        console.log(`📤 Published to ${topic}: ${message}`);
      }
    });
  } else {
    console.warn("⚠ Cannot publish, MQTT not connected");
  }
}

function resubscribeAll() {
  if (!client || !client.connected) return;
  Object.keys(subscriptions).forEach((topic) => {
    client.subscribe(topic, { qos: 1 }, (err) => {
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
    console.log("🔌 Disconnecting MQTT...");
    client.end(true, () => {
      console.log("✅ MQTT disconnected");
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
