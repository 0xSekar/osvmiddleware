#!/bin/bash

find /var/www/oldschoolvalue/app/chart/img/ -mtime +60 -type f -delete
find /var/www/oldschoolvalue/demo/chart/img/ -mtime +60 -type f -delete
