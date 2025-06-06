// MongoDB initialization script
// This script runs when the MongoDB container starts for the first time

// Switch to the iot_app database
db = db.getSiblingDB('iot_app');

// Create collections with indexes
db.createCollection('devices');
db.createCollection('sensor_data');
db.createCollection('users');
db.createCollection('mqtt_logs');

// Create indexes for better performance
db.devices.createIndex({ "device_id": 1 }, { unique: true });
db.devices.createIndex({ "status": 1 });
db.devices.createIndex({ "created_at": 1 });

db.sensor_data.createIndex({ "device_id": 1 });
db.sensor_data.createIndex({ "timestamp": 1 });
db.sensor_data.createIndex({ "sensor_type": 1 });

db.users.createIndex({ "email": 1 }, { unique: true });
db.users.createIndex({ "username": 1 }, { unique: true });

db.mqtt_logs.createIndex({ "timestamp": 1 });
db.mqtt_logs.createIndex({ "topic": 1 });

// Insert sample data
db.devices.insertMany([
    {
        device_id: "IOT_001",
        name: "Temperature Sensor 1",
        type: "temperature",
        location: "Room A",
        status: "active",
        created_at: new Date(),
        updated_at: new Date()
    },
    {
        device_id: "IOT_002", 
        name: "Humidity Sensor 1",
        type: "humidity",
        location: "Room B",
        status: "active",
        created_at: new Date(),
        updated_at: new Date()
    }
]);

db.users.insertOne({
    username: "admin",
    email: "admin@iotapp.com",
    password: "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi", // password
    role: "admin",
    created_at: new Date(),
    updated_at: new Date()
});

print("Database initialization completed!");
print("Sample devices and admin user created.");
print("Admin credentials: admin@iotapp.com / password");