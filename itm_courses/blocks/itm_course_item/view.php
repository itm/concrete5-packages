<?php
defined('C5_EXECUTE') or die("Access Denied.");
$content = $controller->getContent();
?>

<h2 class="itmCourseEntry">
	<?= $title ?>
</h2>
<div class="itmCourseEntry">
	<?= $content ?>
</div>