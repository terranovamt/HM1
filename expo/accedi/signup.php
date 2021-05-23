<?php
    require_once 'checksess.php';
    if (checkSess()) {
        header("Location: ./");
        exit;
    }   

    // Verifica l'esistenza di dati POST
    if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["email"]) && !empty($_POST["name"]) && 
        !empty($_POST["surname"]) && !empty($_POST["confirm_password"]) && !empty($_POST["allow"]))
    {
        $errore = array();
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

        
        // USERNAME
        // Controlla che l'username rispetti il pattern specificato
        if(!preg_match('/^[a-zA-Z0-9. ]{1,20}$/', $_POST['username'])) {
            $errore[] = "Username non valido";
        } else {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            // Cerco se l'username esiste già o se appartiene a una delle 3 parole chiave indicate
            $query = "SELECT username FROM users WHERE username = '$username'";
            $res = mysqli_query($conn, $query);
            if (mysqli_num_rows($res) > 0) {
                $errore[] = "Username già utilizzato";
            }
        }
        // PASSWORD
        if (strlen($_POST["password"]) < 8) {
                $errore[] = "Caratteri password insufficienti";
        } 
        // CONFERMA PASSWORD
        if (strcmp($_POST["password"], $_POST["confirm_password"]) != 0) {
            $errore[] = "Le password non coincidono";
        }
        // EMAIL
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errore[] = "Email non valida";
        } else {
            $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));
            $res = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
            if (mysqli_num_rows($res) > 0) {
                $errore[] = "Email già utilizzata";
            }
        }
        
        
        // REGISTRAZIONE NEL DATABASE
        if (count($errore) == 0) {
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $surname = mysqli_real_escape_string($conn, $_POST['surname']);

            $password = mysqli_real_escape_string($conn, $_POST['password']);
            $password = password_hash($password, PASSWORD_BCRYPT);
            

            $query = "INSERT INTO users(username, password, name, surname, email) VALUES('$username', '$password', '$name', '$surname', '$email')";
            
            if (mysqli_query($conn, $query)) {
                $_SESSION["_expo_username"] = $_POST["username"];
                $_SESSION["_expo_user_id"] = mysqli_insert_id($conn);
                mysqli_close($conn);
                header("Location: ./");
                exit;
            } else {
                $errore[] = "Errore di connessione al Database";
            }
        }

        mysqli_close($conn);
    }
    else if (isset($_POST["username"])) {
        $errore = array("Riempi tutti i campi");
    }

?>


<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>EXPO</title>
    <link rel="shortcut icon" href="../img/logo.ico" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab&family=Syne+Mono&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../style.css" />
    <link rel="stylesheet" href="./signup.css" />
    <script src="./signup.js" defer></script>
    </head>
    <body>
    <header>
    <nav id="menu">
      <a class="button" href="../">Home</a>
      <a class="button" href="../spazi">Spazi</a>
      <a class="button" href="../eventi">Eventi</a>
    </nav>
    <h1>
      <strong>Aziende</strong>
    </h1>
  </header>
        <main>
        <section>
                    <h1>Presentati</h1>
            <form name='signup' method='post' enctype="multipart/form-data" autocomplete="off">
                <div class="row">
                    <div class="name">
                        <div><label for='name'>Nome</label></div>
                        <!-- Se il submit non va a buon fine, il server reindirizza su questa stessa pagina, quindi va ricaricata con 
                            i valori precedentemente inseriti -->
                        <div><input type='text' name='name' <?php if(isset($_POST["name"])){echo "value=".$_POST["name"];} ?> ></div>
                        <span></span>
                    </div>
                    <div class="surname">
                        <div><label for='surname'>Cognome</label></div>
                        <div><input type='text' name='surname' <?php if(isset($_POST["surname"])){echo "value=".$_POST["surname"];} ?> ></div>
                        <span></span>
                    </div>
                </div>
                <div class="row">

                    <div class="username">
                        <div><label for='username'>Nome azienda</label></div>
                        <div><input type='text' name='username' <?php if(isset($_POST["username"])){echo "value=".$_POST["username"];} ?>></div>
                        <span>Nome utente non disponibile</span>
                    </div>
                    <div class="email">
                        <div><label for='email'>Email</label></div>
                        <div><input type='text' name='email' <?php if(isset($_POST["email"])){echo "value=".$_POST["email"];} ?>></div>
                        <span>Indirizzo email non valido</span>
                    </div>
                </div>
                <div class="row">
                <div class="password">
                    <div><label for='password'>Password</label></div>
                    <div><input type='password' name='password' <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?>></div>
                    <span>Inserisci almeno 8 caratteri</span>
                </div>
                <div class="confirm_password">
                    <div><label for='confirm_password'>Conferma Password</label></div>
                    <div><input type='password' name='confirm_password' <?php if(isset($_POST["confirm_password"])){echo "value=".$_POST["confirm_password"];} ?>></div>
                    <span>Le password non coincidono</span>
                </div>
                </div>
                
                <div class="allow"> 
                    <div><input required type='checkbox' name='allow' value="1" required <?php if(isset($_POST["allow"])){echo $_POST["allow"] ? "checked" : "";} ?>></div>
                    <div><label for='allow'> Acconsento ...</label></div>
                </div>
                <div class="submit">
                    <input type='submit' value="Registrati" id="submit" disabled>
                </div>
            </form>
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