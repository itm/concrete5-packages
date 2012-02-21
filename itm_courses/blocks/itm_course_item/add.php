<?php
defined('C5_EXECUTE') or die("Access Denied.");
$bt->inc('editor_init.php');
?>
<div>
	<h4><?= t('Course Item Caption') ?></h4>
	<div id="titleFieldWrapper">
		<script language="JavaScript">
			CourseItem.items = items;
			CourseItem.switchIcon = switchIcon;
			$('#titleFieldWrapper').append(CourseItem.renderTitleField(''));
		</script>
	</div>
</div>
<div>
	<h4><?= t('Content of this custom content block') ?></h4>
	<div style="text-align: center"><textarea id="ccm-content-<?php echo $a->getAreaID() ?>" class="advancedEditor ccm-advanced-editor" name="content"></textarea></div>
</div>