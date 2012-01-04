<?php
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php');
?>

<div id="rechts">
	<?php
	$as = new Area('Right Sidebar');
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