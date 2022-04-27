<?php
$getServers = "SELECT * FROM servers";
$getServers = mysqli_query($sqlcon, $getServers);

if ($getServers->num_rows > 0) {
while ($server = $getServers->fetch_assoc()) {
    echo $server['name'];
}
} else {
    echo "<div class='alert alert-warning'>No servers have been added yet.</div>";
}
?>