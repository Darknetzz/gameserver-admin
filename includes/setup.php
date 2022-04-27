<?php
if (file_exists("sqlcon.php")) {
    # setup already done
    die();
}
?>
<div class="container">
<div class="card">
<h3 class="card-header">PHPGSAdmin has not yet been set up</h3>
<div class="card-body">
<p>To install, do the following:</p>
<ul>
    <li>Import <b>serveradmin.sql</b> to your MySQL instance.</li>
    <li>Check config.php and set an admin password. Also change the pepper.</li>
    <li>Open the file <b>sqlcon.example.com</b> and set your SQL connection parameters, and rename file to <b>sqlcon.php</b>.</li>
</ul>
<p>When you have saved sqlcon.php, refresh this page.</p>
</div>
</div>
</div>