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
    $query ="SELECT * FROM evento JOIN 
    (SELECT  id 
    FROM evento 
    where id not in (SELECT  id 
                    FROM evento 
                    JOIN 
                    (SELECT evento FROM partecipa WHERE azienda = $userid) Part
                    on evento.id = Part.evento)) Evt on evento.id  = Evt.id 
                    WHERE data_inizio > CURRENT_DATE ORDER BY data_inizio";
    $res_1 = mysqli_query($conn, $query);
     $query ="SELECT * FROM interno_liberi GROUP BY modello";
     $res_2 = mysqli_query($conn, $query);
    
    while($evento = mysqli_fetch_assoc($res_1)){
        $array1[]=array(
            'id'=>$evento['id'],
            'nomeevento'=>$evento['nome'],
            'edizione'=>$evento['edizione'],
            'data'=>$evento['data_inizio'],
            'fine'=>$evento['data_fine'],
            'costo'=>$evento['prezzo'],
            'durata'=>$evento['durata'],
                     
        );
    }
     while($modello = mysqli_fetch_assoc($res_2)){
         $array2[]=array(
             'nomemodello'=>$modello['modello'],
             'codice'=>$modello['codice'],
                        
         );
     }
    $array= array("evento"=>$array1,"modello"=>$array2);
    echo json_encode($array);
    mysqli_close($conn);

?>