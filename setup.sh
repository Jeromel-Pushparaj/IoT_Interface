#!/bin/bash

systemctl stop mosquitto

# to start the backend
docker compose down
docker compose up -d 

# to start the frontend 
cd frontend
npm run dev