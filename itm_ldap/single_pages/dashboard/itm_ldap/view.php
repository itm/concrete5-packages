<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<!-- this view will never be shown. -->
<?php
$ih = Loader::helper('concrete/interface');
$dh = Loader::helper('concrete/dashboard');
?>
<?php echo $dh->getDashboardPaneHeaderWrapper(t('Synchronize concrete5 users with LDAP users'), false, false, true, array(), Page::getByPath("/dashboard")); ?>

<?php
	$h = Loader::helper('itm_ldap', 'itm_ldap');
	
	$staff = $h->getLdapStaff($bind);
	var_dump($staff);
?>

<?php echo $dh->getDashboardPaneFooterWrapper(); ?>