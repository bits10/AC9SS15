#include <stdio.h>
#include <errno.h>
#include <string.h>
#include <sys/socket.h>
#include <sys/types.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <netdb.h>
#include <unistd.h>
#include <stdlib.h>

#define PORT 50290

void clearLCD(char *host, int line) {
	int sock;
	struct sockaddr_in host_addr;
	char command[1024];
	char buf[1024];
	unsigned int bytes_sent, bytes_recv;
	sock = socket(AF_INET, SOCK_STREAM, 0);
	if (sock == -1) {
		perror("socket()");
	}
	memset(&host_addr, 0, sizeof(host_addr));
	host_addr.sin_family = AF_INET;
	host_addr.sin_port = htons(PORT);
	host_addr.sin_addr.s_addr = inet_addr(host);
	if (connect(sock, (struct sockaddr *) &host_addr, sizeof(struct sockaddr))
			== -1) {
		perror("connect()");
	}
	sprintf(command, "clearlcd %d\r", line);
	bytes_sent = send(sock, command, strlen(command), 0);
	if (bytes_sent == -1) {
		perror("send()");
		exit(EXIT_FAILURE);
	}
	bytes_recv = recv(sock, buf, sizeof(buf), 0);
	if (bytes_recv == -1) {
		perror("recv()");
		exit(EXIT_FAILURE);
	}
	close(sock);
	return;
}

void writeLCD(char *host, int line, char *text) {
	int sock;
	struct sockaddr_in host_addr;
	char command[1024];
	char buf[1024];
	unsigned int bytes_sent, bytes_recv;
	sock = socket(AF_INET, SOCK_STREAM, 0);
	if (sock == -1) {
		perror("socket()");
	}
	memset(&host_addr, 0, sizeof(host_addr));
	host_addr.sin_family = AF_INET;
	host_addr.sin_port = htons(PORT);
	host_addr.sin_addr.s_addr = inet_addr(host);
	if (connect(sock, (struct sockaddr *) &host_addr, sizeof(struct sockaddr))
			== -1) {
		perror("connect()");
	}
	sprintf(command, "writelcd %d.%s\r", line, text);
	bytes_sent = send(sock, command, strlen(command), 0);
	if (bytes_sent == -1) {
		perror("send()");
		exit(EXIT_FAILURE);
	}
	bytes_recv = recv(sock, buf, sizeof(buf), 0);
	if (bytes_recv == -1) {
		perror("recv()");
		exit(EXIT_FAILURE);
	}
	close(sock);
	return;
}

void setPort(char *host, int port, int value) {
	int sock;
	struct sockaddr_in host_addr;
	char command[1024];
	char buf[1024];
	unsigned int bytes_sent, bytes_recv;
	sock = socket(AF_INET, SOCK_STREAM, 0);
	if (sock == -1) {
		perror("socket()");
	}
	memset(&host_addr, 0, sizeof(host_addr));
	host_addr.sin_family = AF_INET;
	host_addr.sin_port = htons(PORT);
	host_addr.sin_addr.s_addr = inet_addr(host);
	if (connect(sock, (struct sockaddr *) &host_addr, sizeof(struct sockaddr))
			== -1) {
		perror("connect()");
	}
	sprintf(command, "setport %d.%d\r", port, value);
	bytes_sent = send(sock, command, strlen(command), 0);
	if (bytes_sent == -1) {
		perror("send()");
		exit(EXIT_FAILURE);
	}
	bytes_recv = recv(sock, buf, sizeof(buf), 0);
	if (bytes_recv == -1) {
		perror("recv()");
		exit(EXIT_FAILURE);
	}
	close(sock);
	return;
}

int getPort(char *host, int port) {
	int sock;
	struct sockaddr_in host_addr;
	char command[1024];
	char buf[1024];
	unsigned int bytes_sent, bytes_recv;
	sock = socket(AF_INET, SOCK_STREAM, 0);
	if (sock == -1) {
		perror("socket()");
	}
	memset(&host_addr, 0, sizeof(host_addr));
	host_addr.sin_family = AF_INET;
	host_addr.sin_port = htons(PORT);
	host_addr.sin_addr.s_addr = inet_addr(host);
	if (connect(sock, (struct sockaddr *) &host_addr, sizeof(struct sockaddr))
			== -1) {
		perror("connect()");
	}
	sprintf(command, "getport %d\r", port);
	bytes_sent = send(sock, command, strlen(command), 0);
	if (bytes_sent == -1) {
		perror("send()");
		exit(EXIT_FAILURE);
	}
	bytes_recv = recv(sock, buf, sizeof(buf), 0);
	int value = (int) buf[0] -48;
	//printf("%d\n",value);
	if (bytes_recv == -1) {
		perror("recv()");
		exit(EXIT_FAILURE);
	}
	close(sock);
	return value;
}

