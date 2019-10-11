#!/bin/bash

start()
{
        PROCPID=`ps -ef|grep ${commName}_main|grep -v grep|grep -v "su"|grep -v tail |grep -v vi|grep -v admin.sh|awk '{print $2}'`
        if [ "$PROCPID" != "" ]
        then
            echo "${commName} is already running pid[$PROCPID]\n"
        else
            echo "Starting ${commName} ..."
            # cd ${HEATRUM_HOME_LIB}
    		nohup ${HEATRUM_HOME_LIB}/${commName}_main > /dev/null 2>&1 &
        fi
}

stop()
{
        echo "Stoping ${commName} ..."
        for proc in `ps -ef|grep ${commName}_main |grep -v grep|grep -v "su"|grep -v tail |grep -v vi|awk '{print $2}'`
        do
			kill -9 $proc
        done

}

show()
{
        echo "=========================================[${commName}(${commName}_main) process]==========================================="
        ps -ef|grep ${commName}_main|grep -v "grep"|grep -v "su"|grep -v tail|grep -v vi
        PROCPID=`ps -ef|grep ${commName}_main|grep -v grep|grep -v "su"|grep -v tail |grep -v vi|grep -v admin.sh|awk '{print $2}'`
        if [ "$PROCPID" != "" ]
        then
            echo "${commName} (${commName}_main) is already running pid[$PROCPID]\n"
        else
            echo "${commName} is not run !"
        fi
        echo "================================================================================================================="
}
restart()
{
        stop
        start
}

