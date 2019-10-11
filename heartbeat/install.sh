#!/bin/bash

export HEATRUM_HOME=pwd

# check if user is root   A
if [ $(id -u) != "0" ]; then
    echo "Error: you must be root to run this script:  su or sudo su"
    exit 1
fi

chmod -R 777 ${HEATRUM_HOME}/*

echo 'Input this command name (default heartbeat):'
read comm_name
commName=${comm_name}
if [ -z ${comm_name} ]; then
    commName='heartbeat'
fi

heartbeat="/usr/local/bin/"${commName}
heartbeat_lib="/usr/local/bin/heartbeat-lib"

if [ ! -f ${heartbeat} ]; then

    grep "commName=" ${HEATRUM_HOME}heartbeat.sh > /dev/null
    if [ $? -eq 1 ]; then   # 文件中不存在该字段，则添加
        sed -i "2icommName=${commName}" ${HEATRUM_HOME}heartbeat.sh
    else  # 文件中存在该字段，则修改
        sed -i "s#^commName=.*#commName=${commName}#g"  ${HEATRUM_HOME}heartbeat.sh
    fi

   \cp ${HEATRUM_HOME}"heartbeat.sh" ${heartbeat} -rf
   \cp ${HEATRUM_HOME}"heartbeat-lib" ${heartbeat_lib} -rf
   cd ${heartbeat_lib}

    if [ ${commName}_main -eq 'heartbeat_main' ]
    then
            gcc heartbeat_main.c -o ${commName}_main
    fi
else
   echo ${commName}" is exist !"
fi

echo 'please echo shell to /usr/local/bin/heatbeat-lib/work.sh'




