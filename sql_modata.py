#!/usr/bin/env python
import pymodbus
import serial
from pymodbus.pdu import ModbusRequest
from pymodbus.client.sync import ModbusSerialClient as ModbusClient
from pymodbus.transaction import ModbusRtuFramer

import os
#from datetime import datetime
import datetime
import glob
import MySQLdb
from time import strftime

import time                             # time used for delays
import httplib, urllib                  # http and url libs used for HTTP $
import socket

server = "data.sparkfun.com"            # base URL of your feed
publicKey = "GEGLXdO3dnHDKar5x2xW"
privateKey = "Nnlxo7DK7Vij456RVJVD"
fields = ["aux_temp","bat_temp","comp_curr","hp","lp","milk_temp"]    # Your feed's data fields


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
    time_ = (time.strftime("%H:%M:%S"))
    #time_ =  "%s-%s-%s" % (datetime.now().hour,datetime.now().minute, datetime.now().second)  
    #datestamp =  "%s-%s-%s" % (datetime.now().year,datetime.now().month, datetime.now().day)
	
    print datetimeWrite
    print time_
    if Milk<=25 or Battery==-10:
        sql = ("""INSERT INTO tempLog(date,time, Bat_temp, Milk_temp, Aux_temp, Comp_curr,HP,LP) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)""",(datetimeWrite,time_,Battery,Milk,Ambient,Comp,HP,LP))  
    try:
        print "Writing to database..."
        # Execute the SQL command
        cur.execute(*sql)
        # Commit your changes in the database
        db.commit()
        print "Write Complete"
	data = {} # Create empty set, then fill in with our three fields:
                # Field 0, light, gets the local time:
	data[fields[0]] = aux_temp
                # Field 1, switch, gets the switch status:
	data[fields[1]] = bat_temp
	data[fields[2]] = comp_curr
	data[fields[3]] ="0"# hp
	data[fields[4]] ="0"# lp
	data[fields[5]] = milk_temp
	params = urllib.urlencode(data)

	headers = {} # start with an empty set
                # These are static, should be there every time:
	headers["Content-Type"] = "application/x-www-form-urlencoded"
	headers["Connection"] = "close"
	headers["Content-Length"] = len(params) # length of data
	headers["Phant-Private-Key"] = privateKey # private key he

	c = httplib.HTTPConnection(server)

	c.request("POST", "/input/" + publicKey + ".txt", params, headers)
	r = c.getresponse() # Get the server's response and print it
	print r.status, r.reason

	time.sleep(1) # delay for aseco

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

