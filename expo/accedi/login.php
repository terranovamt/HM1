<?php
    // Verifica che l'utente sia già loggato, in caso positivo va direttamente alla home
    include 'checksess.php';  
    if (checkSess()) {
        header('Location: ./');
        exit;
    }

    if (!empty($_POST["username"]) && !empty($_POST["password"]) )
    {
        // Se username e password sono stati inviati
        // Connessione al DB
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
        // Preparazione 
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        
        
        // Permette l'accesso tramite email o username in modo intercambiabile
        $searchField = filter_var($username, FILTER_VALIDATE_EMAIL) ? "email" : "username";
        // ID e Username per sessione, password per controllo
        $query = "SELECT id, username, password FROM users WHERE $searchField = '$username'";
        // Esecuzione
        
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
        if (mysqli_num_rows($res) == 0){
          $error = "Nome azienda o email non registrata."; 
          
        }
  
        if (mysqli_num_rows($res) > 0) {
          $entry = mysqli_fetch_assoc($res);
          
          $cont=password_verify($password,$entry['password']);
          
          if ($cont==1) {
            $_SESSION["_expo_username"] = $entry['username'];
            $_SESSION["_expo_user_id"] = $entry['id'];
            header("Location: ./");
            mysqli_free_result($res);
            mysqli_close($conn);
            exit;
          }
          $error = "Password errata.";
        }
        // Se l'utente non è stato trovato o la password non ha passato la verifica
        
    }
    else if (isset($_POST["username"]) || isset($_POST["password"])) {
        // Se solo uno dei due è impostato
        $error = "Inserisci nome azienda e password.";
    }

?>


<!DOCTYPE html>
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
    <!----------------------------------------------->
    <link rel="stylesheet" href="./accedi.css" />
    <!----------------------------------------------->

    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="704116477846-g00cn0npuqrhf40ns8pajre07idb50bb.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    
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
    <section class="main_left">
      <!--
      <div class="g-signin2" data-onsuccess="onSignIn" ></div>
      <script>
        function onSignIn(googleUser) {
          // Useful data for your client-side scripts:
          var profile = googleUser.getBasicProfile();
          
          console.log("ID: " + profile.getId()); // Don't send this directly to your server!
          console.log('Full Name: ' + profile.getName());
          console.log('Given Name: ' + profile.getGivenName());
          console.log('Family Name: ' + profile.getFamilyName());
          console.log("Image URL: " + profile.getImageUrl());
           $_POST['username'] = profile.getEmail());

          // The ID token you need to pass to your backend:
          var id_token = googleUser.getAuthResponse().id_token;
          console.log("ID Token: " + id_token);
        }
      </script>
            <a href="#" onclick="signOut();">Sign out</a>
      <script>
        function signOut() {
          var auth2 = gapi.auth2.getAuthInstance();
          auth2.signOut().then(function () {
            console.log('User signed out.');
          });
        }
      </script>-->
      <div class="signup">La tua azienda non é iscritta?   <a href="signup.php">Iscriviti</a>
        </section>
    <section class="main_right">
            <h1>Bentornati</h1>
            <?php
                // Verifica la presenza di errori
                if (isset($error)) {
                    echo "<span class='error'>$error</span>";
                }
                
            ?>
            <form name='login' method='post'>
                <!-- Seleziono il valore di ogni campo sulla base dei valori inviati al server via POST -->
                <div class="username">
                    <div><label for='username'>Nome azienda o email</label></div>
                    <div><input type='text' name='username' <?php if(isset($_POST["username"])){echo "value=".$_POST["username"];} ?>></div>
                </div>
                <div class="password">
                    <div><label for='password'>Password</label></div>
                    <div><input type='password' name='password' <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} 
                    ?>></div>
                  <div class="remember">
                    <div><input type='checkbox' name='remember' value="1" <?php if(isset($_POST["remember"])){echo $_POST["remember"] ? "checked" : "";} ?>></div>
                    <div><label for='remember'>Ricorda l'accesso</label></div>
                </div>
                </div>
                    <div class="accedi">
                    <input type='submit' value="Accedi">
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
