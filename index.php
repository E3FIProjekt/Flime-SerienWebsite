<?php
$apikey = "eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyNmQ4NWE3YWUxM2Q2MWE1Y2ZjNDNjYjdjMjFhMDQ1ZCIsInN1YiI6IjYzMjk4OWNkZGYyOTQ1MDA3YmFjYTNiZCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.BXumrZuJ8oFoLPDyL9vfTjvH6xlXYzQoPavaXHiJsZM";
session_start();
//Datenbank-Verbindungdaten
//$mysqli = new mysqli("localhost", "Larry", "123456789", "LoginDB");


print '
<head>
	<link href="bootstrap.min.css" rel="stylesheet">
	<script src="bootstrap.bundle.min.js" type="text/javascript" ></script>
    <link rel="stylesheet" href="css/style.css">
    <link href="css/fontawesome.min.css" rel="stylesheet">
    <link href="css/brands.min.css" rel="stylesheet">
    <link href="css/solid.min.css" rel="stylesheet">
    <title>Film Serien Website</title>
</head>

<nav class="navbar navbar-expand-lg navbar-light bg-light">

  <div class="container-fluid">
    <a class="navbar-brand" href="http://localhost/FilmSerienWebsite/index.php">Film Serien Website</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Filme
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="http://localhost/FilmSerienWebsite/index.php?popularmovies">Beliebte Filme</a></li>
          </ul>
        </li>
        
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            TV Shows
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="http://localhost/FilmSerienWebsite/index.php?populartv">Beliebte TV Shows</a></li>
          </ul>
        </li>
        
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Personen
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="http://localhost/FilmSerienWebsite/index.php?popularperson">Beliebte Personen</a></li>
          </ul>
        </li>
         
      </ul>';
      if(isset($_SESSION["benutzer"])){
      print'<li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">';
          //Select für benutzername
          $benutzerid = $_SESSION['benutzer'];
          $search_username = $mysqli->prepare("SELECT benutzername from benutzer where id =?");
          $search_username->bind_param('s', $benutzerid);
          $search_username->execute();
          $username_result = $search_username->get_result();

          while ($row = $username_result->fetch_assoc()) {
              $username = $row['benutzername'];
          }
          print $username;

          print'</a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="http://localhost/FilmSerienWebsite/index.php?profil=true">Profil anzeigen</a></li>
            <li><a class="dropdown-item" href="http://localhost/FilmSerienWebsite/index.php?einstellung">Einstellungen</a></li>
            <li><a class="dropdown-item" href="http://localhost/FilmSerienWebsite/index.php?abmelden=true">Abmelden</a></li>
          </ul>
        </li>';
       }else{
          print '<a href="http://localhost/FilmSerienWebsite/index.php?anmelden=true"><button type="button" class="btn btn-light">Anmelden</button></a>';
      }
 print'   </div>
  </div>
</nav>';

if (isset($_POST['absenden'])) :

    //Variablen werden gesetzt
    $benutzername = $_POST['benutzername'];
    $passwort = $_POST['passwort1'];
    $passwort2 = $_POST['passwort2'];

    //Select ob Benutzername schon gesetzt ist
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
                print '<div class="alert alert-light form-signin" role="alert">
Erfolgreich registriert</div>';
            endif;
        else :
            print '<div class="alert alert-light form-signin" role="alert">
Die Passwörter stimmen nicht überein</div>';
            echo '';
        endif;

    else :
        print '<div class="alert alert-light center form-signin" role="alert">
Schon vergeben</div>';
    endif;
endif;

if (isset($_POST['absendenanmelden'])) :
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

    else :
        print '<div class="alert alert-light center form-signin" role="alert">
Stimmt nicht überein</div>';
    endif;
endif;




      if(!isset($_REQUEST["anmelden"]) and !isset($_REQUEST["registrieren"])) {
          print '
<div class="searchbar">
<form>
<label for="suche">Suche:</label>
<input type="text" id="suche2" name="suche2" placeholder="Suche nach TV Show, Filme und Personen" size="50">
<input type="hidden" value="1" name="page">
<input type="submit" value="suchen">
</form>
</div>
';
      }

if(isset($_REQUEST["popularmovies"])){
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.themoviedb.org/3/discover/movie?sort_by=popularity.desc&language=de',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyNmQ4NWE3YWUxM2Q2MWE1Y2ZjNDNjYjdjMjFhMDQ1ZCIsInN1YiI6IjYzMjk4OWNkZGYyOTQ1MDA3YmFjYTNiZCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.BXumrZuJ8oFoLPDyL9vfTjvH6xlXYzQoPavaXHiJsZM",
            "Content-Type: application/json;charset=utf-8"
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $test = json_decode($response, true);
    }

    print '<div class="container">
<h2>Beliebte Filme</h2> ';

  print'<div class="row">';
        for ($i = 0; $i <= 5; $i++) {
        print '<div class="col-2 border">';
        print '<div><a href="http://localhost/FilmSerienWebsite/index.php?moviedetails=' . $test["results"][$i]["id"] . '"><img class="shadow-sm  bg-body rounded" src="https://image.tmdb.org/t/p/w500' . $test["results"][$i]["backdrop_path"] . '" width="196" height="175"></a></div>';
        print '<div>Bewertung</div>';
        print '<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) . '%;" aria-valuenow="' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) . '" aria-valuemin="0" aria-valuemax="100">' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) .'%</div></div>';
        print '<div><strong>' . $test["results"][$i]["title"] . '</strong></div>';
        print '<div>' . $test["results"][$i]["release_date"] . '</div>';
        print '</div>';
    }
    print '</div>';

    print'<div class="row">';
        for ($i = 6; $i <= 11; $i++) {
        print '<div class="col-2 border">';
        print '<div><a href="http://localhost/FilmSerienWebsite/index.php?moviedetails=' . $test["results"][$i]["id"] . '"><img class="shadow-sm  bg-body rounded" src="https://image.tmdb.org/t/p/w500' . $test["results"][$i]["backdrop_path"] . '" width="196" height="175"></a></div>';
        print '<div>Bewertung</div>';
        print '<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) . '%;" aria-valuenow="' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) . '" aria-valuemin="0" aria-valuemax="100">' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) .'%</div></div>';
        print '<div><strong>' . $test["results"][$i]["title"] . '</strong></div>';
        print '<div>' . $test["results"][$i]["release_date"] . '</div>';
        print '</div>';
    }
    print '</div>';

    print'<div class="row">';
    for ($i = 12; $i <= 17; $i++) {
        print '<div class="col-2 border">';
        print '<div><a href="http://localhost/FilmSerienWebsite/index.php?moviedetails=' . $test["results"][$i]["id"] . '"><img class="shadow-sm  bg-body rounded" src="https://image.tmdb.org/t/p/w500' . $test["results"][$i]["backdrop_path"] . '" width="196" height="175"></a></div>';
        print '<div>Bewertung</div>';
        print '<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) . '%;" aria-valuenow="' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) . '" aria-valuemin="0" aria-valuemax="100">' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) .'%</div></div>';
        print '<div><strong>' . $test["results"][$i]["title"] . '</strong></div>';
        print '<div>' . $test["results"][$i]["release_date"] . '</div>';
        print '</div>';
    }
    print '</div>';

