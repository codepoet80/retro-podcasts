#!/bin/bash
find /var/www/podcasts/cache/*.* -mmin +90 -exec rm -r -f {} \;

