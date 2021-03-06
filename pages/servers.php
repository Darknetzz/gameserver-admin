<?php
$getServers = "SELECT * FROM servers";
$getServers = mysqli_query($sqlcon, $getServers);

if ($getServers && $getServers->num_rows > 0) {
echo "<table class='table table-".CFG_TABLESTYLE."'>
<thead>
<tr class='bg-".CFG_TABLEHEADERCOLOR."'>
    <th>Name</th>
    <th>IP</th>
    <th>Game</th>
    <th>Host</th>
    <th>Gameserver</th>
    <th>Actions</th>
</tr>
</thead>";

while ($server = $getServers->fetch_assoc()) {

    $gsStatus = pingGameServer($server['ip'], $server['gameport']);
    $hsStatus = pingServer($server['ip']);

    echo "
    <tbody>
    <tr>
    <td><a href='?p=server&id=$server[id]'>$server[name]</a></td>
    <td>$server[ip]</td>
    <td>$server[game]</td>
    <td>$hsStatus</td>
    <td>$gsStatus</td>
    <td>
    <span class='mdi mdi-console'></span>
    <span class='mdi mdi-restart'></span>
    <span class='mdi mdi-delete'></span>
    </td>
    </tr>
    </tbody>";
}
echo "</table>";
} else {
    echo "<div class='alert alert-warning'>No servers have been added yet.</div>";
}
echo "<a href='?p=addserver' class='btn btn-success'>New server</a>";
?>