<?php
/**
 * Represents the view for the administration dashboard.
 * Here we can set the type of bar that can be launched.
 *
 * @package   Connecto
 * @author    Connecto <contact@thoughtfabrics.com>
 * @license   GPL-2.0+
 * @link      http://www.connecto.io
 * @copyright 2014 ThoughtFabrics Solutions Private Limited
 */
?>

<div class="wrap">
  <h2>Your notifications:</h2>
  <div style="float:right; margin-top:-20px;">
  <strong><?php esc_html_e( 'Change Connecto Account ?');?></strong>
  <form action="" method="post" id="launch-bar" style="float:right;margin-top:-20px;margin-left:10px">
    <input name="change_api_key" type="hidden" value="">
    <a href=""><button type="submit" class="button button-primary">Change API Key</button></a>
  </form>
  </div>
  <hr/><br/>
  <iframe src="http://www.connecto.io/n/wordpress_dashboard?api_key=<?php echo $connecto_key . '&site='. get_option('siteurl')?>" width="100%" style="height:850px" scrolling="no">
  </iframe>
  <hr/><br/>
</div>
