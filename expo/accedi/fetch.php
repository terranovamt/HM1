<?php 
    require_once 'checksess.php';
    if (!$userid = checkSess()) {
        header("Location: login.php");
        exit;
    }
    
    
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
    $userid = mysqli_real_escape_string($conn, $userid);
    $_SESSION['_expo_funzione']= mysqli_real_escape_string($conn, $_POST['function']);
    $function =$_SESSION['_expo_funzione'];

    



    if($function == "insert_esterno") {
        $_SESSION["_expo_idevento"]= mysqli_real_escape_string($conn, $_POST['evento']);
        $evento = $_SESSION["_expo_idevento"];
        $liberi_query ="SELECT codice FROM esterno_liberi";
        $liberi_res = mysqli_query($conn, $liberi_query) or die(mysqli_error($conn));
        $liberi_info = mysqli_fetch_assoc($liberi_res);
        $liberi_id = $liberi_info["codice"];
        $ev_query = "INSERT INTO partecipa(evento,azienda,stand) values ($evento, $userid, $liberi_id)";
        $ev_res =  mysqli_query($conn, $ev_query) or die(mysqli_error($conn));
        $insert_query = "INSERT INTO locazionesterno values ($liberi_id, $userid)";
        $insert_res =  mysqli_query($conn, $insert_query) or die(mysqli_error($conn));
        exit;
    } 
    if($function == "insert_interno") {

        $_SESSION["_expo_idevento"]= mysqli_real_escape_string($conn, $_POST['evento']);
        $evento = $_SESSION["_expo_idevento"];
        $_SESSION["_expo_modellostand"]= mysqli_real_escape_string($conn, $_POST['modello']);
        $modello = $_SESSION["_expo_modellostand"];
        $liberi_query ="SELECT codice FROM interno_liberi WHERE modello = '$modello' GROUP BY modello ";
        $liberi_res = mysqli_query($conn, $liberi_query) or die(mysqli_error($conn));
        $liberi_info = mysqli_fetch_assoc($liberi_res);
        $liberi_id = $liberi_info["codice"];

        $ev_query = "INSERT INTO partecipa(evento,azienda,stand) values ($evento, $userid, $liberi_id)";
        $ev_res = mysqli_query($conn, $ev_query) or die(mysqli_error($conn));
        
        $insert_query = "INSERT INTO locazioneinterno values ($liberi_id, $userid)";
        $insert_res =  mysqli_query($conn, $insert_query) or die(mysqli_error($conn));
        exit;
    }        
    if($function == "elimina_evento") {

        $_SESSION["_expo_idevento"]= mysqli_real_escape_string($conn, $_POST['evento']);
        $evento = $_SESSION["_expo_idevento"];
        
         $partecipa_query ="SELECT * FROM partecipa WHERE evento = '$evento'";
         $partecipa_res = mysqli_query($conn, $partecipa_query) or die(mysqli_error($conn));
         $partecipa_info = mysqli_fetch_assoc($partecipa_res);
         $stand_id = $partecipa_info["stand"];
         $_SESSION["PROVA"] =$stand_id;
         $delete1_query ="DELETE FROM partecipa WHERE evento = '$evento' AND azienda = '$userid' AND stand = '$stand_id'";
         $delete1_res = mysqli_query($conn, $delete1_query) or die(mysqli_error($conn));
         $delete1_info = mysqli_fetch_assoc($delete1_res);
         $delete2_query ="DELETE FROM locazionesterno WHERE stand = '$stand_id' AND azienda = '$userid'";
         $delete2_res = mysqli_query($conn, $delete2_query) or die(mysqli_error($conn));
         $delete2_info = mysqli_fetch_assoc($delete2_res);
         $delete3_query ="DELETE FROM locazioneinterno WHERE stand = '$stand_id' AND azienda = '$userid'";
         $delete3_res = mysqli_query($conn, $delete3_query) or die(mysqli_error($conn));
         $delete3_info = mysqli_fetch_assoc($delete3_res);
         
        
        exit;
    }        
    mysqli_close($conn); 
?>