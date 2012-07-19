<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

/*
 * Process of this file:
 * 1) Get course list
 * 2) Run through the list and output courses that belongs to given group
 */

$list = $this->controller->getCourseList();
$ch = Loader::helper('itm_courses', 'itm_courses');
$customTitle = $this->controller->getCustomTitle();
if (strlen($customTitle))
{
	$groupTitle = $customTitle;
}
else
{
	if ($this->controller->getGroupFilter() == '') $groupTitle = t($ch->getCourseGroupByHandle($groupName)->name);
}

$typeMapping = array(t('Course'), t('Seminar'), t('Workshop'), t('Practical Course'), t('Project'));


if (strlen($groupTitle)) echo '<h2>' . $groupTitle . '</h2>';

if (!count($list)) :
	echo !empty($groupName) ? '<p>' . t('There are no courses available for ') . $groupTitle . '.</p>' : '';
else :
	?>
	<table class="itmTable itmSemester">
		<thead>
			<tr>
				<th class="name">
					<?= t('Name') ?>
				</th>
				<th class="type">
					<?= t('Type') ?>
				</th>
				<th class="credits">
					<?= t('Credits') ?>
				</th>
				<!--<th>
					<?= t('Mode') ?>
				</th>-->
			</tr>
		</thead>
		<?php for ($i = 0; $i < count($list); $i++): ?>
			<tbody>
				<tr>
					<td>
						<a href="<?= $list[$i]['link'] ?>">
							<?= $list[$i]['name'] ?>
						</a>
					</td>
					<td>
						<?= $typeMapping[$list[$i]['type']] ?>
					</td>
					<td>
						<?= strlen($list[$i]['credits']) ? $list[$i]['credits'] : 'N/A' ?>
					</td>
					<!--<td>
						<?= $list[$i]['mode'] ?>
					</td>-->
				</tr>
			</tbody>
		<?php endfor; ?>
	</table>
<?php endif; ?>