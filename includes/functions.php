<?php
function translateRole($role) {
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
        return "<font color='red'><b>Offline</b>";
    }
    $shell = shell_exec('ping -c 1 -W 1 '.$ip);
    if ($shell) {
      if (!strpos($shell, "Unreachable") !== false
       && !strpos($shell, "100% packet loss") !== false
       && !strpos($shell, "0 received") !== false
       && !strpos($shell, "+1 errors") !== false) {
        return "<font color='green'><b>Online</b></font>";
        if ($verbose == 1) {
        return "<pre style='color: green;'>$shell</pre></font>";
        }
      } else {
        return "<font color='red'><b>Offline</b></font>";
        if ($verbose == 1) {
        return "<pre style='color: red;'>$shell</pre></font>";
        }
      }
    } else {
      return "<font color='red'><b>Error</b> Unable to ping.</font>";
    }
}
?>