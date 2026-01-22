#!/usr/bin/env bash
set -euo pipefail

#####################################
# CONFIGURATION
#####################################

# Glob for audio files
AUDIO_GLOB="./pub/*.mp3"

# Glob for image files
IMAGE_GLOB="../traumapacker/covers/*.jpg"

# YouTube stream key
YOUTUBE_STREAM_KEY="qs86-b422-zh62-xe53-09xp"

# YouTube RTMP endpoint
YOUTUBE_RTMP_URL="rtmp://a.rtmp.youtube.com/live2"

#####################################
# FUNCTIONS
#####################################

pick_random_file() {
  shopt -s nullglob
  local files=($1)
  shopt -u nullglob

  if [ ${#files[@]} -eq 0 ]; then
    echo "No files found for pattern: $1" >&2
    exit 1
  fi

  printf '%s\n' "${files[RANDOM % ${#files[@]}]}"
}

#####################################
# MAIN LOOP
#####################################

while true; do
  AUDIO_FILE=$(pick_random_file "$AUDIO_GLOB")
  IMAGE_FILE=$(pick_random_file "$IMAGE_GLOB")

  echo "Streaming:"
  echo "  Audio: $AUDIO_FILE"
  echo "  Image: $IMAGE_FILE"

    ffmpeg \
      -re \
      -loop 1 \
      -i "$IMAGE_FILE" \
      -i "$AUDIO_FILE" \
      -c:v libx264 \
      -preset veryfast \
      -tune stillimage \
      -pix_fmt yuv420p \
      -r 30 \
      -g 120 \
      -keyint_min 120 \
      -sc_threshold 0 \
      -b:v 2500k \
      -maxrate 2500k \
      -bufsize 5000k \
      -vf "scale=1280:720,format=yuv420p" \
      -c:a aac \
      -b:a 160k \
      -ar 44100 \
      -shortest \
      -f flv \
      "$YOUTUBE_RTMP_URL/$YOUTUBE_STREAM_KEY"

    ffmpeg \
      -re \
      -loop 1 \
      -i "$IMAGE_FILE" \
      -i "$AUDIO_FILE" \
      -c:v libx264 \
      -preset veryfast \
      -tune stillimage \
      -pix_fmt yuv420p \
      -r 30 \
      -g 120 \
      -keyint_min 120 \
      -sc_threshold 0 \
      -c:a aac \
      -b:a 160k \
      -ar 44100 \
      -shortest \
      -f flv \
      "$YOUTUBE_RTMP_URL/$YOUTUBE_STREAM_KEY"

  echo "Stream ended. Selecting next track..."
  sleep 2
done
