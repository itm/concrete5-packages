<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<div>
	<?php
	$ch = Loader::helper('itm_courses', 'itm_courses');
	$groups = $ch->getCourseGroups();
	?>
	<h4><?= t('Filter courses by selecting a group') ?></h4>

	<?php if (count($groups)) : ?>
		<p id="userp">
			<?= $form->select('groupName', $groups, $groupName, array('style' => 'width: 100%')) ?>
		</p>
	<?php else : ?>
		<?php echo t('There are currently no groups available. Go to <i>Dashboard / ITM Courses</i> and define at least one.'); ?>
		<?=$form->hidden('groupName', '')?>
	<?php endif; ?>
</div>