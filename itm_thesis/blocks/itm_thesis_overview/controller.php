<?php

/**
 * The itm_thesis_overview block outputs a list of thesis topics linking
 * to the corresponding thesis pages. No data is written.
 */
class ItmThesisOverviewBlockController extends BlockController
{
	protected $btTable = "btItmThesisOverview";
	protected $btInterfaceWidth = "350";
	protected $btInterfaceHeight = "200";

	public function getBlockTypeDescription()
	{
		return t("Adds a thesis overview to a page.");
	}

	public function getBlockTypeName()
	{
		return t("ITM Thesis Overview");
	}

	public function save($data)
	{
		// do nothing on save - this is a read only block
	}

	// is called during page view and adds custom stylesheet
	public function on_page_view()
	{
		$bt = BlockType::getByHandle($this->btHandle);
		$uh = Loader::helper('concrete/urls');
		$this->addHeaderItem('<link rel="stylesheet" type="text/css" href="'. $uh->getBlockTypeAssetsURL($bt, 'style.css') .'" />');
	}
	
	public function getThesisList()
	{
		// get current page collection
		$c = Page::getCurrentPage();

		// resolve children - these will be all thesis entries
		$children = $c->getCollectionChildrenArray();
		
		// if there is no data, leave
		if (empty($children))
		{
			return array();
		}

		// create placeholder for thesis entries and their maintained data
		$items = array();
		
		// load navigation helper to create links from pages
		$nh = Loader::helper('navigation');

		// loop children and fill $items array
		for ($i = 0; $i < count($children); $i++)
		{
			$child = $children[$i];
			
			// since $children list only contains numbers, fetch corresponding
			// objects from database
			$page = Page::getByID($child);
			
			// fetch the pages blocks
			$blocks = $page->getBlocks();

			// try to discover ITM Thesis Entry item
			// the page will be ignored if no such item is found
			for ($j = 0; $j < count($blocks); $j++)
			{
				$block = $blocks[$j];
				
				// get controller to compare block type and
				// finally ascertain thesis data
				$bController = $block->getController();
				if ($bController instanceof ItmThesisEntryBlockController)
				{
					// get controller data - amongst others the thesis
					// data is included
					$ctrlData = $bController->getBlockControllerData();
					
					// copy that data to a new item array plus a page link
					$item = array(
						'topic' => $ctrlData->topic,
						'status' => $ctrlData->status,
						'type' => $ctrlData->type,
						'link' => $nh->getCollectionURL($page)
					);
					
					// add item to result list
					$items[] = $item;
					
					break;
				}
			}
		}

		return $items;
	}

}

?>