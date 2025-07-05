<?php
namespace Controllers;

use Database;
use MongoDB\BSON\ObjectId;
use Services\MQTTService;

class DeviceController
{
    private $client;
    private $collection;
    private $decode;

    public function __construct($decode = null)
    {
        $this->client = Database::connect();
        $this->decode = $decode;
        if (!$this->client) {
            http_response_code(500);
            echo json_encode(['error' => 'Database connection failed']);
            exit;
        }
        $this->collection = $this->client->selectCollection("devices");
        if (!$this->collection) {
            http_response_code(500);
            echo json_encode(['error' => 'Collection not found']);
            exit;       
        }
    }

    // POST /api/device/register
    public function register($requestData)
    {
        if (!isset($requestData['device_id']) || !isset($requestData['name'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        $device = [
            'device_id' => $requestData['device_id'],
            'owner_id' => $this->decode['user']['sub'],
            'name' => $requestData['name'],
            'type' => $requestData['type'] ?? 'generic', // default to 'generic' if not provided
            'value' => $requestData['value'] ?? null, // optional value field
            'status' => 'offline', //active|inactive|maintenance|error
            'isActive' => false, // false|true
            'created_at' => date('c'), // ISO8601 format, e.g., "2025-06-05T12:34:56+00:00"
            'updated_at' => date('c')
        ];

        $existing = $this->collection->findOne(['device_id' => $device['device_id']]);

        if ($existing) {
            http_response_code(409);
            echo json_encode(['error' => 'Device already registered']);
            return;
        }

        $this->collection->insertOne($device);
        echo json_encode(['message' => 'Device registered successfully']);
    }

    // GET /api/device/list
    public function list($requestData)
    {
        // Fetch devices for the given user
        $devices = $this->collection->find(['owner_id' => $this->decode['user']['sub']])->toArray();
        echo json_encode($devices);
    }

    public function viewDevice($requestData, $id)
    {
        if (!isset($id) || empty($id)) {
            http_response_code(400);
            echo json_encode(['error' => 'Device ID is required']);
            return;
        }

        
        $device = $this->collection->findOne([
            '_id' => new ObjectId(strval($id)),
            'owner_id' => $this->decode['user']['sub'] // Ensure the device belongs to the user
        ]);

        if (!$device) {
            http_response_code(404);
            echo json_encode(['error' => 'Device not found']);
            return;
        }

        echo json_encode($device);
    }
    

    // PATCH /api/device/update
    public function updateStatus($requestData)
    {
        $deviceId = $requestData['device_id'] ?? null;
        $status = $requestData['status'] ?? null;
        if (!isset($deviceId) || !isset($status) || empty($deviceId) || empty($status)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing fields']);
            return;
        }

            $mqtt = new MQTTService();
            $topic = "device/$deviceId/command";
            $message = ['action' => $status];

            $success = $mqtt->publish($topic, $message);

        if ($success) {
            echo json_encode(['success' => true, 'message' => "Command sent to $deviceId"]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to publish to MQTT']);
        }
    }

    public function deleteDevice($requestData, $id)
    {
        if (!isset($id) || empty($id)) {
            http_response_code(400);
            echo json_encode(['error' => 'Device ID is required']);
            return;
        }

        

        $userId = $this->decode['user']['sub'] ?? null; // Get user ID from decoded JWT or session
        // Ensure the user owns the device before deleting
        if (!isset($userId)) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }
        $result = $this->collection->deleteOne([
            /** @var \MongoDB\BSON\ObjectId */
            '_id' => new ObjectId(strval($id)),
            'owner_id' => $userId // Ensure the device belongs to the user
        ]);

        if ($result->getDeletedCount() === 1) {
            echo json_encode(['status' => 'Device deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Device not found or not authorized']);
        }
    }
}
