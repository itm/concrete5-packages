<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php
	$theme = PageTheme::getByHandle('itm_theme');
	$themeUrl = $theme->getThemeURL();
	$imgBase = $themeUrl . "/images";
?>
<div id="fuss">
	<div>
		<img src="<?=$imgBase?>/strichSchmalGrau.gif" width="280" height="10" hspace="10">
		<img src="<?=$imgBase?>/balkenBreitGrauHell.gif" width="650" height="10">
	</div>
	<div style="font-size: 10px; text-align: right; padding-right: 10px">
		&copy; <?php echo date('Y') ?> <a href="<?php echo DIR_REL ?>/"><?php echo SITE ?></a>.
		&nbsp;&nbsp;
		<?php echo t('All rights reserved.') ?>
		<?php
		$u = new User();
		if ($u->isRegistered())
		{
			?>
			<?php
			if (Config::get("ENABLE_USER_PROFILES"))
			{
				$userName = '<a href="' . $this->url('/profile') . '">' . $u->getUserName() . '</a>';
			}
			else
			{
				$userName = $u->getUserName();
			}
			?>
			<span class="sign-in"><?php echo t('Currently logged in as <b>%s</b>.', $userName) ?> <a href="<?php echo $this->url('/login', 'logout') ?>"><?php echo t('Sign Out') ?></a></span>
<?php }
else
{ ?>
			<span class="sign-in"><a href="<?php echo $this->url('/login') ?>"><?php echo t('Sign In to Edit this Site') ?></a></span>
<?php } ?>
	</div>
</div>

</div>

<?php Loader::element('footer_required'); ?>

</body>
</html>