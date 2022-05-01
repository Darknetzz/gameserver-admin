<?php
if (isset($_GET['id'])) {
    
    $getUsers = "SELECT * FROM users WHERE id = ?";
    
    $stmt = $sqlcon->prepare($getUsers);
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
    echo "<table class='table table-default'>";
    while ($user = $result->fetch_assoc()) {

        if ($user['ssh'] == 1) {
            $ssh = "<span class='badge bg-success'>Yes</span>";
        } else {
            $ssh = "<span class='badge bg-danger'>No</span>";
        }

        echo "
        <tr><td>Username</td><td>$user[username]</td></tr>
        <tr><td>SSH-enabled</td><td>$ssh</td></tr>
        ";
    }
    echo "</table>";


} else {
    echo "<div class='alert alert-danger'>This user does not exist</div>";
}
}
?>