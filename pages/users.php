<?php
$getUsers = "SELECT * FROM users";
$getUsers = mysqli_query($sqlcon, $getUsers);

if ($getUsers && $getUsers->num_rows > 0) {
echo "<table class='table table-default'>
<tr>
    <th>Username</th>
    <th>Role</th>
</tr>";
while ($user = $getUsers->fetch_assoc()) {
    if ($user['ssh'] == 1) {
        $sshEnabled = "(SSH-enabled)";
    } else {
        $sshEnabled = null;
    }
    echo "
    <tr>
    <td><a href='?p=user&id=$user[id]'>$user[username]</a></td>
    <td>".translateRole($user['role'])." $sshEnabled</td>
    </tr>";
}
echo "</table>";
} else {
    echo "<div class='alert alert-warning'>No users have been added yet.</div>";
}
?>