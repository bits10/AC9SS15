﻿/*------------------------------------------------------------------------------
 Copyright:      Radig Ulrich  mailto: mail@ulrichradig.de
 Author:         Radig Ulrich
 Remarks:        
 known Problems: none
 Version:        31.12.2007
 Description:    Analogeingänge Abfragen
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
#include "config.h"
#include "analog.h"
#include <avr/io.h>
#include <avr/interrupt.h>
#include <avr/pgmspace.h>
#include "httpd.h" //für Variablen Array

#if USE_ADC
volatile unsigned char channel = 0;

//------------------------------------------------------------------------------
//
void ADC_Init(void)
{ 
	ADMUX = (1<<REFS0);		// AVCC ist Referenz
	//Free Running Mode, Division Factor 128, Interrupt on
	ADCSRA=(1<<ADEN)|(1<<ADSC)|(1<<ADATE)|(1<<ADPS2)|(1<<ADPS1)|(1<<ADPS0)|(1<<ADIE);
}

//------------------------------------------------------------------------------
//
ISR (ADC_vect)
{
    ANALOG_OFF; //ADC OFF

	var_array[channel++] = ADC;

	// hier können Umrechnungen für die einzelnen AD-Kanäle eingebaut werden:
	// z.B. für AD-Kanal 0 die Angabe in %, wobei 0=0% und 1023=100%
	// AD-Kanal 0...3 entsprechen [channel-1]=4..7
	if ((channel-1)==4) var_array[channel-1] = var_array[channel-1]*10;			// 0...10240
	if ((channel-1)==4) var_array[channel-1] = var_array[channel-1]/1024;			// 0...10
	if ((channel-1)==4) var_array[channel-1] = var_array[channel-1]*10;			// 0...100

	//usart_write("Kanal(%i)=%i\n\r",(channel-1),var_array[(channel-1)]);
	if (channel > 7) channel = 0;
    ADMUX =(1<<REFS0) + channel;
    //ANALOG_ON;//ADC ON
}

#endif //USE_ADC


