<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<!-- this view will never be shown. -->
<?php
$ih = Loader::helper('concrete/interface');
$dh = Loader::helper('concrete/dashboard');
?>
<?php echo $dh->getDashboardPaneHeaderWrapper(t('Manage course groups'), false, false, true, array(), Page::getByPath("/dashboard")); ?>

<?php
	
?>

<?php echo $dh->getDashboardPaneFooterWrapper(); ?>