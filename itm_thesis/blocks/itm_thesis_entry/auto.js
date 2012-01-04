/*
 * validate add/edit field data
 */
ccmValidateBlockForm = function() {

	if ($('#topic')[0].value.length == 0)
	{
		ccm_addError(ccm_t('title-required'));		
	}
	
	return false;
}