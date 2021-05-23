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
    $query ="SELECT * FROM modello";
    $res = mysqli_query($conn, $query);

    while($modello = mysqli_fetch_assoc($res)){
        $array[]=array(
            'title'=>$modello['nome'],
            'img'=>$modello['img'],
            'dett'=>$modello['descrizione'],
        );
    }

    echo json_encode($array);
    mysqli_close($conn);

?>