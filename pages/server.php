<?php
if (isset($_GET['id'])) {
$getServers = "SELECT * FROM servers WHERE id = ?";

$stmt = $sqlcon->prepare($getServers);
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 1) {
while ($server = $result->fetch_assoc()) {

    $gsStatus = pingGameServer($server['ip'], $server['port']);
    if ($gsStatus) {
        $gsStatus = "<span class='badge bg-success'>Online</span>";
    } else {
        $gsStatus = "<span class='badge bg-danger'>Offline</span>";
    }

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
            <td>Host Status</td> <td>'.pingServer($server['ip']).'</td>
            </tr>
            <tr>
            <td>Gameserver Status</td> <td>'.$gsStatus.'</td>
            </tr>
        </tbody>
        </table>
        
        <div class="card">
        <h4 class="card-header">Actions</h4>
        <div class="card-body">
            <table class="table table-default">
                <tr><td>Broadcast message</td> <td><button class="btn btn-info">Broadcast</button></td>
                <tr><td>Open remote terminal</td><td><button class="btn btn-secondary">Terminal</button></td>
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