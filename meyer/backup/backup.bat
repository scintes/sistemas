@echo off
echo "***   REALIZANDO BACKUP DEL SISTEMA    ****"
rem C:\wamp\bin\mysql\mysql5.5.24\bin\mysqldump.exe -u backup -p gvk35on meyer > C:\wamp\backup\meyer.sql.dump
FOR /F "tokens=1,2,3 delims=/ " %%i IN ('date /T') do (set DIA= %%k%%j%%i)
FOR /F "tokens=1,2 delims=: " %%n IN ('time /T') do (set HORA= %%n%%o) 

md "C:\wamp\backup\%dia%_%hora%"

C:\wamp\bin\mysql\mysql5.5.24\bin\mysqldump.exe -u root  meyer > "C:\wamp\backup\%dia%_%hora%\meyer_%dia%_%hora%.sql"

pause