print'</div>';
}


if(isset($_REQUEST["populartv"])){
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.themoviedb.org/3/discover/tv?sort_by=popularity.desc&language=de',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyNmQ4NWE3YWUxM2Q2MWE1Y2ZjNDNjYjdjMjFhMDQ1ZCIsInN1YiI6IjYzMjk4OWNkZGYyOTQ1MDA3YmFjYTNiZCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.BXumrZuJ8oFoLPDyL9vfTjvH6xlXYzQoPavaXHiJsZM",
            "Content-Type: application/json;charset=utf-8"
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $test = json_decode($response, true);
    }

    print '<div class="container">
<h2>Beliebte Serien</h2> ';

    print'<div class="row">';
    for ($i = 0; $i <= 5; $i++) {
        print '<div class="col-2 border">';
        print '<div><a href="http://localhost/FilmSerienWebsite/index.php?tvdetails=' . $test["results"][$i]["id"] . '"><img class="shadow-sm  bg-body rounded" src="https://image.tmdb.org/t/p/w500' . $test["results"][$i]["backdrop_path"] . '" width="196" height="175"></a></div>';
        print '<div>Bewertung</div>';
        print '<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) . '%;" aria-valuenow="' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) . '" aria-valuemin="0" aria-valuemax="100">' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) .'%</div></div>';
        print '<div><strong>' . $test["results"][$i]["name"] . '</strong></div>';
        print '<div>' . $test["results"][$i]["first_air_date"] . '</div>';
        print '</div>';
    }
    print '</div>';

    print'<div class="row">';
    for ($i = 6; $i <= 11; $i++) {
        print '<div class="col-2 border">';
        print '<div><a href="http://localhost/FilmSerienWebsite/index.php?tvdetails=' . $test["results"][$i]["id"] . '"><img class="shadow-sm  bg-body rounded" src="https://image.tmdb.org/t/p/w500' . $test["results"][$i]["backdrop_path"] . '" width="196" height="175"></a></div>';
        print '<div>Bewertung</div>';
        print '<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) . '%;" aria-valuenow="' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) . '" aria-valuemin="0" aria-valuemax="100">' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) .'%</div></div>';
        print '<div><strong>' . $test["results"][$i]["name"] . '</strong></div>';
        print '<div>' . $test["results"][$i]["first_air_date"] . '</div>';
        print '</div>';
    }
    print '</div>';

    print'<div class="row">';
    for ($i = 12; $i <= 17; $i++) {
        print '<div class="col-2 border">';
        print '<div><a href="http://localhost/FilmSerienWebsite/index.php?tvdetails=' . $test["results"][$i]["id"] . '"><img class="shadow-sm  bg-body rounded" src="https://image.tmdb.org/t/p/w500' . $test["results"][$i]["backdrop_path"] . '" width="196" height="175"></a></div>';
        print '<div>Bewertung</div>';
        print '<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) . '%;" aria-valuenow="' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) . '" aria-valuemin="0" aria-valuemax="100">' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) .'%</div></div>';
        print '<div><strong>' . $test["results"][$i]["name"] . '</strong></div>';
        print '<div>' . $test["results"][$i]["first_air_date"] . '</div>';
        print '</div>';
    }
    print '</div>';

    print'</div>';
}


if(isset($_REQUEST["popularperson"])){
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.themoviedb.org/3/person/popular?&language=de',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyNmQ4NWE3YWUxM2Q2MWE1Y2ZjNDNjYjdjMjFhMDQ1ZCIsInN1YiI6IjYzMjk4OWNkZGYyOTQ1MDA3YmFjYTNiZCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.BXumrZuJ8oFoLPDyL9vfTjvH6xlXYzQoPavaXHiJsZM",
            "Content-Type: application/json;charset=utf-8"
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $test = json_decode($response, true);
    }

    print '<div class="container">
<h2>Beliebte Personen</h2> ';

    print'<div class="row">';
    for ($i = 0; $i <= 5; $i++) {
        print '<div class="col-2 border">';
        if (isset($test["results"][$i]["profile_path"])) {
            print '<div><a href="http://localhost/FilmSerienWebsite/index.php?persondetails=' . $test["results"][$i]["id"] . '"><img class="shadow-sm  bg-body rounded" src="https://image.tmdb.org/t/p/w500' . $test["results"][$i]["profile_path"] . '" width="196" height="175"></a></div>';
        } else {
            print '<div><a href="http://localhost/FilmSerienWebsite/index.php?persondetails=' . $test["results"][$i]["id"] . '"><img class="shadow-sm bg-body rounded"  src="placeholder.jpg" width="196" height="175"></a></div>';
        }
        print '<div><strong>' . $test["results"][$i]["name"] . '</strong></div>';
        print '</div>';
    }
    print '</div>';

    print'<div class="row">';
    for ($i = 6; $i <= 11; $i++) {
        print '<div class="col-2 border">';
        if (isset($test["results"][$i]["profile_path"])) {
            print '<div><a href="http://localhost/FilmSerienWebsite/index.php?persondetails=' . $test["results"][$i]["id"] . '"><img class="shadow-sm  bg-body rounded" src="https://image.tmdb.org/t/p/w500' . $test["results"][$i]["profile_path"] . '" width="196" height="175"></a></div>';
        } else {
            print '<div><a href="http://localhost/FilmSerienWebsite/index.php?persondetails=' . $test["results"][$i]["id"] . '"><img class="shadow-sm bg-body rounded"  src="placeholder.jpg" width="196" height="175"></a></div>';
        }
        print '<div><strong>' . $test["results"][$i]["name"] . '</strong></div>';
        print '</div>';
    }
    print '</div>';

    print'<div class="row">';
    for ($i = 12; $i <= 17; $i++) {
        print '<div class="col-2 border">';
        if (isset($test["results"][$i]["profile_path"])) {
            print '<div><a href="http://localhost/FilmSerienWebsite/index.php?persondetails=' . $test["results"][$i]["id"] . '"><img class="shadow-sm  bg-body rounded" src="https://image.tmdb.org/t/p/w500' . $test["results"][$i]["profile_path"] . '" width="196" height="175"></a></div>';
        } else {
            print '<div><a href="http://localhost/FilmSerienWebsite/index.php?persondetails=' . $test["results"][$i]["id"] . '"><img class="shadow-sm bg-body rounded"  src="placeholder.jpg" width="196" height="175"></a></div>';
        }
        print '<div><strong>' . $test["results"][$i]["name"] . '</strong></div>';
        print '</div>';
    }
    print '</div>';

    print'</div>';
}


