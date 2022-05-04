<?php
$getUsers = "SELECT * FROM users";
$getUsers = mysqli_query($sqlcon, $getUsers);

if ($getUsers && $getUsers->num_rows > 0) {
echo "<table class='table table-".CFG_TABLESTYLE."'>
<tr class='bg-".CFG_TABLEHEADERCOLOR."'>
    <th>Username</th>
    <th>Role</th>
    <th>SSH</th>
    <th>Web</th>
</tr>";
while ($user = $getUsers->fetch_assoc()) {
    $sshEnabled = ($user['ssh'] == 1) ? "<span class='badge bg-success'>Enabled</span>" : "<span class='badge bg-danger'>Disabled</span>";
    $webEnabled = ($user['web'] == 1) ? "<span class='badge bg-success'>Enabled</span>" : "<span class='badge bg-danger'>Disabled</span>";

    echo "
    <tr>
    <td><a href='?p=user&id=$user[id]'>$user[username]</a></td>
    <td>".translateRole($user['role'])."</td>
    <td>$sshEnabled</td>
    <td>$webEnabled</td>
    </tr>";
}
echo "</table>";
} else {
    echo "<div class='alert alert-warning'>No users have been added yet.</div>";
}
echo "<a href='?p=adduser' class='btn btn-success'>New user</a>";
?>