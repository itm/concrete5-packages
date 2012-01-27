<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<div>
	<h4><?= t('Staff group name') ?></h4>

	<p id="userp" style="padding-right: 10px">
		<?= $form->text('groupName', $groupName, array('style' => 'width: 100%')) ?>
	</p>
</div>
