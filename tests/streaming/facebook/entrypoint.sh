#!/bin/bash

if [ -z "$STREAM_NAME" ] || [ -z "$FB_URL" ]; then
  echo "Missing required environment variables STREAM_NAME or FB_URL"
  exit 1
fi

ffmpeg -re -i "rtmp://rtmp-server/$STREAM_NAME" \
       -c:v copy -c:a aac -strict experimental \
       "$FB_URL" -hide_banner -loglevel error
