import mqtt from "mqtt";

const MQTT_BROKER_URL = "ws://192.168.1.12:15675/ws";
const RECONNECT_PERIOD = 2000;
const KEEPALIVE = 60;

let client = null;
let isConnecting = false;
const subscriptions = {}; // { topic: Set(callbacks) }

function connect() {
  if (client && client.connected) {
    console.log("✅ MQTT already connected");
    return;
  }
  if (isConnecting) {
    console.log("⏳ MQTT connection already in progress...");
    return;
  }

  isConnecting = true;

  console.log("🔌 Connecting to MQTT broker...");
  client = mqtt.connect(MQTT_BROKER_URL, {
    clientId: `react_client_${Math.random().toString(16).substr(2, 8)}`,
    keepalive: KEEPALIVE,
    reconnectPeriod: RECONNECT_PERIOD,
    username: "guest",
    password: "guest",
    clean: false, // Keep session alive
  });

  client.on("connect", () => {
    console.log("✅ MQTT connected");
    resubscribeAll();
    isConnecting = false;
  });

  client.on("reconnect", () => {
    console.log("🔄 MQTT reconnecting...");
  });

  client.on("offline", () => {
    console.warn("⚠ MQTT offline");
  });

  client.on("error", (err) => {
    console.error("❌ MQTT error:", err);
    client.end();
    isConnecting = false;
  });

  client.on("message", (topic, message) => {
    const payload = message.toString();
    console.log(`📩 Message received on ${topic}: ${payload}`);
    if (subscriptions[topic]) {
      subscriptions[topic].forEach((cb) => {
        try {
          cb(payload);
        } catch (err) {
          console.error(`⚠️ Error in callback for ${topic}:`, err);
        }
      });
    }
  });
}

function subscribe(topic, callback) {
  connect();

  if (!subscriptions[topic]) {
    subscriptions[topic] = new Set();
    client.subscribe(topic, { qos: 1 }, (err) => {
      if (err) {
        console.error(`❌ Failed to subscribe ${topic}:`, err);
      } else {
        console.log(`📡 Subscribed to ${topic}`);
      }
    });
  }

  if (!subscriptions[topic].has(callback)) {
    subscriptions[topic].add(callback);
    console.log(`✅ Added callback for ${topic}`);
  } else {
    console.log(`⚠️ Callback already registered for ${topic}`);
  }

  return () => unsubscribe(topic, callback);
}

function unsubscribe(topic, callback) {
  if (!subscriptions[topic]) return;

  subscriptions[topic].delete(callback);
  console.log(`🗑 Removed callback for ${topic}`);

  if (subscriptions[topic].size === 0) {
    client.unsubscribe(topic, (err) => {
      if (err) {
        console.error(`❌ Failed to unsubscribe ${topic}:`, err);
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
        console.error(`❌ Failed to publish ${topic}:`, err);
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
    console.log(`🔄 Re-subscribing to ${topic}`);
    client.unsubscribe(topic, () => {
      client.subscribe(topic, { qos: 1 }, (err) => {
        if (err) {
          console.error(`❌ Failed to re-subscribe ${topic}:`, err);
        } else {
          console.log(`🔄 Re-subscribed to ${topic}`);
        }
      });
    });
  });
}

function isConnected() {
  return client && client.connected;
}

function disconnect() {
  if (client) {
    client.end(true, () => {
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
