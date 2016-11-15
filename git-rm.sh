#!/bin/bash
if [ "$1" = "" ]; then
  echo "Repository name is empty!" 1>&2
  exit 1
else
  if [ -d "$1" ]; then
    rm -rf "$1"
    rc=$?; if [[ $rc != 0 ]]; then exit $rc; fi  
    echo "Repository successfully deleted!"
  else
    echo "No such repository!" 1>&2
    exit 1
  fi
fi