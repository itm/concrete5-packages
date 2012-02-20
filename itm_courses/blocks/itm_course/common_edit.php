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
	.itmCourse label
	{
		float: none;
		width: 100%;
		text-align: left;
		cursor: pointer;
	}
	
	.itmCourse td:nth-child(2)
	{
		padding-right: 20px;
	}
</style>


	<table class="itmCourse zebra-striped">
		<thead>
			<th colspan="2"><?= t('General Course Information') ?></th>
		</thead>
		<tbody>
			<tr>
				<td><?= t('Name') ?></td>
				<?php
					$cp = Page::getCurrentPage();
					if ($topic == t('Course topic goes here'))
					{
						$topic = $cp->getCollectionName();
					}
				?>
				<td><?= $form->text('name', $name, array('style' => 'width: 100%')) ?></td>
			</tr>
			<tr>
				<td><?= t('Type') ?></td>
				<td>
					<div>
						<label for="type1">
							<?= $form->radio('type', '0', $type) ?> <?= t('Course') ?>
						</label>
					</div>
					<div>
						<label for="type2">
							<?= $form->radio('type', '1', $type) ?> <?= t('Seminar') ?>
						</label>
					</div>
					<div>
						<label for="type3">
							<?= $form->radio('type', '2', $type) ?> <?= t('Practical Course') ?>
						</label>
					</div>
				</td>
			</tr>
			<tr>
				<td><?= t('Credits') ?></td>
				<td><?= $form->text('credits', $credits, array('style' => 'width: 100%')) ?></td>
			</tr>
		</tbody>
	</table>

	<?php
	$json = Loader::helper('json');
	?>
	<script language="JavaScript" type="text/javascript">
		var CourseData = 
		{
			ICON_REMOVE: '<img src="<?= ASSETS_URL_IMAGES ?>/icons/delete_small.png" width="16" height="16" alt="<?= t('Remove') ?>" title="<?= t('Remove') ?>" style="vertical-align: middle"/>',
			ICON_SWITCH: '<img src="<?= ASSETS_URL_IMAGES ?>/icons/edit_small.png" width="16" height="16" alt="<?= t('Switch Edit Mode') ?>" title="<?= t('Switch Edit Mode') ?>" style="vertical-align: middle"/>',
			ICON_UP: '<img src="<?= ASSETS_URL_IMAGES ?>/icons/arrow_up_black.png" width="11" height="6" alt="<?= t('Move Up') ?>" title="<?= t('Move Up') ?>" style="vertical-align: middle"/>',
			ICON_DOWN: '<img src="<?= ASSETS_URL_IMAGES ?>/icons/arrow_down_black.png" width="11" height="6" alt="<?= t('Move Down') ?>" title="<?= t('Move Down') ?>" style="vertical-align: middle"/>',
			ICON_UP_DISABLED: '<img src="<?= ASSETS_URL_IMAGES ?>/icons/arrow_up.png" width="11" height="6" alt="<?= t('Move Up disabled') ?>" title="<?= t('Move Up disabled') ?>" style="vertical-align: middle"/>',
			ICON_DOWN_DISABLED: '<img src="<?= ASSETS_URL_IMAGES ?>/icons/arrow_down.png" width="11" height="6" alt="<?= t('Move disabled') ?>" title="<?= t('Move Down disabled') ?>" style="vertical-align: middle"/>',
			LDAP_USERS: <?= $json->encode($this->controller->getLdapUsers()) ?>,
			lecturers: <?= $json->encode($this->controller->getLecturers()) ?>,
			assistants: <?= $json->encode($this->controller->getAssistants()) ?>,
			serializeLecturers: function()
			{
				return JSON.stringify(this.lecturers);
			},
			serializeAssistants: function()
			{
				return JSON.stringify(this.assistants);
			}
		}
		
	</script>

	<table class="itmCourse zebra-striped">
		<thead>
			<th colspan="2"><?= t('People') ?></th>
		</thead>
		<tr>
			<td style="width: 150px"><?= t('Lecturer(s)') ?></td>
			<td>
				<div id="lecturerWrapper">
					<script language="JavaScript" type="text/javascript">
						$('#lecturerWrapper').wrapInner(CourseEntry.renderList('lecturer'));
					</script>
				</div>
				<div>
					<a href="#" onclick="CourseEntry.addItem('lecturer'); return false;" style="border: 0px">
						<img src="<?= ASSETS_URL_IMAGES ?>/icons/add_small.png" width="16" height="16" alt="<?= t('Add item') ?>" title="<?= t('Add item') ?>" style="vertical-align: middle"/>
					</a>
				</div>
				<input type="hidden" id="lecturersJson" name="lecturersJson" value=""/>
			</td>
		</tr>
		<tr>
			<td><?= t('Teaching Assistant(s) ') ?></td>
			<td>
				<div id="assistantWrapper">
					<script language="JavaScript" type="text/javascript">
						$('#assistantWrapper').wrapInner(CourseEntry.renderList('assistant'));
					</script>
				</div>
				<div>
					<a href="#" onclick="CourseEntry.addItem('assistant'); return false;" style="border: 0px">
						<img src="<?= ASSETS_URL_IMAGES ?>/icons/add_small.png" width="16" height="16" alt="<?= t('Add item') ?>" title="<?= t('Add item') ?>" style="vertical-align: middle"/>
					</a>
				</div>
				<input type="hidden" id="assistantsJson" name="assistantsJson" value=""/>
			</td>
		</tr>
	</table>
