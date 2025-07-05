import mqtt from "mqtt";

// Broker URL (RabbitMQ with WebSockets)
const brokerUrl = "ws://192.168.1.12:15675/ws"; // Change IP & port
const options = {
  clean: true, // clean session
  connectTimeout: 4000, // 4 seconds
  username: "guest", // your RabbitMQ user
  password: "guest", // your RabbitMQ password
  reconnectPeriod: 1000, // auto-reconnect after 1s
};

let client = null;

const connect = () => {
  if (!client) {
    client = mqtt.connect(brokerUrl, options);

    client.on("connect", () => {
      console.log("MQTT Connected");
    });

    client.on("error", (err) => {
      console.error("MQTT Connection Error:", err);
      client.end();
    });

    client.on("reconnect", () => {
      console.log("Reconnecting to MQTT...");
    });

    client.on("offline", () => {
      console.log("MQTT Client Offline");
    });
  }
};

const subscribe = (topic, callback) => {
  if (client) {
    client.subscribe(topic, (err) => {
      if (!err) {
        console.log(`Subscribed to topic: ${topic}`);
      } else {
        console.error(`Subscribe error: ${err}`);
      }
    });

    client.on("message", (receivedTopic, message) => {
      if (receivedTopic === topic) {
        callback(message.toString());
      }
    });
  }
};

const publish = (topic, message) => {
  if (client) {
    client.publish(topic, message, (err) => {
      if (err) {
        console.error("Publish error:", err);
      } else {
        console.log(`Published to ${topic}: ${message}`);
      }
    });
  }
};

const subscribeToDevice = (deviceId, callback) => {
  const topic = `device/${deviceId}/status`; // Topic pattern
  if (client) {
    client.subscribe(topic, (err) => {
      if (!err) {
        console.log(`✅ Subscribed to ${topic}`);
      } else {
        console.error(`🚨 Subscribe error: ${err}`);
      }
    });

    client.on("message", (receivedTopic, message) => {
      if (receivedTopic === topic) {
        callback(message.toString(), deviceId);
      }
    });
  }
};

const publishToDevice = (deviceId, payload) => {
  const topic = `device/${deviceId}/control`; // e.g. send control commands
  if (client) {
    client.publish(topic, payload, (err) => {
      if (err) {
        console.error(`🚨 Publish error: ${err}`);
      } else {
        console.log(`📤 Published to ${topic}: ${payload}`);
      }
    });
  }
};

const disconnect = () => {
  if (client) {
    client.end();
    console.log("MQTT Disconnected");
    client = null;
  }
};

export default {
  connect,
  subscribe,
  publish,
  disconnect,
};
