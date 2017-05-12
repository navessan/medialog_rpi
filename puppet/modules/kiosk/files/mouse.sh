#!/bin/bash

while true; do
    eval $(xdotool getmouselocation --shell)

    echo X=$X	Y=$Y

    if (( $X > 1915)) && (( $Y > 1075))
    then
	echo rrrr
        conky &
	P=$!
        echo $P
	sleep 10
        kill $P
    else
        sleep 1
    fi
done
