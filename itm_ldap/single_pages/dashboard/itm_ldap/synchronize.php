<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<?php
$ih = Loader::helper('concrete/interface');

$uidFilter = htmlentities($filter['value']);
$c5Filter = $filter['c5'] == 1;
$ldapFilter = $filter['ldap'] == 1;

$userArray = array();
foreach ($userlist as $key => $item)
{
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

	$obj = array(
		'isLdap' => $ldap,
		'isC5' => $c5,
		'uid' => $key
	);

	array_push($userArray, $obj);
}

$json = Loader::helper('json');
?>
<script language="JavaScript" type="text/javascript">
	/*
	 * Dynamically organizes the LDAP user list. No AJAX calls will be made,
	 * there is just a reorganization of the user list be effected
	 */
	var LdapC5Users =
		{
		userlist: <?= $json->encode($userArray) ?>,
		filterValue: '',
		filterC5: true,
		filterLdap: true,
		updateFilter: function()
		{
			this.filterValue = $('#ldapC5UserFilterValue').val();
			this.filterC5 = $('#ldapC5UserFilterC5').filter(':checked').length;
			this.filterLdap = $('#ldapC5UserFilterLdap').filter(':checked').length;
		},
		confirmRemove: function(name)
		{
			return confirm('Are you sure you want to remove ' + name + ' from the concrete5 database?');

		},
		confirmUpdate: function(name)
		{
			return confirm('Are you sure you want to update ' + name + ' in the concrete5 database?');

		},
		confirmRemoveSelected: function()
		{
			return confirm('Are you sure you want to remove selected users?');
		},
		confirmUpdateSelected: function()
		{
			return confirm('Are you sure you want to update selected users?');
		},
		selectAll : function()
		{
			$('.ldapC5UserCheckbox').each(function(num,el){el.checked=true;});
		},
		selectNone: function()
		{
			$('.ldapC5UserCheckbox').each(function(num,el){el.checked=false;});
		},
		getSelectedUsers: function()
		{
			return $('.ldapC5UserCheckbox').map(function(num, el)
			{
				return el.checked ? el.value : null;
			}).get();
		},
		serializeSelectedUsers: function()
		{
			return JSON.stringify(this.getSelectedUsers());
		},
		serializeFilter: function()
		{
			this.updateFilter();
			return JSON.stringify({
				'value': this.filterValue,
				'c5': this.filterC5,
				'ldap': this.filterLdap
			});
		},
		filter: function()
		{		
			$('.ldapC5UserUserlistRow').detach();
			
			this.updateFilter();
			
			var tbody = $('#ldapC5UserUserlistTable').children('tbody');
						
			for (var i = 0; i < this.userlist.length; i++)
			{
				var uid = this.userlist[i]['uid'];
				var isC5 = this.userlist[i]['isC5'];
				var isLdap = this.userlist[i]['isLdap'];
				
				if (this.filterC5 || this.filterLdap)
				{
					if (this.filterC5 && this.filterLdap && (!isC5 || !isLdap))
					{
						continue;
					}
					
					if (!this.filterC5 && isC5)
					{
						continue;
					}

					if (!this.filterLdap && isLdap)
					{
						continue;
					}
				}
				
				if (uid.indexOf(this.filterValue) > -1)
				{		
					tbody.append('<tr class="ldapC5UserUserlistRow">\n\
				<td class="center">\n\
					<div class="ldapC5UserCheckbox">\n\
						<input type="checkbox" checked="checked" value="' + uid + '" class="ldapC5UserCheckbox" name="selected_users">\n\
					</div>\n\
				</td>\n\
				<td>\n\
					' + uid + '\n\
				</td>\n\
				<td class="center">\n\
					<img src="<?= ASSETS_URL_IMAGES ?>/icons/' + (isC5 ? 'success.png' : 'error.png') + '" width="16" height="16" alt="' + (isC5 ? 'Yes' : 'No') + '" />\n\
				</td>\n\
				<td class="center">\n\
					<img src="<?= ASSETS_URL_IMAGES ?>/icons/' + (isLdap ? 'success.png' : 'error.png') + '" width="16" height="16" alt="' + (isLdap ? 'Yes' : 'No') + '" />\n\
				</td>\n\
				<td class="center">\n\
					<form onsubmit="this.filter.value=LdapC5Users.serializeFilter(); return LdapC5Users.confirmUpdate(\'' + uid + '\');" action="<?= $this->action('update_user') ?>" method="post" style="display: inline">\n\
						<input type="hidden" value="' + uid + '" name="uid"/>\n\
						<input type="hidden" value="" name="filter"/>\n\
						<input type="submit" value="<?= t('Update') ?>" class="ccm-button-v2 ccm-button-inactive ccm-button-v2-left"/>\n\
					</form>\n\
					<form onsubmit="this.filter.value=LdapC5Users.serializeFilter(); return LdapC5Users.confirmRemove(\'' + uid + '\');" action="<?= $this->action('remove_user') ?>" method="post" style="display: inline">\n\
						<input type="hidden" value="' + uid + '" name="uid"/>\n\
						<input type="hidden" value="" name="filter"/>\n\
						<input type="submit" value="<?= t('Remove') ?>" class="ccm-button-v2 ccm-button-inactive ccm-button-v2-left"/>\n\
					</form>\n\
				</td>\n\
			</tr>');
										}
									}
								}
							}

							$(document).ready(function()
							{
								$('#ldapC5UserFilterValue').keyup(function(event)
								{
									LdapC5Users.filter();
								});
								LdapC5Users.filter();
							});	
