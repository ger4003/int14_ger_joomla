<?php
/**
 * @version 1.1 $Id$
 * @package Joomla
 * @subpackage EventList
 * @copyright (C) 2005 - 2009 Christoph Lukes
 * @license GNU/GPL, see LICENCE.php
 * EventList is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.

 * EventList is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with EventList; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


// Get the breadcrumbs
// Get the breadcrumbs
$pathwaySite	= $mainframe->getPathway();
$pathway_reverse = array_reverse($pathwaySite->getPathwayNames());
?>

<script type="text/javascript">
		Window.onDomReady(function(){
			document.formvalidator.setHandler('date',
				function (value) {
					if(value=="") {
						return true;
					} else {
						timer = new Date();
						time = timer.getTime();
						regexp = new Array();
						regexp[time] = new RegExp('[0-9]{4}-[0-1][0-9]-[0-3][0-9]','gi');
						return regexp[time].test(value);
					}
				}
			);
		});

		function submitbutton( pressbutton ) {


			if (pressbutton == 'cancelevent' || pressbutton == 'addvenue') {
				elsubmitform( pressbutton );
				return;
			}

			var form = document.getElementById('adminForm');
			var validator = document.formvalidator;
			var title = $(form.title).getValue();
			title.replace(/\s/g,'');

			if ( title.length==0 ) {
   				alert("<?php echo JText::_( 'ADD TITLE', true ); ?>");
   				validator.handleResponse(false,form.title);
   				return false;
  			} else if ( validator.validate(form.locid) === false ) {
    			alert("<?php echo JText::_( 'SELECT VENUE', true ); ?>");
    			validator.handleResponse(false,form.locid);
    			return false;
				} else if ( form.cid.selectedIndex == -1 ) {
    			alert("<?php echo JText::_( 'SELECT CATEGORY', true ); ?>");
    			validator.handleResponse(false,form.cid);
    			return false;
  			} else if ( validator.validate(form.dates) === false ) {
   				alert("<?php echo JText::_( 'DATE WRONG', true ); ?>");
   				validator.handleResponse(false,form.dates);
   				return false;
  			} else if ( validator.validate(form.enddates) === false ) {
  				alert("<?php echo JText::_( 'DATE WRONG', true ); ?>");
    			validator.handleResponse(false,form.enddates);
  				return false;  			
  			} else {
  			<?php
  			if ($this->editoruser) {
					// JavaScript for extracting editor text
					echo $this->editor->save( 'datdescription' );
  			}
			?>
				submit_unlimited();
				elsubmitform(pressbutton);

				return true;
			}
		}
		
		//joomla submitform needs form name
		function elsubmitform(pressbutton){
			
			var form = document.getElementById('adminForm');
			if (pressbutton) {
				form.task.value=pressbutton;
			}
			if (typeof form.onsubmit == "function") {
				form.onsubmit();
			}
			form.submit();
		}


		var tastendruck = false
		function rechne(restzeichen)
		{

			maximum = <?php echo $this->elsettings->datdesclimit; ?>

			if (restzeichen.datdescription.value.length > maximum) {
				restzeichen.datdescription.value = restzeichen.datdescription.value.substring(0, maximum)
				links = 0
			} else {
				links = maximum - restzeichen.datdescription.value.length
			}
			restzeichen.zeige.value = links
		}

		function berechne(restzeichen)
   		{
  			tastendruck = true
  			rechne(restzeichen)
   		}
	</script>

<div id="eventlist" class="el_editevent">
	<div class="heading_wrap">
		<div class="componentheading">
			<h2><?php echo $pathway_reverse[1]?></h2>
			<p class="subheader"><?php echo $pathway_reverse[0]?></p>			
		</div>
	</div>
	
	
  <?php if ($this->params->def( 'show_page_title', 1 )) : ?>
	<div class="contentheading">
		<h3><?php echo $this->escape($this->row->title); ?></h3>
	</div>
	<?php endif; ?>
	<div class="clear"><!--  --></div>
	
	
	<div class="contentpane">		
    <?php if ($this->params->get('showintrotext')) : ?>
      <div class="description no_space floattext">
        <?php echo $this->params->get('introtext'); ?>
      </div>
    <?php endif; ?>

    <form enctype="multipart/form-data" id="adminForm" action="<?php echo JRoute::_('index.php') ?>" method="post" class="stnd form-validate">
    	<fieldset class="input el_fldst_details">
        	<legend><?php echo JText::_('NORMAL INFO'); ?></legend>

          <dl class="el_title">
          	<dt>
              <label for="title">
                  <?php echo JText::_( 'TITLE' ).':'; ?>
              </label>
						</dt>
						<dd>
              <input class="inputbox required" type="text" id="title" name="title" value="<?php echo $this->row->title; ?>" size="65" maxlength="60" />
            </dd>
          </dl>
          
          <dl class="el_venue column_last">
          	<dt>
              <label for="a_id">
                  <?php echo JText::_( 'VENUE' ).':'; ?>
              </label>
						</dt>
						<dd>
							<?php if ($this->elsettings->ownedvenuesonly) :
							echo $this->lists['venueselect'];
							?>
							<div class='el_buttons'>
								<?php if ( $this->delloclink == 1 && !$this->row->id ) : //show location submission link ?>
								<a class="el_venue_add modal" title="<?php echo JText::_('DELIVER NEW VENUE'); ?>" href="<?php echo JRoute::_('index.php?view=editvenue&mode=ajax&tmpl=component'); ?>" rel="{handler: 'iframe', size: {x: 800, y: 500}}">
									<span><?php echo JText::_('DELIVER NEW VENUE')?></span>
								</a>
								<?php endif; ?>
							</div>

							<?php else: ?>

              <input type="text" id="a_name" name="venue" class="inputbox" value="<?php echo $this->row->venue; ?>" disabled="disabled" />

              <div class='el_buttons'>
              
                  <a class="el_venue_reset" title="<?php echo JText::_('NO VENUE'); ?>" onclick="elSelectVenue(0,'<?php echo JText::_('NO VENUE'); ?>');return false;" href="#">
                      <span><?php  echo JText::_('NO VENUE'); ?></span>
                  </a>
                  <a class="el_venue_select modal" title="<?php echo JText::_('SELECT'); ?>" href="<?php echo JRoute::_('index.php?view=editevent&layout=choosevenue&tmpl=component'); ?>" rel="{handler: 'iframe', size: {x: 650, y: 375}}">
                      <span><?php echo JText::_('SELECT')?></span>
                  </a>
                  
                  <input class="required" type="hidden" id="a_id" name="locid" value="<?php echo $this->row->locid; ?>" />
             
                <?php if ( $this->delloclink == 1 && !$this->row->id ) : //show location submission link ?>
                  <a class="el_venue_add modal" title="<?php echo JText::_('DELIVER NEW VENUE'); ?>" href="<?php echo JRoute::_('index.php?view=editvenue&mode=ajax&tmpl=component'); ?>" rel="{handler: 'iframe', size: {x: 800, y: 500}}">
                      <span><?php echo JText::_('DELIVER NEW VENUE')?></span>
                  </a>
                <?php endif; ?>
              </div>
              <?php endif; ?>
            </dd>
          </dl>
          <div class="clear"><!--  --></div>

          <dl class="el_category">
          	<dt>
          		<label for="cid" class="cid">
                  <?php echo JText::_( 'CATEGORY' ).':';?>
              </label>
            </dt>
            <dd>
          		<?php echo $this->categories; ?>
          	</dd>
          </dl>
          
          
					<dl class="el_date el_startdate">
          	<dt>
              <label for="dates">
                  <?php echo JText::_( 'DATE' ).':'; ?>
              </label>
              <small class="editlinktip hasTip" title="<?php echo JText::_( 'NOTES' ); ?>::<?php echo JText::_('DATE HINT'); ?>">
      		    <?php echo $this->infoimage; ?>
          		</small>
            </dt>
            <dd>
              <?php echo JHTML::_('calendar', $this->row->dates, 'dates', 'dates', '%Y-%m-%d', array('class' => 'inputbox required validate-date')); ?>              
          	</dd>
				</dl>
				
				<dl class="el_date el_starttime  column_last">
          	<dt>
              <label for="el_starttime">
                        <?php echo JText::_( 'TIME' ).':'; ?>
              </label>
              <?php if ( $this->elsettings->showtime == 1 ) : ?>
        			<small class="editlinktip hasTip" title="<?php echo JText::_( 'NOTES' ); ?>::<?php echo JText::_('TIME HINT'); ?>">
        			    <?php echo $this->infoimage; ?>
        			</small>
        			<?php else : ?>
        			<small class="editlinktip hasTip" title="<?php echo JText::_( 'NOTES' ); ?>::<?php echo JText::_('ENDTIME HINT'); ?>">
        			    <?php echo $this->infoimage; ?>
        			</small>
        			<?php endif;?>
						</dt>
						<dd>
        			<?php
        			/* <input class="inputbox validate-time" id="el_starttime" name="times" value="<?php echo substr($this->row->times, 0, 5); ?>" size="15" maxlength="8" /> */
							echo ELHelper::buildtimeselect(23, 'starthours', substr( $this->row->times, 0, 2 )).' : ';
							echo ELHelper::buildtimeselect(59, 'startminutes', substr( $this->row->times, 3, 2 ));
							?>              
        		</dd>
      		</dl>

      	<dl class="el_date el_enddate">
      		<dt>
              <label for="enddates">
                  <?php echo JText::_( 'ENDDATE' ).':'; ?>
              </label>
              <small class="editlinktip hasTip" title="<?php echo JText::_( 'NOTES' ); ?>::<?php echo JText::_('DATE HINT'); ?>">
        			    <?php echo $this->infoimage; ?>
        			</small>
            </dt>
            <dd>
              <?php echo JHTML::_('calendar', $this->row->enddates, 'enddates', 'enddates', '%Y-%m-%d', array('class' => 'inputbox validate-date')); ?>        			
        		</dd>
      		</dl>
      		

          <dl class="el_date el_endtime column_last">
          	<dt>
              <label for="el_endtime">
                  <?php echo JText::_( 'ENDTIME' ).':'; ?>
              </label>
              <small class="editlinktip hasTip" title="<?php echo JText::_( 'NOTES' ); ?>::<?php echo JText::_('ENDTIME HINT'); ?>">
        			    <?php echo $this->infoimage; ?>
        			</small>
            </dt>
            <dd>
        			<?php
        			/* <input class="inputbox validate-time" id="el_endtime" name="endtimes" value="<?php echo substr($this->row->endtimes, 0, 5); ?>" size="15" maxlength="8" />&nbsp; */
							echo ELHelper::buildtimeselect(23, 'endhours', substr( $this->row->endtimes, 0, 2 )).' : ';
							echo ELHelper::buildtimeselect(59, 'endminutes', substr( $this->row->endtimes, 3, 2 ));
							?>        			
        		</dd>
      		</dl>
      		<div class="clear"><!--  --></div>

        </fieldset>

			  <fieldset class="input el_fldst_recurrence">

          <legend><?php echo JText::_('RECURRENCE'); ?></legend>

          <dl class="recurrence_select">
          	<dt>
              <label for="recurrence_select"><?php echo JText::_( 'RECURRENCE' ); ?>:</label>
            </dt>
            <dd>
              <?php echo $this->lists['recurrence_type']; ?>
            </dd>
          </dl>

          <dl class="recurrence_output column_last">
          	<dt>
            	<label id="recurrence_output">&nbsp;</label>
            </dt>            
						<dd id="counter_row" style="display:none;">
							<label for="recurrence_limit_date"><?php echo JText::_( 'RECURRENCE COUNTER' ); ?>:</label>
							<div class="el_date>"><?php echo JHTML::_('calendar', ($this->row->recurrence_limit_date <> 0000-00-00) ? $this->row->recurrence_limit_date : JText::_( 'UNLIMITED' ), "recurrence_limit_date", "recurrence_limit_date"); ?>
								<a href="#" onclick="include_unlimited('<?php echo JText::_( 'UNLIMITED' ); ?>'); return false;"><img src="components/com_eventlist/assets/images/unlimited.png" width="16" height="16" alt="<?php echo JText::_( 'UNLIMITED' ); ?>" /></a>
              </div>
						</dd>
          </dl>
          
			    <input type="hidden" name="recurrence_number" id="recurrence_number" value="<?php echo $this->row->recurrence_number;?>" />
			    <input type="hidden" name="recurrence_byday" id="recurrence_byday" value="<?php echo $this->row->recurrence_byday;?>" />

          <script type="text/javascript">
        	<!--
        	  var $select_output = new Array();
        		$select_output[1] = "<?php echo JText::_( 'OUTPUT DAY' ); ?>";
        		$select_output[2] = "<?php echo JText::_( 'OUTPUT WEEK' ); ?>";
        		$select_output[3] = "<?php echo JText::_( 'OUTPUT MONTH' ); ?>";
        		$select_output[4] = "<?php echo JText::_( 'OUTPUT WEEKDAY' ); ?>";

        		var $weekday = new Array();
		        $weekday[0] = new Array("MO", "<?php  echo JText::_ ( 'MONDAY' ); ?>");
		        $weekday[1] = new Array("TU", "<?php  echo JText::_ ( 'TUESDAY' ); ?>");
		        $weekday[2] = new Array("WE", "<?php  echo JText::_ ( 'WEDNESDAY' ); ?>");
		        $weekday[3] = new Array("TH", "<?php  echo JText::_ ( 'THURSDAY' ); ?>");
		        $weekday[4] = new Array("FR", "<?php  echo JText::_ ( 'FRIDAY' ); ?>");
		        $weekday[5] = new Array("SA", "<?php  echo JText::_ ( 'SATURDAY' ); ?>");
		        $weekday[6] = new Array("SU", "<?php  echo JText::_ ( 'SUNDAY' ); ?>");

        		var $before_last = "<?php echo JText::_( 'BEFORE LAST' ); ?>";
        		var $last = "<?php echo JText::_( 'LAST' ); ?>";

        	-->
          </script>
    	</fieldset>
			
    	<fieldset class="description">
      		<legend><?php echo JText::_('DESCRIPTION'); ?></legend>

      		<?php
      		//if usertyp min editor then editor else textfield
      		if ($this->editoruser) :
      			echo $this->editor->display('datdescription', $this->row->datdescription, '100%', '400', '70', '15', array('pagebreak', 'readmore') );
      		else :
      		?>
      		<textarea style="width:100%;" rows="10" name="datdescription" class="inputbox" wrap="virtual" onkeyup="berechne(this.form)"><?php echo $this->row->datdescription; ?></textarea><br />
      		<?php echo JText::_( 'NO HTML' ); ?><br />
      		<input disabled value="<?php echo $this->elsettings->datdesclimit; ?>" size="4" name="zeige" /><?php echo JText::_( 'AVAILABLE' ); ?><br />
      		<a href="javascript:rechne(document.adminForm);"><?php echo JText::_( 'REFRESH' ); ?></a>
      		<?php endif; ?>
    	</fieldset>
    	
    	<?php if ( $this->elsettings->showfroregistra == 2 ) : ?>
    	<fieldset class="input el_fldst_registration">

          <legend><?php echo JText::_('REGISTRATION'); ?></legend>

          <?php if ( $this->elsettings->showfroregistra == 2 ) : ?>
          <dl>
          	<dt>
              <label><?php echo JText::_( 'SUBMIT REGISTER' ).':'; ?></label>
            </dt>
            <dd>
              <label for="registra0"><?php echo JText::_( 'NO' ); ?></label>
        			<input type="radio" name="registra" id="registra0" value="0" <?php echo (!$this->row->registra) ? 'checked="checked"': ''; ?> />

              <label for="registra1"><?php echo JText::_( 'YES' ); ?></label>
            	<input type="radio" name="registra" id="registra1" value="1" <?php echo ($this->row->registra) ? 'checked="checked"': ''; ?> />
          	</dd>
          </dl>
          
      		<dl class="column_last">
      			<dt>
              <label for="maxplaces"><?php echo JText::_( 'COM_EVENTLIST_MAX_PLACES' ); ?></label>
              <small class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EVENTLIST_MAX_PLACES' ); ?>::<?php echo JText::_('COM_EVENTLIST_MAX_PLACES_TIP'); ?>">
        			    <?php echo $this->infoimage; ?>
        			</small>
            </dt>
            <dd>
        			<input type="text" name="maxplaces" id="maxplaces" class="inputbox" value="<?php echo $this->row->maxplaces; ?>" />        			
        		</dd>        		
      		</dl>
      		<div class="clear"><!--  --></div>
      		<?php endif;//register end ?>
      		
      		<?php if ( $this->elsettings->showfrounregistra == 2 ) : ?>
      		<dl class="el_unregister">
      			<dt>
        			<label><?php echo JText::_( 'SUBMIT UNREGISTER' ).':'; ?></label>
        		<dt>
						<dd>
            	<label for="unregistra0"><?php echo JText::_( 'NO' ); ?></label>
        			<input type="radio" name="unregistra" id="unregistra0" value="0" <?php echo (!$this->row->unregistra) ? 'checked="checked"': ''; ?> />

            	<label for="unregistra1"><?php echo JText::_( 'YES' ); ?></label>
            	<input type="radio" name="unregistra" id="unregistra1" value="1" <?php echo ($this->row->unregistra) ? 'checked="checked"': ''; ?> />
            </dd>
      		</dl>
      		<?php endif; //unregister end ?>
      		
      		<?php if ( $this->elsettings->showfroregistra == 2 ) : ?>
      		<dl class="column_last">
          	<dt>
        			<label><?php echo JText::_( 'COM_EVENTLIST_ENABLE_WAITINGLIST' ).':'; ?></label>
        			<small class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EVENTLIST_ENABLE_WAITINGLIST' ); ?>::<?php echo JText::_('COM_EVENTLIST_ENABLE_WAITINGLIST_TIP'); ?>">
        			    <?php echo $this->infoimage; ?>
        			</small>
        		</dt>
        		<dd>
            	<label for="waitinglist0"><?php echo JText::_( 'NO' ); ?></label>
        			<input type="radio" name="waitinglist" id="waitinglist0" value="0" <?php echo (!$this->row->waitinglist) ? 'checked="checked"': ''; ?> />
        			
            	<label for="waitinglist1"><?php echo JText::_( 'YES' ); ?></label>
            	<input type="radio" name="waitinglist" id="waitinglist1" value="1" <?php echo ($this->row->waitinglist) ? 'checked="checked"': ''; ?> />
        		</dd>            	
          </dl>
          <div class="clear"><!--  --></div>
          <?php endif;?>
    	</fieldset>
    	<?php endif; //registration end ?>


    	<?php if (( $this->elsettings->imageenabled == 2 ) || ($this->elsettings->imageenabled == 1)) : ?>
    	<fieldset class="el_fldst_image">
      	  <legend><?php echo JText::_('IMAGE'); ?></legend>
      		<?php
          if ($this->row->datimage) :
      		    echo ELOutput::flyer( $this->row, $this->dimage, 'event' );
      		else :
      		    echo JHTML::_('image', 'components/com_eventlist/assets/images/noimage.png', JText::_('NO IMAGE'), array('class' => 'modal'));
      		endif;
        	?>
          <label for="userfile"><?php echo JText::_('IMAGE'); ?></label>
      		<input class="inputbox <?php echo $this->elsettings->imageenabled == 2 ? 'required' : ''; ?>" name="userfile" id="userfile" type="file" />
      		<small class="editlinktip hasTip" title="<?php echo JText::_( 'NOTES' ); ?>::<?php echo JText::_('MAX IMAGE FILE SIZE').' '.$this->elsettings->sizelimit.' kb'; ?>">
      		    <?php echo $this->infoimage; ?>
      		</small>
              <!--<div class="el_cur_image"><?php echo JText::_( 'CURRENT IMAGE' ); ?></div>
      		<div class="el_sel_image"><?php echo JText::_( 'SELECTED IMAGE' ); ?></div>-->
    	</fieldset>
    	<?php endif; ?>
    	
    	<?php echo $this->loadTemplate('attachments'); ?>
    	

			<div class="el_save_buttons">
        <button type="submit" class="button button_prm " onclick="return submitbutton('saveevent')">
            <?php echo JText::_('SAVE') ?>
        </button>
        <button type="reset" class="button button_scd cancel" onclick="submitbutton('cancelevent')">
            <?php echo JText::_('CANCEL') ?>
        </button>
      </div>
            
		<p class="clear">
    	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
    	<input type="hidden" name="referer" value="<?php echo @$_SERVER['HTTP_REFERER']; ?>" />
    	<input type="hidden" name="created" value="<?php echo $this->row->created; ?>" />
    	<input type="hidden" name="author_ip" value="<?php echo $this->row->author_ip; ?>" />
    	<input type="hidden" name="created_by" value="<?php echo $this->row->created_by; ?>" />
    	<input type="hidden" name="curimage" value="<?php echo $this->row->datimage; ?>" />
    	<input type="hidden" name="version" value="<?php echo $this->row->version; ?>" />
		<input type="hidden" name="hits" value="<?php echo $this->row->hits; ?>" />
    	<?php echo JHTML::_( 'form.token' ); ?>
    	<input type="hidden" name="task" value="" />
    	</p>
    </form>


	</div>
</div>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>