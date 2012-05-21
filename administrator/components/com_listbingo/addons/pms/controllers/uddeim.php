<?php
/**
 * uddeim.php
 *@package Listbingo
 *@subpackage pms
 *
 *Listbingo Uddeim Controller
 *
 */

defined ( 'JPATH_BASE' ) or die ();
gbimport ( "gobingoo.addonscontroller" );

class GControllerPms_Uddeim extends GAddonsController {
	
	function load() {
		global $option;

		if (GHelper::isValidExtension ( "com_uddeim" )) {

			$css = JUri::root()."components/com_uddeim/templates/default/css/uddeim.css";
			$document = JFactory::getDocument();			
			$document->addStyleSheet($css);
			?>
			<script type="text/javascript">
			<!--
			_EVAL_SCRIPTS=true;			
			-->
			</script>
			<?php
			
			$adid = JRequest::getInt('adid',0);
			$model = gbimport('listbingo.model.ad');
			$row = $model->load($adid);
			
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
			require_once ($pathtouser . '/crypt.class.php');
			require_once ($pathtouser . '/getpiclink.php'); // after includes.db.php and admin.shared.php
			// require_once(uddeIMgetPath('absolute_path').'/includes/pageNavigation.php');
			// if (!define( '_MOS_MAMBO_INCLUDED'))
			// if (file_exists(uddeIMgetPath('absolute_path').'/includes/mambo.php'))
			//	require_once(uddeIMgetPath('absolute_path').'/includes/mambo.php');
			

			require ($pathtoadmin . "/config.class.php"); // get the configuration file
			$config = new uddeimconfigclass ();
			
			$userid = uddeIMgetUserID ();
			$usergid = uddeIMgetGID ( $userid );
			$cbitemid = uddeIMinitGetPicLink ( $config );
			$config->userid = $userid;
			$config->usergid = $usergid;
			$config->cbitemid = $cbitemid;
			$config->modeshowallusers = 0;
			
			uddeIMcheckConfig ( $pathtouser, $pathtoadmin, $config );
			uddeIMloadLanguage ( $pathtoadmin, $config );
			
			// if no Itemid is passed on, try to find one somewhere
			// $option = uddeIMmosGetParam( $_REQUEST, 'option', 'com_uddeim' );
			$Itemid = uddeIMmosGetParam ( $_REQUEST, 'Itemid' );
			if (! $Itemid || ! isset ( $Itemid ) || empty ( $Itemid )) {
				$Itemid = uddeIMgetItemid ( $config );
			} else if ($config->overwriteitemid) {
				$Itemid = ( int ) $config->useitemid;
			}
			
			$item_id = ( int ) $Itemid;
			$messageid = ( int ) uddeIMmosGetParam ( $_REQUEST, 'messageid' );
			$to_id		= (int)uddeIMmosGetParam ($_POST, 'to_id');
			$runame = uddeIMmosGetParam ( $_REQUEST, 'runame' ); //  blocking NAME and new message
			if(isset($row))
			{			
			$recip = ( int ) $row->user_id; // blocking ID and new message
			}
			else
			{				
				$recip = ( int ) uddeIMmosGetParam ( $_REQUEST, 'recip' ); // blocking ID and new message
			}
			$pmessage = strip_tags ( uddeIMmosGetParam ( $_POST, 'pmessage', '', _MOS_ALLOWHTML ) );
			
			$this->uddeIMnewMessage ( $userid, $item_id, $to_id, $recip, $runame, $pmessage, $config );
		}
		else
		{
			echo JText::_('Unable to locate uddeim component');
		}
	}
	
