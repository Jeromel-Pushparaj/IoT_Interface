<?php

use PSpell\Config;
class Database {
    private $client;
    private $database;
    private $collection;
    private $connectionString;
    private $databaseName;
    
    /**
     * Constructor to initialize MongoDB connection
     * 
     * @param string $host MongoDB host (default: localhost)
     * @param int $port MongoDB port (default: 27017)
     * @param string $database Database name
     * @param string $username Username (optional)
     * @param string $password Password (optional)
     * @param array $options Additional connection options
     */
    public function __construct($host = 'localhost', $port = 27017, $database = '', $username = '', $password = '', $options = []) {
        try {
            // Build connection string
            if (!empty($username) && !empty($password)) {
                $this->connectionString = "mongodb://{$username}:{$password}@{$host}:{$port}";
            } else {
                $this->connectionString = "mongodb://{$host}:{$port}";
            }
            
            $this->databaseName = $database;
            
            // Default options
            $defaultOptions = [
                'connectTimeoutMS' => 5000,
                'serverSelectionTimeoutMS' => 5000,
            ];
            
            $options = array_merge($defaultOptions, $options);
            
            // Create MongoDB client
            $this->client = new MongoDB\Client($this->connectionString, $options);
            
            // Select database if provided
            if (!empty($database)) {
                $this->database = $this->client->selectDatabase($database);
            }
            
        } catch (Exception $e) {
            throw new Exception("Failed to connect to MongoDB: " . $e->getMessage());
        }
    }

    public static function connect() {
        $config = require __DIR__ . "/../config/auth.php";
        return new self(
            $config['db_host'], 
            $config['db_port'], 
            $config['db_name'], 
            $config['db_user'], 
            $config['db_password']
        );
    }
    
    /**
     * Select database
     * 
     * @param string $databaseName
     * @return $this
     */
    public function selectDatabase($databaseName) {
        $this->databaseName = $databaseName;
        $this->database = $this->client->selectDatabase($databaseName);
        return $this;
    }
    
    /**
     * Select collection
     * 
     * @param string $collectionName
     * @return $this
     */
    public function selectCollection($collectionName) {
        if (!$this->database) {
            throw new Exception("No database selected. Please select a database first.");
        }
        $this->collection = $this->database->selectCollection($collectionName);
        return $this;
    }
    
    /**
     * Insert a single document
     * 
     * @param array $document
     * @param string $collectionName (optional if collection already selected)
     * @return MongoDB\InsertOneResult
     */
    public function insertOne($document, $collectionName = null) {
        try {
            $collection = $this->getCollection($collectionName);
            return $collection->insertOne($document);
        } catch (Exception $e) {
            throw new Exception("Insert operation failed: " . $e->getMessage());
        }
    }
    
    /**
     * Insert multiple documents
     * 
     * @param array $documents
     * @param string $collectionName (optional if collection already selected)
     * @return MongoDB\InsertManyResult
     */
    public function insertMany($documents, $collectionName = null) {
        try {
            $collection = $this->getCollection($collectionName);
            return $collection->insertMany($documents);
        } catch (Exception $e) {
            throw new Exception("Insert many operation failed: " . $e->getMessage());
        }
    }
    
    /**
     * Find documents
     * 
     * @param array $filter
     * @param array $options
     * @param string $collectionName (optional if collection already selected)
     * @return MongoDB\Driver\Cursor
     */
    public function find($filter = [], $options = [], $collectionName = null) {
        try {
            $collection = $this->getCollection($collectionName);
            return $collection->find($filter, $options);
        } catch (Exception $e) {
            throw new Exception("Find operation failed: " . $e->getMessage());
        }
    }
    
    /**
     * Find one document
     * 
     * @param array $filter
     * @param array $options
     * @param string $collectionName (optional if collection already selected)
     * @return array|object|null
     */
    public function findOne($filter = [], $options = [], $collectionName = null) {
        try {
            $collection = $this->getCollection($collectionName);
            return $collection->findOne($filter, $options);
        } catch (Exception $e) {
            throw new Exception("Find one operation failed: " . $e->getMessage());
        }
    }
    
    /**
     * Update a single document
     * 
     * @param array $filter
     * @param array $update
     * @param array $options
     * @param string $collectionName (optional if collection already selected)
     * @return MongoDB\UpdateResult
     */
    public function updateOne($filter, $update, $options = [], $collectionName = null) {
        try {
            $collection = $this->getCollection($collectionName);
            return $collection->updateOne($filter, $update, $options);
        } catch (Exception $e) {
            throw new Exception("Update one operation failed: " . $e->getMessage());
        }
    }
    
    /**
     * Update multiple documents
     * 
     * @param array $filter
     * @param array $update
     * @param array $options
     * @param string $collectionName (optional if collection already selected)
     * @return MongoDB\UpdateResult
     */
    public function updateMany($filter, $update, $options = [], $collectionName = null) {
        try {
            $collection = $this->getCollection($collectionName);
            return $collection->updateMany($filter, $update, $options);
        } catch (Exception $e) {
            throw new Exception("Update many operation failed: " . $e->getMessage());
        }
    }
    
