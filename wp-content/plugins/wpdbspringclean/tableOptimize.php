<?php

/***********************************************************************
* Do DB query for All non-optimized tables.
**********************************************************************/
class wpdbsc_optimize_admin {

   function __construct()
   {
		global $wpdb;
		//$tables_to_display = array();
		$freespace = $overhead = $sql_query = $sql_2 = $errors = NULL;
   }

   function find_unoptimized() 
   {
   		global $wpdb;
		$results = NULL;
		$errors = '';
		$sql_2 = '';
		$freespace = '';
   		$verify = filter_var($_POST['overhead'], FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range'=>100)));
		if (!($_POST['overhead'] == "") && !$verify) 
		{
			$errors .= 'Please enter valid integer between 0 and 100.<br/><br/>';
		} 
		else if ($_POST['overhead'] == "")
		{
			$overhead = 0.1; //default value for overhead
		}
		else
		{
			$overhead = ($_POST['overhead'])/100;
		}
		
		if (isset($_POST['refine'])) //if checkbox enabled construct second part of sql query
		{
			$freespace = $_POST['freespace'] * 1000;
			$sql_2 = " AND Data_free > ".$freespace;
		}
		if (!$errors)
		{
			$sql_query = "SHOW TABLE STATUS WHERE Data_free / Data_length > ".$overhead.$sql_2;
			($freespace != NULL ? (($freespace/1000).' kB') : ($freespace = 'any'));
			update_option('wpdbsc_optimize_search_criteria', array($overhead, $freespace)); //store the values in wp_options table
			$results = $wpdb->get_results($sql_query);
		} else 
		{
			echo '<div style="color: red">' . $errors . '<br/></div>';
			die();
		}
   		return $results;
   } //end function find_unoptimized()
   
   function optimize_table()
   {
   		global $wpdb;
		$tables = $_POST['tabname'];
		$outcomes = array(array());
		$success = TRUE;
		$q = 0;
        foreach($tables as $table)
        {
            $table_name_to_optimize = strip_tags($table);
			$query = "OPTIMIZE TABLE $table_name_to_optimize";
			$res = $wpdb->query($query);
			$wpdbsc_optimize_options = get_option('wpdbsc_optimize_candidate');
			if (($key = array_search($table, $wpdbsc_optimize_options)) !== false) {
        		//as well as deleting the table from DB, let's delete it from our options too
        		unset($wpdbsc_optimize_options[$key]); //remove the particular entry
			    update_option('wpdbsc_optimize_candidate', $wpdbsc_optimize_options); //store the results in WP options table
			}
			
			
			//if one of our DB operations failed, store the table name
			if (!$res)
			{
				$outcomes[$q] = array('name'=>$table, 'res'=>$res);
				$q++;
				$success = FALSE;
			}
			
        }
        if ($success)
        {
        	echo '<div id="message" class="updated fade">';
		    echo '<p>Table(s) optimized successfully!</p>';
		    echo '</div>';        	
        } else if (!$success)
        {
			foreach($outcomes as $y)
	 	    {
				echo '<div id="message" class="error fade">';
			    echo '<p>The table ('. $y['name'] .') could not be optimized!</p>';
			    echo '</div>'; 			
	        }
        }
   } //end function optimize_table()

   function store_in_options($tables)
   {
   		$tab = new stdClass();
   		$tables_to_display = array();
		$table_name = "Name";
		$found_at_least_a_table = false;
		//Store each table in array and then save in options table
		foreach( $tables as $tab ) {
			array_push($tables_to_display, $tab->$table_name);
		}
		update_option('wpdbsc_optimize_candidate', $tables_to_display); //store the results in WP options table
   }
   
   function display_tables($opt_tables)
   {
   		$tab = new stdClass();
   		$search_options = array();
		$table_name = "Name";
		if ($opt_tables)
			$found_at_least_a_table = true;
		else 
			$found_at_least_a_table = false;
		if (get_option('wpdbsc_optimize_search_criteria'))
		{
			$search_options = get_option('wpdbsc_optimize_search_criteria');
			$overhead = $search_options[0];
			$freespace = $search_options[1];
			
			echo '<div><i> Displaying Results For The Following Search Criteria:<br /><br /> 
			Min Overhead Per Table = <b>'.($overhead*100).'%</b>
			<br />
			Min Unused Space Per Table = <b>'.($freespace != 'any' ? (($freespace/1000).' kB') : $freespace).'</b>
			<br /><br />
			</i></div>';
		
		}
		if(!$found_at_least_a_table)
		{
			echo "<td>There are currently no tables in your DB which need optimization</td>";
		} else 
		{
			foreach( $opt_tables as $tab ) 
			{
?>
				<tr>
<?php 
				$found_at_least_a_table = true;
				echo '<td><input type="checkbox" class="case" value="'.$tab.'" name="tabname[]"/> '.$tab.'</td>';
?>			
				<td> 
				<form action="" method="POST" >
				<input type="hidden" name="table_name_to_optimize" value="<?php echo $tab->$table_name; ?>" />
				</form>
				</td>
				</tr>		
<?php 			
			}
		}
?>
<?php	
   	
   	
   } //end function display_tables()
} //end class

function findFragmentedTables() {
?>
    <div class="wrap">
	<h2>WPDBSpringClean - Optimize DB Tables</h2>
	<div class="wpdbsc_blue_box">
	<p>Sometimes your DB tables can contain a lot of allocated but unused space which is usually created after many deletions and insertions. This in turn increases the overhead
	on your DB and effectively puts it in a non-optimal state.
    <br />WPDBSpringClean can identify and optimize DB tables which are in a non-optimal state, hence making your DB more compact and faster.</p>
    </div>
    <h4>Click the button below to start searching for DB tables which are in a non-optimal state</h4>
	<div style="border-bottom:1px solid #dedede;height:10px"></div>
	<p style="font-style: italic;">Use The Fields Below To Refine Your Search. (These Are Optional And Can Be Left Blank)</p>
    <form action="" method="POST" onsubmit="">
	<table class="form-table">
	<tr valign="top">
	<th style="width:380px" scope="row"> <label for="overhead"> Minimum Percentage Overhead Per Table:</label> </th>
	<td> <input maxlength="3" size="10" name="overhead"/> <span class="description">Enter Integer Between 0-100 (Defaults to 10% if left blank)</span></td>
	</tr>
	<tr valign="top">
	<th scope="row"> <label for="freespace"> Only Show Results For Tables With Unused Space Greater Than: </label> </th>
	<td> <input type="checkbox" name="refine"/>
	<select name="freespace">
	<option value="10"> 10 </option>
	<option value="50"> 50 </option>
	<option value="100"> 100 </option>
	<option value="500"> 500 </option>
	<option value="1000"> 1000 </option>
	</select>
	<span class="description">kB (Leave unchecked if you want to display all search results)</span>
	</td>
	</tr>
	</table>
	<div style="border-bottom:1px solid #dedede;height:10px"></div>
	<br />
	<input type="submit" name="search_for_tables" value="Perform Search" class="button-primary search-db" /> 
	</form>
	<div style="border-bottom:1px solid #dedede;height:10px"></div>
	<br />
<?php 
	$optimizeListTables = new wpdbsc_optimize_admin();
?>
		<form action="" method="POST" >
		<table class="widefat">
		<thead>
		<tr>
		<th style="width:100%;"><input type="checkbox" id="selectall"/> Table Name </th>
		</tr>
		</thead>
		<tfoot>
		<tr>
		<th></th>
		</tr>
		</tfoot>
		<tbody>
<?php
	//Process optimize button submission
	if (isset($_POST['bulk_optimize']) && isset($_POST['tabname'])){
		$optimizeListTables->optimize_table();
	}

	//Process search button submission
	if(isset($_POST['search_for_tables']))
	{	
	    $table_list = $optimizeListTables->find_unoptimized(); //Fetch data...
    	$optimizeListTables->store_in_options($table_list); //store results in wp_options table
    	$saved_list = get_option('wpdbsc_optimize_candidate');
    	$optimizeListTables->display_tables($saved_list);
	} 
	else {
		$saved_list = get_option('wpdbsc_optimize_candidate');
		$optimizeListTables->display_tables($saved_list);
	}

?>
	</tbody>
	</table>
	<br />
	<input type="submit" name="bulk_optimize" value="Optimize" class="button-primary" /> 
	</form>
	<div style="border-bottom:1px solid #dedede;height:10px"></div>
	<h5>Please support me by buying the following amazing plugins:</h5>
	<a href="http://wpsolutions-hq.com/wp/recommended/eStore/" target="_blank">
	<img src="https://s3.amazonaws.com/product_banners/eStore_banner_468_60.gif" alt="WordPress Shopping Cart" border="0" /></a>
	<br /> <br />
	<a href="http://wpsolutions-hq.com/wp/recommended/emember/" target="_blank">
	<img src="https://s3.amazonaws.com/product_banners/wp_emember_banner_468x60.gif" alt="WordPress Membership Plugin" border="0" /></a>        
    </div>
    <script type="text/javascript">
		/* <![CDATA[ */
		jQuery(document).ready(function($) {
			$('div.loading').hide(); //hide the spinner gif after page has successfully loaded
			$('.search-db').on("click", function(){
				$('div.updated').hide();
				$('div.loading').show();
			});
		    // add multiple select / deselect functionality
		    $("#selectall").on("click", function(){
		          $('.case').attr('checked', this.checked);
		    });
		 
		    // if all checkbox are selected, check the selectall checkbox
		    // and viceversa
		    $(".case").on("click", function(){
		 
		        if($(".case").length == $(".case:checked").length) {
		            $("#selectall").attr("checked", "checked");
		        } else {
		            $("#selectall").removeAttr("checked");
		        }
		 
		    });			
		});		
		/* ]]> */
		</script>
<?php
}
?>