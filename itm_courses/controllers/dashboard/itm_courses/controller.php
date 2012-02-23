<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

class DashboardItmCoursesController extends Controller
{
	public function view()
	{

	}
	
	public function delete_group()
	{
		$h = Loader::helper('itm_courses', 'itm_courses');
		$h->deleteCourseGroup($this->get('handle'));
		$this->set('message', 'Group has successfully been removed.');
	}
	
	public function insert_group()
	{
		$val = Loader::helper('validation/error');
		$h = Loader::helper('itm_courses', 'itm_courses');
		
		if ($h->getCourseGroupByHandle($this->post('new_handle')) !== null)
		{
			$val->add('Insertion failed. Handle already exists.');
			$this->set('error', $val);
			return;
		}
		
		try
		{
			$h->addCourseGroup($this->post('new_handle'), $this->post('new_name'));
		}
		catch (Exception $e)
		{
			$val->add('Insertion failed due to a DB error:' . $e);
			$this->set('error', $val);
			return;
		}
		
		$this->set('message', 'Group has successfully been added.');
	}
}
?>
