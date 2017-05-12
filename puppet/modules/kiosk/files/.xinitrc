xset s off &
xset -dpms &
xset s noblank &
ratpoison &
x11vnc -forever >/dev/null &
/home/pi/mouse.sh > /dev/null 2>&1 &
#xterm -geometry 318x82 -bg black -fg grey -e /boot/rdp.sh
#/home/pi/kiosk.sh
/home/pi/kiosk2.sh
