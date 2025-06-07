<?php

namespace Services;

use Bluerhinos\phpMQTT;


class MQTTService
{
    private $mqtt;
    private $mqttHost;
    private $mqttPort;
    private $mqttUsername;
    private $mqttPassword;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/auth.php';
        $this->mqttHost = $config['mqtt_host'];   // Container name or IP
        $this->mqttPort = $config['mqtt_port']; // Default MQTT port
        $this->mqttUsername = $config['mqtt_username']; // MQTT username
        $this->mqttPassword = $config['mqtt_password']; // MQTT password

        $clientId = $config['mqtt_client_id']; // Unique client ID for MQTT connection

        $this->mqtt = new phpMQTT($this->mqttHost, $this->mqttPort, $clientId);
    }

    public function publish($topic, $message, $qos = 0)
    {
        try {
            if ($this->mqtt->connect(true, null, $username = $this->mqttUsername, $password = $this->mqttPassword)) {
                // Ensure the topic is a string and message is JSON encoded
                $this->mqtt->publish($topic, json_encode($message), $qos);
                $this->mqtt->close();
                return true;
            } else {
                error_log("MQTT connection failed.");
                return false;
            }
        } catch (\Exception $e) {
            error_log("MQTT publish error: " . $e->getMessage());
            return false;
        }
    }
}
