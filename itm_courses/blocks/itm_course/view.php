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

	default :
		$typePlain = t('Practical Course');
		break;
}
?>

<h1 class="itmCourseEntryName"><?= $name ?></h1>

<h2 class="itmCourseEntry"><?=t('Course type')?></h2>
<div class="itmCourseEntry">
	<?=$typePlain?>
</div>

<h2 class="itmCourseEntry"><?=t('Semester')?></h2>
<div class="itmCourseEntry">
	SUMMER/WINTER TERM + YEAR
</div>

<h2 class="itmCourseEntry"><?=t('Semester')?></h2>
<div class="itmCourseEntry">
	SUMMER/WINTER TERM + YEAR
</div>

<h2 class="itmCourseEntry"><?=t('Credits')?></h2>
<div class="itmCourseEntry">
	<?=$credits?>
</div>

<?php
	$lecturers = $this->controller->getLecturers();
	$assistants = $this->controller->getAssistants();
	
	
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
