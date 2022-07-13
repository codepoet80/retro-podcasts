# Overview

A PHP service, leveraging PodcastIndex.org to provide a podcast directory, and a proxy service, for retro devices that are capable of playing MP3s, but not capable of negotiating modern HTTPS connections.

# Requirements

Provide your PodcastIndex API key and secret in a file called secrets.php

Get your API credentials here: https://api.podcastindex.org/signup

Create a writable folder (or symlink) called `cache` for the service to write to

# Prerequisites

* Apache, Nginx (possibly other web servers) with PHP 7
* sudo apt install php-xml
* sudo apt install php-gd
* sudo apt install php-curl

# Optional Prerequisites

* x-sendfile

**Note:** If you have x-sendfile installed, you must allow it for the cache folder, or downloads will not work. See https://codeutopia.net/blog/2009/03/06/sending-files-better-apache-mod_xsendfile-and-php/
