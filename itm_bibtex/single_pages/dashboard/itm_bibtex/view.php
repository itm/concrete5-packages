
<div>
<?php
Loader::library('bibtexbrowser', 'itm_bibtex');

$entries = array();

$fID  = 2; //given in settings
$fsID = 3; //given by application

/*
 * Algorithmus:
 * ============
 * 
 * Checken, ob Bib-Datei vorhanden, sonst Fehler
 * 
 * Wenn Bib-Datei vorhanden, suche PHP-serialisierte Form
 * 
 * Wenn serialisierte Form nicht vorhanden, verarbeite Bib-Datei und
 * serialisiere sie.
 * 
 * Wenn serialisierte Form vorhanden, aber Modifikationsdatum ist älter
 * als das Modifikationsdatum der Bib-Datei, dann aktualisiere serialisierte
 * Form.
 * 
 * Die deserialisierte Form befindet sich nun in einem PHP-Objekt/PHP-Array.
 * 
 * Zeige Daten ab einem betimmten Jahr eines bestimmten Autors an.
 */

$fh = Loader::helper('file');

echo '<p><b>Protokoll:</b><p><ul>';

$f = File::getByID($fID);
if ($f->isError())
{
	echo '<li>Bib-Datei konnte nicht geladen werden.</li>';
}
else
{
	echo '<li>Bib-Datei geladen: ' . $f->getPath() . '<br/></li>';
}

$cachedFile = DIR_FILES_CACHE . '/' . md5($f->getFileID()) . '.tmp';
$bibDb = new BibDataBase();
$bibArr = array();

if (!file_exists($cachedFile))
{
	echo '<li>Temp. Datei nicht vorhanden. Neu anlegen.</li>';
	$bibArr = $bibDb->load($f->getPath());
	$bibStr = serialize($bibArr);
	file_put_contents($cachedFile, $bibStr, LOCK_EX);
}
else
{
	echo '<li>Temp. Datei nicht vorhanden. Neu anlegen.</li>';
	if (filemtime($cachedFile) < strtotime($f->getDateAdded()))
	{
		echo '<li>Temp. Datei ist veraltet. Aktualisieren.</li>';
		$bibArr = $bibDb->load($f->getPath());
		$bibStr = serialize($bibArr);
		file_put_contents($cachedFile, $bibStr, LOCK_EX);
	}
	else
	{
		echo '<li>Temp. Datei ist auf dem neusten Stand. Datei wird deserialisiert.</li>';
		$serializedBibDb = file_get_contents($cachedFile);
		$bibArr = unserialize($serializedBibDb);
	}	
}

echo '</li>';

echo '<p><b>Bib-Entries:</b></p>';
echo '<pre>';
var_dump($bibDb->getEntries());
echo '</pre>';

//$db = new BibDBBuilder(, $entries, &$stringdb);

?>
</div>
