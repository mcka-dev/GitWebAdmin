#!/bin/bash
if [ "$1" = "" ]; then
  echo "Repository name is empty!" 1>&2
  exit 1
else
  if [ -d "$1" ]; then
    echo "The repository name already exists!" 1>&2
    exit 1
  fi
  git init --bare "$1"
fi