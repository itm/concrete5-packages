<?php
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php');
?>

<div id="links">
	<?php
	$as = new Area('Sidebar');
	$as->display($c);
	?>
</div>
<div id="seite">
	<?php
	$a = new Area('Main');
	$a->display($c);
	?>
</div>

<?php $this->inc('elements/footer.php'); ?>