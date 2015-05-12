/*
The MIT License (MIT)

Copyright (c) 2015 Sotirios Nikoloutsopoulos sotirisnik@gmail.com

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <fcntl.h>
#include <signal.h>
#include <string.h>
#include <time.h>
#include <sys/ptrace.h>
#include <syscall.h>
#include <sys/time.h>
#include <sys/types.h>
#include <sys/reg.h>
#include <sys/wait.h>
#include <sys/stat.h>
#include <stdarg.h>
#include <errno.h>
#include <pthread.h>
#include <math.h>
#include <ucontext.h>

#define max(a,b) a>b?a:b

int MEMORY_LIMIT = 64;

char RED[] = "\e[1;31m";
char GREEN[] = "\e[1;32m";
char BLUE[] = "\e[1;34m";
char CYAN[] = "\e[1;36m";
char NC[] = "\e[0m";

volatile time_t endtime;
int limit_millis = 3;
int how;
pid_t cpid, w;
int time_out, memory_out, memory, time_limit_ex;
long orig_eax;

struct rusage resources, sres;

#define TIME_LIMIT_EXCEED 3
#define MEM_LIMIT_EXCEED 4
#define RUNTIME_ERROR 5

double passTime;
int TIME_LIMIT, mle;
unsigned long long c;
double runtime;

void alarm_child( ) {

	 printf( "time exceeded : (\n" );
	 kill( cpid, SIGKILL );

}

int statusresult( int status ) {

    if ( status == SIGSEGV || mle == 1 ) {
        printf( "MLE\n" );
        return ( MEM_LIMIT_EXCEED );
    }
    
    if ( status == SIGALRM || status == SIGXCPU || runtime > TIME_LIMIT || time_limit_ex == 1 ) {
        printf( "TLE\n" );
        return ( TIME_LIMIT_EXCEED );
    }
    
	if ( WIFEXITED(status) ) {
        return 0;
    }

    printf("RunTime Error\n" );
    
    return ( RUNTIME_ERROR );
  
}

int find_memusage( ) {

     int fd, data, stack, peak;
  
     char buf[4096];
     
     sprintf( buf, "/proc/%d/status", cpid );
     fd = open( buf, O_RDONLY );
     read( fd, buf, 4095 );
     close(fd);
     buf[4095] = '\0';
     
     data = stack = peak = 0;
     
     char *vm = strstr( buf, "VmData:" );
     
     if ( vm ) {
         sscanf( vm, "%*s %d", &data );
     }
    
     vm = strstr( buf, "VmStk:" );
     
     if ( vm ) {
         sscanf( vm, "%*s %d", &stack );
     }
     
     vm = strstr( buf, "VmPeak:" );
     
     if ( vm ) {
         sscanf( vm, "%*s %d", &peak );
     }

     return ( data + stack );
     
}

volatile int breakflag = 3;

void handle( int sig ) {

     --breakflag;

	 time_limit_ex = 1;
	 kill( cpid, SIGKILL );
     memory = max( memory, find_memusage() );
     
     ++how;

     alarm( 1 );
}

int setlim( int limit, rlim_t val ) {
    struct rlimit lim;
    lim.rlim_cur = val;
    lim.rlim_max = val+1;
    return ( setrlimit( limit, &lim ) );
}

struct rlimit tLimit, mLimit, dataLimit, forkLimit;

void limits_set( ) {

     mLimit.rlim_cur = (MEMORY_LIMIT+1)*1024*1024 + 4329900;
     mLimit.rlim_max = (MEMORY_LIMIT+1)*1024*1024+1 + 4329900;

     forkLimit.rlim_cur = 0;
     forkLimit.rlim_max = 0;

     if ( setrlimit( RLIMIT_AS, &mLimit ) == -1 ) {
         printf( "setrlimit memory limit error!\n" );
     }
     
     if ( setrlimit( RLIMIT_NPROC, &forkLimit ) == -1 ) {
         printf( "setrlimit fork limit error!\n" );
     }
  
}

void limits_check( struct rusage *usage ) {

     double mtime = usage->ru_utime.tv_sec + usage->ru_utime.tv_usec;
     mtime /= 1000000;

     if ( mtime > limit_millis ) {
       time_out = 1;
     }
  
}

void my_alarm_handler( int x ) {
     printf( "time out!\n" );
}

void sighandle( int sig ) {
     printf("Caught signal\n");
	 mle = 1;
	 kill( cpid, SIGKILL );
}

int python27_fl, java_fl, csharp_fl;

int main( int argc, char*argv[] ) {

	if ( argc < 3 ) {
		TIME_LIMIT = 1;
	}else {
		TIME_LIMIT = atoi(argv[2]);
	}

    if ( argc < 4 ) {
		MEMORY_LIMIT = 8;
	}else {
        MEMORY_LIMIT = atoi(argv[3]);
    }

	if ( argc >= 5 ) {
		if ( strcmp( argv[4], "python2.7" ) == 0 ) {
			python27_fl = 1;
		}else if ( strcmp( argv[4], "java" ) == 0 ) {
			java_fl = 1;
		}else if ( strcmp( argv[4], "c#" ) == 0 ) {
			csharp_fl = 1;
		}
	}

    signal( SIGALRM, handle );

	unsigned long long c = TIME_LIMIT + 0.1*TIME_LIMIT;	

    struct sigaction sa;
    memset(&sa, 0, sizeof(struct sigaction));
    sa.sa_handler = sighandle;//my_action;
    sigemptyset(&sa.sa_mask);
    sigaction(SIGSEGV, &sa, NULL);

    struct rusage usage;
    
    int status;
    char *Nargv[] = {NULL};

	char *python_argv[3];
	python_argv[0] = "/usr/bin/python2.7";
	python_argv[1] = argv[1];
	python_argv[2] = 0;

	char *java_argv[2];
	java_argv[0] = argv[1];
	java_argv[1] = 0;

	char *csharp_argv[3];
	csharp_argv[0] = "/usr/bin/mono";
	csharp_argv[1] = argv[1];
	csharp_argv[2] = 0;
    
    struct timeval start, end;
    
    gettimeofday( &start, NULL );

	cpid = fork( );

    if ( cpid == 0 ) {
        
        struct rlimit lim;
        
		limits_set( );
	
		if ( getrlimit( RLIMIT_DATA, &lim ) < 0 ) {
		  //printf( "failed\n" );
		}

		if ( python27_fl == 1 ) {
			execvp( python_argv[0], python_argv );
		}else if ( java_fl == 1 ) {
			execvp( java_argv[0], java_argv );
		}else if ( csharp_fl == 1 ) {
			execvp( csharp_argv[0], csharp_argv );
		}else {
			execvp( argv[1], Nargv );
		}

		perror("execvp");

    }else {
	
		alarm( c );

		waitpid( cpid, &status, 0 );

		alarm(0);

		getrusage( RUSAGE_SELF, &sres );
		getrusage( RUSAGE_CHILDREN, &resources);

		gettimeofday( &end, NULL );	
		getrusage( RUSAGE_CHILDREN, &usage );		
		
		runtime = (resources.ru_utime.tv_sec - sres.ru_utime.tv_sec ) * 1000000
		       + resources.ru_utime.tv_usec - sres.ru_utime.tv_usec;
	  	runtime /= 1000000;
	  	runtime = fabs(runtime);

		FILE*fout = fopen("time_info.txt","w");
		fprintf( fout, "%.3lf\n", runtime );

		fclose(fout);

		chmod( "time_info.txt", 0777 );

		if ( WIFEXITED(status) ) {
			//printf( "exited, status=%s%d%s\n", GREEN, WEXITSTATUS(status), NC );
		}else {
			//printf( "exited, status=%s%d%s\n", RED, WEXITSTATUS(status), NC );
		}

		fout = fopen("exit_status.txt","w");
		fprintf( fout, "%d\n", WEXITSTATUS(status) );
		fclose(fout);
		chmod( "exit_status.txt", 0777 );
	
		if ( WIFSIGNALED(status) ) {
			printf( "exit by signal: %d\n", status, WTERMSIG(status) );
		}
	
		return statusresult( status );
	
    }
  
    return 0;
  
}