if (isset($_REQUEST["profil"])){
    $page2 = '';
    if (isset($_GET['page2'])) $page2 = strtolower($_GET['page2']);
    if (isset($_SESSION) && $_SESSION != null) {
        $benutzerid = $_SESSION['benutzer'];
            //Select für Anzahl an bewertungen
            $search_bewertung = $mysqli->prepare("SELECT count(id) from bewertung where benutzer_id =?");
            $search_bewertung->bind_param('s', $benutzerid);
            $search_bewertung->execute();
            $search_result = $search_bewertung->get_result();
            if ($search_result->num_rows > 0) {
                $anzBewertung = $search_result->num_rows;
            } else {
                $anzBewertung = 0;
            }
            //Select für durchschnittliche Bewertungen
            $search_username = $mysqli->prepare("SELECT punkte from bewertung where benutzer_id =?");
            $search_username->bind_param('s', $benutzerid);
            $search_username->execute();
            $username_result = $search_username->get_result();
            $username = 0;

            while ($row = $username_result->fetch_assoc()) {

                $username = $username + $row['punkte'];
            }
            if ($anzBewertung == 0) {
                $durchpunkte = 0;
            } else {
                $durchpunkte = $username / $anzBewertung;
            }
            //Select für benutzername
            $search_username = $mysqli->prepare("SELECT benutzername from benutzer where id =?");
            $search_username->bind_param('s', $benutzerid);
            $search_username->execute();
            $username_result = $search_username->get_result();

            while ($row = $username_result->fetch_assoc()) {
                $username = $row['benutzername'];
            }
            print '
        <img src ="User.jpg">
        <p>Benutzername: ' . $username . '</p>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" aria-label="Tenth navbar example">
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample08" aria-controls="navbarsExample08" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
    
          <div class="collapse navbar-collapse justify-content-md-center" id="navbarsExample08">
            <ul class="navbar-nav">
              
              <li class="nav-item">
                <a class="nav-link" href="index.php?profil=true&page2=ubersicht">Übersicht</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="index.php?profil=true&page2=watchlist">Watchlist</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="index.php?profil=true&page2=einstellungen">Einstellungen</a>
              </li>
              
            </ul>
          </div>
        </div>
      </nav>
    
    ';
        if(isset($_REQUEST["page2"])){
        if($_REQUEST["page2"]=="ubersicht"){
        print '<p>' . $anzBewertung . '</p>
    <p>durchschnittliche Bewertung</p>
    <p>Ø' . $durchpunkte . '<span class="fa fa-star checked"></span></p>';
        }}
        if ($page2 == 'watchlist') {
            // Select für Film-ID und dann durch API auf Bilder zugreifen

            $select_watchlist = $mysqli->prepare("SELECT film_id from watchlist where benutzer_id=?");
            $select_watchlist->bind_param('s', $benutzerid);
            $select_watchlist->execute();
            $select_watchlist_result = $select_watchlist->get_result();
            $ergebnis = $select_watch_result->num_rows;

            for ($anzahl = 0; $anzahl > $ergebnis; $anzahl++) {
                // API zugriff und Bilder hinzufügen
            }
        }
        // Einstellungen
        /*
        - E-Mail-Adresse hinterlegen --> auch in login?
        - Benutzer löschen OK
        - Passwort ändern OK
        - (Beschreibung hinzufügen)
        - (Profilbild ändern)
        */
        if ($page2 == 'einstellungen')
            print '<form action="" method="post">
                <input type="submit" name="loeschen" value="Konto löschen">
                <button name="aendern">Passwort ändern</button>
                </form>';
        if (isset($_POST['loeschen'])) {
            $del_user = $mysqli->prepare("DELETE from benutzer where id=?");
            $del_user->bind_param('s', $benutzerid);
            $del_user->execute();


            $del_bew = $mysqli->prepare("DELETE from bewertung where benutzer_id=?");
            $del_bew->bind_param('s', $benutzerid);
            $del_bew->execute();

            $del_watch = $mysqli->prepare("DELETE from watchlist where benutzerid=?");
            $del_watch->bind_param('s', $benutzerid);
            $del_watch->execute();

            session_unset();

            if ($del_user !== false && $del_bew !== false && $del_watch !== false) :
                echo "Deine Konto wurde gelöscht"; // popup draus machen
                require_once('index.php');
            endif;
        }
        if (isset($_POST['aendern'])) {

            print '<form action="" method="post">
        <label for="altesPw">altes Passwort:</label><br>
    <input type="password" id="altesPw" name="altesPw" placeholder="altes Passwort"><br>
    <label for="neuesPw">neues Passwort:</label><br>
    <input type="password" id="neuesPw" name="neuesPw" placeholder="neues Passwort"><br>
    <input type="submit" name="Pwaendern" value="Passwort ändern"><br>
    </form>';
        }
        if (isset($_POST['Pwaendern'])) {
            $altesPw = $_POST['altesPw'];
            $altesPw=md5($altesPw);
            $search_passwort = $mysqli->prepare("SELECT * from benutzer where id =? and passwort = ?");
            $search_passwort->bind_param('ss', $benutzerid, $altesPw);
            $search_passwort->execute();
            $search_passwort_result = $search_passwort->get_result();

            if ($search_passwort_result->num_rows == 1) {
                $neuesPw = md5($_POST['neuesPw']);

                $update_passwort = $mysqli->prepare("UPDATE benutzer set passwort=? where id=?");
                $update_passwort->bind_param('ss', $neuesPw, $benutzerid);
                $update_passwort->execute();
                if ($update_passwort !== false) :
                    echo "Dein Passwort wurde geändert";
                endif;
            }else{
                echo "Das alte Passwort ist falsch!";
            }
        }
    } else {
        header('Location: anmelden.php');
    }
}

