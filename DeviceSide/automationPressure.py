#!/usr/bin/env python
import pymodbus
import serial
from pymodbus.pdu import ModbusRequest
from pymodbus.client.sync import ModbusSerialClient as ModbusClient
from pymodbus.transaction import ModbusRtuFramer
import sys
import os

#from datetime import datetime
import datetime
import glob
import MySQLdb
from time import strftime

import time                             # time used for delays
import httplib, urllib                  # http and url libs used for HTTP $
import socket
import json
import ctypes
import RPi.GPIO as gpio
import RPi.GPIO as GPIO
gpio.setwarnings(False)
gpio.setmode(gpio.BOARD)
gpio.setup(36,gpio.OUT)
gpio.output(36,0)

SPI_CLK = 23
SPI_MISO = 21
SPI_MOSI = 19
SPI_CS = 24
FLOW_SENSOR = 16
GPIO.setup(FLOW_SENSOR, GPIO.IN, pull_up_down = GPIO.PUD_UP)

global start_counter
start_counter = 1
global count
count = 0
def countPulse(ch):
   global count
   if start_counter == 1:
      count = count+1
      print count
      flow = count / (60 * 7.5)
      print(flow)
GPIO.add_event_detect(FLOW_SENSOR, GPIO.FALLING, callback=countPulse)

def setup():
    GPIO.setmode(GPIO.BOARD)
    GPIO.setup(SPI_MOSI, GPIO.OUT)
    GPIO.setup(SPI_MISO, GPIO.IN)
    GPIO.setup(SPI_CLK, GPIO.OUT)
    GPIO.setup(SPI_CS, GPIO.OUT, initial = GPIO.HIGH)

def readADC(channel):
    LOW = GPIO.LOW
    HIGH = GPIO.HIGH

    if channel > 7 or channel < 0: # illegal channel
        return -1

    GPIO.output(SPI_CLK, LOW) # Start with clock low
    GPIO.output(SPI_CS, LOW)  # Enable chip

    # Send command
    control = channel # control register
    control |= 0b00011000  # Start bit at b4,
                           # Single-ended bit at b3
                           # Channel number (b2, b1, b0)
    for i in range(5):  # Send bit pattern starting from bit b4
        if control & 0x10:  # Check bit b4
            GPIO.output(SPI_MOSI, HIGH)
        else:
            GPIO.output(SPI_MOSI, LOW)
        control <<= 1 # Shift left
        GPIO.output(SPI_CLK, HIGH) # Clock pulse
        GPIO.output(SPI_CLK, LOW)

    # Get reply
    data = 0
    for i in range(11):  # Read 11 bits and insert at right
        GPIO.output(SPI_CLK, HIGH)  # Clock pulse
        GPIO.output(SPI_CLK, LOW)
        data <<= 1  # Shift left, LSB = 0
        if GPIO.input(SPI_MISO): # If high, LSB = 1,
            data |= 0x1

    GPIO.output(SPI_CS, HIGH) # Disable chip
    return data
setup()
channel1 = 0
channel2 = 1
t = 0

#############################################################################################

#client=ModbusClient(method='rtu',port='/dev/ttyUSB0',baudrate=9600,timeout=1,parity='N')
#client.connect()

db = MySQLdb.connect(host="localhost", user="root",passwd="root", db="temp")
cur = db.cursor()
print "Connection success"
#response=client.read_holding_registers(0004,6,unit=1) #(starting addr, no of registers to be read, slave addr)
#responseFault=client.read_holding_registers(0022,14,unit=1)

with open('/var/www/html/Version0/temp.json')as f:
    data = json.loads(f.read())

print data['serial']
serialNum = data['serial']

print data['machineNum']
machineNum = data['machineNum']

print data['companyName']
companyName = data['companyName']

print data['personName']
personName = data['personName']

######################### Read MOD-BUS registers ############################
#bat_x = response.registers[0]
bat_temp = 24.4#(ctypes.c_int16(bat_x).value)/10.0
print bat_temp

