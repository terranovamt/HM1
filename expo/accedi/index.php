<?php 
    require_once 'checksess.php';
    if (!$userid = checkSess()) {
        header("Location: login.php");
        exit;
    }
?>

<html>
    <?php 
    
        require_once 'dbconfig.php';
        // Carico le informazioni dell'utente loggato per visualizzarle nella sidebar (mobile)
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
        $userid = mysqli_real_escape_string($conn, $userid);
        $query = "SELECT * FROM users WHERE id = $userid";
        $res_1 = mysqli_query($conn, $query);
        $userinfo = mysqli_fetch_assoc($res_1);
      
       
    ?>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>EXPO</title>
    <link rel="shortcut icon" href="../img/logo.ico" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab&family=Syne+Mono&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../style.css" />
    <link rel="stylesheet" href="./riservata.css" />
     <!------------------>
     <script src="./riservata.js" defer></script>
    </head>

    <body>
        <form></form>
        <header>
        <nav id="menu">
            <a class="button" href="../">Home</a>
            <a class="button" href="../spazi">Spazi</a>
            <a class="button" href="../eventi">Eventi</a>
        </nav>
        <h1>
            <strong>Aziende </strong>     
        </h1>
        <div class="logout">
            <a class="button" href="./logout.php">Logout</a>
        </div>
        </header>

        <span class ="nomeazienda"><?php echo"<h1>ECCOVI, ".$userinfo['username'] ."</h1>"?></span>
        <main>
                      
            <section class="left">
                <nav class="sidebar"> 
                    <div class="active" id="home">
                        Home
                    </div>
                    <div id="resevent">
                        Prenotazione eventi
                    </div>                  
                </nav>
                
                
          
            </section>
            <section class="right">
                <div id ="title"></div>
                
                <divid id="content"></div>
                
            </section>
            
        </main>

        <footer>
            <div>
            <div id="img"></div>
            <div id="contatto">
                <h1>Terranova Matteo</h1>
                <p>O46002133</p>
            </div>
            </div>
        </footer>
    </body>
</html>

<?php mysqli_close($conn); ?>