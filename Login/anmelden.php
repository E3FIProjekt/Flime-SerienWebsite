<form action="" method="post">
    <label for="benutzername1">Benutzername:</label><br>
    <input type="text" id="benutzername1" name="benutzername" placeholder="Benutzername"><br>
    <label for="password1">Passwort:</label><br>
    <input type="password" id="password1" name="passwort1" placeholder="Passwort"><br>
    <input type="submit" name="absenden" value="Absenden"><br>
</form>


<?php

session_start();
//Datenbank-Verbindungdaten
$mysqli = new mysqli("localhost", "Larry", "123456789", "LoginDB");
if ($mysqli->connect_error) {
    die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
} else {
    echo $mysqli->host_info . "\n";
}

// falls absenden gedrückt wird
if (isset($_POST['absenden'])) :
      //Variablen werden gesetzt und passwort verschlüsselt
    $benutzername = strtolower($_POST['benutzername']);
    $passwort = $_POST['passwort1'];
    $passwort = md5($passwort);
    
    //select ob Benutzername + Passwort vorhanden ist
    $vorhanden = $mysqli->prepare("SELECT id from benutzer where benutzername =? and passwort = ?");
    $vorhanden->bind_param('ss', $benutzername, $passwort);
    $vorhanden->execute();
    $search_result = $vorhanden->get_result();
    
    //falls ja wird der User wieder auf seine Seite gepackt
    if ($search_result->num_rows == 1) :
        $search_object = $search_result->fetch_object();
        $_SESSION['benutzer'] = $search_object->id;
        header('Location: /ui.php'); //!!!!!!!!!!!!!!!! muss noch geändert werden
    else :
        echo 'Nö!!!!';
    endif;

endif;




?>