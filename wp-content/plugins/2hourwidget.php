<?php
/*
Plugin Name: Twitter, Facebook and 2 Hour Commitment
Plugin URI: http://2hourcommitment.org/
Description: Adds twitter, facebook and 2 Hour Commitment links
Author: 2 Hour Commitment Team
Version: 1
Author URI: http://2hourcommitment.org/
*/

function simplelinkhtml()
{
?>
<!--<div id="socialnetworkheader">
	Check us out on:
</div>-->
<div id="socialnetworking">
	<div id="socialnetworkheader" class="widgettitle">Check us out on:</div>
	<ul>
		<li>
			<a href="http://www.facebook.com/2hourcommitment"><div class="facebook"></div><div class="socialnetworktext facetext">Facebook</div></a>
		</li>
		<li>
			<a href="http://www.twitter.com/2hourcommitment"><div class="twitter"></div><div class="socialnetworktext twittext">Twitter</div></a>
		</li>
		<li>
			<a href="http://2hourcommitment.org/"><div class="twohourcommitment"></div><div class="socialnetworktext twohctext">2 Hour Commitment</div></a>
		</li>
	</ul>
</div>
<?php
}
?>

<?php
function widget_twohouroutput() {
?>
  <?php simplelinkhtml(); ?>
<?php
}
 
function makingdawidget()
{
  register_sidebar_widget(__('2 Hour Commitment links'), 'widget_twohouroutput');
}
add_action("plugins_loaded", "makingdawidget");

?>