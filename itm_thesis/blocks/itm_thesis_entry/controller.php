<?php

class ItmThesisEntryBlockController extends BlockController
{
	protected $btTable = 'btItmThesis';
	protected $btInterfaceWidth = "500";
	protected $btInterfaceHeight = "400";

	public function getBlockTypeDescription()
	{
		return t("Adds a thesis entry to a page.");
	}

	public function getBlockTypeName()
	{
		return t("ITM Thesis Entry");
	}

	public function save($data)
	{
			parent::save($data);
	}

	public function getJavaScriptStrings()
	{
		// return translated strings available for Java Script
		return array(
			'title-required' => t('Please enter a thesis topic')
		);
	}

}

?>