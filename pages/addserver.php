<h3>New server</h3>
        <?php
        if (isset($_POST['addserver'])) {
            $addServer = "INSERT INTO servers 
            (`name`, `os`, `ip`, `sshport`, `gameport`, `game`, `type`, `sshuser`) 
            VALUES (?,?,?,?,?,?,?,?,?)";
    
            $stmt = $sqlcon->prepare($addServer);
            $stmt->bind_param("sisiisii", $_POST['name'], $_POST['os'], $_POST['ip'], $_POST['sshport'],
                                          $_POST['gameport'], $_POST['game'], $_POST['name'], $_POST['username']);
            $stmt->execute();
        }
        ?>
        <form action="" method="POST">
        <table class="table table-border">
        <tbody>
            <tr>
                <td>Name</td> <td><input type="text" name="name" class="form-control"></td>
            </tr>
            <tr>
                <td>OS</td> <td><?php echo selectorFromDB("os", "name"); ?></td>
            </tr>
            <tr>
                <td>IP</td> <td><input type="text" name="ip" class="form-control"></tr>
            </tr>
            <tr>
                <td>SSH Port</td> <td><input type="number" name="sshport" class="form-control"></td></tr>
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
                <td>Terminal</td> <td><?php echo selectorFromDB("terminals", "name"); ?></td>
            </tr>
        </tbody>
        </table>
        <input type="submit" name="addserver" value="Add server" class="btn btn-success">
        </form>