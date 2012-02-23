<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

$pubList = $this->controller->getFilteredPubList();

if (!count($pubList)) :
	?>
	<h1 style="color: red; font-weight: bold">
		<?php echo t('Please specify required filter data.'); ?>
	</h1>
<?php else : ?>
	<h2>Publications</h2>
	<div>
		<?php
		$bh = Loader::helper('itm_bibtex', 'itm_bibtex');
		foreach ($pubList as $year => $bibEntries)
		{
			echo "<h3>$year</h3>";
			echo '<ul class="itmBibtexList">';
			foreach ($bibEntries as $bibEntry)
			{
				echo $bh->renderBibEntry($bibEntry, $popupurl . '?key=' . $bibEntry->getKey() . '&bf=' . $this->controller->getFileID());
			}
			echo '</ul>';
		}
		?>
	</div>
<?php endif; ?>
