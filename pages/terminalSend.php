<?php
# This file is standalone, so it needs the includes
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
        $session = establishSSH($server['ip'], $server['sshport'], $sshuser, $sshpass);
        echo sendSSH($session, $_POST['cmd']);
        }
    } else {
        echo "Command failed to send. No server with this ID.";
    }
} else {
    echo "ID or CMD not set. Can't do anything.";
}
?>