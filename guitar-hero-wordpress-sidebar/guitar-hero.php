<?php
/*
Plugin Name: Guitar Hero Sidebar
Plugin URI: http://blog.aphex3k.de/guitar-hero-wordpress-plugin-sidebar/
Description: Include a Guitar Hero statistics sidebar in your wordpress blog. 
Version: 0.1
Author: Michael Henke
Author URI: http://blog.aphex3k.de

Copyright 2008  Michael Henke (email : mail@aphex3k.de)

Guitar Hero Font used is Nightmare Hero by Laurent Mouy.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

add_action('admin_menu', 'gh_config_page');
add_option('gh_game', "gh3");
add_option('gh_show_username', false);
add_option('gh_show_groupies', true);
  
function gh_config_page() {
  if ( function_exists('add_submenu_page') )
		add_submenu_page('plugins.php', __('Guitar Hero Configuration'), __('Guitar Hero Configuration'), 'manage_options', 'guitar-hero-conf', 'gh_conf');

}

function gh_conf() {
  if (isset($_POST['action'])) {
    if (trim($_POST['ghID'])!= "") {
      update_option('ghID', trim($_POST['ghID']));
    }
    if ($_POST['show_username'] == "on" ) {
      update_option('gh_show_username', true);
    } elseif ($_POST['show_username'] != "on" ) {
      update_option('gh_show_username', false);
    }
    
    if ($_POST['show_groupies'] == "on" ) {
      update_option('gh_show_groupies', true);
    } elseif ($_POST['show_groupies'] != "on") {
      update_option('gh_show_groupies', false);
    }
    update_option('gh_game', $_POST['game']);
  }

?>
<div class="wrap">
<table width="100%">
  <tr>
    <td><h2><img src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/guitar-hero/guitarhero.gif" alt="Guitar Hero" align="absmiddle" /><?php _e(' Configuration'); ?></h2></td>
    <td><form name="_xclick" action="https://www.paypal.com/us/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="aphex3k@gmx.de">
<input type="hidden" name="item_name" value="Donation: Guitar Hero Wordpress Plugin Developer">
<input type="hidden" name="currency_code" value="EUR">
<!-- <input type="hidden" name="amount" value="1.00"> -->
<input type="image" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/guitar-hero/btn_donate_LG.gif" border="0" name="submit" alt="Please feel free to donate to the developer for his effort!">
</form></td>
    </tr>
</table>
<div class="narrow">
<form action="" method="post" name="ghconfpost">
<?php wp_nonce_field('update-options'); ?>
<table class="form-table">
  <tr valign="top"> 
    <th scope="row"><label>Community&nbsp;ID</label></th> 
    <td><input name="ghID" size="7" maxlength="6" value="<?php echo get_option('ghID');?>" /> 
      <br /> 
      Please enter your Guitar Hero community ID here. If you don't know your ID just log in to the guitar hero community at
      <a href="http://community.guitarhero.com" target="_blank">http://community.guitarhero.com</a> and take a look at your browsers adress bar. It will
      look something like this <i>http://community.guitarhero.com/accounts/<b>755673</b></i>. The last 6 digits is your community ID. Beware that this field is not for your community account name!</td> 
  </tr> 
  <tr valign="top"> 
    <th scope="row"><label>Username</label></th> 
    <td><input type="checkbox" name="show_username"<?php echo get_option('gh_show_username')? " checked" : ""; ?> />
      <br /> 
      Tick this box if you want to display your Guitar Hero community account username here. The username will be linked to your community
      profile page.</td> 
  </tr> 
  <tr valign="top"> 
    <th scope="row"><label>Groupies</label></th> 
    <td><input type="checkbox" name="show_groupies"<?php echo get_option('gh_show_groupies')? " checked" : ""; ?> />
      <br /> 
      To display the numbers of your groupies tick this box.</td> 
  </tr>
  <tr valign="top">
    <th scope="row"><label>Game&nbsp;Link</label></th>
    <td><select name="game">
          <option value="gh3"<?php echo (get_option('gh_game')=="gh3")? " selected" : ""; ?>>Guitar Hero III - Legends of Rock</option>
          <option value="aerosmith"<?php echo (get_option('gh_game')=="aerosmith")? " selected" : ""; ?>>Guitar Hero - Aerosmith</option>
        </select><br />
        What game do you want to link your profile to?
    </td>
  </tr>
  <tr valign="top">
    <th scope="row"><label>Feedback</label></th>
    <td>You can send feedback, comments, feature requests via mail to <a href="mailto:mail@aphex3k.de?subject=Guitar%20Hero%20WordPress%20Plugin%20Feedback">mail@aphex3k.de</a> or post comments in my
        <a href="http://blog.aphex3k.de/guitar-hero-wordpress-plugin-sidebar/" target="_blank">blog</a>.
        If you and your band really like this plugin you might want to 
        <a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=aphex3k%40gmx%2ede&item_name=Donation%3a%20Guitar%20Hero%20Wordpress%20Plugin%20Developer&no_shipping=0&no_note=1&tax=0&currency_code=EUR&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8">donate</a> 
        something for my effort and time in which I was coding this plugin instead of shredding my guitar ;) 
    </td>
  </tr>
</table>
  <input type="hidden" name="action" value="update" />
  <input type="hidden" name="page_options" value="show_groupies,show_username,ghID" />
  <p class="submit"><input type="submit" name="submit" value="<?php _e('Update Configuration &raquo;'); ?>" /></p>
</form>
</div>
</div>
<?php
}

function gh_stats() {
  #echo "Debug: Guitar Hero Plugin Output\n";
  
  static $plugin_path = "wp-content/plugins/guitar-hero/";
  
  include("xml_parser.php");
  
  echo "<img src=\"". get_option('siteurl') ."/wp-content/plugins/guitar-hero/guitarhero.gif\" alt=\"Guitar Hero\" align=\"absmiddle\" /><br />\n";
  echo "<p style=\"margin-left: 10px\">\n";
  echo get_option('gh_show_username')? "<a href=\"http://community.guitarhero.com/accounts/profile/". get_option('ghID') ."?game=". get_option('gh_game') ."\" target=\"_blank\">". gh_getUsername() ."</a><br />" :"";
  echo gh_getRockerStatus() ."<br />\n";
  echo get_option('gh_show_groupies')? gh_getGroupies() ."&nbsp;Groupies<br />\n" : "";
  echo "# ". gh_getRank() ."&nbsp;Rank<br />\n";
  echo "\$ ". gh_getCash() .",-&nbsp;Cash<br />\n";
  
  echo "</p>\n";
}
?>
