<?php

function ItmBibtexBuildUrl($bibEntry)
{
	return '';
}

class ItmBibtexHelper
{

	public function renderBibEntry($bibEntry, $rawLink = false, $jsCall = '')
	{
		$result = '<li>';
		$result .= bib2html($bibEntry);

		if ($rawLink)
		{
			$result .= '<a target="_blank" class="biburl" title="' . $bibEntry->getKey() . '" href="' . $rawLink . '" onclick="' . $jsCall . '; return false;">[bib]</a>';
		}

		if ($bibEntry->hasField('doi'))
		{
			$result .= ' <a target="_blank" href="http://dx.doi.org/' . $bibEntry->getField("doi") . '">[doi]</a>';
		}

		$urlLink = $bibEntry->getUrlLink();
		if (!empty($urlLink))
		{
			$result .= " $urlLink";
		}

		if ($bibEntry->hasField('gsid'))
		{
			$result .= ' <a target="_blank" href="http://scholar.google.com/scholar?cites=' . $bibEntry->getField("gsid") . '">[cites]</a>';
		}

		$result .= '</li>';

		return $result;
	}

	public function renderBibEntryRaw($bibEntry)
	{
		$raw = $bibEntry->getFullText();
		$raw = str_replace("\n", '<br/>', $raw);
		$raw = str_replace("\t", "&nbsp;", $raw);
		$raw = str_replace(" ", '&nbsp;', $raw);
		$raw = str_replace("'", "\\'", $raw);

		return $raw;
	}

	public function getBibDb($fID)
	{
		$f = File::getByID($fID);
		if ($f->isError())
		{
			return null;
		}

		$fv = $f->getApprovedVersion();

		$cachedFile = DIR_FILES_CACHE . '/' . md5("itm_bibtex_" . $f->getFileID() . "_" . $fv->getFileVersionID()) . '.tmp';
		$bibDb = new BibDataBase();

		if (!file_exists($cachedFile))
		{
			$bibDb->load($f->getPath());
			$bibStr = serialize($bibDb);
			file_put_contents($cachedFile, $bibStr, LOCK_EX);
		}
		else
		{
			$serializedBibDb = file_get_contents($cachedFile);
			$bibDb = unserialize($serializedBibDb);
		}

		return $bibDb;
	}

}