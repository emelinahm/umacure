<?php

  global $der_framework, $theme_options;
  
  /////////////////////////////////////////////
  // GENERAL
  /////////////////////////////////////////////
  
  $theme_options['General'] = array(
    
    // "icon" => "icon-cog",
    
    "Text Color" => array(
      'type' => 'color',
      'id' => 'black',
      'description' => "Text color to use.",
      'default' => "#292929"
    ),
    
    "Background Color" => array(
      'type' => 'color',
      'id' => 'bg',
      'description' => "Background color to use.",
      'default' => "#ffffff"
    ),
    
    "Accent Color" => array(
      'type' => 'color',
      'id' => 'accent',
      'description' => "First accent color.",
      'default' => "#fa5b15"
    ),
    
    "Accent Color 1" => array(
      'type' => 'color',
      'id' => 'accent1',
      'description' => "Second accent color.",
      'default' => ""
    ),
    
    "Accent Color 2" => array(
      'type' => 'color',
      'id' => 'accent2',
      'description' => "Third accent color.",
      'default' => ""
    ),
    
    "Foreground Color Variations" => array(
      'type' => 'message',
      'content' => 'The colors below are variations of the <u>Text Color</u> option, which is defined above. These options are included to allow fine grained control of the color theme. Goes from dark to light.'
    ),
    
    "Foreground Color 1" => array(
      'type' => 'color',
      'id' => 'black1',
      'description' => "Foreground color #1.",
      'default' => ""
    ),
    
    "Foreground Color 2" => array(
      'type' => 'color',
      'id' => 'black2',
      'description' => "Foreground color #2.",
      'default' => ""
    ),
    
    "Foreground Color 3" => array(
      'type' => 'color',
      'id' => 'black3',
      'description' => "Foreground color #3.",
      'default' => ""
    ),
    
    "Foreground Color 4" => array(
      'type' => 'color',
      'id' => 'black4',
      'description' => "Foreground color #4.",
      'default' => ""
    ),
    
    "Foreground Color 5" => array(
      'type' => 'color',
      'id' => 'black5',
      'description' => "Foreground color #5.",
      'default' => ""
    ),
    
    "Foreground Color 6" => array(
      'type' => 'color',
      'id' => 'black6',
      'description' => "Foreground color #6.",
      'default' => ""
    ),
    
    "Foreground Color 7" => array(
      'type' => 'color',
      'id' => 'black7',
      'description' => "Foreground color #7.",
      'default' => ""
    ),

  );
  
  /////////////////////////////////////////////
  // BACKGROUND
  /////////////////////////////////////////////
  
  $theme_options['Background'] = array(
    
    // "icon" => "icon-picture",
    
    "Background Image" => array(
      'type' => 'upload',
      'id' => 'bgImage',
      'description' => "Background Image to use."
    ),
    
    "Background Repeat" => array(
      'type' => 'select',
      'id' => 'bgRepeat',
      'description' => "How to repeat the background image.",
      'default' => 'repeat',
      'values' => array(
        'repeat' => "Repeat",
        'repeat-x' => "Repeat Horizontally",
        'repeat-y' => "Repeat Vertically",
        'no-repeat' => "No Repeat"
      )
    ),
    
    "Background Position" => array(
      'type' => 'select',
      'id' => 'bgPosition',
      'description' => "How to position the background image.",
      'default' => 'center',
      'values' => array(
        'center' => "Center",
        'center left' => "Center Left",
        'center right' => "Center Right",
        'top' => "Top",
        'top left' => "Top Left",
        'top right' => "Top Right",
        'bottom' => "Bottom",
        'bottom left' => "Bottom Left",
        'bottom right' => "Bottom Right"
      )
    ),
    
    "Background Attachment" => array(
      'type' => 'select',
      'id' => 'bgAttachment',
      'description' => "The background image attachment.",
      'default' => 'scroll',
      'values' => array(
        'scroll' => "Scroll",
        'fixed' => "Fixed"
      )
    )

  );
  
  /////////////////////////////////////////////
  // BOXED STYLES
  /////////////////////////////////////////////
  
  $theme_options['Boxed Styles'] = array(
    
    ' ' => array(
      'type' => 'message',
      'content' => '<p style="color: #888;"><strong style="color: #444;">NOTE: </strong> <em>The options below are only effective if the Boxed Layout option is enabled in the <a href="?page=theme-options#general">General Settings</a>.</em></p>'),
    
    "Border Radius (pixels)" => array(
      'type' => 'text',
      'id' => 'boxedRadius',
      'description' => "Radius to apply to the box wrapper.",
      'default' => 0
    ),
    
    "Vertical Margin (pixels)" => array(
      'type' => 'text',
      'id' => 'boxedMargin',
      'description' => "Amount of top/bottom margin to add to the box wrapper.",
      'default' => 50
    ),
    
    "Drop Shadow Spread (pixels)" => array(
      'type' => 'text',
      'id' => 'boxedBlurAmount',
      'description' => "Amount of pixels in which to spread the shadow of the box wrapper.",
      'default' => 4
    ),
    
    "Background Color" => array(
      'type' => 'color',
      'id' => 'boxedBg',
      'description' => "Background Color to use for the box wrapper.",
      'default' => ""
    ),
    
    "Shadow Color" => array(
      'type' => 'color',
      'id' => 'boxedShadowColor',
      'description' => "Shadow color to use for the box wrapper's drop shadow.",
      'default' => ""
    ),
    
    "Shadow Intensity (%)" => array(
      'type' => 'text',
      'id' => 'boxedShadowIntensity',
      'description' => "Intensity to use for the box wrapper's drop shadow.",
      'default' => 7
    ),
    
    "Border Color" => array(
      'type' => 'color',
      'id' => 'boxedBorderColor',
      'description' => "Border Color to use for the box wrapper.",
      'default' => ""
    ),
    
    "Border Color Intensity (%)" => array(
      'type' => 'text',
      'id' => 'boxedBorderIntensity',
      'description' => "Intensity to apply to the box wrapper's border color.",
      'default' => "10.5"
    ),
    
    // --------------- BACKGROUND IMAGE SECTION OF BOXED LAYOUT ---------------
    
    "Background Image" => array(
      'type' => 'upload',
      'id' => 'boxedBgImage',
      'description' => "Background Image to use."
    ),
    
    "Background Repeat" => array(
      'type' => 'select',
      'id' => 'boxedBgRepeat',
      'description' => "How to repeat the background image.",
      'default' => 'repeat',
      'values' => array(
        'repeat' => "Repeat",
        'repeat-x' => "Repeat Horizontally",
        'repeat-y' => "Repeat Vertically",
        'no-repeat' => "No Repeat"
      )
    ),
    
    "Background Position" => array(
      'type' => 'select',
      'id' => 'boxedBgPosition',
      'description' => "How to position the background image.",
      'default' => 'center',
      'values' => array(
        'center' => "Center",
        'center left' => "Center Left",
        'center right' => "Center Right",
        'top' => "Top",
        'top left' => "Top Left",
        'top right' => "Top Right",
        'bottom' => "Bottom",
        'bottom left' => "Bottom Left",
        'bottom right' => "Bottom Right"
      )
    )

  );
  
  /////////////////////////////////////////////
  // HEADER
  /////////////////////////////////////////////
  
    $theme_options['Header'] = array(
      
    "Social Icons Color" => array(
      'type' => 'select',
      'id' => 'header_social_icons_color',
      'description' => "Color to use for the social icons.",
      'default' => 'black',
      'values' => array(
        'white' => "Light",
        'black' => "Dark"
      )
    ),
  
    "Background Color" => array(
      'type' => 'color',
      'id' => 'headerBgColor',
      'description' => "Background color to use.",
      'default' => ""
    ),
  
    "Background Image" => array(
      'type' => 'upload',
      'id' => 'headerBgImage',
      'description' => "Background Image to use."
    ),
  
    "Background Repeat" => array(
      'type' => 'select',
      'id' => 'headerBgRepeat',
      'description' => "How to repeat the background image.",
      'default' => 'repeat',
      'values' => array(
        'repeat' => "Repeat",
        'repeat-x' => "Repeat Horizontally",
        'repeat-y' => "Repeat Vertically",
        'no-repeat' => "No Repeat"
      )
    ),
  
    "Background Position" => array(
      'type' => 'select',
      'id' => 'headerBgPosition',
      'description' => "How to position the background image.",
      'default' => 'center',
      'values' => array(
        'center' => "Center",
        'center left' => "Center Left",
        'center right' => "Center Right",
        'top' => "Top",
        'top left' => "Top Left",
        'top right' => "Top Right",
        'bottom' => "Bottom",
        'bottom left' => "Bottom Left",
        'bottom right' => "Bottom Right"
      )
    )
        
  );
  
  /////////////////////////////////////////////
  // NAVIGATION
  /////////////////////////////////////////////

  $theme_options['Navigation'] = array(
    
    "Navigation Color" => array(
      'type' => 'color',
      'id' => 'navigationColor',
      'description' => "Color to use for the navigation.",
      'default' => ""
    ),
    
    "Navigation Hover Color" => array(
      'type' => 'color',
      'id' => 'navigationHoverColor',
      'description' => "Hover color to use for the navigation.",
      'default' => ""
    ),
    
    "Navigation Active Color" => array(
      'type' => 'color',
      'id' => 'navActiveColor',
      'description' => "Active color for the navigation menu items.",
      'default' => ""
    ),
    
    "Navigation Background Color" => array(
      'type' => 'color',
      'id' => 'navBg',
      'description' => "Background color to use for the navigation.",
      'default' => ""
    ),
    
    "Navigation Text Shadow Color" => array(
      'type' => 'color',
      'id' => 'headerTextShadowColor',
      'description' => "Text shadow color to use for the navigation menu items",
      'default' => ""
    ),
    
    "Navigation Text Shadow Color Intensity (%)" => array(
      'type' => 'text',
      'id' => 'headerTextShadowIntensity',
      'description' => "Text Shadow Intensity for the navigation menu items",
      'default' => "80"
    ),
    
    "Dropdown Menu Opacity" => array(
      'type' => 'text',
      'id' => 'navOpacity',
      'description' => "Opacity to apply to the dropdown menus.",
      'default' => "0.98"
    ),
    
    "Dropdown Menu Border Color" => array(
      'type' => 'color',
      'id' => 'navBorderColor',
      'description' => "Border color to use for the dropdowns.",
      'default' => ""
    ),
    
    "Dropdown Menu Shadow Color" => array(
      'type' => 'color',
      'id' => 'navDropShadowColor',
      'description' => "Dropdown menu shadow color.",
      'default' => ""
    ),
    
    "Dropdown Items Color" => array(
      'type' => 'color',
      'id' => 'navLinkColor',
      'description' => "Color to use for the dropdown menu items.",
      'default' => ""
    ),
    
    "Dropdown Items Hover Color" => array(
      'type' => 'color',
      'id' => 'navLinkHoverColor',
      'description' => "Hover color to use for the dropdown menu items.",
      'default' => ""
    ),
    
    "Dropdown Items Hover Background" => array(
      'type' => 'color',
      'id' => 'navLinkHoverBackground',
      'description' => "Background color to use when hovering dropdown items.",
      'default' => ""
    ),
    
    "Dropdown Items Separator Color 1" => array(
      'type' => 'color',
      'id' => 'navSeparatorColor',
      'description' => "Color to use for the dropdown item separator 1.",
      'default' => ""
    ),
    
    "Dropdown Items Separator Color 2" => array(
      'type' => 'color',
      'id' => 'navDarkSeparatorColor',
      'description' => "Color to use for the dropdown item separator 2.",
      'default' => ""
    ),
    
    "Dropdown Items Text Shadow Color" => array(
      'type' => 'color',
      'id' => 'navTextShadowColorBase',
      'description' => "Color to use for the dropdown items text shadow.",
      'default' => ""
    )
    
  );
  
  
  /////////////////////////////////////////////
  // SECTION TITLE
  /////////////////////////////////////////////
  
  $theme_options['Section Title'] = array(
    
    "Background Color" => array(
      'type' => 'color',
      'id' => 'sectionTitleBg',
      'description' => "Color to use for the section title background.",
      'default' => ""
    ),
    
    "Gradient Intensity (%)" => array(
      'type' => 'text',
      'id' => 'sectionTitleGradientIntensity',
      'description' => "This value determines how intense is the section title's gradient.",
      'default' => "3"
    ),
    
    "Border Color" => array(
      'type' => 'color',
      'id' => 'sectionTitleBorderColor',
      'description' => "Color to use for the section title's border.",
      'default' => ""
    ),

    "Inner Shadow Color" => array(
      'type' => 'color',
      'id' => 'sectionTitleShadow',
      'description' => "Color to use for the section title's inner shadow.",
      'default' => ""
    ),
    
    "Shadow Intensity (%)" => array(
      'type' => 'text',
      'id' => 'sectionTitleShadowIntensity',
      'description' => "This value determines how intense is the section title's shadow.",
      'default' => "6"
    ),
 
    "Text Shadow Color" => array(
      'type' => 'color',
      'id' => 'sectionTitleTextShadow',
      'description' => "Shadow color to use for the section title text",
      'default' => ""
    ),
    
    "Text Shadow Intensity (%)" => array(
      'type' => 'text',
      'id' => 'sectionTitleTextShadowIntensity',
      'description' => "This value determines how intense is the section title's text shadow.",
      'default' => "90"
    ),

    "Main Heading Color" => array(
      'type' => 'color',
      'id' => 'sectionTitleHeadingColor',
      'description' => "Color to use for the section title's main heading.",
      'default' => ""
    ),

    "Description Text Color" => array(
      'type' => 'color',
      'id' => 'sectionTitleTextColor',
      'description' => "Color to use for the section title's description text.",
      'default' => ""
    ),

    "Link Hover Color " => array(
      'type' => 'color',
      'id' => 'sectionTitleLinkHoverColor',
      'description' => "Hover color to use for the section title links.",
      'default' => ""
    )
    
  );
  
  /////////////////////////////////////////////
  // TWITTER BAR
  /////////////////////////////////////////////

  $theme_options['Twitter Bar'] = array(
    
    "Background Color" => array(
      'type' => 'color',
      'id' => 'twitterBarBackground',
      'description' => "Background color to use for the twitter bar.",
      'default' => ""
    ),
  
    "Inner Shadow Color" => array(
      'type' => 'color',
      'id' => 'twitterBarInnerShadowColor',
      'description' => "The color to use for the twitter bar's inner shadow.",
      'default' => ""
    ),
  
    "Inner Shadow Color Intensity (%)" => array(
      'type' => 'text',
      'id' => 'twitterBarInnerShadowColorIntensity',
      'description' => "The intensity to use for the twitter bar's inner shadow.",
      'default' => "9"
    ),
    
    "Nav Buttons Background Color" => array(
      'type' => 'color',
      'id' => 'twitterBarNavBackground',
      'description' => "Color to use for the twitter bar's navigation buttons.",
      'default' => ""
    ),
    
    "Nav Buttons Color" => array(
      'type' => 'color',
      'id' => 'twitterBarNavColor',
      'description' => "Color to use for the twitter bar's navigation buttons.",
      'default' => ""
    ),
  
    "Nav Buttons Hover Color" => array(
      'type' => 'color',
      'id' => 'twitterBarNavHoverColor',
      'description' => "Color to use for the navigation buttons when hovering.",
      'default' => ""
    ),
  
    "Twitter Icon Color" => array(
      'type' => 'color',
      'id' => 'twitterBarIconColor',
      'description' => "Color to use for the twitter icon.",
      'default' => ""
    ),
  
    "Text Color" => array(
      'type' => 'color',
      'id' => 'twitterBarTextColor',
      'description' => "Text color to use for the twitter bar.",
      'default' => ""
    ),
  
    "Text Shadow Color" => array(
      'type' => 'color',
      'id' => 'twitterBarTextShadowColor',
      'description' => "Text shadow color to use for the tiwtter bar.",
      'default' => ""
    ),
  
    "Text Shadow Intensity (%)" => array(
      'type' => 'text',
      'id' => 'twitterBarTextShadowIntensity',
      'description' => "Intensity to use for the twitter bar's text shadow.",
      'default' => "100"
    ),
  
    "Link Color" => array(
      'type' => 'color',
      'id' => 'twitterBarLinkColor',
      'description' => "Color to use for the twitter bar links.",
      'default' => ""
    ),
  
    "Link Hover Color" => array(
      'type' => 'color',
      'id' => 'twitterBarLinkHoverColor',
      'description' => "Color to use for the twitter bar links on hover.",
      'default' => ""
    ),
    
    "Spinner Color" => array(
      'type' => 'color',
      'id' => 'twitterBarSpinnerColor',
      'description' => "Color to use for the twitter bar's loading spinner.",
      'default' => ""
    )
    
  );
  
  /////////////////////////////////////////////
  // FOOTER
  /////////////////////////////////////////////

  $theme_options['Footer'] = array(
    
    // "icon" => "icon-cog",
    
    "Social Icons Color" => array(
      'type' => 'select',
      'id' => 'footer_social_icons_color',
      'description' => "Color to use for the social icons.",
      'default' => 'white',
      'values' => array(
        'white' => "Light",
        'black' => "Dark"
      )
    ),
    
    "Background Color" => array(
      'type' => 'color',
      'id' => 'footerBgColor',
      'description' => "Background color to use for the footer.",
      'default' => ""
    ),
    
    "Text Color" => array(
      'type' => 'color',
      'id' => 'footerTextColor',
      'description' => "Background color to use for the footer's text.",
      'default' => ""
    ),
    
    "Text Shadow Color" => array(
      'type' => 'color',
      'id' => 'footerTextShadowColorBase',
      'description' => "The color to use for the footer's text shadow.",
      'default' => ""
    ),
    
    "Widget Title Color" => array(
      'type' => 'color',
      'id' => 'footerWidgetTitleColor',
      'description' => "Color .",
      'default' => ""
    ),
    
    "Link Color" => array(
      'type' => 'color',
      'id' => 'footerLinkColor',
      'description' => "Color to use for the footer links.",
      'default' => ""
    ),
    
    "Link Hover Color" => array(
      'type' => 'color',
      'id' => 'footerLinkHoverColorBase',
      'description' => "Color to use when hovering the footer links.",
      'default' => ""
    ),
    
    "Bottom Bar Background" => array(
      'type' => 'color',
      'id' => 'footerBottomBarBg',
      'description' => "Background color to use for the footer's bottom bar.",
      'default' => ""
    ),
    
    "Bottom Bar Border Color" => array(
      'type' => 'color',
      'id' => 'footerBottomBarBorderColor',
      'description' => "Color to use for the bottom bar's top border color.",
      'default' => ""
    ),
    
    "Background Image" => array(
      'type' => 'upload',
      'id' => 'footerBgImage',
      'description' => "Background Image to use."
    ),
  
    "Background Repeat" => array(
      'type' => 'select',
      'id' => 'footerBgRepeat',
      'description' => "How to repeat the background image.",
      'default' => 'repeat',
      'values' => array(
        'repeat' => "Repeat",
        'repeat-x' => "Repeat Horizontally",
        'repeat-y' => "Repeat Vertically",
        'no-repeat' => "No Repeat"
      )
    ),
  
    "Background Position" => array(
      'type' => 'select',
      'id' => 'footerBgPosition',
      'description' => "How to position the background image.",
      'default' => 'center',
      'values' => array(
        'center' => "Center",
        'center left' => "Center Left",
        'center right' => "Center Right",
        'top' => "Top",
        'top left' => "Top Left",
        'top right' => "Top Right",
        'bottom' => "Bottom",
        'bottom left' => "Bottom Left",
        'bottom right' => "Bottom Right"
      )
    )
    
  );
  
  
  /////////////////////////////////////////////
  // OTHER
  /////////////////////////////////////////////
  
  $theme_options['Other'] = array(
    
    // "icon" => "icon-cog",
    
    "Share Icons Color" => array(
      'type' => 'select',
      'id' => 'share_icons_color',
      'description' => "Color to use for the sharing icons on the single post view, below the content.",
      'default' => 'black',
      'values' => array(
        'white' => "Light",
        'black' => "Dark"
      )
    )

  );

?>