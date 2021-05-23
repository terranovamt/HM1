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
    $query ="SELECT * FROM evento 
    JOIN (SELECT * FROM partecipa WHERE azienda = $userid) pidx 
    WHERE evento.id = pidx.evento AND data_inizio > CURRENT_DATE ORDER BY data_inizio";
    $res_1 = mysqli_query($conn, $query);
    
    $query ="SELECT numero, dimensione, modello, posizione, evento FROM standinterno SI JOIN 
    (SELECT * FROM partecipa WHERE azienda = $userid) LI on SI.codice =LI.stand";
     $res_2 = mysqli_query($conn, $query);

    $query ="SELECT numero, dimensione, posizione, evento FROM standesterno SI JOIN 
    (SELECT * FROM partecipa WHERE azienda = $userid) LI on SI.codice = LI.stand";
    $res_3 = mysqli_query($conn, $query);
    

    $array1=array();
    $array2=array();
    $array3=array();

    while($a=mysqli_fetch_assoc($res_2)){
        $array2[]=array(
            'numero'=>$a['numero'],
            'dimensione'=>$a['dimensione'],
            'modello'=>$a['modello'],
            'posizione'=>$a['posizione'],
            'evento'=>$a['evento'],
        );

    };
   
    while($b=mysqli_fetch_assoc($res_3)){
        $array3[]=array(
            'numero'=>$b['numero'],
            'dimensione'=>$b['dimensione'],
            'posizione'=>$b['posizione'],
            'evento'=>$b['evento'],
        );

    };
    $tem1 =isset($array3);
    $tem2 =isset($array2);
    if(!$tem1)
    {
        $stand =$array2;
    }
    elseif (!$tem2) {

        $stand =$array3;
    } else {
       $stand = array_merge($array2,$array3); 
    }
     
    

     while($evento = mysqli_fetch_assoc($res_1)){
        $array1[]=array(
            'id'=>$evento['id'],
            'nomeevento'=>$evento['nome'],
            'edizione'=>$evento['edizione'],
            'data'=>$evento['data_inizio'],
            'fine'=>$evento['data_fine'],
        );
     };
    $array= array("evento"=>$array1,"stand"=>$stand);
    echo json_encode($array);
    mysqli_close($conn);

?>