if(!isset($_REQUEST["moviedetails"]) and !isset($_REQUEST["page"]) and !isset($_REQUEST["tvdetails"]) and !isset($_REQUEST["persondetails"]) and !isset($_REQUEST["suchergebnis"]) and !isset($_REQUEST["popularmovies"]) and !isset($_REQUEST["anmelden"])  and !isset($_REQUEST["registrieren"]) and !isset($_REQUEST["populartv"]) and !isset($_REQUEST["popularperson"]) and !isset($_REQUEST["profil"]) and !isset($_REQUEST["page2"])){
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.themoviedb.org/3/discover/movie?sort_by=popularity.desc&language=de',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyNmQ4NWE3YWUxM2Q2MWE1Y2ZjNDNjYjdjMjFhMDQ1ZCIsInN1YiI6IjYzMjk4OWNkZGYyOTQ1MDA3YmFjYTNiZCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.BXumrZuJ8oFoLPDyL9vfTjvH6xlXYzQoPavaXHiJsZM",
            "Content-Type: application/json;charset=utf-8"
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $test = json_decode($response, true);
    }



    print '<div class="container">
<h2>Beliebte Filme</h2>
  <div class="row">
  ';

    for ($i = 0; $i <= 5; $i++) {
        print '<div class="col-2 border">';
        print '<div><a href="http://localhost/FilmSerienWebsite/index.php?&moviedetails=' . $test["results"][$i]["id"] . '"><img class="shadow-sm  bg-body rounded" src="https://image.tmdb.org/t/p/w500' . $test["results"][$i]["backdrop_path"] . '" width="196" height="175"></a></div>';
        print '<div>Bewertung</div>';
        print '<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) . '%;" aria-valuenow="' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) . '" aria-valuemin="0" aria-valuemax="100">' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) .'%</div></div>';
        print '<div><strong>' . $test["results"][$i]["title"] . '</strong></div>';
        print '<div>' . $test["results"][$i]["release_date"] . '</div>';
        print '</div>';
    }
    print '</div></div></div>';


    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.themoviedb.org/3/discover/tv?sort_by=popularity.desc&language=de',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyNmQ4NWE3YWUxM2Q2MWE1Y2ZjNDNjYjdjMjFhMDQ1ZCIsInN1YiI6IjYzMjk4OWNkZGYyOTQ1MDA3YmFjYTNiZCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.BXumrZuJ8oFoLPDyL9vfTjvH6xlXYzQoPavaXHiJsZM",
            "Content-Type: application/json;charset=utf-8"
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $test = json_decode($response, true);
    }

    print '<div class="container textrunter">
<h2>Beliebte Serien</h2>
  <div class="row">
  ';

    for ($i = 0; $i <= 5; $i++) {
        print '<div class="col-2 border">';
        print '<div><a href="http://localhost/FilmSerienWebsite/index.php?tvdetails=' . $test["results"][$i]["id"] . '"><img class="shadow-sm  bg-body rounded" src="https://image.tmdb.org/t/p/w500' . $test["results"][$i]["backdrop_path"] . '" width="196" height="175"></a></div>';
        print '<div>Bewertung</div>';
        print '<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) . '%;" aria-valuenow="' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) . '" aria-valuemin="0" aria-valuemax="100">' . substr($test["results"][$i]["vote_average"],0,1).substr($test["results"][$i]["vote_average"],2,1) .'%</div></div>';
        print '<div><strong>' . $test["results"][$i]["name"] . '</strong></div>';
        print '<div>' . $test["results"][$i]["first_air_date"] . '</div>';
        print '</div>';
    }
    print '</div></div></div>';


    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.themoviedb.org/3/person/popular?&language=de',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyNmQ4NWE3YWUxM2Q2MWE1Y2ZjNDNjYjdjMjFhMDQ1ZCIsInN1YiI6IjYzMjk4OWNkZGYyOTQ1MDA3YmFjYTNiZCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.BXumrZuJ8oFoLPDyL9vfTjvH6xlXYzQoPavaXHiJsZM",
            "Content-Type: application/json;charset=utf-8"
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $test = json_decode($response, true);
    }


    print '<div class="container textrunter">
<h2>Beliebte Personen</h2>
  <div class="row">
  ';

    for ($i = 0; $i <= 5; $i++) {
        print '<div class="col-2 border">';
        if (isset($test["results"][$i]["profile_path"])) {
            print '<div><a href="http://localhost/FilmSerienWebsite/index.php?persondetails=' . $test["results"][$i]["id"] . '"><img class="shadow-sm  bg-body rounded" src="https://image.tmdb.org/t/p/w500' . $test["results"][$i]["profile_path"] . '" width="196" height="175"></a></div>';
        } else {
            print '<div><a href="http://localhost/FilmSerienWebsite/index.php?persondetails=' . $test["results"][$i]["id"] . '"><img class="shadow-sm bg-body rounded"  src="placeholder.jpg" width="196" height="175"></a></div>';
        }
        print '<div><strong>' . $test["results"][$i]["name"] . '</strong></div>';
        print '</div>';
    }
    print '</div></div></div>';
}
if (isset($_REQUEST["page"])) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.themoviedb.org/3/search/multi?&language=de&page=' . $_REQUEST["page"] . '&query=' . str_replace(" ", "%20", $_REQUEST["suche2"]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyNmQ4NWE3YWUxM2Q2MWE1Y2ZjNDNjYjdjMjFhMDQ1ZCIsInN1YiI6IjYzMjk4OWNkZGYyOTQ1MDA3YmFjYTNiZCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.BXumrZuJ8oFoLPDyL9vfTjvH6xlXYzQoPavaXHiJsZM",
            "Content-Type: application/json;charset=utf-8"
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $test = json_decode($response, true);




        $serienvar = 0;
        $filmevar = 0;
        $personenvar = 0;
        for ($i = 1; $i <= $test["total_pages"]; $i++) {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.themoviedb.org/3/search/multi?&language=de&page=' . $i . '&query=' . str_replace(" ", "%20", $_REQUEST["suche2"]),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyNmQ4NWE3YWUxM2Q2MWE1Y2ZjNDNjYjdjMjFhMDQ1ZCIsInN1YiI6IjYzMjk4OWNkZGYyOTQ1MDA3YmFjYTNiZCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.BXumrZuJ8oFoLPDyL9vfTjvH6xlXYzQoPavaXHiJsZM",
                    "Content-Type: application/json;charset=utf-8"
                ],
            ]);
            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $suchergebiszeahler = json_decode($response, true);
            }

            foreach ($suchergebiszeahler["results"] as $value) {
                if ($value["media_type"] == "movie") {
                    $filmevar = $filmevar + 1;
                }
                if ($value["media_type"] == "tv") {
                    $serienvar = $serienvar + 1;
                }
                if ($value["media_type"] == "person") {
                    $personenvar = $personenvar + 1;
                }
            }
        }


        print'<div id="steuerung">
