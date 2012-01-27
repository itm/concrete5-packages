<?php
defined('C5_EXECUTE') or die("Access Denied.");
$bt->inc('editor_init.php');
?>
<div>
	<h4><?= t('Title of this custom content block') ?></h4>
	<div style="padding-right: 10px">
		<?= $form->text('title', $title, array('style' => 'width: 100%')) ?>
	</div>
</div>
<div>
	<h4><?= t('Content of this custom content block') ?></h4>
	<div style="text-align: center"><textarea id="ccm-content-<?php echo $a->getAreaID() ?>" class="advancedEditor ccm-advanced-editor" name="content"></textarea></div>
</div>