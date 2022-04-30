<?php
if (isset($_GET['id'])) {
$getServers = "SELECT * FROM servers WHERE id = ?";

$stmt = $sqlcon->prepare($getServers);
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 1) {
while ($server = $result->fetch_assoc()) {

    $hsStatus = pingServer($server['ip']);
    $gsStatus = pingGameServer($server['ip'], $server['gameport']);

    # Create modal for each server
    # Notice the <td> at the start, clever hack to get a "table inside table" kinda
    echo '
        <table class="table table-border">
        <tbody>
            <tr>
                <td>Name</td> <td><span class="badge bg-info">'.$server['name'].'</span></td>
            </tr>
            <tr>
                <td>OS</td> <td>'.$server['os'].'</td>
            </tr>
            <tr>
                <td>IP</td> <td>'.$server['ip'].'</tr>
            </tr>
            <tr>
                <td>Port</td> <td>'.$server['gameport'].'</tr>
            </tr>
            <tr>
                <td>Game</td> <td>'.$server['game'].'</td>
            </tr>
            <tr>
                <td>Players online</td> <td></td>
            </tr>
            <tr>
                <td>SSH User</td> <td>'.$server['username'].'</td>
            </tr>
            <tr>
                <td>Terminal</td> <td>'.$server['type'].'</td>
            </tr>
            <tr>
                <td>Host Status</td> <td>'.$hsStatus.'</td>
            </tr>
            <tr>
                <td>Gameserver Status</td> <td>'.$gsStatus.'</td>
            </tr>
        </tbody>
        </table>

        <div class="modal" id="modal'.$server['id'].'" tabindex="-1">
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
                <tr><td>IP</td><td><input type="text" class="form-control" value="'.$server['ip'].'"></td></tr>
                <tr><td>Gameserver Port</td><td><input type="number" class="form-control" value="'.$server['port'].'"></td></tr>
                <tr><td>SSH User</td><td>'.selectorFromDB('users', 'username').'</td></tr>
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
        
        <div class="card">
        <h4 class="card-header">Actions</h4>
        <div class="card-body">
            <table class="table table-default">
                <tr><td>Edit server</td> <td><button class="btn btn-primary"
                data-bs-toggle="modal" data-bs-target="#modal'.$server['id'].'">Edit</button></td>
                <tr><td>Broadcast message</td> <td><button class="btn btn-primary">Broadcast</button></td>
                <tr><td>Open remote terminal</td><td><button class="btn btn-primary">Terminal</button></td>
                <tr><td>Restart gameserver</td> <td><button class="btn btn-danger">Restart gameserver</button></td>
                <tr><td>Reboot host</td> <td><button class="btn btn-danger">Reboot host</button></td>
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