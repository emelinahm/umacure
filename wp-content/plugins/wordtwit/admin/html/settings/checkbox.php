<input type="checkbox" class="checkbox" name="<?php wordtwit_the_tab_setting_name(); ?>" id="<?php wordtwit_the_tab_setting_name(); ?>"<?php if ( wordtwit_the_tab_setting_is_checked() ) echo " checked"; ?> />	
<label class="checkbox" for="<?php wordtwit_the_tab_setting_name(); ?>">
	<?php wordtwit_the_tab_setting_desc(); ?>
	
	<?php if ( wordtwit_the_tab_setting_has_tooltip() ) { ?>
	<a href="#" class="wordtwit-tooltip" title="<?php wordtwit_the_tab_setting_tooltip(); ?>">?</a>
	<?php } ?>
</label>			
<input type="hidden" name="<?php wordtwit_the_tab_setting_name(); ?>-hidden" />