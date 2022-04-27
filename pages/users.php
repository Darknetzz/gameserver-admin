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
    echo "
    <tr>
    <td>$user[username]</td>
    <td>".translateRole($user['role'])."</td>
    </tr>";
}
echo "</table>";
} else {
    echo "<div class='alert alert-warning'>No users have been added yet.</div>";
}
?>