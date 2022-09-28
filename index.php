<?php
$apikey = "eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyNmQ4NWE3YWUxM2Q2MWE1Y2ZjNDNjYjdjMjFhMDQ1ZCIsInN1YiI6IjYzMjk4OWNkZGYyOTQ1MDA3YmFjYTNiZCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.BXumrZuJ8oFoLPDyL9vfTjvH6xlXYzQoPavaXHiJsZM";
print '
<form>
<label for="suche">Suche:</label>
<input type="text" id="suche" name="suche" placeholder="Suche nach TV Show, Filme und Personen" size="50">
<input type="submit" value="suchen">
</form>';

print'<form>
<a href="https://www.w3schools.com"><input type="submit" value="Filme"></a>
<input type="submit" value="Serien">
<input type="submit" value="Personen">
</form>';


if(isset($_REQUEST["suche"])){
$curl = curl_init();
curl_setopt_array($curl, [
	CURLOPT_URL => 'https://api.themoviedb.org/3/search/multi?&query='.str_replace(" ","%20",$_REQUEST["suche"]),
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
	$test = json_decode($response,true);

	print "<pre>";
	print_r($test);
	print "</pre>";

	foreach($test["results"] as $value){
		if (strlen($value["backdrop_path"])>=1) {
			print '<a href="http://localhost/FilmSerienWebsite/index.php?moviedetails='.$value["id"].'">';
			print '<img src="https://image.tmdb.org/t/p/w500' . $value["poster_path"] . '" width="94" height="141"></a>';
		}else{
			print '<a href="http://localhost/FilmSerienWebsite/index.php?moviedetails='.$value["id"].'">';
			print '<img src="placeholder.jpg" width="94" height="141"></a>';
		}
		//print '<img src="https://image.tmdb.org/t/p/w500' . $value["profile_path"] . '" width="94" height="141"></a>';
		if(isset($value["title"])) {
			print $value["title"] . "<br>";
		}elseif($value["name"]){
			print $value["name"] . "<br>";
		}else{
			print "keine name vorhanden amk <br>";
		}

	}



	//print '<img src="https://image.tmdb.org/t/p/w500'.$test["results"][0]["backdrop_path"].'">';

	for($i = 1; $i <= $test["total_pages"]; $i++) {
		print'<a href="http://localhost/FilmSerienWebsite/index.php?suche2='.$_REQUEST["suche"].'&page='.$i.'"> '.$i.' </a>';
	}



}

//curl watchproviders
$curl = curl_init();
curl_setopt_array($curl, [
	CURLOPT_URL => 'https://api.themoviedb.org/3/movie/11/watch/providers?',
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
	/*print "<pre>";
	print_r($test);
	print "</pre>";*/
}
}

if(isset($_REQUEST["page"])){
	$curl = curl_init();
	curl_setopt_array($curl, [
		CURLOPT_URL => 'https://api.themoviedb.org/3/search/multi?&page='.$_REQUEST["page"].'&query='.str_replace(" ","%20",$_REQUEST["suche2"]),
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
		$test = json_decode($response,true);
		/*
		print "<pre>";
		print_r($test);
		print "</pre>";
*/




		foreach($test["results"] as $value){
			if (strlen($value["backdrop_path"])>=1) {
				print '<a href="http://localhost/FilmSerienWebsite/index.php?moviedetails='.$value["id"].'">';
				print '<img src="https://image.tmdb.org/t/p/w500' . $value["poster_path"] . '" width="94" height="141"></a>';
			}else{
				print '<a href="http://localhost/FilmSerienWebsite/index.php?moviedetails='.$value["id"].'">';
				print '<img src="placeholder.jpg" width="94" height="141"></a>';
			}
			//print '<img src="https://image.tmdb.org/t/p/w500' . $value["profile_path"] . '" width="94" height="141"></a>';
			if(isset($value["title"])) {
				print $value["title"] . "<br>";
			}elseif($value["name"]){
				print $value["name"] . "<br>";
			}else{
				print "keine name vorhanden amk <br>";
			}
		}

		//print '<img src="https://image.tmdb.org/t/p/w500'.$test["results"][0]["backdrop_path"].'">';

		for($i = 1; $i <= $test["total_pages"]; $i++) {
			print'<a href="http://localhost/FilmSerienWebsite/index.php?suche2='.$_REQUEST["suche2"].'&page='.$i.'"> '.$i.' </a>';
		}}
}


if(isset($_REQUEST["moviedetails"])){
	$curl = curl_init();
	curl_setopt_array($curl, [
		CURLOPT_URL => 'https://api.themoviedb.org/3/movie/'.$_REQUEST["moviedetails"].'?',
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
		print "<pre>";
        print_r($test);
        print "</pre>";
	}
}



?>
