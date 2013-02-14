<?php
defined('C5_EXECUTE') or die("Access Denied.");

Loader::model('page_list');

/**
 * The itm_thesis_overview block outputs a list of thesis topics linking
 * to the corresponding thesis pages. No data is written.
 */
class ItmSemesterBlockController extends BlockController
{
	protected $btTable = "btItmSemester";
	protected $btInterfaceWidth = "300";
	protected $btInterfaceHeight = "300";
	protected $btWrapperClass = 'ccm-ui';

	public function getBlockTypeDescription()
	{
		return t("Adds a course overview to a semester page.");
	}

	public function getBlockTypeName()
	{
		return t("ITM Course Overview");
	}

	public function save($data)
	{
		parent::save($data);
	}

	public function getJavaScriptStrings()
	{
		// return translated strings available for Java Script
		return array(
			'group-required' => t('Please select a group.'),
		);
	}
	
	/**
	 * @return string custom group title from DB record.
	 */
	public function getCustomTitle()
	{
		return $this->groupTitle;
	}
	
	/**
	 * @return string group filter.
	 */
	public function getGroupFilter()
	{
		return $this->groupFilter;
	}
	
	public static function cmpObj($a, $b)
    {
        //return strcmp($a['order'], $b['order']);
		return strcmp($a['name'], $b['name']);
    }
	
	/**
	 * @return array array of thesis items. Thesis items are assoc. arrays with
	 *               keys 'topic', 'status', 'type' and 'link', whereby 'link'
	 *               is a URL to the thesis resource.
	 */
	public function getCourseList()
	{
		if (empty($this->groupName) && empty($this->groupFilter))
		{
			return array();
		}
		
		// load navigation helper to create links from pages
		$nh = Loader::helper('navigation');
		
		// load thesis helper
		$th = Loader::helper('itm_thesis', 'itm_thesis');
		$pl = new PageList();
		//$pl->ignoreAliases();
		$pl->ignorePermissions();
		$pl->filterByCollectionTypeHandle('itm_course_page');
		$pl->filterByParentID(Page::getCurrentPage()->getCollectionID());

		$collections = $pl->get();
		
		//file_put_contents('semov.txt', print_r($collections, true), FILE_APPEND);
		
		// create placeholder for courses and their maintained data
		$items = array();
		foreach ($collections as $collection)
		{
			$blocks = $collection->getBlocks();
			foreach ($blocks as $block)
			{
				$bCtrl = $block->getController();
				if ($bCtrl instanceof ItmCourseBlockController)
				{
					//file_put_contents('semov.txt', print_r($blocks, true), FILE_APPEND);
					//file_put_contents('semov.txt', '__________________________________________________________________', FILE_APPEND);
					
					$ctrlData = $bCtrl->getBlockControllerData();
					
					//check user filter
					$groups = $bCtrl->getCourseGroups();
					foreach ($groups as $group)
					{
						$match = false;
						if (strlen($this->groupFilter))
						{
							if (!(strpos(strtolower($group), strtolower($this->groupFilter)) === false))
							{
								$match = true;
							}
						}
						else
						{
							if ($group == $this->groupName)
							{
								$match = true;
							}
						}
						
						
						
						if ($match)
						{
							// copy data to a new item array plus a page link
							$item = array(
								'credits' => $ctrlData->credits,
								'mode' => $ctrlData->mode,
								'type' => $ctrlData->type,
								'name' => $ctrlData->name,
								'link' => $nh->getLinkToCollection($collection),
								'order' => $collection->getCollectionDisplayOrder()
							);
							// add item to result list
							$items[] = $item;
							
							break;
						}
					}
					break;
				}
			}
		}
		
		usort($items, array('ItmSemesterBlockController', 'cmpObj'));
		
		return $items;
	}
}

?>