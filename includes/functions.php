<?php
# Refer to database -> roles
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

function strCrypt($string, $passphrase = CFG_PASSPHRASE, $method = "AES-256-CBC") {
  $passphrase = hash('sha512', $passphrase, true);
  $iv = openssl_random_pseudo_bytes(32);

  $ciphertext = openssl_encrypt($string, $method, $passphrase, OPENSSL_RAW_DATA, $iv);
  $hash = hash_hmac('sha512', $ciphertext.$iv, $passphrase, true);

  return base64_encode($iv.$hash.$ciphertext);
}

function strdCrypt($string, $passphrase = CFG_PASSPHRASE, $method = "AES-256-CBC") {
  $string = base64_decode($string);
  $iv = substr($string,0,32);
  $hash = substr($string,32,96);
  $ciphertext = substr($string,96);
  $passphrase = hash('sha512', $passphrase, true);

  #if (!hash_equals(hash_hmac('sha512', $ciphertext.$iv, $passphrase, true), $hash)) return null;

  return openssl_decrypt($ciphertext, $method, $passphrase, OPENSSL_RAW_DATA, $iv);
}

function translateID($id, $table, $column) {
  global $sqlcon;
  $getCol = "SELECT * FROM $table WHERE id = '$id'";
  $getCol = mysqli_query($sqlcon, $getCol);
  if ($getCol->num_rows > 0) {
  while ($row = $getCol->fetch_assoc()) {
    return $row[$column];
  }
} else {
  return false;
}
}

function pingServer($ip, $verbose = false) {
    if (empty($ip)) {
        return "<span class='badge bg-secondary'>No IP</span>";
    }
    $shell = shell_exec('ping -c 1 -W 1 '.$ip);
    if ($shell) {
      if (!strpos($shell, "Unreachable") !== false
       && !strpos($shell, "100% packet loss") !== false
       && !strpos($shell, "0 received") !== false
       && !strpos($shell, "+1 errors") !== false) {
        return "<span class='badge bg-success'>Online</span>";
        if ($verbose == 1) {
        return "<pre style='color: green;'>$shell</pre></font>";
        }
      } else {
        return "<span class='badge bg-danger'>Offline</span>";
        if ($verbose == 1) {
        return "<pre style='color: red;'>$shell</pre></font>";
        }
      }
    } else {
      return "<span class='badge bg-danger'>Unable to ping.</span>";
    }
}

function pingGameServer($ip, $port, $timeout = CFG_FSOCKTIMEOUT) {
  if (!empty($ip) && !empty($port)) {
  $fp = @fsockopen($ip, $port, $errno, $errstr, $timeout);
    if (!$fp) {
        @fclose($fp);
        return "<span class='badge bg-danger'>Offline</span>"; # no connection
    } else {
        @fclose($fp);
        return "<span class='badge bg-success'>Online</span>"; # connection established
    }
  } else {
    return "<span class='badge bg-secondary'>Missing IP or Port</span>"; # ip or port missing
  }
}

function establishSSH($ip, $port = 22, $sshuser, $sshpass) {
    $ssh = ssh2_connect($ip, $port);
    if (!$ssh) {
        $error = "Unable to connect to terminal. Check IP and port.";
        return $error;
        # throw new Exception($error);
    }

    $login = ssh2_auth_password($ssh, $sshuser, strdCrypt($sshpass));
    if (!$login) {
        $error = "Unable to login as $sshuser, please check username and password for this session.";
        return $error;
        # throw new Exception($error);
    }

    return $ssh;
  }

function sendSSH($session, $cmd) {
  $cmd = ssh2_exec($session, $cmd);
  stream_set_blocking($cmd, true);
  $cmd_out = ssh2_fetch_stream($cmd, SSH2_STREAM_STDIO);
  return stream_get_contents($cmd_out);
}

function selectorFromDB($table, $column, $valuecol = "id", $name = null) {
  global $sqlcon;

  if ($name == null) {
    $name = $column;
  }

  $query = "SELECT * FROM $table";
  $query = mysqli_query($sqlcon, $query);

  if ($query && $query->num_rows > 0) {
  $select = "<select name='$name' class='form-select'>";

  while ($row = $query->fetch_assoc()) {
    # if currently set value, default to selected
    # no way to check for this right now, comparison would be in a different sql query
    if (1==1) {
      $select .= "<option value='$row[$valuecol]'>$row[$column]</option>";
    } else {
      $select .= "<option value='$row[$valuecol]' selected>$row[$column]</option>";
    }
  }

  $select .= "</select>";
  return $select;
} else {
  return null;
}
}

function echoOr($first, $second) {
  if (!empty($first)) {
    return $first;
  } else {
    return $second;
  }
}
?>