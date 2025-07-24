# IoT Interface Platform

This project is a modular IoT platform that provides a RESTful PHP backend, MongoDB database, MQTT broker, and a web client. It is designed for managing IoT devices, collecting sensor data, and integrating with MQTT-enabled hardware.

---

![image](https://github.com/user-attachments/assets/dff384d6-1761-4e02-9c6f-54efaac34374)

## Features

-   **User Authentication** (JWT-based)
-   **Device Registration & Management**
-   **Device Ownership** (per-user)
-   **Device Status Update via MQTT**
-   **REST API** for user and device operations
-   **MongoDB** for persistent storage
-   **MQTT Broker** (Eclipse Mosquitto) for device communication
-   **Mongo Express** for database management UI
-   **Docker Compose** for easy setup

---

## Project Structure

```
.
.
├── .vscode/               # VSCode workspace configs
├── backend/               # PHP backend with MVC structure
├── frontend/              # React frontend (Vite-based)
├── mongo/                 # MongoDB initialization scripts
├── mqtt-broker/           # Mosquitto MQTT broker config and data
├── mqtt-subscriber/       # Python-based MQTT subscriber service
├── rabbitmq/              # RabbitMQ configuration (minimal)
├── docker-compose.yml     # Docker orchestration
├── .gitignore
└── README.md              # Project overview and documentation
```

---

## Getting Started

### Prerequisites

-   [Docker](https://www.docker.com/get-started)
-   [Docker Compose](https://docs.docker.com/compose/)

### Setup & Run

1. **Clone the repository:**

    ```sh
    git clone https://github.com/Jeromel-Pushparaj/IoT_Interface.git
    cd IoT_Interface
    ```

2. **Start all services:**

    ```sh
    docker-compose up --build
    ```

    This will start:

    - PHP backend (on [http://localhost:8080](http://localhost:8080))
    - MongoDB (on port 27017)
    - Mongo Express (on [http://localhost:8081](http://localhost:8081))
    - MQTT Broker (on port 1883, WebSocket on 9001)

3. **Access Mongo Express:**

    - URL: ://[http://localhost:8081](httplocalhost:8081)
    - Username: `admin`
    - Password: `admin123`

4. **API Endpoints:**

    - Register: `POST /api/register`
    - Login: `POST /api/login`
    - Profile: `GET /api/profile` (JWT required)
    - Device Register: `POST /api/device/register` (JWT required)
    - Device List: `GET /api/device/list` (JWT required)
    - Device Show: `GET /api/device/show/{id}` (JWT required)
    - Device Delete: `DELETE /api/device/delete/{id}` (JWT required)
    - Device Update: `POST /api/device/update` (JWT required)

    > Use an API client like Postman or curl to interact with the endpoints.

5. **Default Admin User:**
    - Email: `admin@iotapp.com`
    - Password: `password`

---

## How MQTT is in action?

### Architecture Overview

```
+-------------+            +-----------------+            +--------------+
|  Frontend   |  ---> API  |   PHP Backend   |  ---> MQTT |   IoT Device |
+-------------+            +-----------------+            +--------------+
                                |  uses Paho MQTT PHP
                                |
                                | Publishes to topic like:
                                |  device/{device_id}/cmd

```

### Related Micro Service

`python-mqtt-subscriber.py` is a microservice running for storing a data in DB

```
[ IoT Device ] ---> MQTT ---> [ Subscriber Service (PHP CLI/Node/Python) ] ---> MongoDB

e.g., device/123/data → { "temp": 32, "hum": 78 }

```

## Development

-   PHP dependencies managed via Composer (composer.json)
-   MongoDB collections and indexes are initialized via init.js
-   MQTT broker configuration in mosquitto.conf
-   Backend code in src

---

## Contributing

Contributions are welcome! To contribute:

1. Fork the repository
2. Create a new branch (`git checkout -b feature/your-feature`)
3. Commit your changes
4. Push to your fork and open a Pull Request

**Guidelines:**

-   Write clear commit messages
-   Follow PSR-12 coding standards for PHP
-   Add tests where possible
-   Document your code

---

## License

This project is licensed under the MIT License.

---

## Contact

For questions or support, open an issue or contact the maintainer.
jeromelpushparaj55@gmail.com
