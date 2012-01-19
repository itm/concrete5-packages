<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php
	$theme = PageTheme::getByHandle('itm_theme');
	$themeUrl = $theme->getThemeURL();
	$imgBase = $themeUrl . "/images";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="<?php echo LANGUAGE?>" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php  Loader::element('header_required'); ?>
	<link rel="stylesheet" media="screen" type="text/css" href="<?php echo $this->getStyleSheet('css/main.css')?>" />
	<link rel="stylesheet" media="screen" type="text/css" href="<?php echo $this->getStyleSheet('css/typography.css')?>" />
</head>
<body>
<div id="rahmen">
	<div id="logo">
		<div id="logoUni"><img src="<?=$imgBase?>/LogoITM.png" width="280" height="87" alt="Universit&auml;t zu L&uuml;beck"></div>
		<div id="logoSlogan"><img src="<?=$imgBase?>/sloganHell.gif" width="158" height="28" alt="Im Focus das Leben"></div>

	</div>
	<div id="kopfBalken"><img src="<?=$imgBase?>/strichSchmalGrau.gif" width="280" height="10" hspace="10"><img src="<?=$imgBase?>/balkenBreitGrauHell.gif" width="650" height="10"></div>
	<div id="pfad">
		<div id="pfadTitel"><a href="<?= DIR_REL ?>">INSTITUTE OF TELEMATICS</a></div>
		<div id="pfadLeiste">
			<?php
			$as = new Area('Breadcrumbs');
			$as->display($c);
			?>
		</div>
	</div>
	<div id="teaser">
		<div id="teaserLinks">
			<?php
			$as = new Area('Teaser Image Left');
			$as->display($c);
			?>
		</div>
		<div id="teaserRechts">
			<?php
			$as = new Area('Teaser Image Right');
			$as->display($c);
			?>
		</div>
	</div>
	<div id="mitte">
		<div id="menu">
			<?php
			$as = new Area('Navigation');
			$as->display($c);
			?>
		</div>
	