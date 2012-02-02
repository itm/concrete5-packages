<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<div>
	<h4><?= t('Choose user') ?></h4>

	<?php if ($this->controller->hasUsers()) : ?>
		<p id="userp" style="padding-right: 10px">
			<?= $form->select('uName', $this->controller->getLdapUsers(), $uName, array('style' => 'width: 100%')) ?>
		</p>
	<?php else : ?>
		<?php echo t('There are currently no users available. Please perform a synchronization!'); ?>
	<?php endif; ?>
</div>
