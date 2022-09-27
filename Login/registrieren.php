<form action="" method="post">
    <label for="benutzername1">Benutzername:</label><br>
    <input type="text" id="benutzername1" name="benutzername" placeholder="Benutzername"><br>
    <label for="password1">Passwort:</label><br>
    <input type="password" id="password1" name="passwort1" placeholder="Passwort"><br>
    <label for="password2">Passwort:</label><br>
    <input type="password" id="password2" name="passwort2" placeholder="Passwort wiederholen"><br>
    <input type="submit" name="absenden" value="Absenden"><br>
</form>

<?php

//Datenbank-Verbindungdaten
$mysqli = new mysqli("localhost", "Larry", "123456789", "LoginDB");
if ($mysqli->connect_error) {
    die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
} else {
    echo $mysqli->host_info . "\n";
}

// falls absenden gedrückt wird
if (isset($_POST['absenden'])) :

    //Variablen werden gesetzt
    $benutzername = $_POST['benutzername'];
    $passwort = $_POST['passwort1'];
    $passwort2 = $_POST['passwort2'];

    //Select ob Benutzername gesetzt ist
    $search_user = $mysqli->prepare("SELECT id from benutzer where benutzername =?");
    $search_user->bind_param('s', $benutzername);
    $search_user->execute();
    $search_result = $search_user->get_result();

    // prüfen ob Resultat aus Select vorhanden ist
    if ($search_result->num_rows == 0) :
        if ($passwort == $passwort2) :
            // Passwort wiederholen richtig -->
            // Passwort wird verschlüsselt und in Datenbank eingetragen
            $passwort = md5($passwort);
            $insert = $mysqli->prepare("INSERT Into benutzer (benutzername, passwort) values (?,?)");
            $insert->bind_param('ss', $benutzername, $passwort);
            $insert->execute();
            if ($insert !== false) :
                echo "Dein Account wurde erfolgreich angelegt :)";
            endif;
        else :
            echo 'Die Passwörter stimmen nicht überein';
        endif;

    else :
        echo 'Der Benutzername ist schon vergeben';
    endif;



endif;

?>