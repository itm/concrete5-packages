<?php
defined('C5_EXECUTE') or die("Access Denied.");
$content = $controller->getContent();
?>

<h2 class="itmThesisEntryContentTitle">
	<?= $title ?>
</h2>
<div class="itmThesisEntryContentBlock">
	<?= $content ?>
</div>