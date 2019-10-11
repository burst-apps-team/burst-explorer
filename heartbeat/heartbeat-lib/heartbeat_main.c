#include <stdio.h>
#include <unistd.h>
#include <stdlib.h>
#include <signal.h>
#include <errno.h>

void mon_daemon()
{
    int pid;

    signal(SIGQUIT, SIG_IGN);  
    signal(SIGKILL, SIG_IGN);  
    signal(SIGUSR1, SIG_IGN);  
    signal(SIGUSR2, SIG_IGN);  
    signal(SIGPIPE, SIG_IGN);  
//    signal(SIGALRM, SIG_IGN);  
    signal(SIGTERM, SIG_IGN);
    signal(SIGINT,  SIG_IGN);


    if( (pid = fork()) > 0 )
        exit(0);
    else if( pid < 0 )
        exit(1);

    setsid();

    if( (pid = fork()) > 0 )
        exit(0);
    else if( pid < 0 )
        exit(1);

    umask(0);


   //放入后台运行
    daemon(1, 128);
    //daemon(1, 1);
}

int main(int argc, char **argv)
{
	int ret;
	
	mon_daemon();

	while(1)
	{
		if(!access("/usr/local/bin/heartbeat-lib/worker.sh", F_OK))
		{
			ret = system("sh /usr/local/bin/heartbeat-lib/worker.sh");
			if(ret != 0)
			{
				fprintf(stderr, "run /usr/local/bin/heartbeat-lib/worker.sh: %s\n", strerror(errno));
			}
		}
		else
		{
			fprintf(stderr, "/usr/local/bin/heartbeat-lib/worker.sh not exist!\n");
		}
		sleep(1);
	}
		
	return 0;
}

// gcc heartbeat_main.c -o heartbeat_main


