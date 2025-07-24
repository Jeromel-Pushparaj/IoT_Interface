<?php

return [
    // Database configuration
    'db_host' => 'mongo',
    'db_port' => 27017,
    'db_name' => 'iot_platform',
    'db_user' => 'admin', // replace with env later
    'db_password' => 'admin123', // replace with env later

    //rabitmq configuration
    'rabbitmq_host' => 'rabbitmq',
    'rabbitmq_port' => 5672,
    'rabbitmq_user' => 'guest', // replace with env later
    'rabbitmq_password' => 'guest', // replace with env later
    'rabbitmq_vhost' => '/', // replace with env later
    'rabbitmq_exchange' => 'iot_exchange', // replace with env later

    'mqtt_host' => 'rabbitmq', // Container name or IP
    'mqtt_port' => 1883, // Default MQTT port
    'mqtt_username' => 'guest', // MQTT username, replace with env later
    'mqtt_password' => 'guest', // MQTT password, replace with env later    
    'mqtt_client_id' => 'iot_mqtt_client' . uniqid(), // Unique client ID for MQTT connection
    //JWT configuration
    'jwt_secret' => 'azuQLMnPYjiA5lwU+mfyYEE61VQDdhFFSincpzOtnqEKHm57AdT1W4QRXHyxHpnE5YjlTs/XWWSmExJXH1ICAQ==', // replace with env later
    'jwt_issuer' => 'iot-interface-platform',
    'jwt_expire' => 43200 // 12 hour
   ];