<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<?php
	// redirect if LDAP Auth is not available
	$hasLdap = $this->controller->hasLdapAuth();
	if (!$hasLdap)
	{
		include_once('noldapauth.php');
	}
	
	if ($hasLdap) :
?>
<h1><span><?php echo t('LDAP Actions') ?></span></h1>
<div class="ccm-dashboard-inner">
	<form name="ldapActions">
		<input type="button" onclick="window.location.href='<?=$this->action('update_new_users')?>'" value="<?=t('Update New Users')?>"/>
	</form>
</div>

<h1><span><?php echo t('Resolved LDAP settings from LDAP Auth') ?></span></h1>
<div class="ccm-dashboard-inner">
	<?php
	//$users = $this->controller->getLDAPUsers();
	?>

		TEST
	
</div>
<?php endif; ?>