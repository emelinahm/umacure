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
				<?php the_content(); ?>
                
                <?php link_pages('<p><strong>Pages:</strong></p>');?>
                <?php edit_post_link('Edit','<p>','</p>');?>
                	
            </div>
        </div>
    <?php endwhile; ?>
    
    	
        
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
