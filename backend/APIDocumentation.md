# API Documentation

This document describes the available REST API endpoints for the IoT Interface Platform backend.

---

## Authentication

All endpoints under `/api/device/*` and `/api/profile` require a valid JWT token in the `Authorization` header:
Authorization: Bearer <token>
---

## Endpoints

### User

#### Register

- **URL:** `/api/register`
- **Method:** `POST`
- **Auth required:** No
- **Body:**
    ```json
    {
      "email": "user@example.com",
      "password": "yourpassword"
    }
    ```
- **Response:**
    - `201 Created` on success
    - `400 Bad Request` on validation error

#### Login

- **URL:** `/api/login`
- **Method:** `POST`
- **Auth required:** No
- **Body:**
    ```json
    {
      "email": "user@example.com",
      "password": "yourpassword"
    }
    ```
- **Response:**
    - `200 OK` with JWT token
    - `401 Unauthorized` on failure

#### Profile

- **URL:** `/api/profile`
- **Method:** `GET`
- **Auth required:** Yes
- **Response:**
    - `200 OK` with user profile data

---

### Device

#### Register Device

- **URL:** `/api/device/register`
- **Method:** `POST`
- **Auth required:** Yes
- **Body:**
    ```json
    {
      "name": "Device Name",
      "type": "sensor",
      "meta": { "location": "Lab 1" }
    }
    ```
- **Response:**
    - `201 Created` with device info

#### List Devices

- **URL:** `/api/device/list`
- **Method:** `GET`
- **Auth required:** Yes
- **Response:**
    - `200 OK` with array of devices

#### Device Status

- **URL:** `/api/device/status`
- **Method:** `GET`
- **Auth required:** Yes
- **Query Parameters:** (optional, e.g., `?id=123`)
- **Response:**
    - `200 OK` with device status

#### Update Device Status

- **URL:** `/api/device/update`
- **Method:** `POST`
- **Auth required:** Yes
- **Body:**
    ```json
    {
      "id": "device_id",
      "status": "active",
      "meta": { "battery": 90 }
    }
    ```
- **Response:**
    - `200 OK` on success

#### Show Device

- **URL:** `/api/device/show/{id}`
- **Method:** `GET`
- **Auth required:** Yes
- **Response:**
    - `200 OK` with device details

#### Delete Device

- **URL:** `/api/device/delete/{id}`
- **Method:** `DELETE`
- **Auth required:** Yes
- **Response:**
    - `204 No Content` on success

---

## Error Responses

- `400 Bad Request` – Invalid input or missing parameters
- `401 Unauthorized` – Invalid or missing JWT token
- `403 Forbidden` – Access denied
- `404 Not Found` – Resource does not exist
- `500 Internal Server Error` – Unexpected error

---

## Notes

- All responses are in JSON format.
- Use an API client (e.g., Postman) for testing.
- JWT tokens expire after a set period; re-authenticate as needed.