int getOutPort(char *host, int port) {
	char str[10];
	getStatus(host, str);
	if (port == 1) {
		int v = (int) str[8] - 48;
		if (v == 1) {
			return 1;
		} else {
			return 0;
		}
	} else if (port == 2) {
		int v = (int) str[7] - 48;
		if (v == 1) {
			return 1;
		} else {
			return 0;
		}
	} else if (port == 3) {
		int v = (int) str[6] - 48;
		if (v == 1) {
			return 1;
		} else {
			return 0;
		}
	} else if (port == 4) {
		int v = (int) str[5] - 48;
		if (v == 1) {
			return 1;
		} else {
			return 0;
		}
	} else if (port == 5) {
		int v = (int) str[4] - 48;
		if (v == 1) {
			return 1;
		} else {
			return 0;
		}
	} else if (port == 6) {
		int v = (int) str[3] - 48;
		if (v == 1) {
			return 1;
		} else {
			return 0;
		}
	} else if (port == 7) {
		int v = (int) str[2] - 48;
		if (v == 1) {
			return 1;
		} else {
			return 0;
		}
	} else if (port == 8) {
		int v = (int) str[1] - 48;
		if (v == 1) {
			return 1;
		} else {
			return 0;
		}
	}
	return 0;
}

int getADC(char *host, int port) {
	int sock;
	struct sockaddr_in host_addr;
	char command[1024];
	char buf[1024];
	unsigned int bytes_sent, bytes_recv;
	sock = socket(AF_INET, SOCK_STREAM, 0);
	if (sock == -1) {
		perror("socket()");
	}
	memset(&host_addr, 0, sizeof(host_addr));
	host_addr.sin_family = AF_INET;
	host_addr.sin_port = htons(PORT);
	host_addr.sin_addr.s_addr = inet_addr(host);
	if (connect(sock, (struct sockaddr *) &host_addr, sizeof(struct sockaddr))
			== -1) {
		perror("connect()");
	}
	sprintf(command, "getadc %d\r", port);
	bytes_sent = send(sock, command, strlen(command), 0);
	if (bytes_sent == -1) {
		perror("send()");
		exit(EXIT_FAILURE);
	}
	bytes_recv = recv(sock, buf, sizeof(buf), 0);
	int value = atoi(buf);
	if (bytes_recv == -1) {
		perror("recv()");
		exit(EXIT_FAILURE);
	}
	close(sock);
	return value;
}

void getStatus(char *host, char* result) {
	int sock;
	struct sockaddr_in host_addr;
	char command[1024];
	char buf[1024];
	unsigned int bytes_sent, bytes_recv;
	sock = socket(AF_INET, SOCK_STREAM, 0);
	if (sock == -1) {
		perror("socket()");
	}
	memset(&host_addr, 0, sizeof(host_addr));
	host_addr.sin_family = AF_INET;
	host_addr.sin_port = htons(PORT);
	host_addr.sin_addr.s_addr = inet_addr(host);
	if (connect(sock, (struct sockaddr *) &host_addr, sizeof(struct sockaddr))
			== -1) {
		perror("connect()");
	}
	sprintf(command, "getstatus\r\n");
	bytes_sent = send(sock, command, strlen(command), 0);
	if (bytes_sent == -1) {
		perror("send()");
		exit(EXIT_FAILURE);
	}
	bytes_recv = recv(sock, buf, sizeof(buf), 0);
	strcpy(result, buf);
	if (bytes_recv == -1) {
		perror("recv()");
		exit(EXIT_FAILURE);
	}
	close(sock);
	return;
}

void initLCD(char *host) {
	int sock;
	struct sockaddr_in host_addr;
	char command[1024];
	char buf[1024];
	unsigned int bytes_sent, bytes_recv;
	sock = socket(AF_INET, SOCK_STREAM, 0);
	if (sock == -1) {
		perror("socket()");
		exit(EXIT_FAILURE);
	}
	memset(&host_addr, 0, sizeof(host_addr));
	host_addr.sin_family = AF_INET;
	host_addr.sin_port = htons(PORT);
	host_addr.sin_addr.s_addr = inet_addr(host);
	if (connect(sock, (struct sockaddr *) &host_addr, sizeof(struct sockaddr))
			== -1) {
		perror("connect()");
		exit(EXIT_FAILURE);
	}
	sprintf(command, "initlcd\r\n");
	bytes_sent = send(sock, command, strlen(command), 0);
	if (bytes_sent == -1) {
		perror("send()");
		exit(EXIT_FAILURE);
	}
	bytes_recv = recv(sock, buf, sizeof(buf), 0);
	if (bytes_recv == -1) {
		perror("recv()");
		exit(EXIT_FAILURE);
	}
	close(sock);
	return;
}

