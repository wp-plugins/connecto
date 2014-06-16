<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Connecto
 * @author    Connecto <contact@thoughtfabrics.com>
 * @license   GPL-2.0+
 * @link      http://www.connecto.io
 * @copyright 2014 ThoughtFabrics Solutions Private Limited
 */
?>

<div class="wrap">
  <h2>Connecto Configuration</h2>
  <hr/><br/>
  <div class="activate-highlight activate-option">
    <div class="option-description">
      <strong><?php esc_html_e( 'Activate Connecto');?></strong>
      <p><?php esc_html_e('Enter your API key from Connecto or create one.'); ?></p>
    </div>
    <a href="http://www.connecto.io/accounts/register?<?php echo 'site='.get_option('siteurl')?>"><button type="submit" class="button button-primary">Get API Key</button></a>
  </div>
  <div class="activate-highlight secondary activate-option">
    <div class="option-description">
      <strong>Manually enter an API key</strong>
      <p>If you already know your API key specify it here.</p>
    </div>
    <form action="" method="post" id="enter-api-key" class="right">
      <input id="connecto_key" name="connecto_key" type="text" size="32" maxlength="32" value="<?php echo $connecto_key ?>" class="regular-text code">
      <br>
      <input type="submit" name="submit" id="submit" class="button button-secondary" value="<?php esc_attr_e('Use this key');?>">
    </form>
  </div>
</div>
