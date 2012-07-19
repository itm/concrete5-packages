<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<div>
	<?php
	$ch = Loader::helper('itm_courses', 'itm_courses');
	$groups = $ch->getCourseGroups();
	?>
	<h4><?= t('Filter courses by selecting a group...') ?></h4>

	<?php if (count($groups)) : ?>
		<p id="userp">
			<?= $form->select('groupName', $groups, $groupName, array('style' => 'width: 100%')) ?>
		</p>
		<p>... or define a custom title and give a filter name (e.g. <i>bachelor</i> for all groups which contain <i>bachelor</i> - case insensitive):</p>
		<p style="padding-right: 10px">
			Title<br/>
			<?= $form->text('groupTitle', $groupTitle, array('style' => 'width: 100%')) ?>
		</p>
		<p style="padding-right: 10px">
			Filter<br/>
			<?= $form->text('groupFilter', $groupFilter, array('style' => 'width: 100%')) ?>
		</p>
	<?php else : ?>
		<?php echo t('There are currently no groups available. Go to <i>Dashboard / ITM Courses</i> and define at least one.'); ?>
		<?=$form->hidden('groupName', '')?>
		<?=$form->hidden('groupFilter', '')?>
	<?php endif; ?>
</div>