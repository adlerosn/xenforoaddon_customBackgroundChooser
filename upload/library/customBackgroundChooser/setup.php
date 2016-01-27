<?php

class customBackgroundChooser_setup
{
	public static function install(){
		customBackgroundChooser_sharedStatic::createTableDB();
	}

	public static function reinstall(){
		customBackgroundChooser_sharedStatic::dropTableDB();
		customBackgroundChooser_sharedStatic::createTableDB();
	}

	public static function uninstall(){
		customBackgroundChooser_sharedStatic::dropTableDB();
	}
}
