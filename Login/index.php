

<form action="" method="post">
	<input type="image" id="profil" name="profil" src="Profil.png">
</form>

<?php

session_start();
//$page = isset($_GET['page']) ? strtolower($_GET['page']) : '';

$page = '';
if (isset($_GET['page'])) $page = strtolower($_GET['page']);

if (isset($_SESSION['user'])) :
	require_once('ui.php');
else :
	if ($page == 'anmelden') :
		echo 'Doch <a href="?page=registrieren">registrieren</a>?';
		header('Location: /anmelden.php');
	elseif ($page == 'registrieren') :
		echo 'Doch <a href="?page=anmelden">anmelden</a>?';
		require_once('registrieren.php');
	else :
		echo 'Willst du dich <a href="?page=anmelden"> anmelden</a> oder <a href="index.php?page=registrieren">registrieren</a>';
	endif;
endif;

?>