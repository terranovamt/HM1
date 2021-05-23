<?php
    header('Content-Type: application/json');
       
    $dbconfig = [
        'host'     => '127.0.0.1',
        'name'     => 'expo',
        'user'     => 'root',
        'password' => ''
    ];
    // Carico le informazioni dell'utente loggato per visualizzarle nella sidebar (mobile)
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
    $query ="SELECT * FROM evento WHERE data_inizio > CURRENT_DATE ORDER BY data_inizio";
    $res = mysqli_query($conn, $query);

    while($evento = mysqli_fetch_assoc($res)){
        $array[]=array(
            'id'=>$evento['id'],
            'nomeevento'=>$evento['nome'],
            'edizione'=>$evento['edizione'],
            'data'=>$evento['data_inizio'],
            'fine'=>$evento['data_fine'],
            'costo'=>$evento['prezzo'],
            'durata'=>$evento['durata'],
            'img'=>$evento['img'],
                     
        );
    }

    echo json_encode($array);
    mysqli_close($conn);

?>