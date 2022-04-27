<?php
$getUsers = "SELECT * FROM users";
$getUsers = mysqli_query($sqlcon, $getUsers);

if ($getUsers->num_rows > 0) {
while ($user = $getUsers->fetch_assoc()) {
    echo $user['username'];
}
} else {
    echo "<div class='alert alert-warning'>No users have been added yet.</div>";
}
?>