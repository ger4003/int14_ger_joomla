<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<script type="text/javascript">
<!--
	Window.onDomReady(function(){
		document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
	});
// -->
</script>

<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
<div class="contentheading <?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
	<h2><?php echo $this->escape($this->params->get('page_title')); ?></h2>
</div>
<div class="clear"><!--  --></div>
<?php endif; ?>


<div class="contentpane">
	<div class="contentdescription">
		Inhaltsbeschreibung
	</div>
	
	<?php
		if(isset($this->message)){
			$this->display('message');
		}
	?>
	
	<form action="<?php echo JRoute::_( 'index.php?option=com_user' ); ?>" method="post" id="josForm" name="josForm" class="stnd form-validate">
		<fieldset class="input">
			<?php /* name */ ?>
			<dl>
				<dt>
					<label id="namemsg" for="name"><?php echo JText::_( 'Name' ); ?>*:</label>
				</dt>
				<dd>
					<input type="text" name="name" id="name" size="40" value="<?php echo $this->escape($this->user->get( 'name' ));?>" class="inputbox required" maxlength="50" />
				</dd>	
			</dl>
			<div class="clear"><!--  --></div>
			
			<?php /* username */ ?>
			<dl>
				<dt>
					<label id="usernamemsg" for="username"><?php echo JText::_( 'User name' ); ?>:</label>
				</dt>
				<dd>
					<input type="text" id="username" name="username" size="40" value="<?php echo $this->escape($this->user->get( 'username' ));?>" class="inputbox required validate-username" maxlength="25" />
				</dd>	
			</dl>
			<div class="clear"><!--  --></div>
			
			<?php /* email */ ?>
			<dl>
				<dt>
					<label id="emailmsg" for="email"><?php echo JText::_( 'Email' ); ?>:</label>
				</dt>
				<dd>
					<input type="text" id="email" name="email" size="40" value="<?php echo $this->escape($this->user->get( 'email' ));?>" class="inputbox required validate-email" maxlength="100" />
				</dd>	
			</dl>
			<div class="clear"><!--  --></div>
			
			<?php /* password */ ?>
			<dl>
				<dt>
					<label id="pwmsg" for="password"><?php echo JText::_( 'Password' ); ?>:</label>
				</dt>
				<dd>
					<input class="inputbox required validate-password" type="password" id="password" name="password" size="40" value="" />
				</dd>	
			</dl>
			<div class="clear"><!--  --></div>
			
			<?php /* password verify */ ?>
			<dl>
				<dt>
					<label id="pw2msg" for="password2"><?php echo JText::_( 'Verify Password' ); ?>:</label>
				</dt>
				<dd>
					<input class="inputbox required validate-passverify" type="password" id="password2" name="password2" size="40" value="" />
				</dd>	
			</dl>
			<div class="clear"><!--  --></div>
			
			<?php echo JText::_( 'REGISTER_REQUIRED' ); ?>
			
			<button class="button validate" type="submit"><?php echo JText::_('Register'); ?></button>
			<input type="hidden" name="task" value="register_save" />
			<input type="hidden" name="id" value="0" />
			<input type="hidden" name="gid" value="0" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</fieldset>
	</form>
</div><!-- contentpane -->