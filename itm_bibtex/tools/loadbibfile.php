<?php

$f = File::getByID($_GET['f']);
if ($f->isError())
{
	header("HTTP/1.0 404 Not Found\n");
	exit();
}

echo file_get_contents($f->getPath());

?>