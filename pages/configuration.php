<?php
$getcfg = "SELECT * FROM config ORDER BY category";
$getcfg = mysqli_query($sqlcon, $getcfg);

echo "<table class='table'>";
echo "<tr class='bg-".CFG_TABLEHEADERCOLOR."'><th>Variable</th><th>Value</th><th>Description</th></tr>";
$curcat = "";
while ($config = $getcfg->fetch_assoc()) {
    if ($curcat <> $config['category']) {
        $curcat = $config['category'];
        echo "<tr class='bg-".CFG_TABLEHEADERCOLOR."'><th colspan='100%'>$curcat</th></tr>";
    }
    echo "
    <tr>
    <td>$config[var]</td>
    <td><input type='text' class='form-control' value='$config[val]'></td>
    <td>$config[description]</td>
    </tr>";
}
echo "<tr><td colspan='100%'><input type='submit' value='Save config' class='btn btn-success'></td></tr>";
echo "</table>";
?>