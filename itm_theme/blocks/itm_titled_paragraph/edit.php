<?php 
defined('C5_EXECUTE') or die("Access Denied.");
//$replaceOnUnload = 1;
$bt->inc('editor_init.php');
?>
<div>
	<h4><?= t('Title of this paragraph') ?></h4>
	<div>
		<?= $form->text('title', $title, array('style' => 'width: 100%')) ?>
	</div>
</div>
<div>
	<h4><?= t('Content of this paragraph') ?></h4>
	<textarea id="ccm-content-<?php echo $b->getBlockID()?>-<?php echo $a->getAreaID()?>" class="advancedEditor ccm-advanced-editor" name="content"><?php echo $controller->getContentEditMode()?></textarea>
</div>