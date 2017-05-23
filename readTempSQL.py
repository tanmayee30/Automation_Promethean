#!/usr/bin/env python
import os
import time
import datetime
import glob
import MySQLdb
from time import strftime
 
 
# Variables for MySQL
db = MySQLdb.connect(host="localhost", user="root",passwd="root", db="temp_database")
cur = db.cursor()
 
 
while True:
    Milk = 5.98;
    Ambient = 28.9;
    Battery = 30.65;
    Comp = 13.8;
    print Milk
    datetimeWrite = (time.strftime("%Y-%m-%d ") + time.strftime("%H:%M:%S"))
    print datetimeWrite
    sql = ("""INSERT INTO tempLog (datetime, Milk_temp, Ambient_temp, Battery_temp, Comp_current) VALUES (%s,%s,%s,%s,%s)""",(datetimeWrite,Milk,Ambient,Battery,Comp))

#    sql = ("""INSERT INTO tempLog (datetime) VALUES (%s,%s)""",(datetimeWrite,temp))
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
