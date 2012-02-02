<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

define('BIBTEXBROWSER_BIB_IN_NEW_WINDOW', true);
define('BIBTEXBROWSER_URL_BUILDER', ItmBibtexBuildUrl);

Loader::library('bibtexbrowser', 'itm_bibtex');

class ItmBibtexBlockController extends BlockController
{
	protected $btTable = 'btItmBibtex';
	protected $btInterfaceWidth = "350";
	protected $btInterfaceHeight = "300";
	protected $btWrapperClass = 'ccm-ui';

	public function getBlockTypeDescription()
	{
		return t("Adds a Bibtex entries.");
	}

	public function getBlockTypeName()
	{
		return t("ITM Bibtex Entry");
	}

	public function save($data)
	{
		parent::save($data);
	}

	public function view()
	{
		
	}

	public function getFileID()
	{
		return $this->fID;
	}

	public function getFileObject()
	{
		return File::getByID($this->fID);
	}

	public function getFilteredPubList()
	{
		if (empty($this->author))
		{
			return array();
		}

		$filter = array();
		if (!empty($this->since))
		{
			$filter['year'] = $since;
		}

		$filter['author'] = $this->author;

		$bh = Loader::helper('itm_bibtex', 'itm_bibtex');
		$bibDb = $bh->getBibDb($this->fID);
		if (empty($bibDb))
		{
			return array();
		}

		if (empty($this->since))
		{
			$this->since = 1990;
		}

		$result = array();
		for ($i = date('Y'); $i >= $this->since; $i--)
		{
			$tmpResult = $bibDb->multisearch(array(
				"author" => $this->author,
				"year" => $i));

			if (!count($tmpResult))
			{
				continue;
			}

			$result[(string) $i] = $tmpResult;
		}

		return $result;
	}

}

?>