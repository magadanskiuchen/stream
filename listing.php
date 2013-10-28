<?php
if (have_posts()) {
	while (have_posts()) {
		the_post();
		?>
		<div id="post-<?php echo get_the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
			<meta itemprop="url" content="<?php the_permalink(); ?>" />
			
			<div class="vcard">
				<?php echo get_avatar( get_the_author_meta( 'ID' ), 64 ); ?>
			</div>
			
			<div class="post-content">
				<?php the_content(); ?>
			</div>
			
			<div class="meta">
				<p><strong itemprop="author"><?php the_author(); ?></strong></p>
				
				<h6>
					<?php
					echo (!is_single()) ? ('<a href="' . get_permalink( get_the_ID() ) . '" rel="bookmark">') : '';
					echo '<span itemprop="datePublished">' . get_the_time( stream_datetime_format() ) . '</span>';
					echo ' ';
					echo '<span itemprop="name">' . get_the_title() . '</span>';
					echo (!is_single()) ? '</a>' : '';
					?>
				</h6>
			</div>
		</div>
		<?php
	}
}
?>