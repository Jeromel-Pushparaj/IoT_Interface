<?php
namespace Controllers;

use Database;
use MongoDB\BSON\ObjectId;
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
            'name' => $requestData['name'],
            'status' => 'offline',
            'owner_id' => $this->decode['user']['sub'],
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
        if (!isset($requestData['device_id']) || !isset($requestData['status'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing fields']);
            return;
        }

        $result = $this->collection->updateOne(
            ['device_id' => $requestData['device_id']],
            ['$set' => [
                'status' => $requestData['status'],
                'updated_at' => date('c') // ISO8601 format, e.g., "2025-06-05T12:34:56+00:00"
                ]]
        );

        if ($result->getModifiedCount() > 0) {
            echo json_encode(['message' => 'Device status updated']);
        } else {
            echo json_encode(['message' => 'No changes made']);
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
