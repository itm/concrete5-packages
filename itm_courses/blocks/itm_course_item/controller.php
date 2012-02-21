<?php

defined('C5_EXECUTE') or die("Access Denied.");

class ItmCourseItemBlockController extends ContentBlockController
{
	protected $btTable = 'btItmCourseItem';
	protected $btInterfaceHeight = "500";
	protected $btWrapperClass = 'ccm-ui';

	public function getBlockTypeDescription()
	{
		return t("HTML/WYSIWYG Editor with option list for course paragraphs.");
	}

	public function getBlockTypeName()
	{
		return t("ITM General Course Item");
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
