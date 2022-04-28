<?php
$getServers = "SELECT * FROM servers";
$getServers = mysqli_query($sqlcon, $getServers);

if ($getServers && $getServers->num_rows > 0) {
echo "<table class='table table-default'>
<tr>
    <th>Name</th>
    <th>IP</th>
    <th>Game</th>
    <th>SSH User</th>
    <th>Host</th>
    <th>Gameserver</th>
</tr>";

$gsStatus = pingGameServer($server['ip'], $server['port']);
if ($gsStatus) {
    $gsStatus = "<font color='green'>Online</font>";
} else {
    $gsStatus = "<font color='red'>Offline</font>";
}

while ($server = $getServers->fetch_assoc()) {
    echo "
    <tr>
    <td>
    <a href='#' data-bs-toggle='modal' data-bs-target='#modal$server[name]'>
    $server[name]
    </a>
    </td>
    <td><span class='label label-default'>$server[ip]</span></td>
    <td>$server[game]</td>
    <td>$server[username]</td>
    <td>".pingServer($server['ip'])."</td>
    <td>$gsStatus</td>
    </tr>";

    echo '
    <!-- Modal -->
    <div class="modal fade" id="modal'.$server['name'].'" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">'.$server['name'].'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            Name: '.$server['name'].'<br>
            OS: '.$server['os'].'<br>
            IP: '.$server['ip'].'<br>
            Game: '.$server['game'].'<br>
            Players online: <br>
            SSH User: '.$server['username'].'<br>
            Status: '.pingServer($server['ip']).'<br>
            <hr>
            <button class="btn btn-info">Broadcast</button>
            <button class="btn btn-warning">Restart gameserver</button>
            <button class="btn btn-danger">Reboot host</button>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>
    ';
}
echo "</table>";
} else {
    echo "<div class='alert alert-warning'>No servers have been added yet.</div>";
}
?>