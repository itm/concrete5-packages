<?php
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php');
?>

<div id="seiteVollbreite">
	<?php
	print $innerContent;
	?>
</div>

<?php $this->inc('elements/footer.php'); ?>