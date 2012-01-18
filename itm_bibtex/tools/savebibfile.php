<?php

$f = File::getByID($_POST['f']);
if ($f->isError())
{
	header("HTTP/1.0 404 Not Found\n");
	exit();
}

Loader::library("file/importer");

$tmpFile = DIR_FILES_INCOMING .'/edited_' . time() . '.bib';

file_put_contents($tmpFile, $_POST['c'], LOCK_EX);

$fi = new FileImporter();
$fv = $fi->import($tmpFile, false, $f);
unlink($tmpFile);

if (!($fv instanceof FileVersion))
{
	header("HTTP/1.0 404 Not Found\n");
	exit();
}

echo '1';

?>