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
        if (!isset($_SESSION['sshSession']) || $_SESSION['sshid'] <> $server['id']) {
            $_SESSION['sshid'] = $server['id'];
            $_SESSION['sshSession'] = establishSSH($server['ip'], $server['sshport'], $sshuser, $sshpass);
            $session = $_SESSION['sshSession'];
            echo "Session $_SESSION[sshid] started\n";
        }
        $session = $_SESSION['sshSession'];
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