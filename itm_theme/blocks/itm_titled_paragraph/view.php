<?php
defined('C5_EXECUTE') or die("Access Denied.");
$content = $controller->getContent();
?>

<h2 class="itmTitledParagraphTitle">
	<?= $title ?>
</h2>
<div class="itmTitledParagraphContent">
	<?= $content ?>
</div>