<div class="vertical-menu">
<p>Suchergebnisse</p>
  <a href="http://localhost/FilmSerienWebsite/index.php?suche2='.$_REQUEST["suche2"].'%20&suchergebnis=tv">Serien (' . $serienvar . ')</a>
  <a href="http://localhost/FilmSerienWebsite/index.php?suche2='.$_REQUEST["suche2"].'%20&suchergebnis=movie">Filme (' . $filmevar . ')</a>
  <a href="http://localhost/FilmSerienWebsite/index.php?suche2='.$_REQUEST["suche2"].'%20&suchergebnis=person">Personen (' . $personenvar . ')</a>
</div></div>';

        print '<div id="zweite_spalte"><div class="container">';

        foreach ($test["results"] as $value) {
            print '<div class="row mb-5">';

            if (isset($value["backdrop_path"]) and strlen($value["backdrop_path"]) >= 1) {
                if($value["media_type"]=="movie"){
                    print '<div class="col-1"><a href="http://localhost/FilmSerienWebsite/index.php?moviedetails=' . $value["id"] . '">';
                }elseif($value["media_type"]=="tv"){
                    print '<div class="col-1"><a href="http://localhost/FilmSerienWebsite/index.php?tvdetails=' . $value["id"] . '">';
                }elseif($value["media_type"]=="person"){
                    print '<div class="col-1"><a href="http://localhost/FilmSerienWebsite/index.php?persondetails=' . $value["id"] . '">';
                }
                print '<img class="shadow-sm  bg-body rounded" src="https://image.tmdb.org/t/p/w500' . $value["poster_path"] . '" width="94" height="141"></a></div>';
            } else {
                print '<div class="col-1"><a href="http://localhost/FilmSerienWebsite/index.php?moviedetails=' . $value["id"] . '">';
                print '<img class="shadow-sm bg-body rounded"  src="placeholder.jpg" width="94" height="141"></a></div>';
            }


            //print '<img src="https://image.tmdb.org/t/p/w500' . $value["profile_path"] . '" width="94" height="141"></a>';
            if (isset($value["title"])) {
                print '<div class="col-11"><strong>' . $value["title"].'</strong>';
                print '<div class="vertical">' . $value["overview"] . "<br></div></div>";
            } elseif (isset($value["name"])) {

                print '<div class="col-11"><strong>'.$value["name"].'</strong>';

                print '<div class="vertical">';
                if (isset($value["overview"])) {
                    print $value["overview"];
                }
                print"<br></div></div>";

            } else {
                print "keine name vorhanden<br></div>";
            }
            print '</div>';
        }


        print '</div></div></div>';

    }

    print '<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">';
    print '<li class="page-item ';if($_REQUEST["page"] == 1){print "disabled";} print '"><a class="page-link" href ="http://localhost/FilmSerienWebsite/index.php?suche2=' . $_REQUEST["suche2"] . '&page=';
        print $_REQUEST["page"] = $_REQUEST["page"] - 1;
        print '" >Previous</a ></li >';
        $_REQUEST["page"] = $_REQUEST["page"] + 1;
        print'<li class="page-item ';
    if ($_REQUEST["page"] == 1) {
        print "active";
    }
    print'"><a class="page-link" href="http://localhost/FilmSerienWebsite/index.php?suche2=' . $_REQUEST["suche2"] . '&page=1">1</a></li>';
    if ($_REQUEST["page"]==1 and $test["total_pages"]>1){
        print'<li class="page-item"><a class="page-link" href="http://localhost/FilmSerienWebsite/index.php?suche2=' . $_REQUEST["suche2"] . '&page=2">2</a></li>';
    }elseif($_REQUEST["page"]==2){
        print'<li class="page-item"><a class="page-link active" href="http://localhost/FilmSerienWebsite/index.php?suche2=' . $_REQUEST["suche2"] . '&page=2">2</a></li>';
        print'<li class="page-item"><a class="page-link" href="http://localhost/FilmSerienWebsite/index.php?suche2=' . $_REQUEST["suche2"] . '&page=2">3</a></li>';
    }elseif($_REQUEST["page"]>2){

    }
    print'<a class="page-link" href="http://localhost/FilmSerienWebsite/index.php?suche2=' . $_REQUEST["suche2"] . '&page=3">3</a></li>

    <li class="page-item ';
    if ($_REQUEST["page"] == 4) {
        print "active";
    }
    print'"><a class="page-link" href="http://localhost/FilmSerienWebsite/index.php?suche2=' . $_REQUEST["suche2"] . '&page=' . $test["total_pages"] . '">' . $test["total_pages"] . '</a></li>';

    print '<li class="page-item';if($_REQUEST["page"] == $test["total_pages"]){print " disabled";} print '"><a class="page-link" href ="http://localhost/FilmSerienWebsite/index.php?suche2=' . $_REQUEST["suche2"] . '&page=';
    print $_REQUEST["page"] = $_REQUEST["page"] + 1;
    print '" >Next</a ></li >';
    $_REQUEST["page"] = $_REQUEST["page"] - 1;

    print'  </li>
  </ul>
</nav>';

}





if (isset($_REQUEST["moviedetails"])) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.themoviedb.org/3/movie/' . $_REQUEST["moviedetails"] . '?&language=de',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyNmQ4NWE3YWUxM2Q2MWE1Y2ZjNDNjYjdjMjFhMDQ1ZCIsInN1YiI6IjYzMjk4OWNkZGYyOTQ1MDA3YmFjYTNiZCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.BXumrZuJ8oFoLPDyL9vfTjvH6xlXYzQoPavaXHiJsZM",
            "Content-Type: application/json;charset=utf-8"
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $test = json_decode($response, true);
    }
    $mediatype="movie";







    $ergebnis=0;
    print '<div class="container" id="zweite_spalte">
  <div class="row">
    <div class="col-4">
      <img class="shadow-sm  bg-body rounded" src="https://image.tmdb.org/t/p/w500' . $test["poster_path"] . '" width="360" height="450">
    </div>
    <div class="col-8">
    
      <h3><strong>'.$test["title"].'</strong></h3>
      
      <div class="unter">'.$test["release_date"];print' - '; foreach($test["genres"] as $value){print $value["name"]." ";} print'</div>

      <div>Benutzerbewertungen: </div>
      <div><div class="progress">
  <div class="progress-bar" role="progressbar" style="width: '.substr($test["vote_average"],0,1).substr($test["vote_average"],2,1).'%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">'.substr($test["vote_average"],0,1).substr($test["vote_average"],2,1).'%</div></div></div>
   <div><span class="fa fa-star" id="1" onclick="checked(1)" onmouseover="hover(1)" onmouseleave="hoverout(1)"></span>
