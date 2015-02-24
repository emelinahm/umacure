<?php

  global $der_framework;

?>

<div id="layout-canvas">
  
  <div class="bar header-bar">
    <span>Header</span>
    <a target="_blank" title="Header Configuration" class="btn-icon config" href="<?php echo admin_url('admin.php?page=theme-options#header') ?>"></a>
     <a title="Insert container above" data-location="head" class="btn-icon split-action split-above"></a>
  </div><!-- .bar -->
  
  <div id="workspace" class="root">

  </div><!-- workspace -->
  
  <div id="component-list" class="floating-box" style="display: none;">
    
    <div class="head">
      <p><strong class="title" >Add Layout Component</strong><br/>
        <span class="desc">Choose a component to add into your layout</span>
        <span class="links"><a class="cancel" href="#">Close</a></span>
      </p>
    </div><!-- .head -->
    
    <div class="content">
      <ul class="items-selection">

      </ul><!-- .items-selection -->
    </div><!-- .content -->
    
    <div class="foot">
      <a class="btn btn-light cancel">Close</a>
    </div><!-- foot -->
    
  </div><!-- component-list -->
  
  <div id="data-entry" class="floating-box" style="display: none;">
    
    <form method="GET" action="#">
      
    <div class="head">
      <p><strong class="title"></strong><br/>
      <span class="desc"></span>
      <span class="links">
        <a class="cancel" href="#">Close</a>
        <a class="save-component" href="#">Save Settings</a>
      </span><!-- .links -->
      </p>
      
    </div>
    
    <div class="content">
      
<?php if (0): ?>
      
      <div class="option text-option">
        <label class="title">Component Title</label>
        <input type="text" name="input_option" value="" />
        <div class="desc">Name of the component.</div>
      </div><!-- .option -->
      
      <div class="option text-option">
        <label class="title">Number</label>
        <input type="text" name="input_option" value="" />
        <div class="desc">This is a small description for the input.</div>
      </div><!-- .option -->
      
      <div class="option textarea-option">
        <label class="title">Textarea</label>
        <textarea name="textarea_option" rows="3"></textarea>
        <div class="desc">This is a small description for the input.</div>
      </div><!-- .option -->
      
      <div class="option radio-option multiple">
        <label class="title">Radio</label>
        <p><label><input type="radio" name="radio_option" value="1" checked="checked" /> Radio 1</label></p>
        <p><label><input type="radio" name="radio_option" value="2" /> Radio 2</label></p>
        <div class="desc">This is a small description for the input.</div>
      </div><!-- .option -->
      
      <div class="option checkbox-option multiple">
        <label class="title">Checkbox</label>
        <p><label><input type="checkbox" name="my_checkbox[1]" /> Checkbox 1</label></p>
        <p><label><input type="checkbox" name="my_checkbox[2]" /> Checkbox 2</label></p>
        <p><label><input type="checkbox" name="my_checkbox[3]" /> Checkbox 3</label></p>
        <div class="desc">This is a small description for the input.</div>
      </div><!-- checkbox-option -->
      
      <div class="option select-option">
        <label class="title">Select</label>
        <select name="select_option">
          <option value="1" selected="selected">First Value</option>
          <option value="2">Second Value</option>
          <option value="3">Third Value</option>
        </select>
        <div class="desc">This is a small description for the input.</div>
      </div><!-- .option -->
      
    </form>
      
<?php endif; ?>
      
    </div><!-- content -->
    
    <div class="foot">
      <a class="btn btn-primary save-component">Save Settings</a>
      <a class="btn btn-light cancel">Close</a>
    </div><!-- foot -->
    
    </form>
    
  </div><!-- data-entry -->
  
  <div class="bar footer-bar">
    <span>Footer</span>
    <a target="_blank" title="Footer Configuration" class="btn-icon config" href="<?php echo admin_url('admin.php?page=theme-options#footer') ?>"></a>
    <a title="Insert container below" data-location="tail" class="btn-icon split-action split-below"></a>
  </div><!-- .bar -->
  
</div><!-- layout-canvas -->
