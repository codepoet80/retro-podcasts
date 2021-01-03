#!/bin/bash
find /var/www/podcasts/cache/*.* -mmin +5 -exec rm -r {} \;

