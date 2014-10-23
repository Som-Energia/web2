<?php
/* 
	Searches and Displays List of Unused Plugin Tables
*/
// Include some files containing functions we are going to use
include_once( WPDBSC_PATH . 'WPDBSpringClean.php' );
include_once( WPDBSC_PATH . 'grepSearch.php' );

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class wpdbspringclean_admin extends WP_List_Table {
  
    /**************************************************************************
     * REQUIRED. Set up a constructor that references the parent constructor. We 
     * use the parent reference to set some default configs.
     ***************************************************************************/
    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'table',     //singular name of the listed records
            'plural'    => 'tables',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
    }
    
    /**************************************************************************
     * This column method is responsible for what
     * is rendered in a column. 
     ***************************************************************************/
    function column_default($item, $column_name){
    	$tab = strip_tags($_REQUEST['tab']);
    	//Build row actions
        $actions = array(
            'delete'    => sprintf('<a href="?page=%s&tab=%s&action=%s&table=%s" onclick="return confirm(\'Are you sure you want to delete this entry?\')">Delete</a>',$_REQUEST['page'],$tab,'delete',$item),
        );
        
        //Return the title contents
        return sprintf('%1$s <span style="color:silver"></span>%2$s',
            /*$1%s*/ $item,
            /*$2%s*/ $this->row_actions($actions)
        );
    }

    /**************************************************************************
     * If displaying checkboxes or using bulk actions the 'cb' column
     * is given special treatment when columns are processed. It ALWAYS needs to
     * have it's own method.
     * 
     **************************************************************************/
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label
            /*$2%s*/ $item                 		//The value of the checkbox should be the record's id
        );
    }
    
    
    /**************************************************************************
     * This method dictates the table's columns and titles. This should
     * return an array where the key is the column slug (and class) and the value 
     * is the column's title text. If you need a checkbox for bulk actions, refer
     * to the $columns array below.
     * 
     * The 'cb' column is treated differently than the rest. If including a checkbox
     * column in your table you must create a column_cb() method. If you don't need
     * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
     * 
     **************************************************************************/
    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'tablename'     => 'TableName',
         );
        return $columns;
    }
    
   
    /**************************************************************************
     * If you need to include bulk actions in your list table, this is
     * the place to define them. Bulk actions are an associative array in the format
     * 'slug'=>'Visible Title'
     * 
     * If this method returns an empty value, no bulk action will be rendered. If
     * you specify any bulk actions, the bulk actions box will be rendered with
     * the table automatically on display().
     * 
     * Also note that list tables are not automatically wrapped in <form> elements,
     * so you will need to create those manually in order for bulk actions to function.
     * 
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }
    
   function process_bulk_action() {
	   if('delete'===$this->current_action()) 
	   {//Process delete bulk actions
		   if(!isset($_REQUEST['table']))
		   {
			   $error_msg = '<div id="message" class="error"><p><strong>';
			   $error_msg .= 'Please select some records using the checkboxes';
			   $error_msg .= '</strong></p></div>';
			   echo $error_msg;
		   } else{
			   $this->delete_entries(($_REQUEST['table']));
		   }
	   }
   }
    
   function delete_entries($entries)
   {
   		global $wpdb;
   		$result = '';
   		$wpdbsc_options = get_option('wpdbsc_unused_tables');
   	    if (is_array($entries))
        {
        	//Drop the tables
            $table_list = implode(",",$entries); //Create comma separate list for DB operation
            $result = $wpdb->query("DROP TABLE $table_list");
            if($result != NULL)
            {
            	//now let's update the options which stored our tables names
            	$tables_diff = array_diff($wpdbsc_options, $entries); //this will get the remaining tables which have not been deleted yet
            	$tables_remaining = array_values($tables_diff); //this will reset the indexes of the above array
				update_option('wpdbsc_unused_tables', $tables_remaining); //update the WP options table
				            	
                $success_msg = '<div id="message" class="updated fade"><p><strong>';
                $success_msg .= 'The selected tables were deleted successfully!';
                $success_msg .= '</strong></p></div>';
                echo $success_msg;
            }
		}
   		else if ($entries != NULL)
        {
            //Delete single record
            $result = $wpdb->query("DROP TABLE $entries");
            if($result != NULL)
            {
            	//now let's update the options which stored our tables names
            	if (($key = array_search($entries, $wpdbsc_options)) !== false) {
	            	unset($wpdbsc_options[$key]); //remove the particular entry
					update_option('wpdbsc_unused_tables', $wpdbsc_options); //store the results in WP options table
            	}
					    
                $success_msg = '<div id="message" class="updated fade"><p><strong>';
                $success_msg .= 'The selected table was deleted successfully!';
                $success_msg .= '</strong></p></div>';
                echo $success_msg;
            }
        }
   		$_POST['search_for_tables'] = null;
   } 
    
        
    
    /**************************************************************************
     *  This is where you prepare your data for display. 
     ***************************************************************************/
    function prepare_items() {
        
        /*
         * First, lets decide how many records per page to show
         */
        $per_page = 50;
        
        
        /*
         * Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        
        /*
         * Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 2 other arrays. One for all columns, one for hidden columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        
        /*
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        $this->process_bulk_action();

        /***********************************************************************
         * This where we are doing the DB query.
         **********************************************************************/
       	global $wpdb;
       	
		$db_prefix = $wpdb->prefix;
		$wpdbsc_data = array();

		//if the DB query results have not already been stored in the options table OR search button has been clicked then do the search
		if (isset($_POST['search_for_tables']))
		{
			//below are the core WP tables we don't want to display or delete!
			$wp_core_tables = array("{$db_prefix}commentmeta", "{$db_prefix}comments", "{$db_prefix}links", "{$db_prefix}options", "{$db_prefix}postmeta", 
									"{$db_prefix}posts", "{$db_prefix}terms", "{$db_prefix}term_relationships", "{$db_prefix}term_taxonomy", "{$db_prefix}usermeta", "{$db_prefix}users");
			$sql = "show tables";
			$results = $wpdb->get_results($sql);
			
			$sum = new stdClass(); //we'll use this in the for loop to represent each object in the "show tables" results
	
			$found_at_least_a_table = false;
			$wpdbsc_string = "Tables_in_".constant("DB_NAME"); //we'll need this later to manipulate through the "show tables" array output
			//$plugins_dir_path = ABSPATH . 'wp-content/plugins/'; 
			//$themes_dir_path = ABSPATH . 'wp-content/themes/';
	
			$wpdbsc_dir_path = plugin_dir_path( __FILE__ ); //dir path of this plugin
			$plugins_dir_path = dirname($wpdbsc_dir_path); //dir path of plugins
			$themes_dir_path = dirname($plugins_dir_path).'/themes'; //dir path of themes
			
			foreach( $results as $sum ) {
				if (!in_array($sum->$wpdbsc_string, $wp_core_tables))
				{
					//Remove the DB prefix so we have the original table name only. 
					//We will use this to search through the files in the plugin directories to identify an installed plugin
					$tab_name_no_pref = substr($sum->$wpdbsc_string, strpos($sum->$wpdbsc_string, $db_prefix) + strlen($db_prefix));

					//if table is NOT associated with a currently installed plugin, store the table for displaying later
					if (!php_grep($tab_name_no_pref, $plugins_dir_path)) 
					{
						$found_at_least_a_table = true;
						array_push($wpdbsc_data, $sum->$wpdbsc_string);
	
					}//end if statement
				}//end outer if statement
			}//end for loop
				
			update_option('wpdbsc_unused_tables', $wpdbsc_data); //store the results in WP options table
		}
		else if(!get_option('wpdbsc_unused_tables') === false) 
		{
			//if search button was not clicked and we have already saved some results to the wp_options then just display the saved table data
			$wpdbsc_data = get_option('wpdbsc_unused_tables');
		}
		else 
		{
			$wpdbsc_data = array();
		}	
					
		/*
         * Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        $current_page = $this->get_pagenum();
        
        /*
         * REQUIRED for pagination. Let's check how many items are in our data array. 
         * In real-world use, this would be the total number of items in your database, 
         * without filtering. We'll need this later, so you should always include it 
         * in your own package classes.
         */
        $total_items = count($wpdbsc_data);
        
        /*
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to do this
         */
        $wpdbsc_data = array_slice($wpdbsc_data,(($current_page-1)*$per_page),$per_page);
        
        
        
        /*
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
        $this->items = $wpdbsc_data;
        
       
        /*
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }
    

	
	/***********************************************************************
	* Send the SQL command to drop the tables from the DB 
	**********************************************************************/
	
	function delete_this_table($table) {
		global $wpdb;
	
		//Process button submission if needed
		$results = $wpdb->query($wpdb->prepare("DROP TABLE %s", $table));
		if ($results === false)
		{
			return false;
		} 
		else 
		{
			return $results;
		}
	}

}//end class wpdbspringclean_admin

