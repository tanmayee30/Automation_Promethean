#!/usr/bin/env python
import pymodbus
import serial
from pymodbus.pdu import ModbusRequest
from pymodbus.client.sync import ModbusSerialClient as ModbusClient
from pymodbus.transaction import ModbusRtuFramer

import os
import time
import datetime
import glob
import MySQLdb
from time import strftime

# from pymodbus.client.sync import ModbusSerialClient as ModbusClient
client=ModbusClient(method='rtu',port='/dev/ttyUSB0',baudrate=9600,timeout=1,parity='N')
client.connect()

db = MySQLdb.connect(host="localhost", user="root",passwd="root", db="temp_database")
cur = db.cursor()

response=client.read_holding_registers(0004,6,unit=1)

bat_temp = response.registers[0]/10.0
milk_temp = response.registers[1]/10.0
aux_temp = response.registers[2]/10.0
comp_curr = response.registers[5]/10.0
client.close()

while True:
    Milk = milk_temp;
    Ambient = aux_temp;
    Battery = bat_temp;
    Comp = comp_curr;
    HP = 1;
    LP = 1;
    print Milk
    print Ambient
    print Battery
    print Comp
    datetimeWrite = (time.strftime("%Y-%m-%d "))
    time = time.strftime("%H:%M:%S")
    print datetimeWrite
    print time
    if Milk<=25 or Battery==-10:
        sql = ("""INSERT INTO tempLog(date,time, Bat_temp, Milk_temp, Aux_temp, Comp_curr,HP,LP) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)""",(datetimeWrite,time,Battery,Milk,Ambient,Comp,HP,LP))  
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


#print response.registers[0:]
#print "Battery Temp:",bat_temp
#print "Milk_temp:",milk_temp
#print "Aux. Temp:",aux_temp
#print "Comp curr:",comp_curr

