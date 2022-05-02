<?php
# Configuration file, make changes here

# General
define('CFG_TITLE', "PHPGSAdmin");        # Friendly name shown in browser
define('CFG_LOGO', null);                 # Link or filepath to logo in navbar
define('CFG_VERSION', "1.0");             # Version number

# Servers
define('CFG_FSOCKTIMEOUT', 3);           # Timeout of the gameserver pinger

# Security
define('CFG_ADMINUSERNAME', "admin");     # Initial administrator username
define('CFG_ADMINPASSWORD', "CHANGEME");  # Initial administrator password
define('CFG_ADMINENABLED', true);         # Enable admin account, you should falsify this after creating a custom admin account
define('CFG_PEPPER', "CHANGEME");         # Change this to a random string (up to 255 chars)
define('CFG_PASSPHRASE', "CHANGEME");     # Change this to a random string
define('CFG_REQUIRELOGIN', true);         # Should always be true unless secured otherwise
?>