<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

<meta viewport="1">

<?php
if (!file_exists("includes/sqlcon.php")) {
    die(include_once("includes/setup.php"));
}
require_once("includes/session.php");
require_once("includes/config.php");
require_once("includes/sqlcon.php");
require_once("includes/functions.php");
require_once("includes/nav.php");
?>
<br>

<title><?php echo CFG_TITLE; ?></title>

<div class="container">
<?php
if (CFG_REQUIRELOGIN == true && !isset($_SESSION['id'])) {
    include_once("pages/login.php");
} else {
if (isset($_GET['p'])) {
    $fp = "pages/".$_GET['p'].".php";
    if (file_exists($fp)) {
        include_once($fp);
    } else {
        include_once("pages/404.php");
    }
} else {
    include_once("pages/home.php");
}
}
?>
</div>