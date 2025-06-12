import paho.mqtt.client as mqtt
import json
from pymongo import MongoClient
from dotenv import load_dotenv, find_dotenv
import os   
import datetime 

# Load environment variables
load_dotenv(find_dotenv())
if not os.getenv("MONGODB_URI"):
    raise EnvironmentError("MONGO_URI not set in .env file")
# Ensure the required environment variable is set
if not os.getenv("MQTT_HOST"):
    raise EnvironmentError("MQTT_BROKER not set in .env file")
if not os.getenv("MQTT_PORT"):
    raise EnvironmentError("MQTT_PORT not set in .env file")

# Mongo Setup
mongo = MongoClient(os.getenv("MONGODB_URI"))
db = mongo["iot_platform"]
collection = db["device_data"]

mqtt_broker = os.getenv("MQTT_HOST")
mqtt_port = int(os.getenv("MQTT_PORT"))
mqtt_uasername = os.getenv("MQTT_USERNAME", None)
mqtt_password = os.getenv("MQTT_PASSWORD", None)

# Callback for message
def on_message(client, userdata, message):
    try:
        payload = json.loads(message.payload.decode())
        topic = message.topic
        print(f"Received on {topic}: {payload}")

        # Extract device_id from topic
        device_id = topic.split("/")[1]
        payload["device_id"] = device_id
        payload["topic"] = topic
        payload["timestamp"] = datetime.datetime.now()  # Add timestamp if needed
        payload["raw_data"] = message.payload.decode()  # Store raw data
        payload["message_id"] = message.mid  # Store message ID

        # Store in DB
        collection.insert_one(payload)

    except Exception as e:
        print(f"Error: {e}")

# MQTT Setup
client = mqtt.Client()
client.on_message = on_message
client.username_pw_set(mqtt_uasername, mqtt_password)
client.connect(mqtt_broker, mqtt_port, 60)


# Subscribe to all device data topics
client.subscribe("device/+/command")

# Loop forever
client.loop_forever()