	function uddeIMnewMessage($myself, $item_id, $to_id, $recip, $runame, $pmessage, $config) {
		
		//$my_gid=uddeIMgetGID((int)$myself);
		$my_gid = $config->usergid;
		
		$recipname = "";
		if ($recip) {
			$recipname = uddeIMgetNameFromID ( $recip, $config );
		} elseif ($runame) {
			$recipname = uddeIMgetNameFromUsername ( $runame, $config );
			if (! $recipname)
				$recipname = $runame;
		}
		
		// write the uddeim menu
		//uddeIMprintMenu ( $myself, 'new', $item_id, $config );
		echo "<div id='uddeim-m'>\n";
		
		// Don't display writeform if inboxlimit set AND over limit
		// how many messages total in inbox? I do not need the number of messages separately for both boxes!
		if ($config->inboxlimit) { // there is a limit for inbox + archive
			if ($config->allowarchive) { // have an archive and an "archive and inbox" limit, so get number of messages in inbox and archive
				$universeflag = _UDDEIM_ARC_UNIVERSE_BOTH; // inbox and archive
				$total = uddeIMgetInboxArchiveCount ( $myself );
			} else { // user has switched of archive but there is an limit for "inbox and archive", so count inbox messages only
				$universeflag = _UDDEIM_ARC_UNIVERSE_INBOX; // inbox
				$total = uddeIMgetInboxCount ( $myself );
			}
			
			if (! uddeIMisAdmin ( $my_gid )) {
				// "The allowed maximum is XX."
				// $limitreached.= _UDDEIM_INBOX_LIMIT_3." ".$config->maxarchive.". ";
				// $limitreached.= " "._UDDEIM_SHOWINBOXLIMIT_2." ".$config->maxarchive.").";	// (of max. )
				

				if ($total > $config->maxarchive) {
					// "You have XX messages in your inbox/inbox+archive."
					$limitreached = _UDDEIM_INBOX_LIMIT_1 . " " . $total;
					$limitreached .= " " . ($total == 1 ? _UDDEIM_INBOX_LIMIT_2_SINGULAR : _UDDEIM_INBOX_LIMIT_2) . " ";
					$limitreached .= $universeflag;
					// You can still receive and read messages but you will not be able to reply or to compose new ones until you delete messages.
					$limitwarning = _UDDEIM_INBOX_LIMIT_4;
					
					$showinboxlimit_borderbottom = "<span class='uddeim-warning'>";
					$showinboxlimit_borderbottom .= $limitreached . " ";
					$showinboxlimit_borderbottom .= $limitwarning;
					$showinboxlimit_borderbottom .= "</span>";
					echo "<div id='uddeim-bottomlines'>" . $showinboxlimit_borderbottom . "</div>";
					// close main container
					echo "</div>\n<div id='uddeim-bottomborder'>" . uddeIMcontentBottomborder ( $myself, $item_id, 'standard', $limitreached, $config ) . "</div>\n";
					return;
				}
			}
		}
		
		// which page did refer to this page?
		// because we want to send back the user where (s)he came from
		$tbackto = uddeIMmosGetParam ( $_SERVER, 'HTTP_REFERER', null );
		if (stristr ( $tbackto, "com_pms" )) {
			$tbackto = "";
		}
		uddeIMdrawWriteform ( $myself, $my_gid, $item_id, $tbackto, $recipname, $pmessage, 0, 0, 0, 0, $config ); // isreply, errorcode, sysmsg
		

		// now check if user is an admin and if system messages are allowed
		if ($config->allowsysgm) {
			if (($config->allowsysgm == 1 && uddeIMisAdmin ( $my_gid )) || ($config->allowsysgm == 2 && uddeIMisManager ( $my_gid ))) {
				echo "<div id='uddeim-bottomlines'><p>";
				echo "<a href='" . uddeIMsefRelToAbs ( "index.php?option=com_uddeim&task=sysgm&Itemid=" . $item_id ) . "'>";
				echo _UDDEIM_WRITE_SYSM_GM;
				echo "</a></p></div>\n";
			}
		}
		echo "</div>\n<div id='uddeim-bottomborder'>" . uddeIMcontentBottomborder ( $myself, $item_id, 'standard', 'none', $config ) . "</div>\n";
	
	}
}

?>