<?php
class customBackgroundChooser_sharedStatic
{
	public static function mysql_escape_mimic_fromPhpDoc($inp)
	{//http://php.net/manual/pt_BR/function.mysql-real-escape-string.php
		return str_replace(array('\\',    "\0",  "\n",  "\r",   "'",   '"', "\x1a"),
						   array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'),
						   $inp);
	}

	public static function startsWith($haystack, $needle)
	{//http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
		 $length = strlen($needle);
		 return (substr($haystack, 0, $length) === $needle);
	}

	public static function createTableDB(){
		$q='CREATE TABLE IF NOT EXISTS kiror_background_chooser (
		uid INTEGER,
		nid INTEGER,
		chosen LONGTEXT,
		PRIMARY KEY (uid,nid)
		) CHARACTER SET utf8 COLLATE utf8_general_ci;';
		$dbc=XenForo_Application::get('db');
		$dbc->query($q);
	}

	public static function dropTableDB(){
		$q='DROP TABLE IF EXISTS kiror_background_chooser;';
		$dbc=XenForo_Application::get('db');
		$dbc->query($q);
	}

	public static function userDefaultAllDB($userid){
		$q='DELETE FROM kiror_background_chooser WHERE uid='.$userid.';';
		$dbc=XenForo_Application::get('db');
		$dbc->query($q);
	}

	public static function userDefaultItemDB($nodeid,$userid){
		$q='DELETE FROM kiror_background_chooser WHERE uid='.$userid.' AND nid='.$nodeid.';';
		$dbc=XenForo_Application::get('db');
		$dbc->query($q);
	}

	public static function putInDB($nodeid,$userid,$choice){
		$choice=customBackgroundChooser_sharedStatic::mysql_escape_mimic_fromPhpDoc($choice);
		customBackgroundChooser_sharedStatic::userDefaultItemDB($nodeid,$userid);
		$q='INSERT INTO kiror_background_chooser (uid,nid,chosen) VALUES
		('.$userid.', '.$nodeid.", '".$choice."');";
		$dbc=XenForo_Application::get('db');
		$dbc->query($q);
	}

	public static function getFromDB($nodeid,$userid){
		$dbc=XenForo_Application::get('db');
		$q='SELECT chosen FROM kiror_background_chooser WHERE uid='.$userid.' AND nid='.$nodeid.';';
		$dbc=XenForo_Application::get('db');
		return $dbc->fetchRow($q)['chosen'];
	}
	
	public static function normalizeImages(&$images){
		ksort($images);
		$out = [];
		foreach($images as $addon => $data){
			$hash = crc32(strval($addon));
			$padding = 0x00FFFFFF & $hash;
			//die(print_r([$padding,$hash],true));
			$data = array_values($data);
			foreach($data as $seq=>$imageObj){
				$seq+=$padding;
				while(array_key_exists($seq,$out)){
					$seq++;
				}
				$out[$seq]=$imageObj;
			}
		}
		$images = $out;
	}
	
	public static function getNormalizedImages(){
		$images = [];
		XenForo_CodeEvent::fire('background_chooser_fill_image_array', array(&$images));
		self::normalizeImages($images);
		//die(print_r($images,true));
		return $images;
	}
}
