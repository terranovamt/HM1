<?php 
    require_once 'checksess.php';
    if (!$userid = checkSess()) {
        header("Location: login.php");
        exit;
    
    }
    header('Content-Type: application/json');
       
    require_once 'dbconfig.php';
    // Carico le informazioni dell'utente loggato per visualizzarle nella sidebar (mobile)
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
    $userid = mysqli_real_escape_string($conn, $userid);
    $query ="SELECT * FROM esterno_liberi";
    $res_1 = mysqli_query($conn, $query);
    $array=array();
    while($stand = mysqli_fetch_assoc($res_1)){
        $array[]=array(
            'codice'=>$stand['codice'],
            'numero'=>$stand['numero'],
            'dimensione'=>$stand['dimensione'],
            'posizione'=>$stand['posizione'],
            
        );
    }
    echo json_encode($array);

?>