<span class="fa fa-star" id="2" onclick="checked(2)" onmouseover="hover(2)" onmouseleave="hoverout(2)"></span>
<span class="fa fa-star" id="3" onclick="checked(3)" onmouseover="hover(3)" onmouseleave="hoverout(3)"></span>
<span class="fa fa-star" id="4" onclick="checked(4)" onmouseover="hover(4)" onmouseleave="hoverout(4)"></span>
<span class="fa fa-star" id="5" onclick="checked(5)" onmouseover="hover(5)" onmouseleave="hoverout(5)"></span>'; if ($ergebnis == 0) {
        print '
        <form action="" method="post">
        <i class="fa-solid fa-heart-circle-plus" id= "test" onclick="hinzufügen()"></i></form>';
    } else if ($ergebnis > 0) {
        print '
        <form action="" method="post">
        <i class="fa-solid fa-heart" style="color:red" id= "test" onclick="hinzufügen()"></i></form>';
    }print'</div>
   <div class="textrunter"><h5>Handlung</h5></div>
   <div>'.$test["overview"].'</div>
   <div></div> 
    
    
    </div>
  </div>
</div>';


}

if (isset($_REQUEST["tvdetails"])) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.themoviedb.org/3/tv/' . $_REQUEST["tvdetails"] . '?&language=de',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyNmQ4NWE3YWUxM2Q2MWE1Y2ZjNDNjYjdjMjFhMDQ1ZCIsInN1YiI6IjYzMjk4OWNkZGYyOTQ1MDA3YmFjYTNiZCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.BXumrZuJ8oFoLPDyL9vfTjvH6xlXYzQoPavaXHiJsZM",
            "Content-Type: application/json;charset=utf-8"
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $test = json_decode($response, true);


    }
    $mediatype="tv";
    $ergebnis=0;
    print '<div class="container" id="zweite_spalte">
  <div class="row">
    <div class="col-4">
      <img class="shadow-sm  bg-body rounded" src="https://image.tmdb.org/t/p/w500' . $test["poster_path"] . '" width="360" height="450">
    </div>
    <div class="col-8">
    
      <h3><strong>'.$test["name"].'</strong></h3>
      
      <div class="unter">'.$test["first_air_date"];print' - '; foreach($test["genres"] as $value){print $value["name"].", ";} print'</div>

      <div>Benutzerbewertungen: </div>
      <div><div class="progress">
  <div class="progress-bar" role="progressbar" style="width: '.substr($test["vote_average"],0,1).substr($test["vote_average"],2,1).'%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">'.substr($test["vote_average"],0,1).substr($test["vote_average"],2,1).'%</div></div></div>
   <div><span class="fa fa-star" id="1" onclick="checked(1)" onmouseover="hover(1)" onmouseleave="hoverout(1)"></span>
<span class="fa fa-star" id="2" onclick="checked(2)" onmouseover="hover(2)" onmouseleave="hoverout(2)"></span>
<span class="fa fa-star" id="3" onclick="checked(3)" onmouseover="hover(3)" onmouseleave="hoverout(3)"></span>
<span class="fa fa-star" id="4" onclick="checked(4)" onmouseover="hover(4)" onmouseleave="hoverout(4)"></span>
<span class="fa fa-star" id="5" onclick="checked(5)" onmouseover="hover(5)" onmouseleave="hoverout(5)"></span>';if ($ergebnis == 0) {
        print '
        <form action="" method="post">
        <i class="fa-solid fa-heart-circle-plus" id= "test" onclick="hinzufügen()"></i></form>';
    } else if ($ergebnis > 0) {
        print '
        <form action="" method="post">
        <i class="fa-solid fa-heart" style="color:red" id= "test" onclick="hinzufügen()"></i></form>';
    }print'</div>
   <div class="textrunter"><h5>Handlung</h5></div>
   <div>'.$test["overview"].'</div>
   <div></div> 
    </div>
  </div>
</div>';



}

if (isset($_REQUEST["persondetails"])) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.themoviedb.org/3/person/' . $_REQUEST["persondetails"] . '?&language=de',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyNmQ4NWE3YWUxM2Q2MWE1Y2ZjNDNjYjdjMjFhMDQ1ZCIsInN1YiI6IjYzMjk4OWNkZGYyOTQ1MDA3YmFjYTNiZCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.BXumrZuJ8oFoLPDyL9vfTjvH6xlXYzQoPavaXHiJsZM",
            "Content-Type: application/json;charset=utf-8"
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $test = json_decode($response, true);
    }

    print '<div class="container" id="zweite_spalte">
  <div class="row">
    <div class="col-4">';
    if(isset($test["profile_path"])) {
        print'<img class="shadow-sm  bg-body rounded" src = "https://image.tmdb.org/t/p/w500' . $test["profile_path"] . '" width = "360" height = "450" >';
    }else{
        print '<img class="shadow-sm bg-body rounded"  src="placeholder.jpg" width="360" height="450">';
    }
    print'</div>
    <div class="col-8">
      <h3><strong>'.$test["name"].'</strong></h3>
      <div class="unter">'.$test["birthday"].' - ' .$test["place_of_birth"].'</div>
     
   <div class="textrunter">Auch bekannt als: ';
   foreach($test["also_known_as"] as $value){
       print $value.", ";
   }
   print '</div><div class="textrunter"><h5>Biografie</h5></div>';
   print '<div>'.$test["biography"].'</div>';

   print '<div></div> 
    
    
    </div>
  </div>
</div>';

}




$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api.themoviedb.org/3/tv/popular?language=de&page=1',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyNmQ4NWE3YWUxM2Q2MWE1Y2ZjNDNjYjdjMjFhMDQ1ZCIsInN1YiI6IjYzMjk4OWNkZGYyOTQ1MDA3YmFjYTNiZCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.BXumrZuJ8oFoLPDyL9vfTjvH6xlXYzQoPavaXHiJsZM",
        "Content-Type: application/json;charset=utf-8"
    ],
]);
$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $test = json_decode($response, true);

}

