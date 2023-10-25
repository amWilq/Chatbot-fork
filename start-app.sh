#!/bin/bash

# Check if the image exists
docker image inspect chatbot-goldenphp:latest > /dev/null 2>&1

# $? is a special variable that holds the exit code of the last command executed
if [ $? -ne 0 ]; then
  echo "Image does not exist, building..."
  docker-compose build goldenphp
fi

# Start the other services IF LOGS DONT NEEDED ADD FLAG -d
docker-compose up backend frontend