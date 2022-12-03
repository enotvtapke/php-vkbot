#!/bin/bash
docker container rm -f calendar-vkbot-run
docker build -t calendar-vkbot .
#docker run --name calendar-vkbot-run -d -p 80:80 calendar-vkbot

docker tag calendar-vkbot enotvtapke/personal:calendar-vkbot
docker push enotvtapke/personal:calendar-vkbot