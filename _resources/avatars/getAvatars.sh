#!/bin/bash


output_dir="./images"
mkdir -p $output_dir

# Loop om 50 afbeeldingen te downloaden
for i in $(seq -w 100 200); do
    wget -O "${output_dir}/${i}.jpeg" https://thispersondoesnotexist.com
done

cp images/*.jpeg ../../identity-server/avatars