#!/bin/bash
commName=heartbeat

export HEATRUM_BIN_HOME="/usr/local/bin/"
export HEATRUM_HOME="/usr/local/bin/"${commName}
export HEATRUM_HOME_LIB="/usr/local/bin/heartbeat-lib"

source ${HEATRUM_HOME_LIB}/heartbeat.sh

if [ "$#" -eq 2 ]
then
        echo "How to run command"
        echo "Usge: ${commName} start|stop|restart|show to start|stop|restart|show command"
        echo "==========================="
        echo "This command will execute once per second"
fi

case "$1" in
        start) start
        ;;
        stop) stop
        ;;
        restart) restart
        ;;
        show) show
        ;;
        *) echo "Usge:
        1.Command to introduce:
         ${commName} start                    start heartbeat
         ${commName} stop                     stop heartbeat
         ${commName} restart                  restart heartbeat
         ${commName} show                     show heartbeat status

        2.Timing task addition introduction
        vi ${HEATRUM_HOME_LIB}/worker.sh
        Add the task you want to execute inside, and execute  ${commName} restart

        "
        ;;
esac

