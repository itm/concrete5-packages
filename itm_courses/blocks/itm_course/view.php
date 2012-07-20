<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<?php
switch ($type)
{
	case 0:
		$typePlain = t('Course');
		break;

	case 1 :
		$typePlain = t('Seminar');
		break;

	case 2 :
		$typePlain = t('Workshop');
		break;
		
	case 3 :
		$typePlain = t('Practical Course');
		break;
	
	default :
		$typePlain = t('Project');
		break;
}
?>

<h1 class="itmCourseEntryName"><?= $name ?></h1>

<div class="itmCourseEntry"><?=$typePlain?>
<?php
$parentPage = Page::getByID(Page::getCurrentPage()->getCollectionParentID());
if ($parentPage->getCollectionTypeHandle() == 'itm_semester_page') :
?>
	<?=t('in')?>&nbsp;<?=$parentPage->getCollectionAttributeValue('semester_term')?>&nbsp;<?=$parentPage->getCollectionAttributeValue('semester_year')?>
<?php endif; ?>
</div>
<?php
	$courseOfStudiesList = $this->controller->getCourseGroups();
	if (!empty($courseOfStudiesList)) :
?>
<h2 class="itmCourseEntry"><?=t('Course of Studies')?></h2>
<div class="itmCourseEntry">
	<?php
		$ch = Loader::helper('itm_courses', 'itm_courses');
		$addComma = false;
		foreach ($courseOfStudiesList as $item)
		{
			if ($addComma) echo ', ';
			echo $ch->getCourseGroupByHandle($item)->name;
			$addComma = true;
		}
	?>
</div>
<?php endif; ?>
<?php
 if (strlen($credits) || strlen($creditHours)) :
?>
<h2 class="itmCourseEntry">
<?php
if (strlen($credits)) echo t('Credits');
if (strlen($credits) && strlen($creditHours)) echo ' / ';
if (strlen($creditHours)) echo t('Credit Hours');
?>
</h2>
<div class="itmCourseEntry">
	<?=$credits?> <?=strlen($credits) && strlen($creditHours) ? ' / ' : ''?> <?=$creditHours?>
</div>
<?php endif; ?>

<?php
	$lecturers = $this->controller->getLecturers();
	$assistants = $this->controller->getAssistants();
?>

<?php
	if (count($lecturers)) :
?>
<h2 class="itmCourseEntry"><?=t('Lecturer')?></h2>
<div class="itmCourseEntry">
	<?php
	$first = 1;
	foreach ($lecturers as $lecturer)
	{
		if (!$first)
		{
			echo ', ';
		}
		$rendered = $this->controller->renderName($lecturer);
		if (strlen($rendered))
		{
			echo $rendered;
		}
		$first = 0;
	}
	?>
</div>
<?php endif; ?>

<?php
	if (count($assistants)) :
?>
<h2 class="itmCourseEntry"><?=t('Teaching Assistants')?></h2>
<div class="itmCourseEntry">
	<?php
	$first = 1;
	foreach ($assistants as $assistant)
	{
		if (!$first)
		{
			echo ', ';
		}
		
		$rendered = $this->controller->renderName($assistant);
		if (strlen($rendered))
		{
			echo $rendered;
		}
		$first = 0;
	}
	?>
</div>
<?php endif; ?>