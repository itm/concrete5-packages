<?php
defined('C5_EXECUTE') or die("Access Denied.");
$bt->inc('editor_init.php');
?>
<div class="ccm-block-field-group">
	<h2><?= t('Title of this custom content block') ?></h2>
	<div>
		<?= $form->text('title', $title, array('style' => 'width: 90%')) ?>
	</div>
</div>
<div class="ccm-block-field-group">
	<h2><?= t('Content of this custom content block') ?></h2>
	<div style="text-align: center"><textarea id="ccm-content-<?php echo $a->getAreaID() ?>" class="advancedEditor ccm-advanced-editor" name="content"></textarea></div>
</div>