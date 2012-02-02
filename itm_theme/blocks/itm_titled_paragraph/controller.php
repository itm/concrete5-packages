<?php

defined('C5_EXECUTE') or die("Access Denied.");

class ItmTitledParagraphBlockController extends ContentBlockController
{
	protected $btTable = 'btItmTitledParagraph';
	protected $btWrapperClass = 'ccm-ui';

	public function getBlockTypeDescription()
	{
		return t("HTML/WYSIWYG Editor with extra title field.");
	}

	public function getBlockTypeName()
	{
		return t("ITM Titled Paragraph");
	}

	function save($data)
	{
		$content = $this->translateTo($data['content']);
		$args['content'] = $content;
		$args['title'] = $data['title'];

		// call save() on BlockController, not on ContentBlockController
		// of course, this isn't nice... any other solution here?
		BlockController::save($args);
	}

}

?>
