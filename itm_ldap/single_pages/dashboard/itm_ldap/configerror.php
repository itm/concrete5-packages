<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<h1><span style="color: red"><?php echo t('ERROR: configuration corrupted') ?></span></h1>
<div class="ccm-dashboard-inner">
	<p><?= $msg ?></p>
	<p><?= t('Please change settings at <i>Dashboard / Users and Groups / LDAP</i>.') ?></p>
</div>
