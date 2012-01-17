<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<div class="ccm-block-field-group">
	<h2><?= t('Choose user') ?></h2>

	<?php if ($this->controller->hasUsers()) : ?>
		<p id="userp">
			<?= $form->select('uName', $this->controller->getLdapUsers(), $uName, array('style' => 'width: 90%')) ?>
		</p>
	<?php else : ?>
		<?php echo t('There are currently no users available. Please perform a synchronization!'); ?>
	<?php endif; ?>
</div>
