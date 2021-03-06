<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

class ItmThesisPackage extends Package
{
	protected $pkgHandle = 'itm_thesis';
	protected $appVersionRequired = '5.5.1';
	protected $pkgVersion = '1.2';

	public function getPackageDescription()
	{
		return t("Installs the ITM thesis package.");
	}

	public function getPackageName()
	{
		return t("ITM Thesis");
	}

	public function install()
	{
		$pkg = parent::install();
		$themePkg = Package::getByHandle('itm_theme');

		if (empty($themePkg))
		{
			$pkg->uninstall();
			throw new Exception("Required package <b>itm_theme</b> not found. Install it in advance.");
		}

		// install blocks
		BlockType::installBlockTypeFromPackage('itm_thesis_entry', $pkg);
		BlockType::installBlockTypeFromPackage('itm_thesis_overview', $pkg);

		// install page type
		Loader::model('collection_types');
		$ctItmThesisPage = CollectionType::getByHandle('itm_thesis_page');
		if (!$ctItmThesisPage || !intval($ctItmThesisPage->getCollectionTypeID()))
		{
			$ctItmThesisPage = CollectionType::add(array('ctHandle' => 'itm_thesis_page', 'ctName' => t('Thesis')), $pkg);
		}

		// add default attribute
		$ctItmThesisPage->assignCollectionAttribute(CollectionAttributeKey::getByHandle('exclude_nav'));

		// install default page of itm_thesis_page page type
		// this includes setting up a default itm_thesis_entry block,
		// a default "Research Area" custom content block and a default
		// "The Thesis Topic" custom content block
		// obtain master template
		$mTplItmThesisPage = $ctItmThesisPage->getMasterTemplate();

		// create content area within master template
		$aThesisInformation = Area::getOrCreate($mTplItmThesisPage, 'Thesis Information');

		// create data array that is passed to addBlock() - what data ever...
		$data = array();

		// get thesis entry and thesis custom content block types
		$btThesisEntry = BlockType::getByHandle("itm_thesis_entry");
		$btThesisCustomContent = BlockType::getByHandle("itm_titled_paragraph");

		// set default data for thesis entry block, add and save it
		$defaultThesisEntryData = array(
			'topic' => t('Click and select Edit to enter thesis data.'),
			'type' => 0,
			'status' => 0,
			'student' => '',
			'beginning' => '',
			'tutor' => '',
			'supervisor' => ''
		);

		$bThesisData = $mTplItmThesisPage->addBlock($btThesisEntry, $aThesisInformation, $data);
		$bThesisData->getController()->save($defaultThesisEntryData);

		// do the same like above for research and thesis topic blocks
		$defaultResearchAreaData = array(
			'title' => t('Research Area'),
			'content' => t('Click and select Edit to enter custom data.')
		);
		$bResearchArea = $mTplItmThesisPage->addBlock($btThesisCustomContent, $aThesisInformation, $data);
		$bResearchArea->getController()->save($defaultResearchAreaData);

		$defaultThesisTopicData = array(
			'title' => t('The Thesis Topic'),
			'content' => t('Click and select Edit to enter custom data.')
		);
		$bThesisTopic = $mTplItmThesisPage->addBlock($btThesisCustomContent, $aThesisInformation, $data);
		$bThesisTopic->getController()->save($defaultThesisTopicData);
	}
}

?>
