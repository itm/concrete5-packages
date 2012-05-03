<?php
/*
 * Process of this file:
 * 1) Get thesis list
 * 2) Run through the list:
 *    - map type and status integer to senseful captions
 *    - output values in tabular form
 */

$list = $this->controller->getThesisList();

if (empty($uName))
{
	echo '<h1>' . t('Theses') . '</h1>';
}
else
{
	echo '<h2>' . t('Theses') . '</h2>';
}

if (!count($list)) :
	echo '<p>' . (empty($uName) ? t('There are currently no theses available.') : t('There are currently no theses supervised by me.')) . '</p>';
else :
	?>
	<table class="itmTable">
		<tr>
			<th class="topic">
				<?= t('Topic') ?>
			</th>
			<th class="type">
				<?= t('Type') ?>
			</th>
			<th class="status">
				<?= t('Status') ?>
			</th>
		</tr>
		<?php for ($i = 0; $i < count($list); $i++): ?>
			<?php
			$topic = $list[$i]['topic'];
			$link = $list[$i]['link'];
			switch ($list[$i]['type'])
			{
				case 0:
					$type = t('Bachelor thesis');
					break;

				case 1 :
					$type = t('Master thesis');
					break;

				default :
					$type = t('Both');
					break;
			}

			$imgBase = DIR_REL . '/packages/itm_thesis/images/';
			switch ($list[$i]['status'])
			{
				case 0 :
					$status = t('Open');
					$statusImg = $imgBase . 'light_green.png';
					break;

				case 1 :
					$status = t('Running');
					$statusImg = $imgBase . 'light_yellow.png';
					break;

				default :
					$status = t('Finished');
					$statusImg = $imgBase . 'light_red.png';
					break;
			}
			?>
			<tr>
				<td class="topic">
					<a href="<?= $link ?>">
						<?= $topic ?>
					</a>
				</td>
				<td class="type">
					<?= $type ?>
				</td>
				<td class="status">
					<img src="<?= $statusImg ?>" width="16" height="16" style="border: 0px;" alt="<?= $status ?>" title="<?= $status ?>"/>
				</td>
			</tr>
		<?php endfor; ?>
	</table>
<?php endif; ?>