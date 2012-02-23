<?php
defined('C5_EXECUTE') or die("Access Denied.");

Loader::model('page_list');

/**
 * The itm_thesis_overview block outputs a list of thesis topics linking
 * to the corresponding thesis pages. No data is written.
 */
class ItmSemesterOverviewBlockController extends BlockController
{
	protected $btTable = "btItmSemesterOverview";
	protected $btInterfaceWidth = "300";
	protected $btInterfaceHeight = "200";
	protected $btWrapperClass = 'ccm-ui';

	public function getBlockTypeDescription()
	{
		return t("Adds a semester overview to a page.");
	}

	public function getBlockTypeName()
	{
		return t("ITM Semester Overview");
	}

	public function save($data)
	{
		parent::save($data);
	}

	// is called during page view and adds custom stylesheet
	public function on_page_view()
	{
		$bt = BlockType::getByHandle($this->btHandle);
		$uh = Loader::helper('concrete/urls');
		$this->addHeaderItem('<link rel="stylesheet" type="text/css" href="'. $uh->getBlockTypeAssetsURL($bt, 'style.css') .'" />');
	}
	
	/**
	 * @return array array of thesis items. Thesis items are assoc. arrays with
	 *               keys 'topic', 'status', 'type' and 'link', whereby 'link'
	 *               is a URL to the thesis resource.
	 */
	public function getSemesterList()
	{
		// load navigation helper to create links from pages
		$nh = Loader::helper('navigation');
		
		$pl = new PageList();
		$pl->ignoreAliases();
		$pl->ignorePermissions();
		$pl->filterByCollectionTypeHandle('itm_semester_page');

		$collections = $pl->get();
		
		foreach ($collections as $collection)
		{
			$aTerm = $collection->getCollectionAttributeValue('semester_term');
			$term = $aTerm->current();
			if ($term == t('Summer term'))
			{
				$term = 'summerterm';
			}
			else
			{
				$term = 'winterterm';
			}
			
			$year = $collection->getAttribute('semester_year');
			$items[$year][$term] = $nh->getLinkToCollection($collection);			
		}
		
		krsort($items);
		return $items;
	}
}

?>