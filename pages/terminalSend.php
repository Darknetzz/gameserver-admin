<?php
# This file is standalone, so it needs the includes
require_once("../includes/session.php");
require_once("../includes/config.php");
require_once("../includes/sqlcon.php");
require_once("../includes/functions.php");

if (isset($_POST['id']) && isset($_POST['cmd'])) {
    $getServers = "SELECT * FROM servers WHERE id = ?";

    $stmt = $sqlcon->prepare($getServers);
    $stmt->bind_param("i", $_POST['id']);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
    while ($server = $result->fetch_assoc()) {
        $sshuser = translateID($server['sshuser'], 'users', 'username');
        $sshpass = translateID($server['sshuser'], 'users', 'password');
        # For every command sent, a new session is established. Not sure how to work around that yet.
        # Perhaps saving the session to the PHP session could solve it?
        # Edit: There is no way to store a resource (active connection) in a session var:
        # https://stackoverflow.com/questions/11432517/php-ssh2-put-ssh-connection-resource-in-session
        $session = establishSSH($server['ip'], $server['sshport'], $sshuser, $sshpass);
        $ssh = sendSSH($session, $_POST['cmd']);
        if (!empty($ssh)) {
            echo $ssh;
        } else {
            echo "[Empty response]";
        }
        }
    } else {
        echo "Command failed to send. No server with this ID.";
    }
} else {
    echo "ID or CMD not set. Can't do anything.";
}
?>