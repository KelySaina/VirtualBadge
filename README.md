# VirtualBadge
A simple verification app for C3LF members.

### Overview
The members will register themselves then get a QR Code

The QR Code will be scanned using a third party app to get the info

The info from the QR Code will be verified against the database

### Setup
Clone the repository
` git clone https://github.com/KelySaina/VirtualBadge.git `

Create a database
` sudo mysql -u user -p -e 'create database reg_c3lf' `

Import the .sql source file
` mysql(reg_c3lf)> source reg_c3lf `

Change the server name or IP adress in index.js and scanner.js to either "localhost" or "IP_ADRESS"

Launch the php server
` php -S localhost:1060 -t . ` 
or
` php -S IP_ADRESS:1060 -t . ` 