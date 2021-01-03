# Overview

A PHP service, leveraging PodcastIndex.org to provide a podcast directory, and a proxy service, for retro devices that are capable of playing MP3s, but not capable of negotiating modern HTTPS connections.

# Requirements

Provide your PodcastIndex API key and secret in a file called secrets.php

Get your API credentials here: https://api.podcastindex.org/signup

# Prerequisites

* Apache (or other web server) with PHP 7
* sudo apt get php-xml
* sudo apt get php-gd
