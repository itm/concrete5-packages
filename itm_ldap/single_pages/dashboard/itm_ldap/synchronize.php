<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<script language="JavaScript" type="text/javascript">
	var LdapC5Users = {
		confirmRemove:function(name){
			if( confirm('Are you sure you want to remove ' + name + ' from the concrete5 database?') )
				return true;
			else return false;
		},
		confirmUpdate:function(name){
			if( confirm('Are you sure you want to update ' + name + ' in the concrete5 database?') )
				return true;
			else return false;
		},
		selectAll : function(){
			$('.ldapC5UserCheckbox').each(function(num,el){
				el.checked=true;
			})
		},
		selectNone: function(){
			$('.ldapC5UserCheckbox').each(function(num,el){
				el.checked=false;
			})
		}
	} 
</script>
<h1><span><?php echo t('Synchronize concrete5 users with LDAP users') ?></span></h1>
<div class="ccm-dashboard-inner">
	<table width="100%" cellspacing="1" cellpadding="0" border="0" class="grid-list">
		<tbody>
			<tr>
				<td class="subheader center" style="width: 50px">
					<a onclick="LdapC5Users.selectAll()" href="javascript:void(0)">All</a> | <a onclick="LdapC5Users.selectNone()" href="javascript:void(0)">None</a>
				</td>
				<td class="subheader" style=""><?= t('Common user ID') ?></td>
				<td class="subheader center" style="width: 150px"><?= t('Available via concrete5') ?></td>
				<td class="subheader center" style="width: 150px"><?= t('Available via LDAP server') ?></td>
				<td class="subheader center" style="width: 200px"><?= t('Actions for single user') ?></td>
			</tr>

			<?php foreach ($userlist as $key => $item) : ?>
				<?php
				$uId = $key;

				$ldap = false;
				$c5 = false;

				if (is_array($item))
				{
					$ldap = true;
				}

				if ($item instanceof ItmLdapUserTuple)
				{
					$ldap = true;
					$c5 = true;
				}

				if ($item instanceof UserInfo)
				{
					$c5 = true;
				}
				?>
				<tr class=" " id="jobItemRow7">
					<td class="center">

						<div class="ldapC5UserCheckbox">
							<input type="checkbox" onchange="" checked="checked" value="7" class="runJobCheckbox" name="runJobCheckbox">
						</div>
					</td>
					<td>
						<?= $uId ?>
					</td>
					<td class="center">
						<img src="<?= ASSETS_URL_IMAGES ?>/icons/<?= $c5 ? 'success.png' : 'error.png' ?>" width="16" height="16" alt="<?= $c5 ? 'Yes' : 'No' ?>" />
					</td>
					<td class="center">
						<img src="<?= ASSETS_URL_IMAGES ?>/icons/<?= $ldap ? 'success.png' : 'error.png' ?>" width="16" height="16" alt="<?= $ldap ? 'Yes' : 'No' ?>" />
					</td>

					<td class="center">
						<form onsubmit="return LdapC5Users.confirmRemove('<?=$uId?>');" action="<?= $this->action('remove_user') ?>" method="post" style="display: inline">
							<input type="hidden" value="<?=$uId?>" name="uid"/>
							<input type="Submit" value="Remove" name="Remove"/>
							
						</form>
						<form onsubmit="return LdapC5Users.confirmUpdate('<?=$uId?>');" action="<?= $this->action('update_user') ?>" method="post" style="display: inline">
							<input type="hidden" value="<?=$uId?>" name="uid"/>
							<input type="Submit" value="Update" name="Update"/>
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>