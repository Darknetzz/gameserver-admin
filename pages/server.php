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
                <tr><td>Broadcast message</td> <td><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#broadcast'.$server['id'].'">Broadcast</button></td>
                <tr><td>Open remote terminal</td><td><a href="?p=terminal&id='.$server['id'].'" class="btn btn-primary">Terminal</a></td>
                <tr><td>Restart gameserver</td> <td><button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#restart'.$server['id'].'">Restart gameserver</button></td>
                <tr><td>Reboot host</td> <td><button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#reboot'.$server['id'].'">Reboot host</button></td>
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