<?php
# Configuration file, make changes here

# General
$cfg['title'] = "PHPGSAdmin";       # Friendly name shown in browser

# Security
$cfg['adminUsername'] = "admin";    # Initial administrator username
$cfg['$adminPassword'] = "CHANGEME"; # Initial administrator password
$cfg['$adminEnabled']  = true;       # Enable admin account, you should falsify this after creating a custom account
$cfg['$pepper'] = "CHANGEME";        # Change this to a random string (up to 255 chars)
$cfg['$requireLogin'] = true;        # Should always be true
?>