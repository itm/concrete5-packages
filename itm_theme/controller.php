<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

class ItmThemePackage extends Package
{
	protected $pkgHandle = 'itm_theme';
	protected $appVersionRequired = '5.4.2';
	protected $pkgVersion = '0.1';

	public function getPackageDescription()
	{
		return t("Installs the ITM theme.");
	}

	public function getPackageName()
	{
		return t("ITM Theme");
	}

	public function install()
	{
		$pkg = parent::install();
		
		// install theme
		PageTheme::add('itm_theme', $pkg);

		// install
		BlockType::installBlockTypeFromPackage('itm_titled_paragraph', $pkg);
		
		// install page type defaults
		Loader::model('collection_types');
		
		// insert page types where to set defaults
		$handles = array(
			'full',
			'right_sidebar',
			'left_sidebar'
		);
		
		$hDescr = array(
			'full' => t('One Column'),
			'right_sidebar' => t('Right Sidebar'),
			'left_sidebar' => t('Left Sidebar')
		);
		
		$hIcon = array(
			'full' => 'main.png',
			'right_sidebar' => 'template3.png',
			'left_sidebar' => 'template1.png'
		);
		
		foreach ($handles as $handle)
		{
			$ct = CollectionType::getByHandle($handle);
			if (!$ct)
			{
				CollectionType::add(array(
					'ctHandle' => $handle,
					'ctName' => $hDescr[$handle],
					'ctIcon' => $hIcon[$handle]
				), $pkg);
			}
		}
		
		// add default page type
		$handles[] = 'page';
		
		// loop page type handles
		foreach ($handles as $handle)
		{
			// get page types and their master template
			$ct = CollectionType::getByHandle($handle);
			if (!$ct)
				continue;
			
			$mTpl = $ct->getMasterTemplate();
			
			// now remove all elements from Navigation area
			$aNavigation = Area::getOrCreate($mTpl, 'Navigation');
			$blocks = $aNavigation->getAreaBlocksArray($mTpl);
			foreach ($blocks as $block)
			{
				$block->delete();
			}
			
			ItmThemePackage::addNavigationBlock($mTpl);
			
			// do the same for Breadcrumbs area
			$aBreadcrumbs = Area::getOrCreate($mTpl, 'Breadcrumbs');
			$blocks = $aBreadcrumbs->getAreaBlocksArray($mTpl);
			foreach($blocks as $block)
			{
				$block->delete();
			}
			
			ItmThemePackage::addBreadcrumbsBlock($mTpl);
		}
	}
	
	/**
	 * Adds a Breadcrumbs block to a collection.
	 * 
	 * @param Collection $collection Collection object where to add the block
	 * @param array $data additional for Collection::addBlock() method
	 * @return Block inserted block 
	 */
	public static function addBreadcrumbsBlock($collection, $data = array())
	{
		$aBreadcrumbs = Area::getOrCreate($collection, 'Breadcrumbs');

		// fetch autonav block type to insert new autonav blocks
		$btAutonav = BlockType::getByHandle("autonav");		

		// database entry for breadcrumbs block
		$breadcrumbsRecord = array(
			'orderBy' => 'display_asc',
			'displayPages' => 'top',
			'displayPagesCID' => '0',
			'displayPagesIncludeSelf' => '0',
			'displaySubPages' => 'relevant_breadcrumb',
			'displaySubPageLevels' => 'enough',
			'displaySubPageLevelsNum' => '0',
			'displayUnavailablePages' => '0'
		);
		
		// insert a new breadcrumbs block with suitbale template
		$bBreadcrumbs = $collection->addBlock($btAutonav, $aBreadcrumbs, $data);
		$bBreadcrumbs->setCustomTemplate('breadcrumb.php');
		$bBreadcrumbs->getController()->save($breadcrumbsRecord);
		
		return $bBreadcrumbs;
	}
	
	/**
	 * Adds a Navigation block to a collection.
	 * 
	 * @param Collection $collection Collection object where to add the block
	 * @param array $data additional for Collection::addBlock() method
	 * @return Block inserted block 
	 */
	public static function addNavigationBlock($collection, $data = array())
	{
		$aNavigation = Area::getOrCreate($collection, 'Navigation');

		// fetch autonav block type to insert new autonav blocks
		$btAutonav = BlockType::getByHandle("autonav");

		// database entry for navigation block
		$navigationRecord = array(
			'orderBy' => 'display_asc',
			'displayPages' => 'top',
			'displayPagesCID' => '0',
			'displayPagesIncludeSelf' => '0',
			'displaySubPages' => 'relevant',
			'displaySubPageLevels' => 'all',
			'displaySubPageLevelsNum' => '0',
			'displayUnavailablePages' => '0'
		);
		
		// insert a new navigation block with suitbale template
		$bNavigation = $collection->addBlock($btAutonav, $aNavigation, $data);
		$bNavigation->setCustomTemplate('itm_vertical_menu.php');
		$bNavigation->getController()->save($navigationRecord);
		
		return $bNavigation;
	}
}

?>
