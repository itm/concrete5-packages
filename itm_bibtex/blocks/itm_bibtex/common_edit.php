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

<div class="ccm-block-field-group">
	<h2><?= t('Choose Bibtex file') ?></h2>
	<div style="margin-bottom: 15px"><?php echo $al->file('ccm-b-file', 'fID', t('Choose File'), $bf, array('fExtension' => 'bib'));?></div>
	
	<h2><?= t('Author') ?></h2>
	<div style="margin-bottom: 15px"><?= $form->text('author', $author, array('style' => 'width: 90%')) ?></div>
	
	<h2><?= t('Publications since') ?></h2>
	<div style="margin-bottom: 15px"><?= $form->select('since', $years, $since, array('style' => 'width: 90%')) ?></div>
	
</div>
