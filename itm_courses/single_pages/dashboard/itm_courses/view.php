<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<?php
$ih = Loader::helper('concrete/interface');
$dh = Loader::helper('concrete/dashboard');
$fh = Loader::helper('form');
$ch = Loader::helper('itm_courses', 'itm_courses');
$groups = $ch->getCourseGroups();
?>
<?php echo $dh->getDashboardPaneHeaderWrapper(t('Manage Course Groups'), false, false, true, array(), Page::getByPath("/dashboard")); ?>

<?php if (count($groups)) : ?>
<table>
	<thead>
		<tr>
			<th style="width: 40%"><?=t('Handle')?></th>
			<th style="width: 40%"><?=t('Name')?></th>
			<th style="width: 10%; text-align: center"><?=t('Delete')?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($groups as $key => $value) : ?>
		<tr>
			<td><?=$key?></td>
			<td><?=$value?></td>
			<td style="text-align: center">
				<a href="<?= $this->action('delete_group') . '?handle='.rawurlencode($key) ?>" onclick="return confirm('<?=t('Are you sure you want to delete this group?')?>')"><img src="<?= ASSETS_URL_IMAGES ?>/icons/delete_small.png" width="16" height="16" alt="<?= t('Remove') ?>" title="<?= t('Remove') ?>" style="vertical-align: middle"/></a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<? else :
	echo '<p>' . t('There are currently no groups available.') . '</p>';
endif;
?>
<div class="clearfix">
	<?php
	$eh = t('Enter handle');
	$en = t('Enter name');
	?>
	<form method="POST" id="new_group_form" action="<?= $this->action('insert_group') ?>">
		<?php echo $fh->text('new_handle', $eh, array('style' => 'margin-right: 5px;float: left')) ?>
		<?php echo $fh->text('new_name', $en, array('style' => 'margin-right: 5px;float: left')) ?>	
		<?php echo $ih->submit(t('Create new group'), 'new_group_submit', 'left', 'primary', array('style' => 'margin-right: 5px;'))?>
	</form>
	
	<script language="JavaScript" type="text/javascript">
		$('#new_handle').focus(function()
		{
			if (this.value == '<?=$eh?>')
			{
				this.value = ''
			}
		});
		$('#new_handle').blur(function()
		{
			if (this.value == '')
			{
				this.value = '<?=$eh?>';
			}
		});
		$('#new_name').focus(function()
		{
			if (this.value == '<?=$en?>')
			{
				this.value = ''
			}
		});
		$('#new_name').blur(function()
		{
			if (this.value == '')
			{
				this.value = '<?=$en?>';
			}
		});
		$('#new_group_form').submit(function()
		{
			if ($('#new_name').val() == '<?=$en?>' || $('#new_handle').val() == '<?=$eh?>')
			{
				alert('<?=t('Please enter valid handle and name.')?>');
				return false;
			}
			
			return true;
		});
	</script>
</div>

<?php echo $dh->getDashboardPaneFooterWrapper(); ?>