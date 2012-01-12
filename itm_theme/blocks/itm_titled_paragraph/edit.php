<?php 
defined('C5_EXECUTE') or die("Access Denied.");
//$replaceOnUnload = 1;
$bt->inc('editor_init.php');
?>
<div class="ccm-block-field-group">
	<h2><?= t('Title of this paragraph') ?></h2>
	<div>
		<?= $form->text('title', $title, array('style' => 'width: 90%')) ?>
	</div>
</div>
<div class="ccm-block-field-group">
	<h2><?= t('Content of this paragraph') ?></h2>
<div style="text-align: center" id="ccm-editor-pane">
<textarea id="ccm-content-<?php echo $b->getBlockID()?>-<?php echo $a->getAreaID()?>" class="advancedEditor ccm-advanced-editor" name="content"><?php echo $controller->getContentEditMode()?></textarea>
</div>
</div>