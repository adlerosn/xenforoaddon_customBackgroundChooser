<?php

class customBackgroundChooser_templatePostRender{
	public static function tpr_callback($templateName, &$content, array &$containerData, XenForo_Template_Abstract $template){
		if($templateName=='xenforo.css'){
			XenForo_Session::startPublicSession();
			$nid = 0; #default
			$visitor = XenForo_Visitor::getInstance();
			if($visitor['user_id']){
				if($visitor->hasPermission('backgroundchanginggroup', 'canchangebkg')){
					$uid=$visitor['user_id'];
					$r=customBackgroundChooser_sharedStatic::getFromDB($nid,$uid);
					$f = null;
					if(customBackgroundChooser_sharedStatic::startsWith($r,'url')){
						$u=substr($r,3);
						$i='rgb(0, 0, 0) url(\''.$u.'\') no-repeat fixed 50% 0 / cover';
						$f=($i);
					}
					else if (customBackgroundChooser_sharedStatic::startsWith($r,'sug')){
						$u=substr($r,3);
						$i='rgb(0, 0, 0) url(\'styles/kiror/customBackgroundChooser/defaultImages/'.$u.'.jpg\') no-repeat fixed 50% 0 / cover';
						$f=($i);
					}
					else if (customBackgroundChooser_sharedStatic::startsWith($r,'clr')){
						$u=substr($r,3);
						$f=($u);
					}
					if($f){
						$content.="\nbody\n{\n\tbackground: ".$f.";\n}\n";
					}
				}
			}
		}
	}
}
