/*
 * validate add/edit field data
 */
ccmValidateBlockForm = function()
{
	if ($('#groupName').val() == '')
	{
		ccm_addError(ccm_t('group-required'));		
	}

	return false;
}