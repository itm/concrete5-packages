<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<div class="ccm-block-field-group">
	<h2><?= t('Staff group name') ?></h2>

	<p id="userp">
		<?= $form->text('groupName', $groupName, array('style' => 'width: 90%')) ?>
	</p>

</div>