    /**
     * Delete a single document
     * 
     * @param array $filter
     * @param array $options
     * @param string $collectionName (optional if collection already selected)
     * @return MongoDB\DeleteResult
     */
    public function deleteOne($filter, $options = [], $collectionName = null) {
        try {
            $collection = $this->getCollection($collectionName);
            return $collection->deleteOne($filter, $options);
        } catch (Exception $e) {
            throw new Exception("Delete one operation failed: " . $e->getMessage());
        }
    }
    
    /**
     * Delete multiple documents
     * 
     * @param array $filter
     * @param array $options
     * @param string $collectionName (optional if collection already selected)
     * @return MongoDB\DeleteResult
     */
    public function deleteMany($filter, $options = [], $collectionName = null) {
        try {
            $collection = $this->getCollection($collectionName);
            return $collection->deleteMany($filter, $options);
        } catch (Exception $e) {
            throw new Exception("Delete many operation failed: " . $e->getMessage());
        }
    }
    
    /**
     * Count documents
     * 
     * @param array $filter
     * @param array $options
     * @param string $collectionName (optional if collection already selected)
     * @return int
     */
    public function countDocuments($filter = [], $options = [], $collectionName = null) {
        try {
            $collection = $this->getCollection($collectionName);
            return $collection->countDocuments($filter, $options);
        } catch (Exception $e) {
            throw new Exception("Count operation failed: " . $e->getMessage());
        }
    }
    
    /**
     * Run aggregation pipeline
     * 
     * @param array $pipeline
     * @param array $options
     * @param string $collectionName (optional if collection already selected)
     * @return MongoDB\Driver\Cursor
     */
    public function aggregate($pipeline, $options = [], $collectionName = null) {
        try {
            $collection = $this->getCollection($collectionName);
            return $collection->aggregate($pipeline, $options);
        } catch (Exception $e) {
            throw new Exception("Aggregation operation failed: " . $e->getMessage());
        }
    }
    
    /**
     * Create index
     * 
     * @param array $keys
     * @param array $options
     * @param string $collectionName (optional if collection already selected)
     * @return string
     */
    public function createIndex($keys, $options = [], $collectionName = null) {
        try {
            $collection = $this->getCollection($collectionName);
            return $collection->createIndex($keys, $options);
        } catch (Exception $e) {
            throw new Exception("Create index operation failed: " . $e->getMessage());
        }
    }
    
    /**
     * Drop collection
     * 
     * @param string $collectionName (optional if collection already selected)
     * @return array|object
     */
    public function dropCollection($collectionName = null) {
        try {
            $collection = $this->getCollection($collectionName);
            return $collection->drop();
        } catch (Exception $e) {
            throw new Exception("Drop collection operation failed: " . $e->getMessage());
        }
    }
    
    /**
     * Get collection instance
     * 
     * @param string $collectionName
     * @return MongoDB\Collection
     */
    private function getCollection($collectionName = null) {
        if ($collectionName) {
            return $this->database->selectCollection($collectionName);
        }
        
        if (!$this->collection) {
            throw new Exception("No collection selected. Please select a collection or provide collection name.");
        }
        
        return $this->collection;
    }
    
    /**
     * Get MongoDB client
     * 
     * @return MongoDB\Client
     */
    public function getClient() {
        return $this->client;
    }
    
    /**
     * Get database instance
     * 
     * @return MongoDB\Database
     */
    public function getDatabase() {
        return $this->database;
    }
    
    /**
     * Get current collection
     * 
     * @return MongoDB\Collection
     */
    public function getCurrentCollection() {
        return $this->collection;
    }
    
    /**
     * Test connection
     * 
     * @return bool
     */
    public function testConnection() {
        try {
            $this->client->listDatabases();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get database statistics
     * 
     * @return array|object
     */
    public function getDatabaseStats() {
        try {
            return $this->database->command(['dbStats' => 1]);
        } catch (Exception $e) {
            throw new Exception("Failed to get database stats: " . $e->getMessage());
        }
    }
    
    /**
     * List all collections in the database
     * 
     * @return MongoDB\Driver\Cursor
     */
    public function listCollections() {
        try {
            return $this->database->listCollections();
        } catch (Exception $e) {
            throw new Exception("Failed to list collections: " . $e->getMessage());
        }
    }
}

// Example usage:
/*
try {
    // Initialize connection
    $mongo = new MongoDBConnection('localhost', 27017, 'myapp', 'username', 'password');
    
    // Test connection
    if ($mongo->testConnection()) {
        echo "Connected successfully!\n";
    }
    
    // Select collection
    $mongo->selectCollection('users');
    
    // Insert document
    $result = $mongo->insertOne([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'age' => 30,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);
    
    echo "Inserted document with ID: " . $result->getInsertedId() . "\n";
    
    // Find documents
    $users = $mongo->find(['age' => ['$gte' => 18]]);
    
    foreach ($users as $user) {
        echo "User: " . $user['name'] . " (" . $user['email'] . ")\n";
    }
    
    // Update document
    $updateResult = $mongo->updateOne(
        ['email' => 'john@example.com'],
        ['$set' => ['age' => 31]]
    );
    
    echo "Modified " . $updateResult->getModifiedCount() . " document(s)\n";
    
    // Count documents
    $count = $mongo->countDocuments(['age' => ['$gte' => 18]]);
    echo "Total adult users: " . $count . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
*/
?>