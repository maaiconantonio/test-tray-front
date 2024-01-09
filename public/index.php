<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "pages/header.php";

$page = null;
if (isset($_REQUEST['page'])) {
	$page = filter_var($_REQUEST["page"]);
}

if ($page != null) {
	if (file_exists("pages/".$page.".php")) {
		include "pages/".$page.".php";
	} else { ?>
		<div class="alert alert-error">Página não encontrada.</div>
	<?php }
}

include_once "pages/footer.php"; ?>