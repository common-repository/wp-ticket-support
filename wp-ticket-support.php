<?php
/*
Plugin Name: WP Ticket Support
Plugin URI: http://wp.svenkubiak.de
Description:  
Version: 1.0
Author: Sven Kubiak
Author URI: http://www.svenkubiak.de

Copyright 2009 Sven Kubiak

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
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

if (!class_exists('WPts'))
{
	Class WPts
	{		
		function wpts()
		{		
			add_action('admin_menu', array(&$this, 'wptsAdminMenu'));
			
			//Register hooks for activation and deactivation
			register_activation_hook(__FILE__, array(&$this, 'activate'));
			register_deactivation_hook(__FILE__, array(&$this, 'deactivate'));				
		}
		
		function createTable()
		{
			global $wpdb;

			if (file_exists(ABSPATH . '/wp-admin/includes/upgrade.php')){
				@require_once (ABSPATH . '/wp-admin/includes/upgrade.php');
			} elseif (file_exists(ABSPATH . WPINC . '/upgrade-functions.php')) {
				@require_once (ABSPATH . WPINC . '/upgrade-functions.php');
			} elseif (file_exists(ABSPATH . '/wp-admin/upgrades.php')) {
				@require_once (ABSPATH . '/wp-admin/upgrades.php');
			} else {
				echo "<div id='message' class='error fade'><p>".__('Required functions for creating the table could not be loaded.','wpts')."</p></div>";
				return false;
			}		

			$query = "CREATE TABLE IF NOT EXISTS `wpts_faq_lists` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
			
			//maybe_create_table($wpdb->prefix . 'wpts', file_get_contents("install.sql"));
			maybe_create_table($wpdb->prefix . 'wpts_faq_lists', $query);
			
			return true;
		}		
		
		function activate()
		{
			$this->createTable();	
		}
		
		function deactivate()
		{
			
		}
		
		function getOptions()
		{
			
		}
		
		function setOptions()
		{
			
		}
		
		function wptsAdminMenu()
		{
			add_options_page('WP Ticket Support', 'WP Ticket Support', 8, 'wp-ticket-support', array(&$this, 'wptsOptionPage'));	
		}

		function wptsOptionPage()
		{	
			if (!current_user_can('manage_options'))
				wp_die(__('Sorry, but you have no permissions to change settings.','wpts'));

			?>
							
			<div class="wrap">
				<div id="icon-options-general" class="icon32"></div>
				<h2><?php echo __('WP Ticket Support Settings','wpts'); ?></h2>
			
				<div id="poststuff" class="ui-sortable">
					<div class="postbox opened">
						<h3><?php echo __('Navigation','wpts'); ?></h3>
						<div class="inside">
							<table class="form-table">
								<tr>
									<th scope="row" valign="top">
									<b><a href="http://www.svenkubiak.de/wp-admin/options-general.php?page=wp-ticket-support&submenu=">Main menu</a> | <a href="http://www.svenkubiak.de/wp-admin/options-general.php?page=wp-ticket-support&submenu=user">User</a> | <a href="http://www.svenkubiak.de/wp-admin/options-general.php?page=wp-ticket-support&submenu=tickets">Tickets</a> | <a href="http://www.svenkubiak.de/wp-admin/options-general.php?page=wp-ticket-support&submenu=faq">FAQ</a> | <a href="http://www.svenkubiak.de/wp-admin/options-general.php?page=wp-ticket-support&submenu=settings">General settings</a> | <a href="http://www.svenkubiak.de/wp-admin/options-general.php?page=wp-ticket-support&submenu=about">About</a></b>	
									</th>
								</tr>
							</table>			
						</div>
					</div>
				</div>
			
			<?php 
			
			switch ($_GET['submenu'])
			{
				case 'user':
					$this->submenuUser();
				break;
				case 'tickets':
					$this->submenuTickets();
				break;
				case 'faq':
					$this->submenuFaq();
				break;
				case 'about':
					$this->submenuAbout();
				break;
				case 'settings':
					$this->submenuSettings();
				break;
				default:
					$this->mainMenu();
				break;						
			}
			
			?>	
						
			</div>
			
			<?php		
		}

		function mainMenu()
		{
			?>
			
			<div id="poststuff" class="ui-sortable">
				<div class="postbox opened">		
					<h3><?php echo __('Overview','wpts'); ?></h3>
					<div class="inside">							

					</div>							
				</div>
			</div>
		
			<?php 				
		
		}
		
		function submenuUser()
		{
			?>
			
			<div id="poststuff" class="ui-sortable">
				<div class="postbox opened">		
					<h3><?php echo __('User','wpts'); ?></h3>
					<div class="inside">							

					</div>							
				</div>
			</div>
		
			<?php 				
		
		}	

		function submenuTickets()
		{
			?>
			
			<div id="poststuff" class="ui-sortable">
				<div class="postbox opened">		
					<h3><?php echo __('Tickets','wpts'); ?></h3>
					<div class="inside">							

					</div>							
				</div>
			</div>
		
			<?php 				
		
		}	

		function submenuFaq()
		{
			global $wpdb;
			
			if ($_POST['action'] == "create_faq_list")
			{
				$wpdb->query("INSERT INTO $wpdb->prefix . 'wpts_faq_lists' (id, name) VALUES(NULL, ".$_POST['faq_list_name'].")");
				echo "foobar";
			}
			
			$faq_lists = $wpdb->get_results("SELECT id, name FROM wpts_faq_lists");
			
			?>
			
			<div id="poststuff" class="ui-sortable">
				<div class="postbox opened">
					<form action="options-general.php?page=wp-ticket-support&submenu=faq" method="post">
					<input type="hidden" value="create_faq_list" name="action">
					<h3><?php echo __('Create new FAQ List','wpts'); ?></h3>
					<div class="inside">							
						<table class="form-table">
							<tr>
								<th scope="row" valign="top"><b><?php echo __('Name','nospamnx'); ?></b></th>
								<td><input type="text" name="faq_list_name" value="" /><td>									
							</tr>
							<tr>
								<td><p><input name="submit" class='button-primary' value="<?php echo __('Create','wpts'); ?>" type="submit" /></p></td>							
							</tr>
						</table>	
					</div>			
					</form>									
				</div>			
				<div class="postbox opened">
					<form action="options-general.php?page=wp-ticket-support&submenu=faq" method="post">
					<input type="hidden" value="create_faq" name="action">
					<h3><?php echo __('Create new FAQ','wpts'); ?></h3>
					<div class="inside">							
						<table class="form-table">
							<tr>
								<td>
								<b><?php echo __('FAQ List','wpts'); ?></b><br/>
								<select name="in_faq_list">
									
									<?php 
									
									foreach ($faq_lists as $faq_list)
										echo "<option value=".$faq_list->id.">".$faq_list->name."</option>";
									
									?>

								</select>
								</td>
							</tr>						
							<tr>
								<td>
								<b><?php echo __('Question','wpts'); ?></b><br/>
								<textarea name="question" class="large-text code"></textarea><br />
								<b><?php echo __('Answer','wpts'); ?></b><br/>
								<textarea name="answer" class="large-text code"></textarea><br />
								</td>
							</tr>
							<tr>
								<td><p><input name="submit" class='button-primary' value="<?php echo __('Create','wpts'); ?>" type="submit" /></p></td>							
							</tr>
						</table>	
					</div>			
					</form>									
				</div>
			</div>
					
			<?php 				
		
		}	

		function submenuAbout()
		{
			?>
			
			<div id="poststuff" class="ui-sortable">
				<div class="postbox opened">		
					<h3><?php echo __('About','wpts'); ?></h3>
					<div class="inside">							

					</div>							
				</div>
			</div>
		
			<?php 				
		
		}

		function submenuSettings()
		{
			?>
			
			<div id="poststuff" class="ui-sortable">
				<div class="postbox opened">		
					<h3><?php echo __('Generel Settings','wpts'); ?></h3>
					<div class="inside">							

					</div>							
				</div>
			</div>
		
			<?php 				
		
		}			

	}
	
	$wpts = new WPts();
}
?>