<?php defined( '_JEXEC' ) or die( 'Restricted access' );

$templatePath = JURI::base().'templates/'.$this->template;
$document = &JFactory::getDocument();

/* add javascripts */
$document->addScript($templatePath.'/js/jquery-1.4.4.min.js');
$document->addScript($templatePath.'/js/jquery.tools.min.js');
$document->addScript($templatePath.'/js/jquery.hoverIntent.minified.js');
$document->addScript($templatePath.'/js/superfish.js');
$document->addScript($templatePath.'/js/scripts.js');
// add inline scripts
#$document->addScriptDeclaration ('');

/* add stylesheets */
$document->addStylesheet($templatePath.'/css/style.css');

/* check for static content */
$db = JFactory::getDBO();
$isStaticContent = false;

if(JRequest::getCmd('view') == 'article') {
	// get article id
	$article_id = JRequest::getVar('id');
	if(strpos($article_id,':') !== FALSE) {
		$article_id = substr(JRequest::getVar('id'),0,strpos(JRequest::getVar('id'),':'));
	}

	// get sectionid for article
	$query = "SELECT sectionid FROM #__content WHERE id=$article_id";
	$db->setQuery($query);
	$sectionid = $db->loadResult();

	// get categoryid for article
	$query = "SELECT catid FROM #__content WHERE id=$article_id";
	$db->setQuery($query);
	$catid = $db->loadResult();

	if($sectionid == 0 && $catid == 0) {
		$isStaticContent = true;
	}
}


// set title
$app = JFactory::getApplication();
if(JRequest::getCmd('view') != "frontpage")
{
	$this->title = $this->title . " | " . $app->getCfg('sitename');
}

?>

<!DOCTYPE HTML>
<html>

<head>
	<jdoc:include type="head" />

	<meta property="og:image" content="<?php echo $templatePath?>/images/fb_share.jpg" />

	<!-- google analytics -->
	<script type="text/javascript">

		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-31956241-1']);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();

	</script>

</head>

<body>

	<div id="main_container">
		<div id="top_container">
			<jdoc:include type="modules" name="top" />
		</div>

		<div id="navi_container">
			<a href="/" title="Zur Startseite">
				<img id="logo" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/logo.jpg" alt="INT 14 GER Klassenverieinigung" />
			</a>

			<jdoc:include type="modules" name="navigation" style="flyout" />
		</div>
		<div class="clear"><!--  --></div>

		<div id="header_container">
			<!--
			<ul>
				<li>http://extensions.joomla.org/extensions/core-enhancements/embed-a-include/3810</li>
				<li><a href="http://www.joomlaos.de/Downloads/Joomla_und_Mambo_Module/Header_Image.html">Module Header Image</a></li>
			</ul>
			-->
			<jdoc:include type="modules" name="header" />

			<div id="header_extra">
				<jdoc:include type="modules" name="header_extra" />
			</div>
		</div>

		<div id="content" class="<?php echo JRequest::getCmd('option'); ?>_<?php echo JRequest::getCmd('view'); ?><?php echo ($isStaticContent)? "_static" : "";?>">



		<?php if (JRequest::getCmd('view') == "frontpage"): ?>
			<!--  frontpage content -->
			<div id="fp_content_inner" class="modules">
				<jdoc:include type="component" />
			</div>

			<div id="fp_modules_top" class="modules">
				<jdoc:include type="modules" name="fp_modules_top" style="frontpage" />
			</div>
			<div class="clear"><!--  --></div>


			<div id="fp_modules_image" class="modules image_wrap">
				<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/fp_content_image_<?php echo rand(0,3) ?>.jpg" />
			</div>
			<div id="fp_modules" class="modules">
				<jdoc:include type="modules" name="fp_modules" style="frontpage" />
			</div>
			<div class="clear"><!--  --></div>

			<?php if(count(JModuleHelper::getModules('fp_modules_half_left')) > 0): ?>
			<div id="fp_modules_half_left" class="modules modules_half">
				<!-- http://www.latenight-coding.com/joomla-addons/qtabs/documentation.html -->
				<jdoc:include type="modules" name="fp_modules_half_left" style="frontpage_half" />
			</div>
			<?php endif; ?>

			<?php if(count(JModuleHelper::getModules('fp_modules_half_right')) > 0): ?>
			<div id="fp_modules_half_right" class="modules modules_half">
				<?php /* add parameter for using tab navigation */ ?>
				<jdoc:include type="modules" name="fp_modules_half_right" style="frontpage_half" />
			</div>
			<div class="clear"><!--  --></div>
			<?php endif; ?>
			<!--  /frontpage content -->

		<?php else: ?>

			<jdoc:include type="modules" name="breadcrumb" />

			<?php
			if ($isStaticContent == false
					&& JRequest::getCmd('view') == "article"
					|| JRequest::getCmd('option') == "com_listbingo" && JRequest::getCmd('view') == "ad"
					|| JRequest::getCmd('option') == "com_eventlist" && JRequest::getCmd('view') == "details" ): ?>
			<div id="content_extra">
				<jdoc:include type="modules" name="content_extra" style="sidebar" />
			</div>
			<?php endif; ?>

			<div id="content_inner">
				<jdoc:include type="component" />
			</div>
		<?php endif; ?>
		</div>

		<?php if (JRequest::getCmd('view') != "frontpage"): ?>
		<div id="sidebar">
			<jdoc:include type="modules" name="sidebar" style="sidebar" />
		</div>
		<?php endif; ?>
		<br class="clear" />

		<div id="footer">
			<jdoc:include type="modules" name="footer" />
		</div>
	</div>

</body>

</html>

