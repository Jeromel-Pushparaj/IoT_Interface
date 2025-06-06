<?php
require_once __DIR__ . '/../vendor/autoload.php';
$id = new MongoDB\BSON\ObjectId();
echo $id;
MongoDB\BSON\ObjectId::isValid($id) ? 'Valid ObjectId' : 'Invalid ObjectId';
var_dump(class_exists('MongoDB\BSON\ObjectId'));  // Should return `true`

$reflector = new ReflectionClass('MongoDB\BSON\ObjectId');
echo "File: " . $reflector->getFileName() . "\n";
