<?php
function translateRole($role) {
    # This function can be used as a reference for roles
    # You can also change titles here if you want.
    switch($role) {
        case 0:
            return "User";
            break;
        case 1:
            return "Operator";
            break;
        case 2:
            return "Administrator";
            break;
        case 3:
            return "Owner";
            break;
    }
}

function pingServer($ip, $verbose = false) {
    if (empty($ip)) {
        return "<span class='label label-secondary'>No IP</span>";
    }
    $shell = shell_exec('ping -c 1 -W 1 '.$ip);
    if ($shell) {
      if (!strpos($shell, "Unreachable") !== false
       && !strpos($shell, "100% packet loss") !== false
       && !strpos($shell, "0 received") !== false
       && !strpos($shell, "+1 errors") !== false) {
        return "<span class='label label-success'>Online</span>";
        if ($verbose == 1) {
        return "<pre style='color: green;'>$shell</pre></font>";
        }
      } else {
        return "<font color='red'><span class='label label-danger'>Offline</span></font>";
        if ($verbose == 1) {
        return "<pre style='color: red;'>$shell</pre></font>";
        }
      }
    } else {
      return "<span class='label label-danger'>Unable to ping.</span>";
    }
}

function pingGameServer($ip, $port, $timeout = 5) {
  if (!empty($ip) && !empty($port)) {
  $fp = fsockopen($ip, $port, $errno, $errstr, $timeout);
  if (!$fp) {
      return false; # no connection
  } else {
      return true; # connection established
  }
} else {
  return false; # ip or port missing
}
}
?>