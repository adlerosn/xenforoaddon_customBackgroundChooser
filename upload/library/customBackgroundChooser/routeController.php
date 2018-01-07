<?php
class customBackgroundChooser_routeController extends XenForo_ControllerPublic_Abstract
{	
	public function actionIndex(){
		$visitor = XenForo_Visitor::getInstance();
		if(!$visitor['user_id']){
			throw $this->getNoPermissionResponseException();
		};
		if(!$visitor->hasPermission('backgroundchanginggroup', 'canchangebkg')){
			$options = XenForo_Application::get('options');
			$html = $options->notallowedmessagebkgchng;
			$viewParams=array('html'=>$html);
			return $this->responseView(
				'XenForo_ViewPublic_Base',
				'kiror_background_change_main_page',
				$viewParams
			);
		};
		if(customBackgroundChooser_sharedStatic::startsWith($this->_input->getInput()['_origRoutePath'],'backgroundchange/default')){
			customBackgroundChooser_sharedStatic::userDefaultAllDB($visitor['user_id']);
			return $this->responseView(
				'XenForo_ViewPublic_Base',
				'kiror_background_change_main_page',
				array('html'=>'Resetted!<script>window.history.back();</script>')
			);
		};
		$uid=$visitor['user_id'];
		if(customBackgroundChooser_sharedStatic::startsWith($this->_input->getInput()['_origRoutePath'],'backgroundchange/set.all/')){
			$opt=substr($this->_input->getInput()['_origRoutePath'],strlen('backgroundchange/set.all/'));
			customBackgroundChooser_sharedStatic::userDefaultAllDB($visitor['user_id']);
			$nodemodel=XenForo_Model::create('XenForo_Model_Node');
			$nodes=$nodemodel->getAllNodes();
			if(substr($opt,0,3)=='sug' || substr($opt,0,3)=='def' || substr($opt,0,3)=='url' || substr($opt,0,3)=='clr'){
				foreach($nodes as $node){
					$nid=$node['node_id'];
					customBackgroundChooser_sharedStatic::putInDB($nid,$uid,$opt);
				}
				customBackgroundChooser_sharedStatic::putInDB(0,$uid,$opt);
			}
			return $this->responseView(
				'XenForo_ViewPublic_Base',
				'kiror_background_change_main_page',
				array('html'=>'Resetted!<script>window.history.back();</script>')
			);
		};
		if(customBackgroundChooser_sharedStatic::startsWith($this->_input->getInput()['_origRoutePath'],'backgroundchange/set.')){
			$big=substr($this->_input->getInput()['_origRoutePath'],strlen('backgroundchange/set.'));
			$chc=substr($big,1+strpos($big,'/'));
			if($chc==null){$chc='';};
			$nid=intval(substr($big,0,strpos($big,'/')));
			//die(print_r(array($big,$nid,$chc),true));
			if(substr($chc,0,3)=='sug' || substr($chc,0,3)=='def' || substr($chc,0,3)=='url' || substr($chc,0,3)=='clr'){
				customBackgroundChooser_sharedStatic::putInDB($nid,$uid,$chc);
			}
			return $this->responseView(
				'XenForo_ViewPublic_Base',
				'kiror_background_change_main_page',
				array('html'=>'Resetted!<script>window.history.back();</script>')
			);
		};
		$images = customBackgroundChooser_sharedStatic::getNormalizedImages();
		$html='';
		$html.='<div class="secondaryContent"><a>Refresh the page after you clicked your desired background to changes take effect.</a></div>';
		$html.='<div class="secondaryContent"><a>The options below are collapsed. Click to expand.</a></div>';
		$html.='<script>
function globalSetColor(){
	a = document.getElementById("bkgPicker").value;
	window.location="index.php?/backgroundchange/setall/clr"+a;
}
function globalSetUrlImage(){
	a = document.getElementById("urlPlacer").value;
	window.location="index.php?/backgroundchange/setall/url"+a;
}
function regionalSetColor(eid,nid){
	a = document.getElementById(eid,nid).value;
	window.location="index.php?/backgroundchange/set."+nid+"/clr"+a;
}
function regionalSetUrlImage(eid,nid){
	a = document.getElementById(eid).value;
	window.location="index.php?/backgroundchange/set."+nid+"/url"+a;
}

$(document).ready(function() {
$(".expanded").hide();
$(".expandedcontents").hide();

$(".expanded, .collapsed").click(function() {
	$(this).parent().children(".expanded, .collapsed").toggle();
	$(this).parent().children(".expandedcontents, .collapsedcontents").toggle();
});
});
</script>';
		$html.='<ul>';
		$nodehandler=XenForo_Model::create('XenForo_NodeHandler_Forum');
		$nodemodel=XenForo_Model::create('XenForo_Model_Node');
		$nodesk=$nodemodel->getAllNodes(false,true);
		$nodes=array();
		$nodes[0]=$nodemodel->getRootNode();
		$nodes[0]['title']='<i>When inside of no forum</i>';
		foreach($nodesk as $k=>$node){
			if (array_key_exists('node_type_id',$node)){
				$node['special']=true;
				$nodes[$k]=$node;
			}
		};
		$nodes = array_merge(
			[[
				'title'=>'<i>All forums at once</i>',
				'node_id'=>'all',
				'special'=>false,
			]],
			$nodes
		);
		foreach($nodes as $node){
			/*
			 * $node
			 *  [node_id]
			 *  [title]
			 *  [description]
			 *  [node_name]
			 *  [node_type_id]
			 *  [parent_node_id]
			 *  [display_order]
			 *  [display_in_list]
			 *  [lft]
			 *  [rgt]
			 *  [depth]
			 *  [style_id]
			 *  [effective_style_id]
			 *  [breadcrumb_data]
			 */
			if ($node['node_id']!=0){
				if ($node['node_type_id'] != 'Forum'){
					continue;
				};
				$userNodePermissions=$visitor->getNodePermissions($node['node_id']);
				if (! $nodehandler->isNodeViewable($node,$userNodePermissions)){
					continue;
				};
			};
			if(array_key_exists('special',$node) && $node['special']){
				$html.='<li>
				<div class="collapsed"><div class="subHeading">&#x25b8; Forum node "'.$node['title'].'"</div></div>
				<div class="expanded">';
			}else{
				$html.='<li>
				<div class="collapsed"><div class="subHeading">&#x25b8; '.$node['title'].'</div></div>
				<div class="expanded">';
			}
			if(array_key_exists('special',$node) && $node['special']){
				$html.='<div class="subHeading">&#x25be; Forum node "'.$node['title'].'"</div>';
			}else{
				$html.='<div class="subHeading">&#x25be; '.$node['title'].'</div>';
			}
			$html.='</div>';
			$html.='<div class="collapsedcontents"></div>';
			$html.='<div class="expandedcontents">';
			//**//
			$html.='<div class="secondaryContent">';
			$html.='<div class="primaryContent flexcontainer">';
			$html.="\n";
			//
			$html.='<div class="flexitem" style="order: 0;">';
			$html.='<table><tr>';
			$html.='<td>';
			$html.='<a href="index.php?backgroundchange/set.'.$node['node_id'].'/def';
			$html.='"><img src="styles/kiror/customBackgroundChooser/genericImgs/defaultBkg.png" width="150" height="100" alt="" /></a>';
			$html.='</td>';
			$html.='<td>';
			$html.='<div style="margin-left: 10px;">';
			$html.='Default background<br />';
			$html.='</div>';
			$html.='</td>';
			$html.='</tr></table>';
			$html.='</div>'."\n";
			//
			$html.='<div class="flexitem" style="order: 1;">';
			$html.='<table><tr>';
			$html.='<td style="width: 1px; height: 100px;">';
			$html.='</td>';
			$html.='<td>';
			$html.='<div style="margin-left: 10px;">';
			$html.='Custom color: ';
			$html.='<input
						style="display: none;"
						name="bkgPicker'.$node['node_id'].'"
						value="rgb(240,240,240)"
						class="textCtrl ColorPicker DisablePalette"
						id="bkgPicker'.$node['node_id'].'"
						type="text">
					<button class="button" onclick="regionalSetColor(\'bkgPicker'.$node['node_id'].'\','.$node['node_id'].');">
							Set
					</button>';
			$html.='<div class="pickers colorPalette">';
			$html.='</div>';
			$html.='</div>';
			$html.='</td>';
			$html.='</tr></table>';
			$html.='</div>'."\n";
			//
			$html.='<div class="flexitem" style="order: 2;">';
			$html.='<table><tr>';
			$html.='<td style="width: 1px; height: 100px;">';
			$html.='</td>';
			$html.='<td>';
			$html.='<div style="margin-left: 10px;">';
			$html.='Custom image link:<br />';
				$html.='<table><tr>';
				$html.='<td>';
			$html.='<input
						placeholder="Paste image URL"
						class="textCtrl"
						name="urlPlacer'.$node['node_id'].'"
						value=""
						id="urlPlacer'.$node['node_id'].'"
						type="text">';
				$html.='</td>';
				$html.='<td>';
			$html.='<button style="display:table-cell;" class="button" onclick="regionalSetUrlImage(\'urlPlacer'.$node['node_id'].'\','.$node['node_id'].')">
							Set
					</button>';
				$html.='</td>';
				$html.='</tr></table>';
			$html.='</div>';
			$html.='</td>';
			$html.='</tr></table>';
			$html.='</div>'."\n";
			//
			foreach($images as $id=>$image){
				$j=strval($id);
				$html.='<div class="flexitem" style="order: '.($id+3).';">';
				$html.='<table><tr>';
				$html.='<td>';
				$html.='<a href="index.php?backgroundchange/set.'.$node['node_id'].'/sug'.$j;
				$html.='"><img src="'.$image['image']['thumb'].'" width="150" height="100" alt="" /></a>';
				$html.='</td>';
				$html.='<td>';
				$html.='<div style="margin-left: 10px;">';
				$html.=$image['info']['nm'].'<br />';
				$html.=$image['info']['by'].'<br />';
				$html.=$image['info']['lc'];
				$html.='</div>';
				$html.='</td>';
				$html.='</tr></table>';
				$html.='</div>'."\n";
			}
			$html.='</div>';
			$html.='</div>';
			//**//
			$html.='</div>';
		}
		$html.='</li>'."\n";
		///
		$html.='</ul>'."\n";
		$viewParams=array('html'=>$html);
		return $this->responseView(
            'XenForo_ViewPublic_Base',
            'kiror_background_change_main_page',
            $viewParams
        );
	}
}
