<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

class ItmCoursesPackage extends Package
{
	protected $pkgHandle = 'itm_courses';
	protected $appVersionRequired = '5.5.1';
	protected $pkgVersion = '1.0';

	public function getPackageDescription()
	{
		return t("Installs the ITM courses package.");
	}

	public function getPackageName()
	{
		return t("ITM Courses");
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
		BlockType::installBlockTypeFromPackage('itm_course', $pkg);
		BlockType::installBlockTypeFromPackage('itm_course_overview', $pkg);

		// install page type
		Loader::model('collection_types');
		$ctItmCoursePage = CollectionType::getByHandle('itm_course_page');
		if (!$ctItmCoursePage || !intval($ctItmCoursePage->getCollectionTypeID()))
		{
			$ctItmCoursePage = CollectionType::add(array('ctHandle' => 'itm_course_page', 'ctName' => t('Course')), $pkg);
		}

		$ctItmSemesterPage = CollectionType::getByHandle('itm_semester_page');
		if (!$ctItmSemesterPage || !intval($ctItmSemesterPage->getCollectionTypeID()))
		{
			$ctItmSemesterPage = CollectionType::add(array('ctHandle' => 'itm_semester_page', 'ctName' => t('Semester')), $pkg);
		}

		$cakYear = CollectionAttributeKey::add('number', array(
					'akHandle' => 'semester_year',
					'akName' => t('Year (format: YYYY)'),
					'akIsSearchable' => false
						), $pkg);

		$cakTerm = CollectionAttributeKey::add('select', array(
					'akHandle' => 'semester_term',
					'akName' => t('Winter / Summer term'),
					'akIsSearchable' => false
						), $pkg);
		SelectAttributeTypeOption::add($cakTerm, t('Summer term'), 0, 0);
		SelectAttributeTypeOption::add($cakTerm, t('Winter term'), 0, 0);

		$ctItmSemesterPage->assignCollectionAttribute($cakYear);
		$ctItmSemesterPage->assignCollectionAttribute($cakTerm);

		// add default attribute
		$ctItmCoursePage->assignCollectionAttribute(CollectionAttributeKey::getByHandle('exclude_nav'));

		// install default page of itm_thesis_page page type
		// this includes setting up a default itm_thesis_entry block,
		// a default "Research Area" custom content block and a default
		// "The Thesis Topic" custom content block
		// obtain master template
		$mTplItmSemesterPage = $ctItmSemesterPage->getMasterTemplate();
		// create content area within master template
		$aSemesterInformation = Area::getOrCreate($mTplItmSemesterPage, 'Semester Information');
		// create data array that is passed to addBlock() - what data ever...
		// $data = array();
		// get thesis entry and thesis custom content block types
		//$btThesisEntry = BlockType::getByHandle("itm_thesis_entry");
		//$btThesisCustomContent = BlockType::getByHandle("itm_titled_paragraph");

		$btCourseOverview = BlockType::getByHandle("itm_course_overview");

		// set default data for thesis entry block, add and save it
		/* $defaultThesisEntryData = array(
		  'topic' => t('Thesis topic goes here'),
		  'type' => 0,
		  'status' => 0,
		  'student' => '',
		  'beginning' => '',
		  'tutor' => '',
		  'supervisor' => ''
		  ); */

//		var_dump($btCourseOverview);
//		var_dump($aSemesterInformation);
//		var_dump($mTplItmSemesterPage);

		
		try
		{
			$bSemesterData = $mTplItmSemesterPage->addBlock($btCourseOverview, $aSemesterInformation, array());
			//$bSemesterData->getController()->save(array());
		}
		catch (Exception $e)
		{
			$pkg->uninstall();
		}

		// do the same like above for research and thesis topic blocks
		/* $defaultResearchAreaData = array(
		  'title' => t('Research Area'),
		  'content' => 'Type in your content here.'
		  );
		  $bResearchArea = $mTplItmThesisPage->addBlock($btThesisCustomContent, $aThesisInformation, $data);
		  $bResearchArea->getController()->save($defaultResearchAreaData);

		  $defaultThesisTopicData = array(
		  'title' => t('The Thesis Topic'),
		  'content' => 'Type in your content here'
		  );
		  $bThesisTopic = $mTplItmThesisPage->addBlock($btThesisCustomContent, $aThesisInformation, $data);
		  $bThesisTopic->getController()->save($defaultThesisTopicData); */
	}

}

?>
