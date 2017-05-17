Basic workflow of automation testbed project:

1. Measure following parameters from control panel:
   * Milk Temperature
   * Battery Temperature
   * Ambient Temperature
   * Compressor run hours
   * Date and time stamp

2. Log this data into database.(MySQL)

3. setup the server on Raspberry pi which will be running locally. Give access of dB to the server.

4. Display that data into table format over web page.
   * Update Interval : one minute
   * Add calender to the web page and add one button which will enable report generation in excel.