</script>
<style type="text/css">
	.userTableClearfix:after
	{
		content: ".";
		display: block;
		height: 0;
		clear: both;
		visibility: hidden;
	}
</style>
<h1><span><?php echo t('Synchronize concrete5 users with LDAP users') ?></span></h1>
<div class="ccm-dashboard-inner">
	<p>
	<form style="display: inline; line-height: 36px" onsubmit="return false">
		<?= t('Filter') ?>: <input type="text" value="<?= $uidFilter ?>" name="ldapC5UserFilterValue" id="ldapC5UserFilterValue"/>&nbsp;&nbsp;&nbsp;
		<input onchange="LdapC5Users.filter();" type="checkbox" value="1" id="ldapC5UserFilterC5" name="filterC5"<?= $c5Filter ? ' checked="checked' : '"' ?>/><label for="ldapC5UserFilterC5"><?= t('Available at concrete5') ?></label>&nbsp;&nbsp;&nbsp;
		<input onchange="LdapC5Users.filter();" type="checkbox" value="1" id="ldapC5UserFilterLdap" name="filterLdap"<?= $ldapFilter ? ' checked="checked' : '"' ?>/><label for="ldapC5UserFilterLdap"><?= t('Available at LDAP server') ?></label>
	</form>
	<div style="display: inline;" class="userTableClearfix">
		<form style="display: inline;" action="<?= $this->action('remove_users') ?>" method="post" onsubmit="this.items.value=LdapC5Users.serializeSelectedUsers(); this.filter.value=LdapC5Users.serializeFilter(); return LdapC5Users.confirmRemoveSelected()">
			<input type="submit" value="<?= t('Remove selected') ?>"  class="ccm-button-v2 ccm-button-inactive ccm-button-v2-right"/>
			<input type="hidden" value="" name="items"/>
			<input type="hidden" value="" name="filter"/>
		</form>
		<form style="display: inline;" action="<?= $this->action('update_users') ?>" method="post" onsubmit="this.items.value=LdapC5Users.serializeSelectedUsers(); this.filter.value=LdapC5Users.serializeFilter(); return LdapC5Users.confirmUpdateSelected()">
			<input type="submit" value="<?= t('Update selected') ?>" class="ccm-button-v2 ccm-button-inactive ccm-button-v2-right"/>
			<input type="hidden" value="" name="items"/>
			<input type="hidden" value="" name="filter"/>
		</form>
	</div>
</p>
<table width="100%" cellspacing="1" cellpadding="0" border="0" class="grid-list" id="ldapC5UserUserlistTable">
	<thead>
		<tr>
			<td class="subheader center" style="width: 50px">
				<a onclick="LdapC5Users.selectAll()" href="javascript:void(0)"><?= t('All') ?></a> | <a onclick="LdapC5Users.selectNone()" href="javascript:void(0)"><?= t('None') ?></a>
			</td>
			<td class="subheader" style=""><?= t('Common user ID') ?></td>
			<td class="subheader center" style="width: 150px"><?= t('Available via concrete5') ?></td>
			<td class="subheader center" style="width: 150px"><?= t('Available via LDAP server') ?></td>
			<td class="subheader center" style="width: 200px"><?= t('Actions for single user') ?></td>
		</tr>
	</thead>
	<tbody>
		<tr class="ldapC5UserUserlistRow">
			<td colspan="5">
				<?= t('If you can read this, JavaScript is not enabled.') ?>
			</td>
		</tr>
	</tbody>
</table>
<p>
<div class="userTableClearfix">
	<form style="display: inline;" action="<?= $this->action('remove_users') ?>" method="post" onsubmit="this.items.value=LdapC5Users.serializeSelectedUsers(); this.filter.value=LdapC5Users.serializeFilter(); return LdapC5Users.confirmRemoveSelected()">
		<input type="submit" value="<?= t('Remove selected') ?>" class="ccm-button-v2 ccm-button-inactive ccm-button-v2-right"/>
		<input type="hidden" value="" name="items"/>
		<input type="hidden" value="" name="filter"/>
	</form>
	<form style="display: inline;" action="<?= $this->action('update_users') ?>" method="post" onsubmit="this.items.value=LdapC5Users.serializeSelectedUsers(); this.filter.value=LdapC5Users.serializeFilter(); return LdapC5Users.confirmUpdateSelected()">
		<input type="submit" value="<?= t('Update selected') ?>" class="ccm-button-v2 ccm-button-inactive ccm-button-v2-right"/>
		<input type="hidden" value="" name="items"/>
		<input type="hidden" value="" name="filter"/>
	</form>
</div>
</p>
</div>