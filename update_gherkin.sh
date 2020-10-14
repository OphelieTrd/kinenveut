#!/bin/bash

for eachfile in $(ls ./test/resources/*.feature)
do
  nameContext=$( echo $( echo $( echo "$eachfile"| tr -d _) | cut -d"/" -f4) | cut -d"." -f1)Context
  if [ -z "$(cat behat.yml | grep "$nameContext")" ]
  then
    sed -i '/contexts:/a \ \ \ \ \ \ \ \ - '$nameContext behat.yml
  fi
done
vendor/bin/behat --init
for eachfile in $(ls ./test/resources/*.feature)
do
  nameContext=$( echo $( echo $( echo "$eachfile"| tr -d _) | cut -d"/" -f4) | cut -d"." -f1)Context
  eachfile=$(echo "$eachfile" | cut -c 3-)
  if ! vendor/bin/behat --dry-run --append-snippets --colors --snippets-for=$nameContext $eachfile;
  then
    sed -i '/- '$nameContext'/d' behat.yml
    rm $eachfile
  fi
done
