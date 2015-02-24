=== Wordbooker ===

Contributors: SteveAtty
Tags: facebook, news feed, wall, fan page wall, group wall, crosspost, Facebook Send, Facebook Like, social media, open graph.
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=8XNJMQCYDJT6U&lc=GB&currency_code=GBP&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Requires at least: 3.0
Tested up to: 3.8.1
Stable tag: 2.2.1

This plugin allows you to cross-post your blog posts to your Facebook Wall and to any Facebook Fan Page / Group that you are an administrator for.

== Description ==

This plugin allows you to cross-post your blog posts to your Facebook Wall / Fan Page Wall / Group Wall. You can Post as an Extract, A Status Update or even as a Note.

Wordbooker populates all the Open Graph tags needed to integrate your blog post with Facebook's Social Graph.

NOTE : You MUST have the PHP Curl module enabled and configured in such a way that it can connect to the Facebook Servers on a secure HTTP connection. If you do not have curl OR if your hosting company block curl access to externals sites you cannot use this plugin.


== IMPORTANT ==

When ever you upgrade Wordbooker you should visit the Wordbooker Options Screen to make sure that any configuration changes are applied correctly. Failure to do so may make Wordbooker function in unexpected ways.


== Upgrading Wordbooker from Version 2.1.x ==

Upgrading from 2.1.x to 2.2 requires no special actions.


== Installation ==

