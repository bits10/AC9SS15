﻿/*----------------------------------------------------------------------------
 Copyright:      Radig Ulrich  mailto: mail@ulrichradig.de
 Author:         Radig Ulrich
 Remarks:        
 known Problems: none
 Version:        03.11.2007
 Description:    Webserver Config-File
 Modified:       G. Menke, 05.08.2010

 Dieses Programm ist freie Software. Sie können es unter den Bedingungen der 
 GNU General Public License, wie von der Free Software Foundation veröffentlicht, 
 weitergeben und/oder modifizieren, entweder gemäß Version 2 der Lizenz oder 
 (nach Ihrer Option) jeder späteren Version. 

 Die Veröffentlichung dieses Programms erfolgt in der Hoffnung, 
 daß es Ihnen von Nutzen sein wird, aber OHNE IRGENDEINE GARANTIE, 
 sogar ohne die implizite Garantie der MARKTREIFE oder der VERWENDBARKEIT 
 FÜR EINEN BESTIMMTEN ZWECK. Details finden Sie in der GNU General Public License. 

 Sie sollten eine Kopie der GNU General Public License zusammen mit diesem 
 Programm erhalten haben. 
 Falls nicht, schreiben Sie an die Free Software Foundation, 
 Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, USA. 
------------------------------------------------------------------------------*/

#ifndef _CONFIG_H_
	#define _CONFIG_H_	
	
	//Konfiguration der PORTS (HEX)
	//1=OUTPUT / 0=INPUT

	// PA7=ADCIN4       IN
	// PA6=ADCIN3       IN
	// PA5=ADCIN2       IN
	// PA4=ADCIN1       IN
	// PA3=DIGIN4       IN
	// PA2=DIGIN3       IN
	// PA1=DIGIN2       IN
	// PA0=DIGIN1       IN
	#define OUTA 		0x07

	//Achtung!!!! an PORTB ist der ENC
	//nur ändern wenn man weiß was man macht!

	// PC7=DIGOUT8      OUT
	// PC6=DIGOUT7      OUT
	// PC5=DIGOUT6      OUT
	// PC4=DIGOUT5      OUT
	// PC3=DIGOUT4      OUT
	// PC2=DIGOUT3      OUT
	// PC1=DIGOUT2      OUT
	// PC0=DIGOUT1      OUT
	#define OUTC 		0xFF

    // PD0..PD7: Connectior "EXT.,etc."
	#define OUTD 		0x00

    //Watchdog timer for the ENC2860, resets the stack if timeout occurs
    #define WTT 1200 //Watchdog timer in timer interrupt

    //NTP refresh before stack timeout occurs...
    #define NTP_REFRESH 1140

	//Umrechnung von IP zu unsigned long
	#define IP(a,b,c,d) ((unsigned long)(d)<<24)+((unsigned long)(c)<<16)+((unsigned long)(b)<<8)+a

	//IP des Webservers und des Routers
	#define MYIP		IP(192,168,2,6)
	#define ROUTER_IP	IP(192,168,2,1)

	//Netzwerkmaske
	#define NETMASK		IP(255,255,255,0)
	
    //DHCP-Server
    #define USE_DHCP    0 //1 = DHCP Client on
  
    //DNS-Server
    #define USE_DNS     0 //1 = DNS Client on
    #define DNS_IP      IP(192,168,0,20)
	
	//IP des NTP-Servers z.B. Server 1.de.pool.ntp.org
	#define USE_NTP		0 //1 = NTP Client on
	#define NTP_IP		IP(77,37,6,59)
	
	//Broadcast-Adresse für WOL
	#define USE_WOL			1 //1 = WOL on
	#define WOL_BCAST_IP	IP(192,168,0,255)
	#define WOL_MAC 		{0x00,0x1A,0xA0,0x9C,0xC6,0x0A}
	
	//MAC Adresse des Webservers
	#define MYMAC1	0x00
	#define MYMAC2	0x22
	#define MYMAC3	0xF9
	#define MYMAC4	0x01
	#define MYMAC5	0xD0
	#define MYMAC6	0xB6
	
	//Taktfrequenz
    #define F_CPU 16000000UL	
	
	//Timertakt intern oder extern
	#define EXTCLOCK 0 //0=Intern 1=Externer Uhrenquarz

	//Baudrate der seriellen Schnittstelle
	#define BAUDRATE 9600
	
	//AD-Wandler benutzen?
	#define USE_ADC			1

	//Webserver mit Passwort? (0 == mit Passwort)
	#define HTTP_AUTH_DEFAULT	1
	
	//AUTH String "USERNAME:PASSWORT" max 14Zeichen 
	//für Username:Passwort
	#define HTTP_AUTH_STRING "user:pass"
	
    //Email vesand benutzen? Konfiguration des
    //Emailclient in der Sendmail.h
    #define USE_MAIL        0
    
    //Empfang von Wetterdaten auf der Console (über HTTP_GET)
    #define GET_WEATHER     0
    
    //Commandos und Ausgaben erfolgen über Telnet
    #define CMD_TELNET      0
    
#endif //_CONFIG_H


