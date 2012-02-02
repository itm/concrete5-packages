<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
$dh = Loader::helper('concrete/dashboard');
?>

<?php echo $dh->getDashboardPaneHeaderWrapper('<span style="color: red">'.t('ERROR: configuration required').'</span>', false, false, true, array(), Page::getByPath("/dashboard")); ?>

<p><?= $msg ?></p>
<p><?= t('Please change settings at <i>Dashboard / Users and Groups / LDAP</i>.') ?></p>

<?php echo $dh->getDashboardPaneFooterWrapper(); ?>