#milk_y = response.registers[1]
milk_temp = 28.8#(ctypes.c_int16(milk_y).value)/10.0
print milk_temp

aux_temp = "11"#response.registers[2]/10.0
comp_curr = 10.6#response.registers[5]/10.0
volt = 239
power = (volt*comp_curr)/1000
print "Power: ",power
HP=0
LP=0
fault = 4#responseFault.registers[12]
print "Fault register reading: ",fault
if fault == 4:
    HP = 1
elif fault == 5:
    LP = 1
else:
    HP = 0
    LP = 0
#############################################################################

#client.close()
val1 = 0.00
val2 = 0.00
while True:
    Milk = milk_temp
    Ambient = aux_temp
    Battery = bat_temp
    Comp = comp_curr

    val1 = 0.00      #HP
    M1 = 108.77
    dp =0
    mylist1 = [0,0,0,0,0,0,0,0,0,0]
    sum1 = 0.0
    avg1 = 0.0
    for i in range(0,10):
        mylist1[i]= readADC(channel1)
    for j in range(0,10):
        sum1 += mylist1[j]
    avg1 = sum1/10
    value1 = avg1*(5/1023.00)
    time.sleep(1)
    dp = (M1*value1)-(M1-10)
    dp +=15
    print ("HP: " "%.1f" %dp)


    val2 = 0.00	#LP
    M2 = 65.25
    sp =0
    mylist2 = [0,0,0,0,0,0,0,0,0,0]
    sum2 = 0.0
    avg2 = 0.0
    for i in range(0,10):
        mylist2[i]= readADC(channel2)
    for j in range(0,10):
        sum2 += mylist2[j]
    avg2 = sum2/10
    value2 = avg2*(5/1023.00)
#    print ("voltage: " "%.2f" %value)
    time.sleep(1)
    sp = (M2*value2)-(M2-15)
    #pressure2 +=10
    print ("LP: " "%.1f" %sp)

##### FLOW #######
    print "start counter value: ",start_counter
    start_counter = 1
    time.sleep(1)
    start_counter = 0
    flow = (count * 60 *2.25 /1000)
    print "flow: %.3f" %flow
    count = 0
##################

    print "Milk Temp: ",Milk
    print "Aux Temp: ",Ambient
    print "Battery Temp: ",Battery
    print "Comp curr: ",Comp
    print "HP in while : ",HP
    print "LP in while : ",LP
    deltaT = Milk-Battery
    print "Temp diff: ",deltaT
    cp = 2.986
    mass = 0.4021#(flow/60)*0.863
    heat = mass*cp*deltaT
    cop = heat/power 
    print "COP Dummy Value: ",cop
    datetimeWrite = (time.strftime("%Y-%m-%d "))
    time_ = (time.strftime("%H:%M:%S"))

    if 24.8 <= Milk <=25.2:
        print "In milk"
        gpio.output(36,1)
        time.sleep(3)
        gpio.output(36,0)
    elif Battery == -10.0:
        print "In battery"
        gpio.output(36,1)
        time.sleep(6)
        gpio.output(36,0)
    else:
        print "Out of buzzer condition"
    print datetimeWrite
    print time_
    if Milk <= 29.0 or Battery==-10.0:
        print "In query"
        sql = ("""INSERT INTO compData(date,time,SerialNo,machineNo,Milk_temp,Bat_temp,suction,discharge,Aux_temp,Comp,HP,LP,company,person,flow,KWH,COP) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)""",(datetimeWrite,time_,serialNum,machineNum,Milk,Battery,sp,dp,Ambient,Comp,HP,LP,companyName,personName,flow,power,cop))  
    try:
        print "Writing to database..."
        # Execute the SQL command
        cur.execute(*sql)
        # Commit your changes in the database
        db.commit()
        print "Write Complete"

    except:
        # Rollback in case there is any error
        db.rollback()
        print "Failed writing to database"

    cur.close()
    db.close()
    break


