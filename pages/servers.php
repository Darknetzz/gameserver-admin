<?php
$getServers = "SELECT * FROM servers";
$getServers = mysqli_query($sqlcon, $getServers);

if ($getServers && $getServers->num_rows > 0) {
echo "<table class='table table-bordered'>
<thead>
<tr>
    <th>Name</th>
    <th>IP</th>
    <th>Game</th>
    <th>SSH User</th>
    <th>Host</th>
    <th>Gameserver</th>
</tr>
</thead>";

$gsStatus = pingGameServer($server['ip'], $server['port']);
if ($gsStatus) {
    $gsStatus = "<span class='badge bg-success'>Online</span>";
} else {
    $gsStatus = "<span class='badge bg-danger'>Offline</span>";
}

while ($server = $getServers->fetch_assoc()) {
    echo "
    <tbody>
    <tr>
    <td><a href='?p=server&id=$server[id]'>$server[name]</a></td>
    <td>$server[ip]</td>
    <td>$server[game]</td>
    <td>$server[username]</td>
    <td>".pingServer($server['ip'])."</td>
    <td>$gsStatus</td>
    </tr>
    </tbody>";
}
echo "</table>";

} else {
    echo "<div class='alert alert-warning'>No servers have been added yet.</div>";
}
?>