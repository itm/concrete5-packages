<?php
/*
 * This code is generally copy-pasted from autonav header_menu template and
 * decorated with corporate-design-conform style classes.
 */
defined('C5_EXECUTE') or die("Access Denied.");
$aBlocks = $controller->generateNav();
$c = Page::getCurrentPage();
echo("<ul>");

$nh = Loader::helper('navigation');

$isFirst = true;

$level = 0;
$firstLoop = true;
foreach ($aBlocks as $ni)
{
	$_c = $ni->getCollectionObject();
	if (!$_c->getCollectionAttributeValue('exclude_nav'))
	{
		if ($level < $ni->getLevel())
		{
			$level++;
			echo '<ul class="SubMenu">';
		}
		else
		{
			if ($level > $ni->getLevel())
			{
				$level--;
				echo '</li>';
				echo '</ul>';
			}
			else
			{
				if (!$firstLoop)
				{
					echo('</li>');
				}
				else
				{
					$firstLoop = false;
				}
			}
		}
		
		$target = $ni->getTarget();
		if ($target != '')
		{
			$target = 'target="' . $target . '"';
		}

		if ($ni->isActive($c) || strpos($c->getCollectionPath(), $_c->getCollectionPath()) === 0)
		{
			$navSelected = '';
		}
		else
		{
			$navSelected = '';
		}

		$pageLink = false;

		if ($_c->getCollectionAttributeValue('replace_link_with_first_in_nav'))
		{
			$subPage = $_c->getFirstChild();
			if ($subPage instanceof Page)
			{
				$pageLink = $nh->getLinkToCollection($subPage);
			}
		}

		if (!$pageLink)
		{
			$pageLink = $ni->getURL();
		}

		if ($isFirst)
			$isFirstClass = 'first';
		else
			$isFirstClass = '';

		echo '<li class="menuItem ' . $navSelected . ' ' . $isFirstClass . '">';

		if ($c->getCollectionID() == $_c->getCollectionID())
		{
			echo('<a class="nav-selected" href="' . $pageLink . '"  ' . $target . '>' . $ni->getName() . '</a>');
		}
		else
		{
			echo('<a href="' . $pageLink . '"  ' . $target . '>' . $ni->getName() . '</a>');
		}

		$isFirst = false;
	}
}

echo('</ul>');
echo('<div class="ccm-spacer">&nbsp;</div>');
?>