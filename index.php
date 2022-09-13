<?php

$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "https://imdb8.p.rapidapi.com/auto-complete?q=game%20of%20thr",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"X-RapidAPI-Host: imdb8.p.rapidapi.com",
		"X-RapidAPI-Key: f7dd01b80cmshbb44046477e6a50p178728jsn12e5bc520b2e"
	],
]);
$response = curl_exec($curl);
echo "<script>";
echo "var data = ".$response
echo "</script>";
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	echo $response;
}
