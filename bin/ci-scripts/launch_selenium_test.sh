#!/usr/bin/env bash
set -v


# stop already running selenium server
for i in $(lsof -t -i :4444)
do
    kill $i
done


# start fake x
/sbin/start-stop-daemon --start --pidfile /tmp/xvfb_99.pid --make-pidfile --background --exec /usr/bin/Xvfb -- :99 -ac -screen 0 1680x1050x16
export DISPLAY=:99


#export PATH=$PATH:./bin
sel_start_date=$(date)
#bin/selenium-server-standalone -debug -enablePassThrough false > selenium.log 2>&1  &
java -jar ${HOME}/bin/selenium-server-standalone.jar -debug -enablePassThrough false  > selenium.log 2>&1  &

set +e
while [ $(netstat -an | grep LISTEN | grep 4444| wc -l) -eq 0 ]
do
    echo $(date) " wait for selenium start... (since " $sel_start_date ")";
    ps -eaf | grep selenium;
    netstat -an | grep LISTEN | grep 4444;
    sleep 10;
done

echo $(date) " it look like selenium is started (waiting since " $sel_start_date ")";

