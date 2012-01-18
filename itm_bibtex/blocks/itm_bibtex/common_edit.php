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
	<p><?php echo $al->file('ccm-b-file', 'fID', t('Choose File'), $bf, array('fExtension' => 'bib'));?></p>
	
	<h2><?= t('Author') ?></h2>
	<p><?= $form->text('author', $author, array('style' => 'width: 90%')) ?></p>
	
	<h2><?= t('Publications since') ?></h2>
	<p><?= $form->select('since', $years, $since, array('style' => 'width: 90%')) ?></p>
	
</div>
