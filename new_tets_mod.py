

import pymodbus
import serial
from pymodbus.pdu import ModbusRequest
from pymodbus.client.sync import ModbusSerialClient as ModbusClient 
#initialize a serial RTU client instance
from pymodbus.transaction import ModbusRtuFramer

#!/usr/bin/env python

# from pymodbus.client.sync import ModbusSerialClient as ModbusClient
client=ModbusClient(method='rtu',port='/dev/ttyUSB0',baudrate=9600,timeout=1,parity='N')
client.connect()

response=client.read_holding_registers(0004,6,unit=1)

print response.registers[0:]
print "Battery Temp:",response.registers[0]/10.0
print "Milk Temp:",response.registers[1]/10.0
print "Aux. Temp:",response.registers[2]/10.0
#print "Batt. Voltage:",response.registers[3]/1.0
print "Com Curr:", response.registers[5]/1.0


client.close()
