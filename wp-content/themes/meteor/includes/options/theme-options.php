<?php

  global $der_framework, $theme_options;
  
  $icons = true;
  
  /////////////////////////////////////////////
  // GENERAL
  /////////////////////////////////////////////
  
  $theme_options['General'] = array(
    
    "icon" => $icons ? "icon-cog" : null,
    
    "Color Theme" => array(
      'type' => 'select',
      'id' => 'color_theme',
      'description' => 'Color theme to use for your site.

You can create and save multiple color themes and switch to them anytime.

<a id="add-color-theme" class="notd" onclick="return false;" href="#"><i class="icon-plus-sign"></i>&nbsp; Add</a> &nbsp;&nbsp; <a id="rename-color-theme" class="notd" onclick="return false;" href="#"><i class="icon-pencil"></i>&nbsp; Rename</a> &nbsp;&nbsp; <a id="delete-color-theme" class="notd" onclick="return false;" href="#"><i class="icon-remove"></i>&nbsp; Delete</a>

<a id="edit-color-theme" class="notd" href="admin.php?page=color-editor"><i class="icon-edit"></i>&nbsp; Edit Current</a>
',
      'default' => 'default',
      'values' => array_merge(array(
        'default' => "Meteor Default",
      ), $der_framework->get_color_themes())
    ),
    
    "Boxed Layout" => array(
      'type' => 'select',
      'id' => 'boxed_layout',
      'default' => 'disabled',
      'values' => array(
        'enabled' => "Enabled",
        'disabled' => "Disabled"
      ),
      'description' => "Enable or disable the Boxed Layout."
    ),
    
    "Content Width (pixels)" => array(
      'type' => 'select',
      'id' => 'content_width',
      'default' => '1170',
      'values' => array(
        '960' => "960",
        '1170' => "1170"
      ),
      'description' => "Width to use for your site's content."
    ),
    
    "Maximum Image Width (pixels)" => array(
      'type' => 'text',
      'id' => 'max_image_width',
      'default' => '1600',
      'description' => "This value is used as a limit for images displayed across the full <u>screen</u> width.

Higher values provide better quality in big screens and retina devices."
    ),
    
    "Show Edit Post Links" => array(
      'type' => 'select',
      'id' => 'show_edit_post_links',
      'default' => 'yes',
      'values' => array(
        'yes' => "Yes",
        'no' => "No"
      ),
      'description' => "Show the edit post links for logged in users with editing permission."
    ),
    
    "Show Breadcrumbs" => array(
      'type' => 'select',
      'id' => 'show_breadcrumb',
      'default' => 'yes',
      'values' => array(
        'yes' => "Yes",
        'no' => "No"
      ),
      'description' => "Determines if breadcrumbs are shown."
    ),
    
    "Breadcrumb Home Label" => array(
      'type' => 'text',
      'id' => 'breadcrumb_base_name',
      'default' => __("Home", "theme"),
      'description' => "Label to use for the homepage label."
    ),
    
  );
  
  /////////////////////////////////////////////
  // LAYOUTS
  /////////////////////////////////////////////
  
  $theme_options['Layouts'] = array(
    
    "icon" => $icons ? "icon-crop" : null,
    
    'Layout Configuration' => array(
      'type' => 'message',
      'content' => 'You can create and edit layouts using the <a target="_blank" href="admin.php?page=layout-editor">Layout Editor</i></a>.'
    ),
    
    'Homepage Layout' => array(
      'type' => 'layout',
      'id' => 'homepage_layout',
      'description' => 'Layout to use for the Homepage.
        
<strong>NOTE:</strong> <em>Assigning a Layout to the Homepage will override the <strong>Front page displays</strong> setting, located in <a class="notd" href="options-reading.php">Settings &rarr; Reading</a>.</em>'
    ),
    
    'Single Posts Layout' => array(
      'type' => 'layout',
      'id' => 'single_layout',
      'description' => "Layout to use for the Single Posts."
    ),
    
    'Portfolio Posts Layout' => array(
      'type' => 'layout',
      'id' => 'portfolio_layout',
      'description' => "Layout to use for the Portfolio Posts.
      
<strong>Note:</strong> overrides single posts layout."
    ),
    
    'Pages Layout' => array(
      'type' => 'layout',
      'id' => 'page_layout',
      'description' => "Layout to use for Pages."
    ),
    
    'Archives Layout' => array(
      'type' => 'layout',
      'id' => 'archives_layout',
      'description' => "Layout to use for the Archive Pages."
    ),
    
    'Search Layout' => array(
      'type' => 'layout',
      'id' => 'search_layout',
      'description' => "Layout to use for the Search Results."
    ),
    
    '404 Layout' => array(
      'type' => 'layout',
      'id' => '404_layout',
      'description' => "Layout to use for the 404 Error."
    )
    
  );
  
  /////////////////////////////////////////////
  // TYPOGRAPHY
  /////////////////////////////////////////////
  
  /*  NOTE TO SELF: The `theme_admin_extras_styles` function (on theme-admin.php) will assume the last defined
      font as the style to use for the slider captions. If changing the font, make sure the last font is the 
      font used for headings.  */

  $theme_options['Fonts'] = array(

    "icon" => $icons ? "icon-font" : null,

    'Google Webfonts' => array(
      'type' => 'message',
      'content' => '
There are <strong class="webfonts-count"></strong> fonts available. &nbsp; <a class="update-webfonts-link" href="' . admin_url('admin-ajax.php?action=webfonts_update') . '">Update</a> &nbsp; | &nbsp; <a target="_blank" href="http://google.com/webfonts">Browse</a>

    '),
    
    'Webfonts Enabled' => array(
      'type' => 'select',
      'id' => 'webfonts_enabled',
      'description' => "Whether or not to enable Google Webfonts.",
      'default' => 'yes',
      'values' => array(
        'yes' => "Yes",
        'no' => "No"
      )
    ),

    'Base Font Size (pixels)' => array(
      'type' => 'text',
      'id' => 'base_font_size',
      'description' => 'Base font size to use. All text will scale proportionally.',
      'default' => '13'
    ),
    
    'Editor Font Size (pixels)' => array(
      'type' => 'text',
      'id' => 'editor_font_size',
      'description' => 'Base font size to use for the WordPress Editor.',
      'default' => '15'
    ),
    
    'Main Font' => array(
      'type' => 'select',
      'id' => 'webfont',
      'default' => "PT Sans",
      'description' => 'Font to use for your site\'s typography.

<a class="preview-font notd" target="_blank" href="http://google.com/webfonts">View Specimen &nbsp;<i class="icon-angle-right"></i></a>
',
      'update_fonts' => true
    ),

    'Subset' => array(
      'type' => 'checkbox',
      'id' => 'webfont_subsets',
      'description' => 'Subsets to use for your font.',
      'values' => array()
    ),

    'Font Weights' => array(
      'type' => 'checkbox',
      'id' => 'webfont_variants',
      'description' => 'Weights to use for your font',
      'values' => array()
    ),
    
    'Headings & Navigation Font' => array(
      'type' => 'select',
      'id' => 'headings_font',
      'default' => "Raleway",
      'description' => 'Font to use for headers & navigation.

<a class="preview-font notd" target="_blank" href="http://google.com/webfonts">View Specimen &nbsp;<i class="icon-angle-right"></i></a>
',
      'update_fonts' => true
    ),

    'Subset ' => array(
      'type' => 'checkbox',
      'id' => 'headings_font_subsets',
      'description' => 'Subsets to use for your font.',
      'values' => array()
    ),

    'Font Weights ' => array(
      'type' => 'checkbox',
      'id' => 'headings_font_variants',
      'description' => 'Weights to use for your font.',
      'values' => array()
    ),

  );
  
  
  $theme_options['Typography'] = array(
    
    "icon" => "icon-font",
    
    'Menu Items Font Size (%)' =>  array(
      'type' => 'text',
      'id' => 'nav_font_size',
      'description' => 'Font size to use for the menu items',
      'default' => '112'
    ),

    'H1 Font Size (%)' => array(
      'type' => 'text',
      'id' => 'h1_font_size',
      'description' => 'Font size to use for level 1 headings',
      'default' => '150'
    ),
    
    'H2 Font Size (%)' => array(
      'type' => 'text',
      'id' => 'h2_font_size',
      'description' => 'Font size to use for level 2 headings',
      'default' => '150'
    ),
    
    'H3 Font Size (%)' => array(
      'type' => 'text',
      'id' => 'h3_font_size',
      'description' => 'Font size to use for level 3 headings',
      'default' => '110'
    ),
    
    'H4 Font Size (%)' => array(
      'type' => 'text',
      'id' => 'h4_font_size',
      'description' => 'Font size to use for level 4 headings',
      'default' => '110'
    ),
    
    'H5 Font Size (%)' => array(
      'type' => 'text',
      'id' => 'h5_font_size',
      'description' => 'Font size to use for level 5 headings',
      'default' => '110'
    ),
    
    'H6 Font Size (%)' => array(
      'type' => 'text',
      'id' => 'h6_font_size',
      'description' => 'Font size to use for level 6 headings',
      'default' => '110'
    )
    
  );
  
  /////////////////////////////////////////////
  // SIDEBARS
  /////////////////////////////////////////////

  $theme_options['Sidebars'] = array(

    "icon" => $icons ? "icon-ellipsis-vertical" : null,

    "Sidebars" => array(
      'type' => 'textarea',
      'id' => 'sidebars',
      'rows' => 14,
      'default' => THEME_DEFAULT_SIDEBAR,
      'description' => sprintf('Sidebars to add to your site. 

Add them <u>one per line</u>.

<a target="_blank" style="text-decoration: none;" target="_blank" href="%s"><i class="icon-wrench"></i>&nbsp; Manage Widgets</a>', admin_url('widgets.php'))
    )

  );
  
  
  /////////////////////////////////////////////
  // HEADER
  /////////////////////////////////////////////
  
  $theme_options['Header'] = array(
    
    "icon" => $icons ? "icon-cog" : null,
    
    "Sticky Header" => array(
      'type' => 'select',
      'id' => 'sticky_header',
      'default' => 'disabled',
      'values' => array(
        'enabled' => "Enabled",
        'disabled' => "Disabled"
      ),
      'description' => "Makes the header stick to the upper part of the screen when scrolling.
<br/><strong>Note:</strong> Due to technical constraints, the Sticky Header feature is not enabled on touch screen devices."
    ),

    "Sticky on Mobile Layout" => array(
      'type' => 'select',
      'id' => 'sticky_mobile',
      'default' => 'no',
      'values' => array(
        'yes' => "Yes",
        'no' => "No"
      ),
      'description' => "Header is sticky on the <u>mobile layout</u>."
    ),

    "Sticky Scroll Distance (pixels)" => array(
      'type' => 'text',
      'id' => 'sticky_distance',
      'default' => 500,
      'description' => "This is the scroll distance in which the header will descrease its size."
    ),

    "Sticky Offset (pixels)" => array(
      'type' => 'text',
      'id' => 'sticky_offset',
      'default' => 14,
      'description' => "Separation to add so the logo doesn't reach the header boundaries."
    ),
    
    "Show Search Icon" => array(
      'type' => 'select',
      'id' => 'show_header_search',
      'default' => 'yes',
      'values' => array(
        'yes' => "Yes",
        'no' => "No",
      ),
      'description' => "Choose whether or not to display the search icon next to the navigation."
    ),
    
    "Search Behavior" => array(
      'type' => 'select',
      'id' => 'header_search_behavior',
      'default' > 'default',
      'values' => array(
        'default' => "Press enter on the search box",
        'autosearch' => "Click on the search icon after entering text",
      ),
      'description' => "Choose how the search box works."
    ),
    
    "Navigation Bottom Separation (pixels)" => array(
      'type' => 'text',
      'id' => 'navigation_bottom_sep',
      'default' => 20,
      'description' => "The amount of separation to add to the navigation when it's centered."
    )

  );
  
  /////////////////////////////////////////////
  // LOGO
  /////////////////////////////////////////////
  
  $theme_options['Logo'] = array(
    
    "icon" => $icons ? "icon-picture" : null,
    
    "Logo Alignment" => array(
      'type' => 'select',
      'id' => 'logo_align',
      'default' => 'left',
      'values' => array(
        'left' => "Left",
        'center' => "Center",
        'right' => "Right"
      ),
      'description' => "Alignment of the logo in the header.
<br/><strong>Note:</strong> The navigation is automatically aligned depending on this setting."
    ),

    "Logo Image &ndash; 1:1" => array(
      'type' => 'upload',
      'id' => 'logo_image',
      'description' => "Logo to use for your site.
  
<strong>Note:</strong> If you leave this field blank, the \"Retina Logo Image\" will be used."
    ),

    "Retina Logo Image &ndash; 2:1" => array(
      'type' => 'upload',
      'id' => 'retina_logo_image',
      'description' => "Logo to use for devices with retina display (double resolution).
  
<strong>Note:</strong> The image uploaded here will be displayed in <u>half</u> the resolution on devices with retina display."
    ),
    
    "Logo Vertical Padding (pixels)" => array(
      'type' => 'text',
      'id' => 'logo_padding',
      'default' => 36,
      'description' => "Amount of vertical separation to add to the logo. You may need to adjust this value until you get it looking right."
    ),
    
    "Minimum Logo Height (pixels)" => array(
      'type' => 'text',
      'id' => 'min_logo_height',
      'default' => 29,
      'description' => "This will be the minimum logo height in which the logo will be resized.
    
<br/><strong>Note:</strong> This setting is only effective when the Sticky Header is enabled."
    ),

  );
  
  /////////////////////////////////////////////
  // TOPBAR
  /////////////////////////////////////////////

  $theme_options['Topbar'] = array(
  
   "icon" => $icons ? "icon-ellipsis-horizontal" : null,
  
    "Show Topbar" => array(
      'type' => 'select',
      'id' => 'topbar_display',
      'default' => 'yes',
      'values' => array(
        'yes' => "Yes",
        'no' => "No"
      ),
      'description' => "Show or hide the topbar."
    ),
  
    "Topbar Distribution" => array(
      'type' => 'select',
      'id' => 'topbar_social_icons_align',
      'default' => 'right',
      'values' => array(
        'right' => "Text &nbsp; &mdash; &nbsp; Social Icons",
        'left' => "Social Icons &nbsp; &mdash; &nbsp; Text"
      ),
      'description' => "Distribution to use for the topbar."
    ),
  
    "Show Topbar Text" => array(
      'type' => 'select',
      'id' => 'topbar_text_display',
      'default' => 'yes',
      'values' => array(
        'yes' => "Yes",
        'no' => "No"
      ),
      'description' => "Choose whether or not you want the topbar text to display."
    ),
  
    "Show Social Icons" => array(
      'type' => 'select',
      'id' => 'topbar_social_icons_display',
      'default' => 'yes',
      'values' => array(
        'yes' => "Yes",
        'no' => "No"
      ),
      'description' => 'Choose whether or not you want the social icons to display.
      
<a target="_blank" href="?page=social-icons" style="text-decoration: none;"><i class="icon-wrench"></i>&nbsp; Manage Social Icons</a>'
    ),
  
    "Hide on Mobile" => array(
      'type' => 'select',
      'id' => 'topbar_hide_on_mobile',
      'default' => 'text',
      'values' => array(
        'text' => "Topbar Text",
        'social' => "Social Icons"
      ),
      'description' => "What to hide on mobile resolution."
    ),

    "Social Icons Hover Style" => array(
      'type' => 'select',
      'id' => 'header_social_icons_hover_style',
      'default' => 'colorful',
      'values' => array(
        'colorful' => "Brand Colors",
        'simple' => "Simple"
      ),
      'description' => "Hover style to use for the social icons."
    ),
  
    "Show Social Icon Tooltips" => array(
      'type' => 'select',
      'id' => 'topbar_social_icon_tooltips',
      'default' => 'yes',
      'values' => array(
        'yes' => "Yes",
        'no' => "No"
      ),
      'description' => "Display tooltips on the social icons when hovering."
    ),
  
    "Topbar Text Mode" => array(
      'type' => 'select',
      'id' => 'topbar_text_mode',
      'default' => 'text',
      'values' => array(
        'text' => "Plain Text",
        'html' => "HTML"
      ),
      'description' => "Choose how the text is rendered."
    ),
  
    "Topbar Text" => array(
      'type' => 'code',
      'id' => 'topbar_text',
      'rows' => 6,
      'description' => "Text to display on the Topbar."
    )
  
  );
  
  /////////////////////////////////////////////
  // FAVICON
  /////////////////////////////////////////////
  
  $theme_options['Favicon'] = array(
    
   "icon" => $icons ? "icon-info-sign" : null,
    
    "Site Favicon" => array(
      'type' => 'upload',
      'id' => 'favicon',
      'description' => "Favicon to use on your site."
    ),
    
    "Touch Icon (57x57)" => array(
      'type' => 'upload',
      'id' => 'touch_icon_57',
      'description' => "Mobile device icon (small)."
    ),
    
    "Touch Icon (72x72)" => array(
      'type' => 'upload',
      'id' => 'touch_icon_72',
      'description' => "Mobile device icon (medium)."
    ),
    
    "Touch Icon (114x114)" => array(
      'type' => 'upload',
      'id' => 'touch_icon_114',
      'description' => "Mobile device icon (big)."
    ),
    
    "Touch Icon (144x144)" => array(
      'type' => 'upload',
      'id' => 'touch_icon_144',
      'description' => "Mobile device icon (huge)."
    ),
    
    "IOS Touch Icon Display" => array(
      'type' => 'select',
      'id' => 'ios_icon_display',
      'default' => 'normal',
      'values' => array(
        'normal' => "With reflective shine",
        'precomposed' => "Without reflective shine"
      ),
      'description' => "This option controls how you want the icon to appear on the Home Screen."
    )
    
  );
  
  /////////////////////////////////////////////
  // TWITTER BAR
  /////////////////////////////////////////////
  
  $theme_options['Twitter'] = array(
    
    "icon" => $icons ? "icon-twitter" : null,
    
    "Show Twitter Bar" => array(
      'type' => 'select',
      'id' => 'enable_twitterbar',
      'default' => 'yes',
      'values' => array(
        'yes' => "Yes",
        'no' => "No"
      ),
      'description' => "Determines whether or not the twitter bar is shown on the footer."
    ),
    
    "Tweet Rotation" => array(
      'type' => 'select',
      'id' => 'twitterbar_autoplay',
      'default' => 'enabled',
      'values' => array(
        'enabled' => "Enabled",
        'disabled' => "Disabled"
      ),
      'description' => "Automatically rotate tweets."
    ),
    
    
    "Pause on Hover" => array(
      'type' => 'select',
      'id' => 'twitterbar_pause_on_hover',
      'default' => 'yes',
      'values' => array(
        'yes' => "Yes",
        'no' => "No"
      ),
      'description' => "Pauses the tweet rotation when hovering over the twitter bar."
    ),
    
    "Tweet Rotation Delay" => array(
      'type' => 'text',
      'id' => 'twitterbar_delay',
      'default' => 7000,
      'description' => "Amount of seconds to wait before rotating tweets."
    ),
    
    "Tweet Count" => array(
      'type' => 'text',
      'id' => 'twitterbar_tweet_count',
      'default' => 5,
      'description' => "Amount of tweets to retrieve."
    ),
    
    "Twitter Username" => array(
      'type' => 'text',
      'id' => 'twitterbar_username',
      'default' => "",
      'description' => "Username to download tweets from."
    ),
    
    "" => array(
      'type' => 'message',
      'content' => '<strong>Twitter API Authentication Credentials</strong>
<div style="color: #666; font-size: 90%; margin-top: 0.6em;">
<a target="_blank" href="https://dev.twitter.com/docs/api/1.1/overview">Twitter API Version 1.1</a> requires all users to provide authentication credentials when requesting information from their API endpoint.

If you already have created a "Twitter Application" for your website, you can skip the steps below and just paste the credentials in their respective fields.

To get your API Authentication Credentials, do the following:

<ol>
  <li>Sign into your <a target="_blank" href="http://twitter.com">Twitter Account</a></li>
  <li>Go to <a target="_blank" href="http://dev.twitter.com/apps">dev.twitter.com/apps</a></li>
  <li>Click on the <strong>"Create a new Application"</strong> button</li>
  <li>Enter the name of your website in the <strong>"Name"</strong> field. If the name is already taken, use a name that is not taken.</li>
  <li>Enter something in the <strong>"Description"</strong> field.</li>
  <li>Enter your website\'s URL in the <strong>"Website"</strong> field. <u>This is very important</u>, since the API will only work if the requests are made from that URL. (example: http://yoursite.com)</li>
  <li>Agree to the <u>"Developer Rules Of The Road"</u> by checking the box that says <strong>"Yes, I agree"</strong>.</li>
  <li>Enter the right text in the <u>Captcha</u> verification field.</li>
  <li>Click on the <strong>"Create your Twitter Application"</strong> button.</li>
  <li>Once in the application page, click on the <strong>"Create my access token"</strong> button. This will complete the credentials generation.</li>
</ol>

After your credentials are successfully generated, you can proceed to fill in the fields below. if you do not see the credentials under "Your access token". Wait for a few seconds and refresh the page.

</div>'
    ),
    
    "Consumer key" => array(
      'type' => 'text',
      'id' => 'twitter_consumer_key',
      'default' => "",
      'description' => ""
    ),
    
    "Consumer secret" => array(
      'type' => 'text',
      'id' => 'twitter_consumer_secret',
      'default' => "",
      'description' => ""
    ),
    
    "Access token" => array(
      'type' => 'text',
      'id' => 'twitter_access_token',
      'default' => "",
      'description' => ""
    ),
    
    "Access token secret" => array(
      'type' => 'text',
      'id' => 'twitter_access_token_secret',
      'default' => "",
      'description' => ""
    )

  );
  
  /////////////////////////////////////////////
  // FOOTER
  /////////////////////////////////////////////
  
  $theme_options['Footer'] = array(
    
    "icon" => $icons ? "icon-cog" : null,
    
    'Enable Widgets' => array(
      'type' => 'select',
      'id' => 'footer_widgets_enabled',
      'description' => "Enable widgets on the footer.",
      'default' => 'yes',
      'values' => array(
        'yes' => "Yes",
        'no' => "No"
      )
    ),
    
    "Copyright Text" => array(
      'type' => 'code',
      'id' => 'footer_copyright',
      'rows' => 6,
      'description' => "Copyright (or other) text to display on the footer's bottom bar. HTML Allowed.

<strong>Quick Symbols</strong>
&copy; &rarr; &nbsp;<code>&amp;copy;</code>  
&reg; &rarr; &nbsp;<code>&amp;reg;</code>
&trade; &rarr; &nbsp;<code>&amp;trade;</code>"
    ),
    
    "Social Icons" => array(
      'type' => 'select',
      'id' => 'footer_social_icons',
      'default' => 'yes',
      'values' => array(
        'yes' => "Yes",
        'no' => "No"
      ),
      'description' => 'Show social icons on the footer.
    
<a target="_blank" href="?page=social-icons" style="text-decoration: none;"><i class="icon-wrench"></i>&nbsp; Manage Social Icons</a>'
    ),
    
    "Social Icon Tooltips" => array(
      'type' => 'select',
      'id' => 'footer_social_tooltips',
      'default' => 'yes',
      'values' => array(
        'yes' => "Yes",
        'no' => "No"
      ),
      'description' => "Display tooltips on the social icons."
    ),
    

  );
  
  /////////////////////////////////////////////
  // ADVANCED
  /////////////////////////////////////////////

  $theme_options['Advanced'] = array(
    
    "icon" => $icons ? "icon-cogs" : null,
    
    'Thumbnail Resolution' => array(
      'type' => 'select',
      'id' => 'thumb_resolution',
      'description' => "Determines the resolution used for generated thumbnails.
        
You can optimize your site for devices that support double resolution (retina).
      
The thumbnail size may be twice as big as the original thumbnail, which could
directly impact your site's load times.",
      'default' => "1",
      'values' => array(
        '1' => "Standard Definition",
        '2' => "High Definition"
      )
    ),
    
    'HTML5 Video' => array(
      'type' => 'select',
      'id' => 'native_video',
      'description' => 'Determines how to handle HTML5 Video embedding thorought the site.
<br/>The Browser Native option is only effective if the browser has native support for HTML5 Video.
<br/><a class="notd" target="_blank" href="http://en.wikipedia.org/wiki/HTML5_video#Browser_support"><i class="icon-info-sign"></i> Browser video support chart</a>',
      'default' => 'no',
      'values' => array(
        'yes' => "Browser Native",
        'no' => "Meteor Video Player"
      )
    ),
    
    'Custom CSS Code' => array(
      'type' => 'code',
      'id' => 'custom_css',
      'description' => "CSS Code to add to your site.",
      'rows' => 10
    ),
    
    'Custom JavaScript Code' => array(
      'type' => 'code',
      'id' => 'custom_javascript',
      'description' => "JavaScript Code to add to your site.
      
No need to include <code>&lt;script&gt;</code> tags.",
      'rows' => 10
    ),
    
    'Analytics Tracking Code' => array(
      'type' => 'code',
      'id' => 'analytics',
      'rows' => 14,
      'description' => "Analytics tracking code.
      
Paste with <code>&lt;script&gt;</code> tags."
    )
    
  );
  
  /////////////////////////////////////////////
  // MAINTENANCE
  /////////////////////////////////////////////

  $theme_options['Other'] = array(
    
    "icon" => $icons ? "icon-wrench" : null,

    'Check for Updates' => array(
      'type' => 'select',
      'id' => 'update_check_interval',
      'default' => 'weekly',
      'values' => array(
        'hourly' => "Every Hour",
        'daily' => "Every Day",
        'weekly' => "Every Week",
        'monthly' => "Every Month",
        'yearly' => "Every Year",
        'never' => "Never"
      ),
      'description' => 'Recurrence of theme update checks.
        
<a id="refresh-update-cookie" class="notd" href="#reload-cookie" onclick="wpCookies.remove(document.WP_THEME_ID + \'_update_data\', userSettings.url); alert(\'Successfully refreshed update cookie.\'); return false;"><i class="icon-refresh"></i>&nbsp; Refresh update cookie</a>',
    ),

    'ThemeForest Username' => array(
      'type' => 'text',
      'id' => 'tf_username',
      'description' => "Your ThemeForest username.

This is used to generate the <strong>download link</strong> whenever a new update is available.",
      ),
      
    'Meteor Theme Purchase Code' => array(
      'type' => 'text',
      'id' => 'tf_purchase_code',
      'description' => "The theme's <a target='_blank' href='http://support.der-design.com/discussion/1111/how-do-i-get-my-purchase-code-s'>Purchase Code</a>.
      
This is used to generate the <strong>download link</strong> whenever a new update is available.",
      ),
      
    'Import Color Themes' => array(
      'type' => 'textarea',
      'id' => 'colorthemes_data_import',
      'rows' => 8,
      'description' => 'Use this to import your color theme data from a JSON file.
        
Paste the file contents on this field.
      
<a class="notd" href="admin.php?page=color-themes-backup"><i class="icon-save"></i>&nbsp; Download Color Themes Backup</a>',
        'first_empty' => true,
        'default' => ''
      ),

    'Restore Layout Data' => array(
      'type' => 'textarea',
      'id' => 'layout_data_restore',
      'rows' => 8,
      'description' => 'Use this to restore your layout backup data from a JSON file.

Paste the file contents on this field.

<a href="' . admin_url("admin.php?page=layout-editor&backup=1") . '" style="opacity: 1; text-decoration: none;"><i class="icon-save"></i>&nbsp; Download Layouts Backup</a>',
        'first_empty' => true,
        'default' => ''
      ),
      
    );
  

?>