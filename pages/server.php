<?php
if (isset($_GET['id'])) {
    
$getServers = "SELECT * FROM servers WHERE id = ?";

$stmt = $sqlcon->prepare($getServers);
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 1) {
while ($server = $result->fetch_assoc()) {

    if (isset($_POST['restart'])) {
        # Restart gameserver
        # $cmd = "cd /home/$user/;./$user restart";
        $session = establishSSH($server['ip'], $server['sshport'], $server['sshuser'], strdCrypt($server['sshpass']));
        $ssh = sendSSH($session, $cmd);
        if (!$ssh) {
            echo "<div class='alert alert-danger'>Unable to restart server.</div>";
        } else {
            echo "<div class='alert alert-success'>Server restarted!</div>";
        }
    }
    if (isset($_POST['reboot'])) {
        # Reboot server
        $cmd = "reboot";
        $session = establishSSH($server['ip'], $server['sshport'], $server['sshuser'], strdCrypt($server['sshpass']));
        $ssh = sendSSH($session, $cmd);
        if (!$ssh) {
            echo "<div class='alert alert-danger'>Unable to reboot server.</div>";
        } else {
            echo "<div class='alert alert-success'>Server rebooted!</div>";
        }
    }

    $hsStatus = pingServer($server['ip']);
    $ehsStatus = pingServer($server['externalip']);
    $egsStatus = pingGameServer($server['externalip'], $server['gameport']);
    $eshStatus = pingGameServer($server['externalip'], $server['sshport']);
    $gsStatus = pingGameServer($server['ip'], $server['gameport']);
    $shStatus = pingGameServer($server['ip'], $server['sshport']);

    $esshURL = "ssh://".translateID($server['sshuser'], "users", "username")."@$server[externalip]:$server[externalsshport]";
    if (!empty($server['externalsshport']) && !empty($server['externalip'])) {
        $sshExternal = '<a href="'.$esshURL.'" class="btn btn-primary">External Terminal</a>';
    } else {
        $sshExternal = '<a href="#" class="btn btn-primary disabled">Missing External IP or Port</a>';
    }

    if (strpos($shStatus, "Offline") !== false) {
        $broadcastBtn = "disabled";
        $broadcastText = "SSH Port not responding";
        $terminalBtn = "disabled";
        $terminalText = "SSH Port not responding";
        $restartBtn = "disabled";
        $restartText = "SSH Port not responding";
        $rebootBtn = "disabled";
        $rebootText = "SSH Port not responding";
    } else {
        $broadcastBtn = null;
        $broadcastText = "Broadcast";
        $terminalBtn = null;
        $terminalText = "Open Terminal";
        $restartBtn = null;
        $restartText = "Restart";
        $rebootBtn = null;
        $rebootText = "Reboot";

    }

    # Create modal for each server
    # Notice the <td> at the start, clever hack to get a "table inside table" kinda
    echo '
        <h3>Server information</h3>
        <table class="table table-border">
        <tbody>
            <tr>
                <td>Name</td> <td><span class="badge bg-info">'.$server['name'].'</span></td>
            </tr>
            <tr>
                <td>OS</td> <td>'.translateID($server['os'], 'os', 'name').'</td>
            </tr>
            <tr>
                <td>IP</td> <td>'.$hsStatus.' <span class="badge bg-secondary">'.$server['ip'].'</span></tr>
            </tr>
            <tr>
                <td>External IP</td> <td>'.$ehsStatus.' <span class="badge bg-secondary">'.$server['externalip'].'</span></tr>
            </tr>
            <tr>
                <td>SSH Port</td> <td>'.$shStatus.' <span class="badge bg-secondary">'.$server['sshport'].'</span></td></tr>
            </tr>
            <tr>
                <td>External SSH Port</td> <td>'.$eshStatus.' <span class="badge bg-secondary">'.$server['externalsshport'].'</span></tr>
            </tr>
            <tr>
                <td>Gameserver Port</td> <td>'.$gsStatus.' <span class="badge bg-secondary">'.$server['gameport'].'</span></tr>
            </tr>
            <tr>
                <td>Game</td> <td>'.$server['game'].'</td>
            </tr>
            <tr>
                <td>Players online</td> <td></td>
            </tr>
            <tr>
                <td>SSH User</td> <td>'.translateID($server['sshuser'], 'users', 'username').'</td>
            </tr>
            <tr>
                <td>Terminal</td> <td>'.translateID($server['type'], 'terminals', 'name').'</td>
            </tr>
            <!--<tr>
                <td>Host Status</td> <td>'.$hsStatus.'</td>
            </tr>
            <tr>
                <td>Gameserver Status</td> <td>'.$gsStatus.'</td>
            </tr>-->
        </tbody>
        </table>

        <div class="modal" id="edit'.$server['id'].'" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit '.$server['name'].'</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                <form action="" method="POST">
                <tr><td>Name</td><td><input type="text" class="form-control" value="'.$server['name'].'"></td></tr>
                <tr><td>OS</td><td>'.selectorFromDB('os', 'name').'</td></tr>
                <tr><td>Internal IP</td><td><input type="text" class="form-control" value="'.$server['ip'].'"></td></tr>
                <tr><td>Internal SSH Port</td><td><input type="number" class="form-control" value="'.$server['sshport'].'"></td></tr>
                <tr><td>External IP</td><td><input type="number" class="form-control" value="'.$server['externalip'].'"></td></tr>
                <tr><td>External SSH Port</td><td><input type="number" class="form-control" value="'.$server['externalsshport'].'"></td></tr>
                <tr><td>SSH User</td><td>'.selectorFromDB('users', 'username').'</td></tr>
                <tr><td>Gameserver Port</td><td><input type="number" class="form-control" value="'.$server['gameport'].'"></td></tr>
                <tr><td>Terminal</td><td>'.selectorFromDB('terminals', 'name').'</td></tr>
                </form>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success">Save changes</button>
            </div>
            </div>
        </div>
        </div>

        <div class="modal" id="restart'.$server['id'].'" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Restart '.$server['name'].'</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">Are you sure you want to restart '.$server['name'].'?</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger">Restart</button>
            </div>
            </div>
        </div>
        </div>

        <div class="modal" id="reboot'.$server['id'].'" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reboot '.$server['name'].'</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">Are you sure you want to reboot '.$server['name'].'?</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger">Reboot</button>
            </div>
            </div>
        </div>
        </div>
        
        <div class="card">
        <h4 class="card-header">Actions</h4>
        <div class="card-body">
            <table class="table table-default">
                <tr><td>Edit server</td> <td><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit'.$server['id'].'">Edit</button></td>
                <tr><td>Broadcast message</td> <td><button data-bs-toggle="modal" data-bs-target="#broadcast'.$server['id'].'" class="btn btn-primary '.$broadcastBtn.'">'.$broadcastText.'</button></td>
                <tr><td>Open remote terminal</td><td><a href="?p=terminal&id='.$server['id'].'" class="btn btn-primary '.$terminalBtn.'">'.$terminalText.'</a> '.$sshExternal.'</td>
                <tr><td>Restart gameserver</td> <td><button data-bs-toggle="modal" data-bs-target="#restart'.$server['id'].'" class="btn btn-danger '.$restartBtn.'">'.$restartText.'</button></td>
                <tr><td>Reboot host</td> <td><button data-bs-toggle="modal" data-bs-target="#reboot'.$server['id'].'" class="btn btn-danger '.$rebootBtn.'">'.$rebootText.'</button></td>
            </table>
        </div>
        </div>
    ';
}
} else {
    echo "<div class='alert alert-danger'>This server does not exist</div>";
}
}
    ?>