<?php
if (isset($_GET['id'])) {
    $getServers = "SELECT * FROM servers WHERE id = ?";

    $stmt = $sqlcon->prepare($getServers);
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
    while ($server = $result->fetch_assoc()) {
        if (function_exists("ssh2_connect")) {
            $ssh = ssh2_connect($server['ip'], $server['sshport']);
            if (!$ssh) {
                throw new Exception("<div class='alert alert-danger'>Unable to connect to terminal. Check IP and port.</div>");
            }

            $login = ssh2_auth_password($ssh, '', '');
            if (!$login) {
                throw new Exception("<div class='alert alert-danger'>Unable to login, please check username and password for this session.</div>");
            }

            $cmd = ssh2_exec($ssh, "ls");
            echo $cmd;

        } else {
            $phpversion = phpversion();
            if (strpos($phpversion, "7.") !== false) {
                $code = "<code>sudo apt install libssh2-1 php-ssh2</code>";
            } elseif (strpos($phpversion, "8.0") !== false) {
                $code = "<code>sudo apt install libssh2-1 php-8.0-ssh2</code>";
            } elseif (strpos($phpversion, "8.1") !== false) {
                $code = "<code>sudo apt install libssh2-1 php8.1-ssh2</code>";
            } else {
                $code = "<code>sudo apt install libssh2-1 php-ssh2</code>";
            }
            die("
                <div class='alert alert-warning'>
                Function ssh2_connect doesn't exist.<br>
                To install this on PHP $phpversion, run the following from your webserver terminal:<br>
                $code
                </div>");
        }
    }
}
}