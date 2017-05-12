#!/bin/bash

cd /home/pi

url=$(cat url.txt)

#echo $url

/home/pi/uzbl.sh &

sleep 20
echo set show_status=0 > /tmp/$(ls /tmp | grep uzbl)

#main loop
while true
do
    res=$(wget $url --quiet -O - |grep jpg -c)
    echo reload > /tmp/$(ls /tmp | grep uzbl)

    if [ "$res" -gt "0" ]; then
	echo "page"
	sleep 60
    else
        echo "video"
      xterm -fullscreen -fg black -bg black -e omxplayer -o hdmi /home/pi/vidmc.mp4
    fi
done
