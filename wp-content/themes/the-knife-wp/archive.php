<?php get_header(); ?>
<div id="content">
<div id="left">
<div class="leftitem">
<?php include('searchform.php'); ?>
<?php get_sidebar(); ?>
</div>
<div class="leftbottom"></div>
</div>
</div>
<div id="right">
	<?php if(have_posts()): ?><?php while(have_posts()):the_post(); ?>
    	<div class="post"  id="post-<?php the_ID(); ?>">
            <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"> <?php the_title(); ?></a></h2>
            <?php _e('Posted by'); ?> <?php  the_author(); ?> <?php _e('on '); ?><?php the_date()?><br />
            <div class="entry">
				<?php the_excerpt(); ?>
                	<p class="postmetadata">
					<?php _e('Filed under&#58;'); ?> <?php the_category(', ') ?> 
                    <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments                     &#187;'); ?> <?php edit_post_link('Edit', ' &#124; ', ''); ?>
                       </p>
            </div>
        </div>
    <?php endwhile; ?>
    
    	<div class="navigation">
        	<?php posts_nav_link(); ?>
        </div>
        
    <?php else: ?>
    
    <div class="post" id="post-<?php the_ID(); ?>">
    	<h2><?php _e('Not Found'); ?></h2>
    </div>
    
    <?php endif; ?>
</div>
	



<div style="clear: both;"></div>
<?php get_footer(); ?>
</div>
</body>
</html>
