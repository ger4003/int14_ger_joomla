<?php

defined ( 'JPATH_BASE' ) or die ();

gbimport ( 'gobingoo.event' );
gbimport ( "gobingoo.helper" );
gbimport ( 'listbingo.helper' );

class evtPmsUddeim extends GEvent {
	
	function onBeforeDisplayTitle(&$row = null, &$params = null) {
		
		global $option;
		
		if (GHelper::isValidExtension ( "com_uddeim" )) {
			if (is_null ( $row )) {
				return;
			}
			
			$user = JFactory::getUser ();
			if ($user->get ( 'id' ) == $row->user_id) {
				return;
			}		

			$uddeim = GEventHelper::getEvent ( 'uddeim' );
			$addonparams = new JParameter ( $uddeim->params );
		
			$uddeim_isadmin = 0;
			if (defined ( 'JPATH_ADMINISTRATOR' )) {
				$ver = new JVersion ();
				if (! strncasecmp ( $ver->RELEASE, "1.6", 3 )) {
					require_once (JPATH_SITE . '/components/com_uddeim/uddeimlib16.php');
				} else {
					require_once (JPATH_SITE . '/components/com_uddeim/uddeimlib15.php');
				}
			} else {
				global $mainframe;
				require_once ($mainframe->getCfg ( 'absolute_path' ) . '/components/com_uddeim/uddeimlib10.php');
			}
			
			$pathtoadmin = uddeIMgetPath ( 'admin' );
			$pathtouser = uddeIMgetPath ( 'user' );
			$pathtosite = uddeIMgetPath ( 'live_site' );
			
			require_once ($pathtoadmin . "/admin.shared.php"); // before includes.php is included!
			require_once ($pathtouser . '/bbparser.php');
			require_once ($pathtouser . '/includes.php');
			require_once ($pathtouser . '/includes.db.php');
			
			require ($pathtoadmin . "/config.class.php"); // get the configuration file
			$config = new uddeimconfigclass ();
			
			
		// browser switch
		$used_browser = uddeIMmosGetParam($_SERVER, 'HTTP_USER_AGENT', null);
		$css_appendix="";
		$css_alternative="";
		if (stristr($used_browser, "Opera")) {
			$css_appendix="-opera";
		} elseif (stristr($used_browser, "MSIE 4")) {
			$css_appendix="-ie4";
			$css_alternative="-ie";
		} elseif (stristr($used_browser, "MSIE 6") || stristr($used_browser, "MSIE/6")) {
			$css_appendix="-ie6";
			$css_alternative="-ie";
		} elseif (stristr($used_browser, "MSIE 7") || stristr($used_browser, "MSIE/7")) {
			$css_appendix="-ie7";
			$css_alternative="-ie";
		} elseif (((stristr($used_browser, "MSIE 5") || stristr($used_browser, "MSIE/5"))) && stristr($used_browser, "Win")) {
			$css_appendix="-ie5win";
			$css_alternative="-ie";
		} elseif (stristr($used_browser, "MSIE 5") && stristr($used_browser, "Mac")) {
			$css_appendix="-ie5mac";
			$css_alternative="-ie";
		} elseif (stristr($used_browser, "Safari/100")) {
			$css_appendix="-safari100";
			$css_alternative="-safari";
		} elseif (stristr($used_browser, "Safari/85")) {
			$css_appendix="-safari85";
			$css_alternative="-safari";
		} elseif (stristr($used_browser, "Safari")) {
			$css_appendix="-safari";
		} elseif (stristr($used_browser, "Konqueror/2")) {
			$css_appendix="-konq2";
			$css_alternative="-konq";
		} elseif (stristr($used_browser, "Konqueror/3")) {
			$css_appendix="-konq3";
			$css_alternative="-konq";
		} elseif (stristr($used_browser, "Konqueror")) {
			$css_appendix="-konq";
		}
			// load the css file
			$css = "";
			if(file_exists($pathtouser.'/templates/'.$config->templatedir.'/css/uddeim'.$css_appendix.'.css')) {
				$css = $pathtosite."/components/com_uddeim/templates/".$config->templatedir."/css/uddeim".$css_appendix.".css";
			} elseif(file_exists($pathtouser.'/templates/'.$config->templatedir.'/css/uddeim'.$css_alternative.'.css')) {
				$css = $pathtosite."/components/com_uddeim/templates/".$config->templatedir."/css/uddeim".$css_alternative.".css";
			} elseif(file_exists($pathtouser.'/templates/'.$config->templatedir.'/css/uddeim.css')) {
				$css = $pathtosite."/components/com_uddeim/templates/".$config->templatedir."/css/uddeim.css";
			} else {
				// template css doesn't exist, now we try to load the default css file
				if(file_exists($pathtouser.'/templates/default/css/uddeim.css'))
					$css = $pathtosite."/components/com_uddeim/templates/default/css/uddeim.css";
			}
			uddeIMaddCSS($css);
			
			if ($config->useautocomplete) {
				uddeIMdoAutocomplete($config);
			}
			if(($config->showtextcounter && $config->maxlength) || $config->cryptmode==2 || $config->cryptmode==4) {
				uddeIMaddScript($pathtosite."/components/com_uddeim/js/uddeimtools.js");
			}
		
			if($config->allowbb || $config->allowsmile) {
				uddeIMaddScript($pathtosite."/components/com_uddeim/js/bbsmile.js");
			}
			JHTML::_('behavior.mootools');
			JHTML::_('behavior.modal'); 
			?>
			<script type="text/javascript">
			<!--
			_EVAL_SCRIPTS=true;			
			-->			
			</script>
			
						
			<?php
			
			$adid = JRequest::getInt('adid',0);
			$pmswidth = $addonparams->get('uddeim_popup_width',500);
			$pmsheight = $addonparams->get('uddeim_popup_height',350);		
			
			if($addonparams->get('enable_uddeim_moodalbox'))
			{
				$link = JRoute::_("index.php?option=$option&task=addons.pms.uddeim.load&adid=$adid&tmpl=component");				
			}
			else
			{
				$link = JRoute::_("index.php?option=$option&task=addons.pms.uddeim.load&adid=$adid");				
			}

			?>
			<li>
			<img src="<?php echo JUri::root()."administrator/components/com_listbingo/addons/uddeim/img/replyemail.png"?>" />
			<a class="modal" rel="{size: { x: <?php echo $pmswidth;?>, y: <?php echo $pmswidth;?>}}" href="<?php echo $link;?>">
			<?php echo JText::_('REPLY_EMAIL');?></a>
			</li>
			<?php
			
		}
		else
		{
			return;
		}
		
	}

}
?>
