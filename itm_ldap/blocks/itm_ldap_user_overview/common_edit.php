<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<?php
	$groupOptions = array(
		'c5-mitarb' => 'c5-mitarb',
		'c5-alumni' => 'c5-alumni',
		'c5-head' => 'c5-head',
		'c5-admin' => 'c5-admin'
	);
	ksort($groupOptions);
?>
<div>
	<h4><?= t('Staff group') ?></h4>
	<p>
		<?= $form->select('groupName', $groupOptions, $groupName, array('style' => 'width: 100%')); ?>
	</p>
	
	<h4><?= t('Section title') ?></h4>
	<p style="padding-right: 10px">
		<?= $form->text('caption', $caption, array('style' => 'width: 100%')) ?>
	</p>
</div>
