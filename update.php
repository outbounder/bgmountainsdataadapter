<?php
	if(isset($_GET['id'])) 
	{
		$entries = json_decode(file_get_contents('./bgmountains.json'));
		$entry = null;
		foreach($entries as $e)
		{
			if($_GET['id'] == $e->id) 
			{
				$entry = $e;
				break;
			}
		}

		if($entry == null) 
		{
			$entry = new stdObject();
			$entries []= $entry;
		}

		foreach($_GET as $key => $value)
			$entry->$key = $value;

		file_put_contents('./bgmountains.json', json_encode($entries));
		echo 'done';
	}
	
	if(isset($_GET['data']))
	{
		file_put_contents('./bgmountains.json',$_GET['data']);
	}
?>
