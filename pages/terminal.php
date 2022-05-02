<?php
if (isset($_GET['id'])) {
    $getServers = "SELECT * FROM servers WHERE id = ?";

    $stmt = $sqlcon->prepare($getServers);
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
    while ($server = $result->fetch_assoc()) {
        if (function_exists("ssh2_connect")) {
            $sshuser = translateID($server['sshuser'], 'users', 'username');
            $sshpass = translateID($server['sshuser'], 'users', 'password');

            echo "
            <h3>$server[name]</h3>
            <code style='white-space:pre;'>
            <textarea class='form-control' style='height:800px;' id='term' readonly>
            </textarea></code>";
            echo "
            <form action='' method='POST' id='form'>
            <input type='hidden' name='id' value='$server[id]'>
            <div class='input-group mb-3'>
            <div class='input-group-prepend'>
                <span class='input-group-text' id='prepend'>$sshuser@$server[name]:~#</span>
            </div>
            <input type='text' name='cmd' class='form-control' id='cmd' autofocus autocomplete='off'>
            </div>
            </form>
            ";

        } else {
            $phpversion = phpversion();
            if (strpos($phpversion, "7.") !== false) {
                $code = "<code>sudo apt install libssh2-1 php-ssh2</code>";
            } elseif (strpos($phpversion, "8.0") !== false) {
                $code = "<code>sudo apt install libssh2-1 php-8.0-ssh2</code>";
            } elseif (strpos($phpversion, "8.1") !== false) {
                $code = "<code>sudo apt install libssh2-1 php8.1-ssh2</code>";
            } else {
                $code = "<code>sudo apt install libssh2-1 php-ssh2</code>";
            }
            die("
                <div class='alert alert-warning'>
                Function ssh2_connect doesn't exist.<br>
                To install this on PHP $phpversion, run the following from your webserver terminal:<br>
                $code
                </div>");
        }
    }
    echo '
    <script>
    $("#form").submit(function (e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "pages/terminalSend.php",
            data: $("#form").serialize(),
            success: function(response){
                var term = $("#term")
                var cmd = $("#cmd")
                term.append("\n")
                term.append($("#prepend").html()+" "+cmd.val())
                cmd.val("")
                term.append("\n")
                term.append(response)
                term.scrollTop(term[0].scrollHeight - term.height());
            }
        });
    });
    </script>
    ';
}
}