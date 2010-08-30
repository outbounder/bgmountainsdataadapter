<?php
	$idCount = 0;
	$limit = 0;
	
	function parseEntry($entry) 
	{
		global $idCount;
		
		$result = new stdClass();
		$result->id = $idCount++;
		$result->pic = $entry->find("img[src]",0)->src;
		$result->url = $entry->find("a[href]",0)->href;

		$deepsource = file_get_html($result->url);
		$result->name = htmlspecialchars((string)$deepsource->find("h2",0)->innertext);
		$details = $deepsource->find("div[id=hut_details]",0);
		$detailsRows = $details->find("td b span");
		foreach($detailsRows as $row) {
			if($row->innertext == "Sleeping places")
				$result->sleepingPlaces = $row->parent()->parent()->next_sibling()->innertext;
			else
			if($row->innertext == "Address")
				$result->address = htmlspecialchars((string)$row->parent()->parent()->next_sibling()->innertext);
			else
			if($row->innertext == "Hut phone")
				$result->phones = htmlspecialchars((string)$row->parent()->parent()->next_sibling()->innertext);
			else
			if($row->innertext == "Sea level(m) ")
				$result->height = $row->parent()->parent()->next_sibling()->innertext;
			else
			if($row->innertext == "GPS North width ")
				$result->gpsNorthWidth = $row->parent()->parent()->next_sibling()->innertext;
			else
			if($row->innertext == "GPS East length")
				$result->gpsEastLength = $row->parent()->parent()->next_sibling()->innertext;
		}
		
		// TODO fetch comments ... 
		return $result;
	}


	include './libs/simple_html_dom.php';

	$source = file_get_html('http://www.bulgarian-mountains.com/bg/Huts/Rodopi');

	$body = $source->find("div[class=body] tbody", 0);

	$trs = $body->find("tr");
	
	// if limit is not set -> make it limit all found
	if($limit == 0)
		$limit = count($trs);
	
	$list = array();
	foreach($trs as $tr) {
		if($limit <= 0)
			break;
		$limit -= 1;
		$list []= parseEntry($tr);
	}

	file_put_contents("./bgmountains.json", json_encode($list));
	echo 'aggregated '.$idCount;
?>