/***************************** RENDER ADMIN PAGE ********************************
 * This function renders the admin page
 ********************************************************************************/
function wpdbscListUnusedTables(){
	$tables_page = site_url().'/wp-admin/options-general.php?page=wpdbsc_plugin_options&tab=unused_table_settings';
?>
        <h2>WPDBSpringClean - Delete Unused DB Tables</h2>
        <div class="wpdbsc_blue_box"><p>The WPDBSpringClean plugin will identify unused database tables which have been left behind from uninstalled WordPress plugins.
        <br/>WPDBSpringClean will enable you to delete all or some of these unused tables.</p></div>
        <h4>Click the button below to start searching for DB tables which are from uninstalled plugins</h4>
        <form action="<?php echo $tables_page;?>" method="POST">
		<input type="submit" name="search_for_tables" value="Perform Search" class="button-primary search-db" />
		<span class="wpdbsc_loading">
			<img src="<?php echo WPDBSC_URL.'/img/loading.gif'; ?>" alt="Loading..." />
		</span>
		</form>
		<div style="border-bottom:1px solid #dedede;height:10px"></div>
		<div class="wpdbsc_info_with_icon wpdbsc_unused_table_search_msg">....Searching - please wait......Search may take a minute depending on the size of your DB and number of plugins.</div>
<?php
	//if(isset($_POST['search_for_tables']) || !(get_option('wpdbsc_unused_tables') === false)){
		//Create an instance of our package class...
	    $testListTable = new wpdbspringclean_admin();
	    //Fetch, prepare, sort, and filter our data...
	    $testListTable->prepare_items();
?>        		

        <div class="wpdbsc_blue_box">
        	<p>After performing a search, the results for DB tables which WPDBSpringClean deems to be unused by another plugin will be displayed below.</p>
        </div>
        <p class="search-res">NOTE: If you are unsure about deleting any tables, it is recommended that you take a DB backup before using WPDBSpringClean</p>

        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="tables-filter" method="get" onSubmit="return confirm('Are you sure you want to delete the selected entries?');">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
            <input type="hidden" name="tab" value="<?php echo $_REQUEST['tab']; ?>" />
            <!-- Now we can render the completed list table -->
            <?php $testListTable->display(); ?>
        </form>
<?php 
	//} //close if statement
?>
	<div style="border-bottom:1px solid #dedede;height:10px"></div>
	<h5>Please support me by buying the following amazing plugins:</h5>
	<a href="http://wpsolutions-hq.com/wp/recommended/eStore/" target="_blank">
	<img src="https://s3.amazonaws.com/product_banners/eStore_banner_468_60.gif" alt="WordPress Shopping Cart" border="0" /></a>
	<br /> <br />
	<a href="http://wpsolutions-hq.com/wp/recommended/emember/" target="_blank">
	<img src="https://s3.amazonaws.com/product_banners/wp_emember_banner_468x60.gif" alt="WordPress Membership Plugin" border="0" /></a>        
		<script type="text/javascript">
		/* <![CDATA[ */
		jQuery(document).ready(function($) {
			loading_element = $('.wpdbsc_loading');
			search_msg = $('.wpdbsc_unused_table_search_msg');
			loading_element.hide(); //hide the spinner gif after page has successfully loaded
			search_msg.hide(); 
			$('.search-db').on("click",function(){
				$('div.updated').hide();
				loading_element.show();
				search_msg.show();
			});			
		});		
		/* ]]> */
		</script>
<?php
}
?>