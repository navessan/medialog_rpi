
#.bashrc
file { "/home/pi/.bashrc":
    mode => 755,
    owner => 'pi',
    group => 'pi',
    source => 'puppet:///modules/kiosk/.bashrc',
}

#.conkyrc
file { "/home/pi/.conkyrc":
    mode => 644,
    owner => 'pi',
    group => 'pi',
    source => 'puppet:///modules/kiosk/.conkyrc',
}

#.xinitrc
file { "/home/pi/.xinitrc":
    mode => 644,
    owner => 'pi',
    group => 'pi',
    source => 'puppet:///modules/kiosk/.xinitrc',
}

#kiosk2.sh
file { "/home/pi/kiosk2.sh":
    mode => 755,
    owner => 'pi',
    group => 'pi',
    source => 'puppet:///modules/kiosk/kiosk2.sh',
}

#mouse.sh
file { "/home/pi/mouse.sh":
    mode => 755,
    owner => 'pi',
    group => 'pi',
    source => 'puppet:///modules/kiosk/mouse.sh',
}

#uzbl.conf
file { "/home/pi/uzbl.conf":
    mode => 644,
    owner => 'pi',
    group => 'pi',
    source => 'puppet:///modules/kiosk/uzbl.conf',
}

#uzbl.sh
file { "/home/pi/uzbl.sh":
    mode => 755,
    owner => 'pi',
    group => 'pi',
    source => 'puppet:///modules/kiosk/uzbl.sh',
}

#vidmc.mp4
file { "/home/pi/vidmc.mp4":
    mode => 644,
    owner => 'pi',
    group => 'pi',
    source => 'puppet:///modules/kiosk/vidmc.mp4',
}
