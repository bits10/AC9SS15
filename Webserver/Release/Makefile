################################################################################
# Automatically-generated file. Do not edit!
################################################################################

SHELL := cmd.exe
RM := rm -rf

USER_OBJS :=

LIBS := 
PROJ := 

O_SRCS := 
C_SRCS := 
S_SRCS := 
S_UPPER_SRCS := 
OBJ_SRCS := 
ASM_SRCS := 
PREPROCESSING_SRCS := 
OBJS := 
OBJS_AS_ARGS := 
C_DEPS := 
C_DEPS_AS_ARGS := 
EXECUTABLES := 
OUTPUT_FILE_PATH :=
OUTPUT_FILE_PATH_AS_ARGS :=
AVR_APP_PATH :=$$$AVR_APP_PATH$$$
QUOTE := "
ADDITIONAL_DEPENDENCIES:=
OUTPUT_FILE_DEP:=
LIB_DEP:=

# Every subdirectory with source files must be described here
SUBDIRS :=  \
../networkcard


# Add inputs and outputs from these tool invocations to the build variables 
C_SRCS +=  \
../analog.c \
../artnet.c \
../base64.c \
../cmd.c \
../dhcpc.c \
../dnsc.c \
../httpd.c \
../http_get.c \
../lcd.c \
../main.c \
../networkcard/enc28j60.c \
../networkcard/rtl8019.c \
../ntp.c \
../sendmail.c \
../stack.c \
../telnetd.c \
../timer.c \
../udp_lcd.c \
../usart.c \
../wol.c


PREPROCESSING_SRCS += 


ASM_SRCS += 


OBJS +=  \
analog.o \
artnet.o \
base64.o \
cmd.o \
dhcpc.o \
dnsc.o \
httpd.o \
http_get.o \
lcd.o \
main.o \
networkcard/enc28j60.o \
networkcard/rtl8019.o \
ntp.o \
sendmail.o \
stack.o \
telnetd.o \
timer.o \
udp_lcd.o \
usart.o \
wol.o

OBJS_AS_ARGS +=  \
analog.o \
artnet.o \
base64.o \
cmd.o \
dhcpc.o \
dnsc.o \
httpd.o \
http_get.o \
lcd.o \
main.o \
networkcard/enc28j60.o \
networkcard/rtl8019.o \
ntp.o \
sendmail.o \
stack.o \
telnetd.o \
timer.o \
udp_lcd.o \
usart.o \
wol.o

C_DEPS +=  \
analog.d \
artnet.d \
base64.d \
cmd.d \
dhcpc.d \
dnsc.d \
httpd.d \
http_get.d \
lcd.d \
main.d \
networkcard/enc28j60.d \
networkcard/rtl8019.d \
ntp.d \
sendmail.d \
stack.d \
telnetd.d \
timer.d \
udp_lcd.d \
usart.d \
wol.d

C_DEPS_AS_ARGS +=  \
analog.d \
artnet.d \
base64.d \
cmd.d \
dhcpc.d \
dnsc.d \
httpd.d \
http_get.d \
lcd.d \
main.d \
networkcard/enc28j60.d \
networkcard/rtl8019.d \
ntp.d \
sendmail.d \
stack.d \
telnetd.d \
timer.d \
udp_lcd.d \
usart.d \
wol.d

OUTPUT_FILE_PATH +=NewStack_1_2_9.elf

OUTPUT_FILE_PATH_AS_ARGS +=NewStack_1_2_9.elf

ADDITIONAL_DEPENDENCIES:=

OUTPUT_FILE_DEP:= ./makedep.mk

LIB_DEP+= 

# AVR32/GNU C Compiler









































./%.o: .././%.c
	@echo Building file: $<
	@echo Invoking: AVR/GNU C Compiler : 4.8.1
	$(QUOTE)C:\Program Files (x86)\Atmel\Atmel Toolchain\AVR8 GCC\Native\3.4.1051\avr8-gnu-toolchain\bin\avr-gcc.exe$(QUOTE)  -x c -funsigned-char -funsigned-bitfields -Os -ffunction-sections -fdata-sections -fpack-struct -fshort-enums -Wall -mmcu=atmega32 -c -std=gnu99 -MD -MP -MF "$(@:%.o=%.d)" -MT"$(@:%.o=%.d)" -MT"$(@:%.o=%.o)"   -o "$@" "$<" 
	@echo Finished building: $<
	

