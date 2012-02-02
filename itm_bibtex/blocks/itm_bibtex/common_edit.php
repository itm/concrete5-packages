<?php 
	defined('C5_EXECUTE') or die("Access Denied.");
	$al = Loader::helper('concrete/asset_library');
	$bf = null;
	if ($controller->getFileID() > 0) { 
		$bf = $controller->getFileObject();
	}
	
	$years = array();
	for ($i = 1990; $i <= date('Y'); $i++)
	{
		$years[$i] = $i;
	}
	
?>

<div>
	<h4><?= t('Choose Bibtex file') ?></h4>
	<div style="margin-bottom: 15px"><?php echo $al->file('ccm-b-file', 'fID', t('Choose File'), $bf, array('fExtension' => 'bib'));?></div>
	
	<h4><?= t('Author') ?></h4>
	<div style="margin-bottom: 15px; padding-right: 10px"><?= $form->text('author', $author, array('style' => 'width: 100%;')) ?></div>
	
	<h4><?= t('Publications since') ?></h4>
	<div style="margin-bottom: 15px"><?= $form->select('since', $years, $since, array('style' => 'width: 100%')) ?></div>
	
</div>
