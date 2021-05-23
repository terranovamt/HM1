<?php
    require_once 'dbconfig.php';
    session_start();

    function checkSess() {
        // Se esiste già una sessione, la ritorno, altrimenti ritorno 0
        if(isset($_SESSION['_expo_user_id'])) {
            return $_SESSION['_expo_user_id'];
        } else 
            return 0;
    }
?>