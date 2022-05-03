<?php
if (isset($_GET['id'])) {
    
    $getUsers = "SELECT * FROM users WHERE id = ?";
    
    $stmt = $sqlcon->prepare($getUsers);
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
    echo "<table class='table table-hover'>";
    echo "
        <tr class='bg-".CFG_TABLEHEADERCOLOR."'>
            <th>Property</th> <th>Value</th>
        </tr>
    ";
    while ($user = $result->fetch_assoc()) {

        if ($user['ssh'] == 1) {
            $ssh = "<span class='badge bg-success'>Yes</span>";
        } else {
            $ssh = "<span class='badge bg-danger'>No</span>";
        }

        if ($user['web'] == 1) {
            $web = "<span class='badge bg-success'>Yes</span>";
        } else {
            $web = "<span class='badge bg-danger'>No</span>";
        }

        echo "
        <tr><td>Username</td><td>$user[username]</td></tr>
        <tr><td>Role</td><td>".translateRole($user['role'])."</td></tr>
        <tr><td>SSH-enabled</td><td>$ssh</td></tr>
        <tr><td>Web-enabled</td><td>$web</td></tr>
        ";
    }
    echo "</table>";


} else {
    echo "<div class='alert alert-danger'>This user does not exist</div>";
}
}
?>