1. [Download] (http://wordpress.org/extend/plugins/wordbooker/) the latest version of Wordbooker.
1. Unzip the ZIP file.
1. Upload the `wordbooker` directory to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress. Admins of Networked Blogs can active the plugin network wide.
1. Navigate to `Options` &rarr; `Wordbooker` for configuration and follow the on-screen prompts.


== Features ==

For more details on the various features please read the additional Features.txt file or check the [wordbooker](http://blogs.canalplan.org.uk/steve/category/wordbooker/) category on my blog which will contain information on the current and planned features list.

- Works with a complementary [Facebook application](http://www.facebook.com/apps/application.php?id=254577506873) to update your Facebook Wall and friends' News Feeds about your blog and page postings. Does NOT require you to register your own Facebook Application.
- Supports multi-author blogs: each blog author notifies only their own friends of their blog/page posts.
- Features a sidebar widget to display your current Facebook Status and picture. Multiple widgets can be supported in one single blog.
- Features a sidebar widget to display a "Fan"/Like box for any of your pages. Multiple widgets can be supported in one single blog.
- Features a Facebook Like/Send Button which can be customised as to where it appears in your blog.
- Supports the posting of blog posts to Fan Pages and Groups (if you are an administrator of that page or group).
- Pulls and pushes comments between your Blog Posts and the corresponsding Facebook Wall Post.


== Frequently Asked Questions ==

= Isn't Wordbooker the same as importing my blog posts into Facebook Notes? =

It is certainly similar, but not the same:

- Facebook Notes imports and caches your entire blog post - Wordbooker uses the Facebook API to actively update your Facebook Wall just as if you had posted an update yourself on facebook.com. It also means that you can make changes to your blog postings *after* initially publishing them.

- With Wordbooker, your blog postings will have their own space in your Facebook Wall - just as if you'd written directly on to the wall yourself.

- Your updates will show up with a Worbooker logo next to them instead of the normal "Notes" icon, plus a link back to the full entry on your blog.


= Why doesn't the Facebook Like /Send  show up properly even though I've enabled it?

You may need to add the following to the HMTL tag in your theme : xmlns:fb="http://www.facebook.com/2008/fbml".
So it looks something like :  <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:fb="http://www.facebook.com/2008/fbml">


= Why aren't my blog posts showing up in Facebook? =

- Wordbooker will not publish password-protected posts.

- Any errors Wordbooker encounters while communicating with Facebook will be recorded in error logs; the error logs (if any) are viewable in the "Wordbooker" panel of the "Options" WordPress admin page.



= My WordPress database doesn't use the default 'wp_' table prefix. Will this plugin still work? =

Yes, and its also  WP Networked Blogs mode compliant.


= How do I completely reset my Wordbooker/WordPress configuration so I can start over from scratch? =

1. Click the "Reset configuration" button in the "Wordbooker" panel of the "Options" WordPress admin page.
1. Deactivate the Wordbook plugin from your WordPress installation.
1. [Uninstall Wordbooker](http://www.facebook.com/apps/application.php?id=254577506873) from your Facebook account.
1. Download the [latest version](http://wordpress.org/extend/plugins/wordbooker/)
1. Re-install and re-activate the plugin.


= What is the Enable Extended description for Share Link option do? =

If you're using the Share action link on your posts to Facebook it uses the META DESCRIPTION tag to extract something from your post. If you dont have an SEO system which populates this, or if you dont usally use post excerpts then selecting this option populates the tag with the first couple hundred characters of your post which gives a nice block of text in the post that will appear when people share your post on their wall.


= How do I report problems or submit feature requests? =

- Use the [Wordbooker Support Forums](http://wordbooker.tty.org.uk/forums/). Either start a new topic, or add to an existing topic. Please don't post any issue you have onto an existing thread unless you are experiencing the same problem.


== KNOWN CONFLICTS ==

There will be conflicts with other plugins providing Facebook Like/Send Share functionality



== Screenshots ==

1. Wordbooker Options/Configuration : Blog Level options
1. Wordbooker Options/Configuration : User Level options
2. Wordbooker Options : Overrides when posting


== Changelog ==

= Version 2.2.1 25/01/14 =
- Confirmed Wordpress 3.8.1 compatible
- Fixes Fatal error when using One of the template functions
- Fixes problem with Big Facebook IDs getting truncated on 32 bit systems
- Adds a 32/64 bit diagnostic
- Adds some extra authentication handshaking diagnostics.


= Version 2.2.0 27/12/13 =
- Base release of Version 2.2
- Confirmed Wordpress 3.8 compatible
- Adds Support for controlling posts to Facebook using custom Post Types and Categories
- Adds Support for controlling posts to Facebook using Tags
- Adds Support for WP_HTTP for Facebook API calls
- Adds Support for appsecret_proof calls to Facebook
- Follows FB privacy for retrieved Status Messages (Only Public Status updates will be used to update Wordbooker.)
- Adds support for new the Facebook Share Button
- Better handling of Short URLs for posted urls - with new Advanced Option to support this
- Adds support for shipping of Access tokens between Wordbooker Installs
- Adds easy linking to Facebook Friend's profiles
- Rationalisation of code into sub blocks with controlled loading.


= Version 2.1.40 03/11/13 =
- Fixed bug in the FB API initialization logic which meant it ran when it shouldn't have done


= Version 2.1.39 02/11/13 =
- Added a Do Not Log option for diagnostic logging - this turns logging OFF totally
- Added checks for FB "not usable" urls - such as localhost, private IP address ranges etc.
- Confirmed Wordpress 3.7.1 compatibility


= Version 2.1.38 27/10/13 =
- Fixed an obscure bug in the social widget
- Confirmed Wordpress 3.7 compatibility

= Version 2.1.37 06/10/13 =
- Changes to comment handling to avoid problems with anti-spam traps
- Backport of Version checking process from 2.2.0
- Change to og tag generation to fix some errors under certain circumstances


= Version 2.1.36 14/09/13 =
- Confirmed Wordpress 3.6.1 compatible
- Fixed hard coded link in the German Language File
- Added support for Canalplan's two new shortcode tags
- Some minor work removing php warnings


= Version 2.1.35 03/08/13 =
- Confirmed Wordpress 3.6 compatible
- Changed some of the comment diagnostic message levels to hide them unless diagnostic level set to high.
- Added multisite checks so that most support information is hidden from non Super Admin users.
- Fixed the Like Box, and removed groups from available like targets.
- Added stable version check.
- Fixed bug where some diagnostic messages went into the wrong display category.
- Recoded method for pulling pages and groups in the cron to use graph API rather than FQL.
- Added code to list Network Activated plugins to the plugin list.
- Fixed code to stop warning being reported by PHP 5.4
- Added checks to stop Facebook related calls from running where permissions or access tokens are incorrect.


= Version 2.1.34 20/07/13 =
- Comment handling bug fix relating to dates.
- Fix bug relating to PHP installs without multibyte character support
- Fix bug where group wall IDs were lost
- Updated shortcode list to handle a couple more plugins that don't support the shortcode API.


= Version 2.1.33 09/07/13 =
- Confirmed Wordpress 3.5.2 compliant
- Fixed comment diagnostic log clearing problem.
- Changes to cron to rely less on FQL
- Added support for The Wordpress Videos Plugin
- Changed YouTube thumbnail logic


= Version 2.1.32 29/05/13 =
- Fixed a bug in the token auth swapping code.


= Version 2.1.31 18/05/13 =
- Fix for quotes being mangled on some installs
- Adjustment to comment handling to hopefully avoid being tripped up by spam checkers
- Added option to use Post Excerpt as the Post Attribute
- Adjustment to the Tag Stripping and Content Parsing to better support the Canalplan AC Plugin.
- Rebuild Facebook Authorise Button to work round undocumented removal of support of custom button by Facebook
- Added up to date fb_ca_chain_bundle.crt file
- Backported Session checking from 2.2
- Change to Comment handling to handle FB returning different Comment IDs to the ones they actually store.


= Version 2.1.30 01/04/13 =
- Schema creation code fixed so that some installs don't explode with a rogue Fatal Error
- Added a Valid Access token check to hopefully reduce the number of errors being reported by the FB API.
- Changed the Token Swapping process to take a server out of the loop which should help fix problems with some oddly configured hosts
- Added a HTML entity decode to cope with some Wordpress installs that do funky things with html entities in Post Titles.


= Version 2.1.29 12/02/13 =
- Experimental code for Read All Link escaped into the wild. This release rolls that change back


= Version 2.1.28 10/02/13 =
- Fixed a problem with comment handing on posts pushed to FB when comments are closed
- Fixed some more issues with user_meta calls.


= Version 2.1.27 09/02/13 =
- Added a check for Table Storage. FULLTEXT indexes don't work on InnoDB tables.
- Fixed a problem with user settings getting lost
- Fixed a problem where user settings were lost on new posts.


= Version 2.1.26 09/02/13 =
- Confirmed to work with Wordpress 3.5.1
-- Changes to the schema create code to better report problems
- Recoded Schema check/update code to make it a lot more resilient
- Changed image grabbing code so it works with images in single and double quotes. This Might break systems that use quotes in filenames but it means imported images from things like Google Work.
- Put in code to remove arrays when we expect strings
- Suppressed some diagnostics messages being sent when not expected
- Replaced the code to remove JetPacks og tags when Wordbookers are being used.
- Lots of minor niggling bugs cleared up.


= Version 2.1.25 05/01/13 =
- Confirmed to work with Wordpress 3.5
- Fixed a bug in the custom image meta tag handling
- Fixed a problem with a warning about parameters not being arrays.
- Added a check condition for missing Status Messages
- fixed post variable references in wordbooker_return_images
- Added a check in the comment avatar code to catch screwed comment structures
- Fixed an array index typo.
- Removed two calls to mysql_query which has been obsoleted.
- Recoded date cut off logic in the comment handling processes to use calculated timestamps rather than built in functions.
- Added some checks to stop spurious warnings being produced for a couple of no-initialised array entries


= Version 2.1.24 10/11/12 =
- Fixed a bug where setting Meta Tag length to 0 got rid of the og: description tag
- Added code to turn off Jetpacks open graph tags if you are using Wordbookers.


= Version 2.1.23 27/10/2012 =
- Fix problem with Editors publishing where designated wordbooker settings were being lost.
- Minor change to refresh_token call again.
- Add index on post_comments table.
- Fix problem in DB upgrade process so changes roll forward properly.
- Added option to control comment pulling pushing cut off dates rather than relying on the blog setting.
- Recoded a couple of the comment handling queries to make things tighter.
- Fixed a bug in the comment handling process which produced odd results on some installations.
- Removed a pile of redundant diagnostic messages.
- Fixed a problem with a couple of diagnostic messages during comment handling erroring.
- Fix a missing function relating to comment deletion.
- Fix an obscure problem with some installs failing with a unexpected T_VARIABLE error
- Removed the "None" Option from the Action Links. Changed "Share" to use the built in FB Share option


= Version 2.1.22  05/10/2012 =
- Changes to refresh_token calls to reduce load on server.
- Add wrapper round curl_version to stop errors blowing things up
- Added new image size for og:tags so all new images will have a specific image for wordbooker og tags.
- Change og image code to use the new size, or fail back to large if missing.


= Version 2.1.21  03/10/2012 =
- Two new fields that should have been NULL weren't so data failed to get inserted into a table.


= Version 2.1.20  02/10/2012 =
- DB changes needed to support new feature not included in create script so new installs would mis-behave.
- Remove a totally obsolete function.


= Version 2.1.19  29/09/2012 =
- Fix an issue with gravatars not displaying properly when there is no gravatar associated with the commenter
- Put in a check for Attachment post types
- Fixed a problem where invalid but not null Facebook IDs broke the cron job.
- Fixed a prob where sometimes user settings were lost on scheduling a post.
- Minor change to the refresh token process to reduce load on the server.
- Added code to delete records from Wordbooker Comments table when a comment is deleted from the blog.
- Changes to the Status Fetch to reduce incidents of incorrect status messages being posted.
- Added option to Comment Admin page to remove a comment entry from the Wordbooker Comments table.


= Version 2.1.18  03/09/2012 =
- Rollback some changes in the curl include file which were from 2.2


= Version 2.1.17  02/09/2012 =
- Changes to the IPv6 options to curl - option is disabled unless IPv6 available in curl
- Added IPv6 Fail back to IPv4 for DNS resolution in Advanced Options section
- Replaced two backticks with quotes to fix problems with Use Site URL not Blog Description in Wall Posts
- Changed minimum number of FB comments from 2 to 0.


= Version 2.1.16  02/09/2012 =
- Lost in SVN.


= Version 2.1.15 25/08/2012 =
- Changes to the PayPal code to use different servers which should speed up load times.
- Added response timing code to the Curl checker to help diagnose performance problems.
- Added a Curl Version diagnostic line
- Added option to Curl initialisation to hopefully make IPv6 enabled servers run faster.
- Changed the way the comment and cache refresh jobs are scheduled to fix potential problems.
- Changed the Avatar handling code to handle Google+ Comments.
- Removed stray debug command which was confusing.


= Version 2.1.14 30/07/2012 =
- Fixes a problem with Curl detection caused by FB changing things without telling people.


= Version 2.1.13 09/06/2012 =
- Fixed a bug where the Schema 4 code was messing up the schema 5 changes
- Fixed a huge bug in how posting as a non Wordbooker configured user was working.
- Fixed problems with Facebook rejecting large images by forcing the plugin to use the medium sized image if it could find it.
- Limit caption field to 900 characters.. not that it should ever be that big.
- Added support for translation of post title and post caption.
- Added a check for undefined.undefined in shortened urls
- Added support for translation of post attribute
- Added a check to stop All in One Calendar events from taking the blog out due to a bug in the calendar code.
- Removed the call to delete the stored session when the token is killed by Facebook. This stops the Status widgets from failing.
- Added Japanese language files - Thanks to 田中昌平(Shohei Tanaka)


= Version 2.1.12 24/05/2012 =
- Fixed up another http reference that was upsetting secure Wordpress installs
- Recoded the Access swapping code which handles json_decode differences when handling bare strings
- Added the extended extra length values to the User Level Options.
- Added akismet_result meta to allow FB comments to bypass Akismet checking - Thanks to Stefan Jacobi (Again!)
- Recoded the Opions filling code in the Quick Edit block as it wasn't working - now uses the same logic as the block on the Edit/Add post page.
- Added sequence check to Schema Cross check code.
- Changed some of the diagnostic post levels to make things look better.
- Added some more debugs to the comment handling process - to try to work out why some people have problems
- Added an option to allow users to select if access token swapping should be logged.
- Added an option to allow users to select if separate admin/user comment handling messages should be logged.
- Changed width of several FB related columns to handle unexpectedly long strings being returned from Facebook.
- Recoded the Login button so that it can use customised text for different languages.
- Added option to allow the current logged in user to override the publish options set by the original post author
- Added latest CA Certs file from the Facebook PHP SDK
- Fixed a typo in the "Delete on success" logic
- Added an ltrim to remove leading @ signs off the Name field to work round FB Bug : https://developers.facebook.com/bugs/404203109611190
- Recoded access swapping process to remove application information.
- Recoded the fql query calls to fix an apparent change in FB call structures.
- Added better diagnostics to thumbnail/custom image postmeta tags.
- Fixed a bug where images in scheduled posts were not processed.



= Version 2.1.11 06/05/2012 =
- Added some more diagnostics to the token exchange code to try to work out what is going on when its too short
- Put in a patch so that if the token update fails it uses the existing token - this is a dirty fix but gives me time to work on a proper fix.
- Put some null post_ID checking in to get round Wordpress returning ALL assets in a blog when you pass a null ID to it which blew up blogs when you went to add a post.
- Changed some HTTP references to HTTPS to hopefully stop warnings when running blogs on HTTPS front ends.
- Fixed a typo in the auto approve comment variable check. Thanks to Stefan Jacobi
- Tidied up the schema cross check code and moved it into its own function. Part of longer term plan to strip a lot of the "non core" code out of the main wordbooker file.



= Version 2.1.10 04/05/2012 =
- Recoded the token renewal code to handle Facebook returning null values
- Added jpe in the list of recognised image files.


= Version 2.1.9 02/05/2012 =
- Fixed a bug where posts made via Postie didn't pick up the right user ID. This might affect quite a few things.
- Added a schema cross check utility.
- Added a Disabled FB API initialization option to stop clashes with other FB related plugins
- Added code so that simple facebook connect will pick up the locale variable from Wordbooker
- Removed Facebook Share as the functionality is depreciated
- Added an align Left/Right for Like/Send
- Removed some parameters and tweaked others to make the fb like iframes more W3C compliant.
- Added some code to try to work out why token swapping isn't always working.
- Put a check for missing access tokens before calling the publish functions - should speed things up and reduce the number of errors being bounced back from Facebook.
- Changed the code so that blog urls are set rather than just letting Facebook work it out.
- Added code to pull images from post attachments as well as parsing the post
- Added code so that og tags for image and content which are static values and not parsed at post view are parsed when the post is saved, even if its not published. This means that they can be changed.
- Added some checks to make sure that primary and secondary target types are set even if the user fails to set them.
- Added some very large values to the extract length to allow people to post long posts on their wall without using Facebook Notes.
- Changed the og schema insertion code to try to resolve W3C compliance issues.


= Version 2.1.8 23/02/2012 =
- Roll back the FB javascript to an earlier version which doesn't seem to have the problems that the current code does.
- Recode the DB upgrade script to fix some odd problems.
- Remove some redundant, for now, timeline action permissions.

= Version 2.1.7 23/02/2012 =
- Fix a typo in the FB javascript code which only seems to affect Safari.

= Version 2.1.6 22/02/2012 =
- Total embarassment : I somehow rolled a version with a function call in it that shouldn't have been there


= Version 2.1.5 21/02/2012 =
- Coded round the fact that get_users is a WP>3.0 function. So users on WP<3.1 dont get the option to chose the admin user for diagnostic messages
- Added extra diagnostic for comments that have already ben pulled from Facebook
- Reviewed and ratified all the diagnostic message levels.
- Fixed an obscure bug where the FB Comments block didn't show if you turned off Wordbooker's FB Like and Share buttons.
- Change image handling code so that filenames with single quotes in them don't blow things up. As Wordpress uses " " round image URLs this should be OK.
- Fixed a typo in the user guide - all the short tags had [[ ]] rather than [ ]
- Disabled the frictionless sharing option as it was doing some very odd things - like even though I was using an APP access token it was filling MY timeline up.
- Removed a duplicated xmlns tag.


= Version 2.1.4 16/02/2012 =
- Rebuild for SVN after WP restored the plugin and messed things up


= Version 2.1.3 16/02/2012 =
- Rebuild for SVN after WP restored the plugin and messed things up


= Version 2.1.2 16/02/2012 =
- Lost in SVN after WP restored the plugin and messed things up


= Version 2.1.1 16/02/2012 =
- Removed the Thanks section to keep Wordpress mavens happy
- Recoded the strip_images function to hopefully get rid of the phantom url problem (urls with no image on the end of them).
- Fixed a bug in the comment inport/export where the check for disabling incoming comments used the outgoing comment disabled flag!


= Version 2.1.0 15/02/2012 =
- Added comment handling - too many individual changes to comment
- Added a function to delete Wordbooker data when a post is deleted.
- Fixed a long standing bug relating to the og:image tag when posts have no image
- Fixed a long standing bug relating to the og:locale tag - this is now populated with the Wordpress Language value.
- Removed lots of old obsolete commented out debug code.
- Fixed a bug in the logic that identifies malformed image urls.
- Added option to use blog url or blog tag line under the post title in wall posts
- Added New Post Type - "Share" to the list of available post types.
- Fixed a bug with post settings being lost when posts were scheduled
- Fixed a bug where scheduled post diagnostics got recorded against the wrong user.
- Added an option to allow the user id that "admin" diagnostic messages should be recorded against
- Added code to remove wordbooker_channel.php - a file that was added but never used because FB never proved its worth.
- Added a feature to allow Facebook User images to replace Gravatars where a FB URL is detected.
- Added PHP Memory limit line to the support information block
- Added Curl SSL Timeout lines to try to make Wordbooker more reslient to lousy Facebook API performance


= Version 2.0.9 12/12/2011 =
- Finally fixed (I hope) the blank image problem for posts with no obvious image.
- Fixed a bug relating to scraping when og tag production is turned off.
- Added Russian Language files (Thanks Филипп Борисов for the hard work)
- Recoded the cron job to reduce FQL load.
- Checked for compatability with Wordpress 3.3


= Version 2.0.8 19/11/2011 =
- Commented out a debug statement that got left behind.
- Added table prefix line to the support information - trying to debug problems with differing versions of WPMU.
- Tweaked duplicate post fire detection code.


= Version 2.0.7 18/11/2011 =
- Fixed a problem related to Scheduled Posts not getting pushed to Facebook
- Fixed a problem relating to Quick Edit over riding existing post options on posts
- Fixed a problem when Wordbooker is used with the Transcript theme
- Rolled back a couple of the JSON-STRING parameters where they weren't needed
- Recoded part of the Cache Refresh process to try to make it more resilient to Facebook Server timeouts/failures.


= Version 2.0.5 / 2.0.6 15/11/2011 =
- QTranslate processing was missing round one of the post content extracts
- Added code to pull the "viewing" language from qtranslate and use that to change the language of the various FB Social widgets.
- Fixed a bug where parameters were not being passed properly to the notes publishing call
- Fix a bug where the Fan page drop down was always populated with the fan pages and groups of the first user in the wordbooker tables even if there was more than one row.
- Pushed language identification into a function to make it easier to add support for other multi-language plugins later.
- More language strings snagged and tagged
- Language files for French and German added. Thanks to Sebastian Pertsch and Christian Denat
- Changed the size of the wordbooker_blank image to resolve some issues with FB ignoring it.
- Fixed a bug in the Like/Share button logic which meanr that under certain circumstances the code for buttons wasn't included when it should have been.
- Put some checking in the cron code to handle Facebook API timing out during the refresh process and leaving things in a mess
- Fixed a typo in the "Disable Short Urls" option.
- Changed graph calls to use JSON_STRING parameter rather than just JSON (Undocumented Facebook API parameter).


= Version 2.0.4 06/11/2011 =
- Added a Memory usage line to the support information.
- Removed a duplicated constant definition
- Fixed a typo in the fb_widget include.
- Fixed a bug where Save Draft didn't save the Wordbooker options
- Completely changed the Diagnostic/Error log handling.
- Clarified some of the diagnostic messages in the cache refresh code
- Fixed a glitch in the FB Status widget when FB style time formats were used.
- Added L10n handling to the FB Status Widget
- Completed L10n handling in the FB Like Widget
- Added languages folder and first revision of the wordbooker pot file
- Added function call to handle localisation
- Recoded the image handling process to make it more reliable
- Added a trim diagnostic log call to the batch cron job
- Added a check for expired sessions in the cron code.
- Added a ID line in the support information
- Fixed a bug with the "TEST MODE" option
- Fixed a problem relating to base table prefixes in some Networked blog installs.
- Added an extra detail to the target drop down list to differentiate between pages and applications with the same name.


= Version 2.0.3 23/10/2011 =
- Fixed a bug in the code that populates the og:content tag
- Fixed a bug related to mbr string handling
- Changed calls from get_user_meta to get_usermeta which means plugin now works with 2.9 again
- When posting to your personal wall the diagnostic messages showed no target name.
- Logic for Remote publishing clients revised
- Added option to disable short urls on FB posts
- Added a post revision check to hopefully fix double posting issues
- Recoded the "get logged in user" logic in the Cache Refresh to fix an obscure bug related to lost FB IDs
- Changed the level of a couple of diagnostic messages to make sure they always show.
- Changed the logic for the primary and secondary targets so that secondary doesn't appear if you only have a personal wall and the drop down is removed from the primary.
- Added option to use the default og:image tag as the default image for posts with no image.


= Version 2.0.2 15/10/2011 =
- Fixed a bug with app-id/fb:admins which made comment moderation go wrong.
- Fixed a bug in the target handling code where a variable was not being set correctly leading to confusing error message
- Changed Curl calls to supress errors (for sites where curl is blocked/broken and craps the code out)
- Changed FB comment code to supress comment boxes on anything but single post pages (as it seems to upset Facebook).
- Fixed a bug concerning "Publish As" which meant it wasn't working properly.
- Restored an option to allow Non Wordbooker users to chose if a post should be published or not.
- Restored the option to supress like/share/send on Sticky Posts
- Fixed a bug with the og: description tag not being populated.
- Changed logic so og:tags are put out all the time unless you've checked the option to disable them all.
- Fixed a bug where an array was parsed for data even if it didn't exist.
- Fixed a bug where "post attribute" was missing.
- Fixed a bug where when extract length was set to more than 400 it got reset to either 10 or 256
- Added a check so that if a post has no images a blank is loaded to stop Facebook from scraping the page.
- Changed logic so that Posting Options checks if a post is to a page and if not defaults to a post. This should provide a short term work round for custom post types.
- Fixed a bug where the diagnostics reporting which target was active or not didn't show the right target.
- Fixed a bug where the new publish options were being lost when you scheduled a post or saved a draft and then exited and edited the post later.
- Fixed a bug where if you set the "Deactivate Wordbooker functionality" then it crashed out with a fatal error
- 24 hour time formats on the FB Status Widget weren't set correctly
- Added the X509 cert bundle for Curl installs without it properly installed.


= Version 2.0.1 11/10/2011 =
- Fixed a logic mistake concerning Share on Pages/Front pages
- A field  was missing from the post storage routine
- Fixed a bug where Like counts didn't work because the url was missing.
- Put an extra option into the Curl Call which might fix issues with certificate bundles.
- Fixed a bug with app-id/fb:admins which made comment moderation go wrong.


= Verson 2.0.0 30/09/2011 =
- Major new release - too many changes to document here


= version 1.0 :  02/01/2010 =
 - Base Release.


== Upgrade Notice ==

= 2.2 =
Base release of Version 2.2. Lots of minor bug fixes plus some new major features.

= 2.1.27 =
Fixes problem with Fulltext index. Fixes problems with user settings getting lost.

= 2.1.26 =
New Schema validation process, other fixes and confirmed compatibility with Wordpress 3.5.1

= 2.1.25 =
A few minor bug fixes and confirmed compatibility with Wordpress 3.5

= 2.1.24 =
Fixes a problem with Version 2 of Jetpack

= 2.1.21 =
Changes to og:tag images.

= 2.1.20 =
Database problem with new installs fixed.

= 2.1.19 =
Improved comment handling plus other various performance tweaks.

= 2.1.16 =
Adds code to supress warnings related to curl options plus a couple of other minor bugs.
