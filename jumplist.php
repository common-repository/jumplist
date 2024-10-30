<?php
/*
Plugin Name: Jumplist
Plugin URI: http://saj.in/blog/plugins
Description: Jumplist for IE9 (pin to taskbar in Windows 7)
Version: 0.3
Author: Sajin Kunhambu
Author URI: http://saj.in/
License: GPL (http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt)
*/


//Options Section - Begin
if(!get_option('jumplist_settings')) {
	$jumplist_settings = array(
		'recents' => 5,
		'title' => bloginfo( 'name' )
	);
	add_option('jumplist_settings', $jumplist_settings);
} else {
	$jumplist_settings = get_option('jumplist_settings');
}

function jumplist_add_page()
{
     add_options_page("Jumplist Options", "Jumplist", 8, "jumplist", jumplist_options);
}

function jumplist_options() {
	global $jumplist_settings;
	if( isset( $_POST['update_options'] ) )
	{
		$jumplist_settings = $_POST['jumplist_settings'];
		update_option('jumplist_settings', $_POST['jumplist_settings']);
	}
	?>
<div class="wrap">
  <h2>Jumplist Options</h2>
  <form name="jumplist_form" method="post">
    <fieldset class="options">
      <legend>Server</legend>
      <table width="100%" cellspacing="2" cellpadding="5" class="editform">
        <tr valign="top">
          <th width="45%" scope="row">Link Title</th>
          <td>
            <input type="text" name="jumplist_settings[title]" value="<?php echo $jumplist_settings['title'] ?>" size="25" />
          </td>
        </tr>
        <tr valign="top">
          <th width="45%" scope="row">Recent Entries</th>
          <td>
            <select size="1" name="jumplist_settings[recents]" value="<?php echo $jumplist_settings['recents'] ?>" >
              <option value="0" 
                <?php if($jumplist_settings['recents']==0) echo "selected"; ?>>0
              </option>
              <option value="1" 
                <?php if($jumplist_settings['recents']==1) echo "selected"; ?>>1
              </option>
              <option value="2" 
                <?php if($jumplist_settings['recents']==2) echo "selected"; ?>>2
              </option>
              <option value="3" 
                <?php if($jumplist_settings['recents']==3) echo "selected"; ?>>3
              </option>
              <option value="4" 
                <?php if($jumplist_settings['recents']==4) echo "selected"; ?>>4
              </option>
              <option value="5" 
                <?php if($jumplist_settings['recents']==5) echo "selected"; ?>>5
              </option>
              <option value="6" 
                <?php if($jumplist_settings['recents']==6) echo "selected"; ?>>6
              </option>
              <option value="7" 
                <?php if($jumplist_settings['recents']==7) echo "selected"; ?>>7
              </option>
              <option value="8" 
                <?php if($jumplist_settings['recents']==8) echo "selected"; ?>>8
              </option>
              <option value="9" 
                <?php if($jumplist_settings['recents']==9) echo "selected"; ?>>9
              </option>
            </select>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="submit" name="update_options" value="Update Options" />
  </form>
</div>
<?php
}
//Options Section - End




function jump_list() {
  jump_tasks();
?>
<script language="javascript">
/* <![CDATA[ */
  try {
    window.external.msSiteModeClearJumplist();
    function AddJumpList() {
      if (!window.external.msIsSiteMode()) return;
<?php
  jump_cats();
  jump_posts();
?>
      window.external.msSiteModeShowJumplist();
    }
    AddJumpList();
  }
/* ]]> */
</script>
<?php
}

function jump_tasks() {
?>
<meta name="application-name" content="<?php bloginfo( 'name' ); ?>" />
<meta name="msapplication-window" content="width=device-width;height=device-height" />
<meta name="msapplication-tooltip" content="<?php bloginfo( 'description' ); ?>" />
<meta name="msapplication-starturl" content="/" />
<?php
}

function jump_cats() {
?>
      window.external.msSiteModeCreateJumplist("Recent Posts");
<?php
  $categories=  get_categories('child_of=0'); 
  foreach ($categories as $category) { ?>
      window.external.msSiteModeAddJumpListItem("<?php echo $category->name; ?>", "<?php bloginfo( 'url' ); ?>/category/<?php echo $category->slug;?>", "<?php echo bloginfo( 'url' );?>/favicon.ico");
<?php }
}

function jump_posts() {
global $jumplist_settings;
?>
      window.external.msSiteModeCreateJumplist("Recent Posts");
<?php $i=1; if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
<?php if($i > (int)$jumplist_settings['recents']) break; ?>
      window.external.msSiteModeAddJumpListItem("<?php the_title(); ?>", "<?php the_permalink(); ?>", "<?php bloginfo( 'url' ); ?>/favicon.ico");
<?php $i++; ?>
<?php endwhile; ?>
<?php endif; ?>
<?php rewind_posts(); ?>
<?php
}

add_action('admin_menu', 'jumplist_add_page');
add_action('wp_head', 'jump_list');
?>
