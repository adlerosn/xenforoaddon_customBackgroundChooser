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

	public static $imginfo = array(
		'01'=>array('nm'=>'Arboreal ballet',
					'by'=>'by Bob Farrell',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'02'=>array('nm'=>'Beach',
					'by'=>'by Renato Giordanelli',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'03'=>array('nm'=>'Below Clouds',
					'by'=>'by kobinho',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'04'=>array('nm'=>'Blue frost',
					'by'=>'by ppaabblloo77',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'05'=>array('nm'=>'Bosque TK',
					'by'=>'Biker Blue',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'06'=>array('nm'=>'Cedar Wax Wing',
					'by'=>'by Raymond Lavoie',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'07'=>array('nm'=>'Climbling',
					'by'=>'by David Andrie',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'08'=>array('nm'=>'Flocking',
					'by'=>'by noombox',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'09'=>array('nm'=>'Flor de Loto',
					'by'=>'by Pablo Meneses',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'10'=>array('nm'=>'Forever',
					'by'=>'by Shady S.',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'11'=>array('nm'=>'Gota D\'água',
					'by'=>'by Eiti Kimura',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'12'=>array('nm'=>'Gran Canaria',
					'by'=>'by ALF...',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'13'=>array('nm'=>'Grass',
					'by'=>'by Jeremy Hill',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'14'=>array('nm'=>'Grass in A',
					'by'=>'by Andrew Kneebone',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'15'=>array('nm'=>'Leftover',
					'by'=>'by Sagar Jain',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'16'=>array('nm'=>'Maraetai before sunrise',
					'by'=>'by Piotr Zurek',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'17'=>array('nm'=>'Mono Lake',
					'by'=>'by Angela Henderson',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'18'=>array('nm'=>'Mountains',
					'by'=>'by JamesPickles',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'19'=>array('nm'=>'Mount Snowdon, Wales',
					'by'=>'by Adam Vellender',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'20'=>array('nm'=>'Mr. Tau and The Tree',
					'by'=>'by TJ',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'21'=>array('nm'=>'Night Seascape',
					'by'=>'by Davor Dopar',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'22'=>array('nm'=>'Pantano de Orellana',
					'by'=>'by mgarciaiz',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'23'=>array('nm'=>'Quandro',
					'by'=>'by Tomas Vasconcelo',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'24'=>array('nm'=>'Radioactive Sunrise',
					'by'=>'by Piotr Zurek',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'25'=>array('nm'=>'salcantayperu',
					'by'=>'by Life Nomadic',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'26'=>array('nm'=>'Serenity Enchanted',
					'by'=>'by sirpecangum',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'27'=>array('nm'=>'Sunset',
					'by'=>'by Carmen Gloria',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'28'=>array('nm'=>'Tenerife Roques de Anaga',
					'by'=>'by Frederik Schulz',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'29'=>array('nm'=>'Thingvellir',
					'by'=>'by pattersa',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>'),

		'30'=>array('nm'=>'Water web',
					'by'=>'by Tom Kijas',
					'lc'=>'<a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>')
	);
}
