<h3>New server</h3>
        <?php
        if (isset($_POST['addserver'])) {
            $addServer = "INSERT INTO servers 
            (`name`, `os`, `ip`, `sshport`, `gameport`, `game`, `type`, `sshuser`, `externalip`, `externalsshport`) 
            VALUES (?,?,?,?,?,?,?,?,?,?)";
    
            $stmt = $sqlcon->prepare($addServer);
            if (!empty($sqlcon->error)) {
                die("Error: ".$sqlcon->error);
            }
            $stmt->bind_param("sisiisii", 
                            $_POST['sname'], $_POST['os'], $_POST['ip'], $_POST['sshport'],
                            $_POST['gameport'], $_POST['game'], $_POST['terminal'], $_POST['username'],
                            $_POST['externalip'], $_POST['externalsshport']);
            $stmt->execute();
            echo "<div class='alert alert-success'>Server $_POST[name] added!</div>";
        }
        ?>
        <form action="" method="POST">
        <table class="table table-border">
        <tbody>
            <tr>
                <td>Name</td> <td><input type="text" name="sname" class="form-control"></td>
            </tr>
            <tr>
                <td>OS</td> <td><?php echo selectorFromDB("os", "name", "id", "os"); ?></td>
            </tr>
            <tr>
                <td>IP</td> <td><input type="text" name="ip" class="form-control"></tr>
            </tr>
            <tr>
                <td>SSH Port</td> <td><input type="number" name="sshport" value="22" class="form-control"></td></tr>
            </tr>
            <tr>
                <td>External IP</td> <td><input type="number" name="externalip" class="form-control"></td>
            </tr>
            <tr>
                <td>External SSH Port</td> <td><input type="number" name="externalsshport" class="form-control"></td>
            </tr>
            <tr>
                <td>Terminal</td> <td><?php echo selectorFromDB("terminals", "name", "id", "terminal"); ?></td>
            </tr>
            <tr>
                <td>Gameserver Port</td> <td><input type="number" name="gameport" class="form-control"></tr>
            </tr>
            <tr>
                <td>Game</td> <td><input type="text" name="game" class="form-control"></td>
            </tr>
            <tr>
                <td>SSH User</td> <td><?php echo selectorFromDB("users", "username"); ?></td>
            </tr>
            <tr>
                <td>Terminal</td> <td><?php echo selectorFromDB("terminals", "name", "id", "terminal"); ?></td>
            </tr>
        </tbody>
        </table>
        <input type="submit" name="addserver" value="Add server" class="btn btn-success">
        </form>