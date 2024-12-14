#!/bin/bash

if [ -z "$STREAM_NAME" ] || [ -z "$YT_KEY" ]; then
  echo "Missing required environment variables STREAM_NAME or YT_KEY"
  exit 1
fi

ffmpeg -re -i "rtmp://rtmp-server/$STREAM_NAME" \
       -c:v copy -c:a aac -strict experimental \
       "rtmp://a.rtmp.youtube.com/live2/$YT_KEY" -hide_banner -loglevel error
