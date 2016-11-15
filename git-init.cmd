@ECHO off
CHCP 65001>nul
IF %1 == "" (
    ECHO The variable is empty 2>&1
    EXIT /B 1
) ELSE (
    IF EXIST %1 (
	    ECHO The repository name already exists! 2>&1
	    EXIT /B 1
	) ELSE (
	    git init --bare %1
	)
)
