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
	.itmThesisEntry label
	{
		float: none;
		width: 100%;
		text-align: left;
		cursor: pointer;
	}
	
	.itmThesisEntry td:nth-child(2)
	{
		padding-right: 20px;
	}
</style>

	<table class="itmThesisEntry zebra-striped">
		<thead>
			<th colspan="2"><?= t('Thesis general information') ?></th>
		</thead>
		<tbody>
			<tr>
				<td><?= t('Topic *') ?></td>
				<?php
					$cp = Page::getCurrentPage();
					if ($topic == t('Click and select Edit to enter thesis data.'))
					{
						$topic = $cp->getCollectionName();
					}
				?>
				<td><?= $form->text('topic', $topic, array('style' => 'width: 100%')) ?></td>
			</tr>
			<tr>
				<td><?= t('Beginning') ?></td>
				<td>
					<div>
	<?= $form->text('beginning', $beginning, array('style' => 'width: 100%')) ?>
					</div>
					<div class="note" style="width: 100%">
						Leave empty or insert a zero to force "as soon as possible"
					</div>
				</td>
			</tr>
			<tr>
				<td><?= t('Type *') ?></td>
				<td>
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
				<td><?= t('Status *') ?></td>
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
		</tbody>
	</table>

	<?php
	$json = Loader::helper('json');
	?>
	<script language="JavaScript" type="text/javascript">
		var ThesisData = 
		{
			ICON_REMOVE: '<img src="<?= ASSETS_URL_IMAGES ?>/icons/delete_small.png" width="16" height="16" alt="<?= t('Remove') ?>" title="<?= t('Remove') ?>" style="vertical-align: middle"/>',
			ICON_SWITCH: '<img src="<?= ASSETS_URL_IMAGES ?>/icons/edit_small.png" width="16" height="16" alt="<?= t('Switch Edit Mode') ?>" title="<?= t('Switch Edit Mode') ?>" style="vertical-align: middle"/>',
			ICON_UP: '<img src="<?= ASSETS_URL_IMAGES ?>/icons/arrow_up_black.png" width="11" height="6" alt="<?= t('Move Up') ?>" title="<?= t('Move Up') ?>" style="vertical-align: middle"/>',
			ICON_DOWN: '<img src="<?= ASSETS_URL_IMAGES ?>/icons/arrow_down_black.png" width="11" height="6" alt="<?= t('Move Down') ?>" title="<?= t('Move Down') ?>" style="vertical-align: middle"/>',
			ICON_UP_DISABLED: '<img src="<?= ASSETS_URL_IMAGES ?>/icons/arrow_up.png" width="11" height="6" alt="<?= t('Move Up disabled') ?>" title="<?= t('Move Up disabled') ?>" style="vertical-align: middle"/>',
			ICON_DOWN_DISABLED: '<img src="<?= ASSETS_URL_IMAGES ?>/icons/arrow_down.png" width="11" height="6" alt="<?= t('Move disabled') ?>" title="<?= t('Move Down disabled') ?>" style="vertical-align: middle"/>',
			LDAP_USERS: <?=$json->encode($this->controller->getLdapUsers())?>,
			tutors: <?= $json->encode($this->controller->getTutors()) ?>,
			supervisors: <?= $json->encode($this->controller->getSupervisors()) ?>,
			serializeTutors: function()
			{
				return JSON.stringify(this.tutors);
			},
			serializeSupervisors: function()
			{
				return JSON.stringify(this.supervisors);
			}
		}
		
	</script>

	<table class="itmThesisEntry zebra-striped">
		<thead>
			<th colspan="2"><?= t('People') ?></th>
		</thead>
		<tr>
			<td><?= t('Student') ?></td>
			<td>
				<div>
<?= $form->text('student', $student, array('style' => 'width: 100%')) ?>
				</div>
				<div class="note" style="width: 100%">
					As long as there is no student attending the thesis, omit this field
				</div>
			</td>
		</tr>
		<tr>
			<td style="width: 150px"><?= t('Tutor(s)') ?></td>
			<td>
				<div id="tutorWrapper">
					<script language="JavaScript" type="text/javascript">
						$('#tutorWrapper').wrapInner(ThesisEntry.renderList('tutor'));
					</script>
				</div>
				<div>
					<a href="#" onclick="ThesisEntry.addItem('tutor'); return false;" style="border: 0px">
						<img src="<?= ASSETS_URL_IMAGES ?>/icons/add_small.png" width="16" height="16" alt="<?= t('Add item') ?>" title="<?= t('Add item') ?>" style="vertical-align: middle"/>
					</a>
				</div>
				<input type="hidden" id="tutorsJson" name="tutorsJson" value=""/>
			</td>
		</tr>
		<tr>
			<td><?= t('Supervisor(s) ') ?></td>
			<td>
				<div id="supervisorWrapper">
					<script language="JavaScript" type="text/javascript">
						$('#supervisorWrapper').wrapInner(ThesisEntry.renderList('supervisor'));
					</script>
				</div>
				<div>
					<a href="#" onclick="ThesisEntry.addItem('supervisor'); return false;" style="border: 0px">
						<img src="<?= ASSETS_URL_IMAGES ?>/icons/add_small.png" width="16" height="16" alt="<?= t('Add item') ?>" title="<?= t('Add item') ?>" style="vertical-align: middle"/>
					</a>
				</div>
				<input type="hidden" id="supervisorsJson" name="supervisorsJson" value=""/>
			</td>
		</tr>
	</table>