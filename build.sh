#!/bin/bash
docker container rm -f calendar-vkbot-run
docker run --name calendar-vkbot-run -d -p 80:80 calendar-vkbot

docker build -t calendar-vkbot .
docker tag calendar-vkbot enotvtapke/personal:calendar-vkbot
docker push enotvtapke/personal:calendar-vkbot

ssh ubuntu@89.208.87.15 -i "C:\Users\super\.ssh\stupnikov_alexander.pem"