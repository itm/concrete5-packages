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
			<?php
				$cp = Page::getCurrentPage();
				if ($topic == t('Thesis topic goes here'))
				{
					$topic = $cp->getCollectionName();
				}
			?>
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
			<td>
				<?php if ($this->controller->hasItmLdap()) :?>
				<div id="tutorLdap">
					<?= $form->select('tutor_ldap', $this->controller->getLdapUsers(), $this->controller->isLdapTutor() ? $tutor : false, $this->controller->isLdapTutor() ? array('style' => 'width: 80%') : array('style' => 'width: 80%', 'disabled' => 'disabled'))?>
					<span style="font-size: 8pt"><a href="#" onclick="LdapEntry.switchEntry('tutor', '', ''); return false;">Customize...</a></span>
				</div>
				<div id="tutorRaw" style="margin-top: 5px; display: <?= $this->controller->isLdapTutor() ? 'none' : 'block' ?>;">
					<?= $form->text('tutor', $this->controller->isLdapTutor() ? '' : $tutor, array('style' => 'width: 80%')) ?>
					<a href="#" onclick="LdapEntry.hideEntry('tutor', ''); return false;">
						<img src="<?= ASSETS_URL_IMAGES ?>/icons/remove.png" width="16" height="16" alt="<?= t('Remove') ?>" style="vertical-align: middle"/>
					</a>
				</div>
				<?php else : ?>
					<?= $form->text('tutor', $tutor, array('style' => 'width: 90%')) ?>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th class="lightGray"><?= t('Supervisor *') ?></th>
			<td class="lightGray">
				<?php if ($this->controller->hasItmLdap()) :?>
				<div id="supervisorLdap">
					<?= $form->select('supervisor_ldap', $this->controller->getLdapUsers(), $this->controller->isLdapSupervisor() ? $supervisor : false, $this->controller->isLdapSupervisor() ? array('style' => 'width: 80%') : array('style' => 'width: 80%', 'disabled' => 'disabled'))?>
					<span style="font-size: 8pt"><a href="#" onclick="LdapEntry.switchEntry('supervisor', '', ''); return false;">Customize...</a></span>
				</div>
				<div id="supervisorRaw" style="margin-top: 5px; display: <?= $this->controller->isLdapSupervisor() ? 'none' : 'block' ?>;">
					<?= $form->text('supervisor', $this->controller->isLdapSupervisor() ? '' : $supervisor, array('style' => 'width: 80%')) ?>
					<a href="#" onclick="LdapEntry.hideEntry('supervisor', ''); return false;">
						<img src="<?= ASSETS_URL_IMAGES ?>/icons/remove.png" width="16" height="16" alt="<?= t('Remove') ?>" style="vertical-align: middle"/>
					</a>
				</div>
				<?php else : ?>
					<?= $form->text('supervisor', $supervisor, array('style' => 'width: 90%')) ?>
				<?php endif; ?>
			</td>
		</tr>
	</table>
	<p class="note">
<?= t('* Required information') ?>
	</p>
</div>
