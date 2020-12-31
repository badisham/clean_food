<?php
session_start();
session_destroy();
require './components/head.php';
?>
<script>
    setTimeout(() => {
        SweetAlertOk('ออกจากระบบ', '', 'index.php');
    }, 100);
</script>