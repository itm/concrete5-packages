<?php
defined('C5_EXECUTE') or die("Access Denied.");

Loader::model('page_list');

/**
 * The itm_thesis_overview block outputs a list of thesis topics linking
 * to the corresponding thesis pages. No data is written.
 */
class ItmThesisOverviewBlockController extends BlockController
{
	protected $btTable = "btItmThesisOverview";
	protected $btInterfaceWidth = "300";
	protected $btInterfaceHeight = "200";
	protected $btWrapperClass = 'ccm-ui';

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
	public function getThesisList()
	{
		// load navigation helper to create links from pages
		$nh = Loader::helper('navigation');
		
		// load thesis helper
		$th = Loader::helper('itm_thesis', 'itm_thesis');
		$pl = new PageList();
		$pl->ignoreAliases();
		$pl->ignorePermissions();
		$pl->filterByCollectionTypeHandle('itm_thesis_page');

		$collections = $pl->get();
		
		// create placeholder for thesis entries and their maintained data
		$items = array();
		
		foreach ($collections as $collection)
		{
			$blocks = $collection->getBlocks();
			foreach ($blocks as $block)
			{
				$bCtrl = $block->getController();
				if ($bCtrl instanceof ItmThesisEntryBlockController)
				{
					// get controller data - amongst others the thesis
					// data is included
					$ctrlData = $bCtrl->getBlockControllerData();
					
					//check user filter
					if (!empty($this->uName))
					{
						if ($th->isLdapName($ctrlData->tutor))
						{
							if (ITM_THESIS_LDAP_PREFIX . $this->uName != $ctrlData->tutor)
							{
								continue;
							}
						}
						else
						{
							continue;
						}
					}
					
					// copy that data to a new item array plus a page link
					$item = array(
						'topic' => $ctrlData->topic,
						'status' => $ctrlData->status,
						'type' => $ctrlData->type,
						'link' => $nh->getCollectionURL($collection)
					);
					
					// add item to result list
					$items[] = $item;
					
					break;
				}
			}
		}
		
		return $items;
	}

	/**
	 * @return array assoc. array of UserInfo objects with user names as keys
	 */
	public function getLdapUsers()
	{
		if (!$this->hasUsers())
		{
			return array();
		}

		$ilh = Loader::helper('itm_ldap', 'itm_ldap');

		$result['0'] = t('Show all');
		foreach ($ilh->getLdapStaffFromC5() as $user)
		{
			$result[$user->uName] = $user->uName;
		}
		return $result;
	}

	/**
	 *
	 * @return bool true if LDAP users are present, otherwise false
	 */
	public function hasUsers()
	{
		$ilh = Loader::helper('itm_ldap', 'itm_ldap');
		if ($ilh->hasLdapAuth())
		{
			try
			{
				return count($ilh->getLdapStaffFromC5()) > 0;
			}
			catch (Exception $e)
			{
				return false;
			}
		}
	}
	
}

?>