if (isset($_REQUEST["suchergebnis"])) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.themoviedb.org/3/search/multi?&language=de&query=' . str_replace(" ", "%20", $_REQUEST["suche2"]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyNmQ4NWE3YWUxM2Q2MWE1Y2ZjNDNjYjdjMjFhMDQ1ZCIsInN1YiI6IjYzMjk4OWNkZGYyOTQ1MDA3YmFjYTNiZCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.BXumrZuJ8oFoLPDyL9vfTjvH6xlXYzQoPavaXHiJsZM",
            "Content-Type: application/json;charset=utf-8"
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $test = json_decode($response, true);



        $serienvar = 0;
        $filmevar = 0;
        $personenvar = 0;

        for ($i = 1; $i <= $test["total_pages"]; $i++) {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.themoviedb.org/3/search/multi?&language=de&page=' . $i . '&query=' . str_replace(" ", "%20", $_REQUEST["suche2"]),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyNmQ4NWE3YWUxM2Q2MWE1Y2ZjNDNjYjdjMjFhMDQ1ZCIsInN1YiI6IjYzMjk4OWNkZGYyOTQ1MDA3YmFjYTNiZCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.BXumrZuJ8oFoLPDyL9vfTjvH6xlXYzQoPavaXHiJsZM",
                    "Content-Type: application/json;charset=utf-8"
                ],
            ]);
            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $suchergebiszeahler = json_decode($response, true);
            }




            foreach ($suchergebiszeahler["results"] as $value) {
                if ($value["media_type"] == "movie") {
                    $filmevar = $filmevar + 1;
                }
                if ($value["media_type"] == "tv") {
                    $serienvar = $serienvar + 1;
                }
                if ($value["media_type"] == "person") {
                    $personenvar = $personenvar + 1;
                }
            }


        }


        print'<div id="steuerung">
<div class="vertical-menu">
<p>Suchergebnisse</p>
  <a href="http://localhost/FilmSerienWebsite/index.php?suche2='.$_REQUEST["suche2"].'%20&suchergebnis=tv">Serien (' . $serienvar . ')</a>
  <a href="http://localhost/FilmSerienWebsite/index.php?suche2='.$_REQUEST["suche2"].'%20&suchergebnis=movie">Filme (' . $filmevar . ')</a>
  <a href="http://localhost/FilmSerienWebsite/index.php?suche2='.$_REQUEST["suche2"].'%20&suchergebnis=person">Personen (' . $personenvar . ')</a>
</div>
</div>';


        print '<div id="zweite_spalte">
                <div class="container">';

        for ($i = 1; $i <= $test["total_pages"]; $i++) {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.themoviedb.org/3/search/multi?&language=de&page=' . $i . '&query=' . str_replace(" ", "%20", $_REQUEST["suche2"]),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyNmQ4NWE3YWUxM2Q2MWE1Y2ZjNDNjYjdjMjFhMDQ1ZCIsInN1YiI6IjYzMjk4OWNkZGYyOTQ1MDA3YmFjYTNiZCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.BXumrZuJ8oFoLPDyL9vfTjvH6xlXYzQoPavaXHiJsZM",
                    "Content-Type: application/json;charset=utf-8"
                ],
            ]);
            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $suchergebiszeahler = json_decode($response, true);
            }




            foreach ($suchergebiszeahler["results"] as $value) {
                if ($value["media_type"] == $_REQUEST["suchergebnis"]) {

                    print '<div class="row mb-5">';

                    if (isset($value["backdrop_path"]) and strlen($value["backdrop_path"]) >= 1) {
                        if($value["media_type"]=="movie"){
                            print '<div class="col-1"><a href="http://localhost/FilmSerienWebsite/index.php?moviedetails=' . $value["id"] . '">';
                        }elseif($value["media_type"]=="tv"){
                            print '<div class="col-1"><a href="http://localhost/FilmSerienWebsite/index.php?tvdetails=' . $value["id"] . '">';
                        }elseif($value["media_type"]=="person"){
                            print '<div class="col-1"><a href="http://localhost/FilmSerienWebsite/index.php?persondetails=' . $value["id"] . '">';
                        }
                        print '<img class="shadow-sm  bg-body rounded" src="https://image.tmdb.org/t/p/w500' . $value["poster_path"] . '" width="94" height="141"></a></div>';
                    } else {
                        print '<div class="col-1"><a href="http://localhost/FilmSerienWebsite/index.php?moviedetails=' . $value["id"] . '">';
                        print '<img class="shadow-sm bg-body rounded"  src="placeholder.jpg" width="94" height="141"></a></div>';
                    }


                    //print '<img src="https://image.tmdb.org/t/p/w500' . $value["profile_path"] . '" width="94" height="141"></a>';
                    if (isset($value["title"])) {
                        print '<div class="col-11"><strong>' . $value["title"].'</strong>';
                        print '<div class="vertical">' . $value["overview"] . "<br></div></div>";
                    } elseif (isset($value["name"])) {
                        print '<div class="col-11">';
                        if (isset($value["overview"])) {
                            print $value["overview"];
                        }
                        print '<div class="vertical">' . $value["name"] . "<br></div></div>";
                    } else {
                        print "keine name vorhanden<br></div>";
                    }
                    print '</div>';
                }

            }
        }
    }
    print '</div></div></div>';
}

if (isset($_REQUEST["anmelden"])){

    print '<div class="text-center body2">
    <main class="form-signin">
        <form action="" method="post">
            <h1 class="h3 mb-3 fw-normal">Anmelden</h1>

            <div class="form-floating">
                <input class="form-control" type="text" id="benutzername1" name="benutzername" placeholder="Benutzername">
                <label for="benutzername1">Benutzername</label>
            </div>
            <div class="form-floating">
                <input class="form-control" type="password" id="password1" name="passwort1" placeholder="Passwort">
                <label for="password1">Passwort</label>
            </div>
            <input class="w-100 btn btn-lg btn-primary" type="submit" name="absendenanmelden" value="Absenden">
            <p class="mt-5 mb-3 text-muted">Doch <a href="http://localhost/FilmSerienWebsite/index.php?registrieren=true">registrieren</a>?</p>
        </form>
    </main>
</div>';
}

