<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<?php
// set default values for status and type
// afterwards, generate form

if (!isset($status))
{
	$status = 0;
}

if (!isset($type))
{
	$type = 0;
}
?>

<style type="text/css">
	table.itmThesisEntry
	{
		padding-right: 8px;
		width: 500px;
	}

	table.itmThesisEntry th {
		font-weight: bold;
		text-style: normal;
		white-space: nowrap;
		vertical-align: middle;
		padding: 8px;

	}

	table.itmThesisEntry td
	{
		font-size: 12px;
		vertical-align: middle;
		padding: 8px;
	}

	.itmThesisEntry .note
	{
		font-size: 10px;
	}

	table.itmThesisEntry .lightGray
	{
		background-color: #EDEDED;
	}
</style>

<div class="ccm-block-field-group itmThesisEntry">
	<h2>
<?= t('Thesis general information') ?>
	</h2>
	<table class="itmThesisEntry" cellpadding="0" cellspacing="0">
		<tr>
			<th class="lightGray"><?= t('Topic *') ?></th>
			<td class="lightGray"><?= $form->text('topic', $topic, array('style' => 'width: 90%')) ?></td>
		</tr>
		<tr>
			<th><?= t('Beginning') ?></th>
			<td>
				<div>
<?= $form->text('beginning', $beginning, array('style' => 'width: 90%')) ?>
				</div>
				<div class="note" style="width: 90%">
					Leave empty or insert a zero to force "as soon as possible"
				</div>
			</td>
		</tr>
		<tr>
			<th class="lightGray"><?= t('Type *') ?></th>
			<td class="lightGray">
				<div>
					<label for="type1">
<?= $form->radio('type', '0', $type) ?> <?= t('Bachelor thesis') ?>
					</label>
				</div>
				<div>
					<label for="type2">
<?= $form->radio('type', '1', $type) ?> <?= t('Master thesis') ?>
					</label>
				</div>
				<div>
					<label for="type3">
<?= $form->radio('type', '2', $type) ?> <?= t('Bachelor or master thesis') ?>
					</label>
				</div>
			</td>
		</tr>
		<tr>
			<th><?= t('Status *') ?></th>
			<td>
				<div>
					<label for="status4">
<?= $form->radio('status', '0', $status) ?> <?= t('Open') ?>
					</label>
				</div>
				<div>
					<label for="status5">
<?= $form->radio('status', '1', $status) ?> <?= t('Running') ?>
					</label>
				</div>
				<div>
					<label for="status6">
<?= $form->radio('status', '2', $status) ?> <?= t('Finished') ?>
					</label>
				</div>
			</td>
		</tr>
	</table>
	<h2><?= t('People') ?></h2>
	<table class="itmThesisEntry" cellpadding="0" cellspacing="0">
		<tr>
			<th class="lightGray"><?= t('Student') ?></th>
			<td class="lightGray">
				<div>
<?= $form->text('student', $student, array('style' => 'width: 90%')) ?>
				</div>
				<div class="note" style="width: 90%">
					As long as there is no student attending the thesis, omit this field
				</div>
			</td>
		</tr>
		<tr>
			<th><?= t('Tutor *') ?></th>
			<td><?= $form->text('tutor', $tutor, array('style' => 'width: 90%')) ?></td>
		</tr>
		<tr>
			<th class="lightGray"><?= t('Supervisor *') ?></th>
			<td class="lightGray"><?= $form->text('supervisor', $supervisor, array('style' => 'width: 90%')) ?></td>
		</tr>
	</table>
	<p>
		For additional content, please use the block type <i>ITM Thesis Paragraph</i>.
	</p>
	<p class="note">
<?= t('* Required information') ?>
	</p>
</div>
