<?php // no direct access
defined('_JEXEC') or die('Restricted access');

global $listitemid;

$basepathmodule = JUri::root()."modules/mod_lbcategories/";
$document = JFactory::getDocument();


$document->addStylesheet($basepathmodule."css/default.css");
$option = "com_listbingo";

// get ad information
$model = gbimport ( "listbingo.model.ad" );
$ad = $model->loadWithFields ( JRequest::getInt ( 'adid', 0 ), true );

?>


<?php if(count($list)>0): ?>
<ul class="menu">
	<li>
		<span class="label">ID:</span> <?php echo $ad->globalad_id; ?>
	</li>
	<li>
		<span class="label">Aufrufe:</span> <?php echo $ad->views ?>
	</li>
	<li>
		<span class="label">Autor:</span> <?php echo $ad->aduser->profilelink;?>
	</li>
	<?php GApplication::triggerEvent('onBeforeDisplayTitle',array(& $ad,& $this->params)); ?>
</ul>
<?php endif; ?>
