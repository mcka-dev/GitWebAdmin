@ECHO off
CHCP 65001>nul
IF %1 == "" (
    ECHO The variable is empty 2>&1
    EXIT /B 1
) ELSE (
    IF EXIST %1 (
      RMDIR %1 /s /q && ECHO Repository successfully deleted!
      IF EXIST %1 EXIT /B 1
	) ELSE (
	    ECHO No such repository! 2>&1
	    EXIT /B 1
	)
)