#!/bin/bash

if [ -z "$STREAM_NAME" ]; then
  echo "Missing required environment variable STREAM_NAME"
  exit 1
fi

mkdir -p "/mnt/livestream/hls/$STREAM_NAME"

ffmpeg -re -i "rtmp://rtmp-server/$STREAM_NAME" \
       -c:v libx264 -vf scale=-2:720 -r 30 -g 60 -b:v 2400k -maxrate 3000k -bufsize 6000k \
       -c:a aac -b:a 128k -f hls -hls_time 2 -hls_playlist_type event \
       "/mnt/livestream/hls/$STREAM_NAME/index.m3u8"
