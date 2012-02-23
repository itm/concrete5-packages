<?php
/*
 * Process of this file:
 * 1) Get thesis list
 * 2) Run through the list:
 *    - map type and status integer to senseful captions
 *    - output values in tabular form
 */

$list = $this->controller->getSemesterList();
$wtCaption = t('Winter term');
$stCaption = t('Summer term');
echo '<h1>' . t('Teaching') . '</h1>';

if (!count($list)) :
	echo '<p>' . t('There are currently no summer or winter terms available.') . '</p>';
else :
	?>
	<table class="itmSemesterOverview">
		<?php
		foreach($list as $key => $item)
		{
			echo '<tr>';
			if (!empty($item['winterterm']))
			{
				echo sprintf('<td><a href="%s">%s %s</td>', $item['winterterm'], $wtCaption, $key . '/' . substr(($key+1), 2, 2));
			}
			else
			{
				echo '<td></td>';
			}
			
			if (!empty($item['summerterm']))
			{
				echo sprintf('<td><a href="%s">%s %s</td>', $item['summerterm'], $stCaption, $key);
			}
			else
			{
				echo '<td></td>';
			}
		}
		?>
	</table>
<?php endif; ?>