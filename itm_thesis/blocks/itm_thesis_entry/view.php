<h1 class="itmThesisEntryTitle"><?=$topic?></h1>

<?php
switch ($type)
{
	case 0:
		$typePlain = t('Bachelor thesis');
		break;

	case 1 :
		$typePlain = t('Master thesis');
		break;

	default :
		$typePlain = t('Bachelor or master thesis');
		break;
}

switch ($status)
{
	case 0 :
		$statusPlain = t('Open');
		break;

	case 1 :
		$statusPlain = t('Running');
		break;

	default :
		$statusPlain = t('Finished');
		break;
}

if (empty($beginning) || $beginning == '0')
{
	$beginningPlain = 'As soon as possible';
}
else
{
	$beginningPlain = "From $beginning";
}

if (empty($student))
{
	$studentPlain = 'N/A';
}
else
{
	$studentPlain = $student;
}
?>

<div class="itmThesisEntryType">
	<span class="itmThesisEntryCaption"><?= t('Type') ?>:</span>
	<span class="itmThesisEntryValue"><?= $typePlain ?></span>
</div>
<div class="itmThesisEntryStatus">
	<span class="itmThesisEntryCaption"><?= t('Status') ?>:</span>
	<span class="itmThesisEntryValue"><?= $statusPlain ?></span>
</div>
<div class="itmThesisEntryBegin">
	<span class="itmThesisEntryCaption"><?= t('Begin') ?>:</span>
	<span class="itmThesisEntryValue"><?= $beginningPlain ?></span>
</div>
<div class="itmThesisEntryStudent"">
	<span class="itmThesisEntryCaption"><?= t('Student') ?>:</span>
	<span class="itmThesisEntryValue"><?= $studentPlain ?></span>
</div>
<div class="itmThesisEntryTutor">
	<span class="itmThesisEntryCaption"><?= t('Tutor') ?>:</span>
	<span class="itmThesisEntryValue"><?= $tutor ?></span>
</div>
<div class="itmThesisEntrySupervisor">
	<span class="itmThesisEntryCaption"><?= t('Supervisor') ?>:</span>
	<span class="itmThesisEntryValue"><?= $supervisor ?></span>
</div>