networkcard/%.o: ../networkcard/%.c
	@echo Building file: $<
	@echo Invoking: AVR/GNU C Compiler : 4.8.1
	$(QUOTE)C:\Program Files (x86)\Atmel\Atmel Toolchain\AVR8 GCC\Native\3.4.1051\avr8-gnu-toolchain\bin\avr-gcc.exe$(QUOTE)  -x c -funsigned-char -funsigned-bitfields -Os -ffunction-sections -fdata-sections -fpack-struct -fshort-enums -Wall -mmcu=atmega32 -c -std=gnu99 -MD -MP -MF "$(@:%.o=%.d)" -MT"$(@:%.o=%.d)" -MT"$(@:%.o=%.o)"   -o "$@" "$<" 
	@echo Finished building: $<
	



# AVR32/GNU Preprocessing Assembler



# AVR32/GNU Assembler




ifneq ($(MAKECMDGOALS),clean)
ifneq ($(strip $(C_DEPS)),)
-include $(C_DEPS)
endif
endif

# Add inputs and outputs from these tool invocations to the build variables 

# All Target
all: $(OUTPUT_FILE_PATH) $(ADDITIONAL_DEPENDENCIES)

$(OUTPUT_FILE_PATH): $(OBJS) $(USER_OBJS) $(OUTPUT_FILE_DEP) $(LIB_DEP)
	@echo Building target: $@
	@echo Invoking: AVR/GNU Linker : 4.8.1
	$(QUOTE)C:\Program Files (x86)\Atmel\Atmel Toolchain\AVR8 GCC\Native\3.4.1051\avr8-gnu-toolchain\bin\avr-gcc.exe$(QUOTE) -o$(OUTPUT_FILE_PATH_AS_ARGS) $(OBJS_AS_ARGS) $(USER_OBJS) $(LIBS) -Wl,-Map="NewStack_1_2_9.map" -Wl,--start-group -Wl,-lm  -Wl,--end-group -Wl,--gc-sections -mmcu=atmega32  
	@echo Finished building target: $@
	"C:\Program Files (x86)\Atmel\Atmel Toolchain\AVR8 GCC\Native\3.4.1051\avr8-gnu-toolchain\bin\avr-objcopy.exe" -O ihex -R .eeprom -R .fuse -R .lock -R .signature -R .user_signatures  "NewStack_1_2_9.elf" "NewStack_1_2_9.hex"
	"C:\Program Files (x86)\Atmel\Atmel Toolchain\AVR8 GCC\Native\3.4.1051\avr8-gnu-toolchain\bin\avr-objcopy.exe" -j .eeprom  --set-section-flags=.eeprom=alloc,load --change-section-lma .eeprom=0  --no-change-warnings -O ihex "NewStack_1_2_9.elf" "NewStack_1_2_9.eep" || exit 0
	"C:\Program Files (x86)\Atmel\Atmel Toolchain\AVR8 GCC\Native\3.4.1051\avr8-gnu-toolchain\bin\avr-objdump.exe" -h -S "NewStack_1_2_9.elf" > "NewStack_1_2_9.lss"
	"C:\Program Files (x86)\Atmel\Atmel Toolchain\AVR8 GCC\Native\3.4.1051\avr8-gnu-toolchain\bin\avr-objcopy.exe" -O srec -R .eeprom -R .fuse -R .lock -R .signature -R .user_signatures "NewStack_1_2_9.elf" "NewStack_1_2_9.srec"
	"C:\Program Files (x86)\Atmel\Atmel Toolchain\AVR8 GCC\Native\3.4.1051\avr8-gnu-toolchain\bin\avr-size.exe" "NewStack_1_2_9.elf"
	
	





# Other Targets
clean:
	-$(RM) $(OBJS_AS_ARGS) $(EXECUTABLES)  
	-$(RM) $(C_DEPS_AS_ARGS)   
	rm -rf "NewStack_1_2_9.elf" "NewStack_1_2_9.a" "NewStack_1_2_9.hex" "NewStack_1_2_9.lss" "NewStack_1_2_9.eep" "NewStack_1_2_9.map" "NewStack_1_2_9.srec" "NewStack_1_2_9.usersignatures"
	