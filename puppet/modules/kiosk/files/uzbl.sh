#!/bin/bash

url=$(cat url.txt)

#echo $url

#sleep 10
while true; do
    rm -f /tmp/uzbl*
    uzbl -u $url -c /home/pi/uzbl.conf
#    sleep 20
#    echo set show_status=0 > /tmp/$(ls /tmp | grep uzbl)
done