if (isset($_REQUEST["registrieren"])) {
print '
<div class="text-center body2">
    <main class="form-signin">
        <form action="" method="post">
            <h1 class="h3 mb-3 fw-normal">Registrieren</h1>

            <div class="form-floating">
                <input class="form-control" type="text" id="benutzername1" name="benutzername" placeholder="Benutzername">
                <label for="benutzername1">Benutzername</label>
            </div>
            <div class="form-floating">
                <input class="form-control" type="password" id="password1" name="passwort1" placeholder="Passwort">
                <label for="password1">Passwort</label>
            </div>
            <div class="form-floating">
                <input class="form-control" type="password" id="password2" name="passwort2" placeholder="Passwort wiederholen">
                <label for="password1">Passwort wiederholen</label>
            </div>
            <input class="w-100 btn btn-lg btn-primary" type="submit" name="absenden" value="Absenden">
            <p class="mt-5 mb-3 text-muted">Doch <a href="http://localhost/FilmSerienWebsite/index.php?anmelden=true">anmelden</a>?</p>
        </form>
    </main>
</div>';

}

print'<!-- Footer -->
<footer class="text-center text-lg-start bg-light text-muted">
  <!-- Section: Social media -->
  <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
    
  </section>
  <!-- Section: Social media -->

  <!-- Section: Links  -->
  <section class="">
    <div class="container text-center text-md-start mt-5">
      <!-- Grid row -->
      <div class="row mt-3">
        <!-- Grid column -->
        <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
          <!-- Content -->
          <h6 class="text-uppercase fw-bold mb-4">
            <i class="fas fa-gem me-3"></i>Firmenname
          </h6>
          <p>
            Für Fortnite GmbH
          </p>
        </div>
        <!-- Grid column -->

        <!-- Grid column -->
        <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
          <!-- Links -->
          
        </div>
        <!-- Grid column -->

        <!-- Grid column -->
        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
          
        </div>
        <!-- Grid column -->

        <!-- Grid column -->
        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
          <!-- Links -->
          <h6 class="text-uppercase fw-bold mb-4">Kontakt</h6>
          <p ><div class="block">Ferdinand-von-Steinbeis-Schule Gewerbliche Schule I <br>Karlstr. 40 <br>72764 Reutlingen</div></p>
          <p>
            <i class="fas fa-envelope me-3"></i>
            info@steinbeisschule-reutlingen.de
          </p>
          <p><i class="fas fa-phone me-3"></i> (07121) 485 111</p>
          <p><i class="fas fa-print me-3"></i> (07121) 485 190</p>
        </div>
        <!-- Grid column -->
      </div>
      <!-- Grid row -->
    </div>
  </section>
  <!-- Section: Links  -->
</footer>
<!-- Footer -->';


// isset Session prüfen --> dann erst rest prüfen
if (isset($_SESSION["benutzer"]) and isset($mediatype)) {

    $benutzerid = $_SESSION['benutzer'];

    if ($mediatype == "movie") {
        $filmid = $_REQUEST["moviedetails"];
    }
    if ($mediatype == "tv") {
        $filmid = $_REQUEST["tvdetails"];
    }
    if (isset($_POST['data'])) {
        $punkte = $_POST['data'];
        echo ($punkte);
    }
        //erst suchen, obs schon eine bewertung gibt --> sonst reinschreiben / überschreiben
        $search_user = $mysqli->prepare("SELECT id from bewertung where benutzer_id =? and film_id =? and media_type =?");
        $search_user->bind_param('sss', $benutzerid, $filmid, $mediatype);
        $search_user->execute();
        $search_result = $search_user->get_result();

        if ($search_result->num_rows == 0) {
            if (isset($punkte) && $punkte > 0) {
                $insert = $mysqli->prepare("INSERT Into bewertung (benutzer_id, punkte, film_id,media_type) values (?,?,?,?)");
                $insert->bind_param('ssss', $benutzerid, $punkte, $filmid, $mediatype);
                $insert->execute();
                echo $insert;
            }
        } else {
            //Update
            if (isset($punkte) && $punkte > 0) {
                $update = $mysqli->prepare("UPDATE bewertung set punkte=? where benutzer_id=? and film_id=? and media_type=?"); // oder id=? --> $search_result  ??????
                $update->bind_param('ssss', $punkte, $benutzerid, $filmid, $mediatype);
                $update->execute();

            }
        }

}

if(isset($_REQUEST["abmelden"])){
    session_unset();
}






?>
<script type="text/javascript">
    function hover(num) {
        for (var i = 1; num >= i; num--) {
            var h = document.getElementById(num);
            h.classList.add("checked1");
        }
    }

    function hoverout(num) {
        for (var i = 1; num >= i; num--) {
            var h = document.getElementById(num);
            h.classList.remove("checked1");
        }
    }

    function checked(num) {
        var numremove = num + 1;
        var numweiter = num;
        for (var i = 1; num >= i; num--) {
            var h = document.getElementById(num);
            h.classList.add("checked");
        }
        for (var i = 5; numremove <= i; numremove++) {
            var h = document.getElementById(numremove);
            if (h.classList.contains("checked")) {
                h.classList.remove("checked");
            }
        }

        switch (numweiter) {
            case 1:
                fetch("index.php", {
                    body: "data=1",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    method: "post"
                })
                    .then(response => response.text())
                    .then(text => console.log(text))
                break;
            case 2:
                fetch("index.php", {
                    body: "data=2",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    method: "post"
                })
                    .then(response => response.text())
                    .then(text => console.log(text))
                break;
            case 3:
                fetch("index.php", {
                    body: "data=3",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    method: "post"
                })
                    .then(response => response.text())
                    .then(text => console.log(text))
                break;
            case 4:
                fetch("index.php", {
                    body: "data=4",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    method: "post"
                })
                    .then(response => response.text())
                    .then(text => console.log(text))
                break;
            case 5:
                fetch("index.php", {
                    body: "data=5",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    method: "post"
                })
                    .then(response => response.text())
                    .then(text => console.log(text))
                break;
        }







    }
    async function insertData(data = {}) {
        const response = await fetch("index.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)

        });
        let text = await response.text();
        console.log(text);
    }

    function hinzufügen() {
        var watchlist = document.getElementById("test");
        let heartplus = watchlist.classList.contains('fa-heart-circle-plus');
        if (heartplus) {
            watchlist.classList.remove("fa-heart-circle-plus");
            watchlist.classList.add("fa-heart");
            watchlist.style.color = 'red';
            fetch("index.php", {
                body: "data=1",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                method: "post"
            })
                .then(response => response.text())
                .then(text => console.log(text))
        } else {
            watchlist.classList.add("fa-heart-circle-plus");
            watchlist.classList.remove("fa-heart");
            watchlist.style.color = 'black';
            fetch("index.php", {
                body: "data=2",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                method: "post"
            })
                .then(response => response.text())
                .then(text => console.log(text))
        }
    }
</script>