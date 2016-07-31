<?php

class customBackgroundChooser_adminArrayPlaces {
	public static function debug($var){die(print_r($var,true));}
	public static $defaultOptions = array(
			'html'=>true,
			'body'=>false,
			'parallaxHeaderClasses'=>'',
			'parallaxHolesClasses'=>'',
			'parallaxHolesTintColor'=>'rgba(127, 127, 127, 0.2)',
		);
	private static $xfOptions = null;
	public static function getXfOptions(){
		if(is_null(self::$xfOptions)){
			self::$xfOptions = XenForo_Application::get('options');
		}
		return self::$xfOptions;
	}
	public static function getDefaultStyleId(){
		return self::getXfOptions()->defaultStyleId;
	}
	public static function getBackgroundReplaceRules($rules = null){
		$compulsoryOptions = array('style_id','title','user_selectable');
		$defaultOptions = self::$defaultOptions;
		$styleModel = XenForo_Model::create('XenForo_Model_Style');
		$allStyles = $styleModel->getAllStylesAsFlattenedTree();
		ksort($allStyles);
		$stylesWithOption = array();
		if(is_null($rules) || !is_array($rules)){
			$rules = self::getXfOptions()->backgroundReplaceRules;
		}
		foreach($allStyles as $style){
			foreach($compulsoryOptions as $property){
				$stylesWithOption[$style['style_id']][$property]=$style[$property];
			}
			foreach($defaultOptions as $key=>$value){
				$stylesWithOption[$style['style_id']][$key]=$value;
			}
			if(array_key_exists($style['style_id'],$rules)){
				foreach($rules[$style['style_id']] as $key=>$value){
					$stylesWithOption[$style['style_id']][$key]=$value;
				}
			}
		}
		//self::debug($stylesWithOption);
		return $stylesWithOption;
	}
	
	
	public static function render_AdminCP_CustomFieldsAdder(XenForo_View $view, $fieldPrefix, array $preparedOption, $canEdit){
		$choices = $preparedOption['option_value'];
		//self::debug($choices);
		$stylesWithOption = self::getBackgroundReplaceRules($choices);
		//self::debug($stylesWithOption);

		$editLink = $view->createTemplateObject('option_list_option_editlink', array(
			'preparedOption' => $preparedOption,
			'canEditOptionDefinition' => $canEdit
		));

		return $view->createTemplateObject('kiror_customBackground_options_places', array(
			'fieldPrefix' => $fieldPrefix,
			'listedFieldName' => $fieldPrefix . '_listed[]',
			'preparedOption' => $preparedOption,
			'formatParams' => $preparedOption['formatParams'],
			'editLink' => $editLink,

			'choices' => $stylesWithOption,
			'nextCounter' => count($stylesWithOption)
		));
	}
	
	public static function verifier_AdminCP_CustomFieldsAdder(array &$properties, XenForo_DataWriter $dw, $fieldName){
		$output = array();
		$toIter = array('parallaxHeaderClasses','parallaxHolesClasses','parallaxHolesTintColor');
		foreach ($properties AS $styleid=>$property){
			$output[$styleid]=self::$defaultOptions;
			if(array_key_exists('html',$property) && $property['html']=='on'){
				$output[$styleid]['html']=true;
			}
			if(array_key_exists('body',$property) && $property['body']=='on'){
				$output[$styleid]['body']=true;
			}
			foreach($toIter as $copying){
				$output[$styleid][$copying] = $property[$copying];
			}
			if($output[$styleid]['parallaxHolesTintColor']==''){
				$output[$styleid]['parallaxHolesTintColor']='transparent';
			}
		}

		$properties = $output;
		//self::debug($properties);
		return true;
	}
}
