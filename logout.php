<?php 
    session_start();
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    session_destroy();
?>
<script type="text/javascript">
        alert("Berhasil Logout!");
        window.location.href="./login.php";
</script>