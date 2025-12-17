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
devices_collection = db["devices"]

mqtt_broker = os.getenv("MQTT_HOST")
mqtt_port = int(os.getenv("MQTT_PORT"))
mqtt_username = os.getenv("MQTT_USERNAME", None)
mqtt_password = os.getenv("MQTT_PASSWORD", None)

def update(device_id, data, topic):
    """
    Update the device data in the MongoDB collection.
    """
    try:
        result = collection.update_one(
            {"device_id": device_id, "topic": topic},
            {"$set": data},
            upsert=True  # Create a new document if it doesn't exist
        )
        return result.modified_count > 0 or result.upserted_id is not None
    except Exception as e:
        print(f"Error updating device {device_id}: {e}")
        return False

# Callback for message
def on_message(client, userdata, message):
    try:
        print(f"Raw payload: {message.payload}")
        payload_str = message.payload.decode()
        topic = message.topic
        print(f"Received on {topic}: {payload_str}")

        topic_parts = topic.split("/")
        if len(topic_parts) < 2:
            print(f"Ignoring malformed topic: {topic}")
            return
        
        device_id = topic_parts[1]

        # Logic for status updates
        if topic.endswith("/status"):
            new_status = payload_str.lower()
            if new_status not in ['online', 'offline']:
                print(f"Invalid status message on {topic}: {new_status}")
                return

            # Update the 'devices' collection
            result = devices_collection.update_one(
                {"device_id": device_id},
                {"$set": {"status": new_status, "updated_at": datetime.datetime.now()}}
            )
            if result.matched_count > 0:
                print(f"Updated status for device {device_id} to {new_status}")
            else:
                print(f"Device {device_id} not found in devices collection for status update.")

        # Logic for other data
        else:
            doc = collection.find_one({"device_id": device_id, "topic": topic})
            if doc:
                # Update with the new string data
                update_data = {
                    "raw_data": payload_str,
                    "updated_at": datetime.datetime.now()
                }
                if update(device_id, update_data, topic):
                    print(f"Device {device_id} updated successfully.")
                else:
                    print(f"Failed to update device {device_id}.")
            else:
                # Insert a new document with string payload
                new_doc = {
                    "device_id": device_id,
                    "topic": topic,
                    "timestamp": datetime.datetime.now(),
                    "raw_data": payload_str,
                    "message_id": getattr(message, "mid", None),
                    "created_by": device_id,
                    "created_at": datetime.datetime.now(),
                    "updated_at": datetime.datetime.now()
                }
                collection.insert_one(new_doc)

    except Exception as e:
        print(f"Error: {e}")

# MQTT Setup
client = mqtt.Client()
client.on_message = on_message
client.username_pw_set(mqtt_username, mqtt_password)
client.connect(mqtt_broker, mqtt_port, 60)

# Subscribe to all device data topics
client.subscribe("device/+/+")

# Loop forever
client.loop_forever()
