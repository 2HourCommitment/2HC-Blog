<div id="socialnetworkheader">
	Check us out on:
</div>
<div id="socialnetworking">
	<ul>
		<li>
			<a href="http://www.facebook.com/2hourcommitment"><div class="facebook"></div><div class="socialnetworktext facetext">Facebook</div></a>
		</li>
		<li>
			<a href="http://www.twitter.com/2hourcommitment"><div class="twitter"></div><div class="socialnetworktext twittext">Twitter</div></a>
		</li>
		<li>
			<a href="http://www.2hourcommitment.org/"><div class="twohourcommitment"></div><div class="socialnetworktext twohctext">2 Hour Commitment</div></a>
		</li>
	</ul>
</div>



<?php
/**
 * The Sidebar containing the main widget area.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
$options = twentyeleven_get_theme_options();
$current_layout = $options['theme_layout'];

if ('content' != $current_layout) :
	?>
	<div id="secondary" class="widget-area" role="complementary">
		<?php if (!dynamic_sidebar('sidebar-1')) : ?>

			<aside id="archives" class="widget">
				<h3 class="widget-title"><?php _e('Archives', 'twentyeleven'); ?></h3>
				<ul>
					<?php wp_get_archives(array('type' => 'monthly')); ?>
				</ul>
			</aside>

			<aside id="meta" class="widget">
				<h3 class="widget-title"><?php _e('Meta', 'twentyeleven'); ?></h3>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<?php wp_meta(); ?>
				</ul>
			</aside>

		<?php endif; // end sidebar widget area  ?>
	</div><!-- #secondary .widget-area -->
<?php endif; ?>
