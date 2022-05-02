        <?php
        if (isset($_POST['adduser'])) {
            if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $addServer = "INSERT INTO users 
            (`username`, `password`, `role`, `ssh`, `web`) 
            VALUES (?,?,?,?,?)";
    
            $stmt = $sqlcon->prepare($addServer);
            if (!empty($sqlcon->error)) {
                die("Error: ".$sqlcon->error);
            }
            $stmt->bind_param("ssiii", 
                            $_POST['username'], strCrypt($_POST['password']), $_POST['name'],
                            $_POST['ssh'], $_POST['web']);
            $stmt->execute();
            echo "<div class='alert alert-success'>User $_POST[username] added!</div>";
        } else {
            echo "<div class='alert alert-danger'>Please fill out username and password.</div>";
        }
        }
        ?>
        <h3>New user</h3>
        <form action="" method="POST">
        <table class="table table-border">
        <tbody>
            <tr>
                <td>Username</td> <td><input type="text" name="username" class="form-control"></td>
            </tr>
            <tr>
                <td>Password</td> <td><input type="password" name="password" class="form-control"></td>
            </tr>
            <tr>
                <td>Role</td> <td><?php echo selectorFromDB('roles', 'name', 'accesslevel'); ?></td>
            </tr>
            <tr>
                <td>Allow SSH</td>
                <td>
                <div class="form-check form-switch">
                <input type="hidden" name="ssh" value="0">
                <input class="form-check-input" name="ssh" value="1" type="checkbox" role="switch">
                </div>
                </td>
            </tr>
            <tr>
                <td>Allow Web</td>
                <td>
                <div class="form-check form-switch">
                <input type="hidden" name="web" value="0">
                <input class="form-check-input" name="web" value="1" type="checkbox" role="switch">
                </div>
                </td>
            </tr>
        </tbody>
        </table>
        <input type="submit" name="adduser" value="Add user" class="btn btn-success">
        </form>