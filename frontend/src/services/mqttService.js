import mqtt from "mqtt";

const MQTT_BROKER_URL = "ws://192.168.1.12:15675/ws";
const RECONNECT_PERIOD = 2000;
const KEEPALIVE = 60;

let client = null;
let isConnecting = false;
const subscriptions = {}; // { topic: Set(callbacks) }

function connect() {
  if (client && client.connected) {
    console.log("MQTT already connected");
    return;
  }


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
    console.log("MQTT connected");
    resubscribeAll();
    isConnecting = false;
  });

  client.on("reconnect", () => {
    console.log("MQTT reconnecting...");
  });

  client.on("offline", () => {
    console.warn("MQTT offline");
  });

  client.on("error", (err) => {
    console.error("MQTT error:", err);
    client.end();
    isConnecting = false;
  });

  client.on("message", (topic, message) => {
    const payload = message.toString();
    console.log(`Message received on ${topic}: ${payload}`);
    if (subscriptions[topic]) {
      subscriptions[topic].forEach((cb) => {
        try {
          cb(payload);
        } catch (err) {
          console.error(`Error in callback for ${topic}:`, err);
        }
      });
    }
  });
}

function subscribe(topic, callback) {
  if (!client || !client.connected) {
    console.warn(`Cannot subscribe to ${topic}: MQTT client not connected`);
    return () => {}; // return a no-op unsubscribe function
  }

  if (!subscriptions[topic]) {
    subscriptions[topic] = [];
    client.subscribe(topic, (err) => {
      if (err) {
        console.error(`Failed to subscribe to ${topic}:`, err);
      } else {
        console.log(`Subscribed to ${topic}`);
      }
    });
  }

  subscriptions[topic].push(callback);

  return () => unsubscribe(topic, callback);
}

function unsubscribe(topic, callback) {
  if (!client || !client.connected) {
    console.warn(`Cannot unsubscribe from ${topic}: MQTT client not connected`);
    return;
  }

  if (subscriptions[topic]) {
    subscriptions[topic] = subscriptions[topic].filter((cb) => cb !== callback);
    if (subscriptions[topic].length === 0) {
      client.unsubscribe(topic, (err) => {
        if (err) {
          console.error(`Failed to unsubscribe from ${topic}:`, err);
        } else {
          console.log(`Unsubscribed from ${topic}`);
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
        console.error(`Failed to publish ${topic}:`, err);
      } else {
        console.log(`Published to ${topic}: ${message}`);
      }
    });
  } else {
    console.warn("Cannot publish, MQTT not connected");
  }
}

function resubscribeAll() {
  if (!client || !client.connected) return;

  Object.keys(subscriptions).forEach((topic) => {
    console.log(`Re-subscribing to ${topic}`);
    client.unsubscribe(topic, () => {
      client.subscribe(topic, { qos: 1 }, (err) => {
        if (err) {
          console.error(`Failed to re-subscribe ${topic}:`, err);
        } else {
          console.log(`Re-subscribed to ${topic}`);
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
      console.log("MQTT disconnected");
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
