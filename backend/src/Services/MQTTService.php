<?php

namespace Services;

use Bluerhinos\phpMQTT;


class MQTTService
{
    private $mqtt;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/auth.php';
        $server = $config['mqtt_host'];   // Container name or IP
        $port = $config['mqtt_port']; // Default MQTT port

        $clientId = $config['mqtt_client_id']; // Unique client ID for MQTT connection

        $this->mqtt = new phpMQTT($server, $port, $clientId);
    }

    public function publish($topic, $message, $qos = 0)
    {
        try {
            if ($this->mqtt->connect()) {
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
