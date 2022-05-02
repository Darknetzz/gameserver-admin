<?php
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    # Insert conditional for config admin account
    if ($username == CFG_ADMINUSERNAME && $password == CFG_ADMINPASSWORD && CFG_ADMINENABLED == 1) {
        # Authentication successful
        echo "<div class='alert alert-success'>Welcome $username!</div>

        <script>
        window.setTimeout(function(){

            // Move to a new location or you can do something else
            window.location.href = 'index.php';
    
        }, 1000);
        </script>
        
        ";
        $_SESSION['id'] = 1;
    } else {

    $getUsers = "SELECT * FROM users WHERE username = ?";
    
    $stmt = $sqlcon->prepare($getUsers);
    $stmt->bind_param("s", $_POST['username']);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        while ($user = $result->fetch_assoc()) {
            $dbPassword = strdCrypt($user['password']);
            if ($_POST['password'] == $dbPassword) {
                # Authentication successful
                echo "<div class='alert alert-success'>Welcome $user[username]!</div>
                
                <script>
                window.setTimeout(function(){

                    // Move to a new location or you can do something else
                    window.location.href = 'index.php';
            
                }, 1000);
                </script>
                
                ";
                $_SESSION['id'] = $user['id'];
            } else {
                # Authentication failed
                echo "<div class='alert alert-danger'>Wrong username/password</div>";
            }
        }
    } else {
        echo "<div class='alert alert-danger'>Wrong username/password</div>";
    }
}
}
?>

<div class="card">
<h4 class="card-header">Login</h4>
<div class="card-body">
<form action="" method="POST">
<table class="table">
<tr><td>Username:</td> <td><input name="username" type="text" class="form-control" autocomplete="off"></td></tr>
<tr><td>Password:</td> <td><input name="password" type="password" class="form-control" autocomplete="off"></td></tr>
<tr><td colspan="100%"><input type="submit" name="login" value="Login" class="btn btn-primary"></td></tr>
</table>
</form>
</div>
</div>