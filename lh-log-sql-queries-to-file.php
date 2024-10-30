<?php
/**
 * Plugin Name: LH Log Sql queries to file
 * Plugin URI: https://lhero.org/portfolio/lh-log-sql-queries-to-file/
 * Description: Save all SQL queries in log file
 * Author: Peter Shaw
 * Author URI: https://shawfactor.com/
 * Version: 1.00
 * Text Domain: lh_log_sql_queries_to_file
 * Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

 if ( ! defined( 'SAVEQUERIES' ) ) {
	define( 'SAVEQUERIES', true );
}


/**
* LH Log Sql queries to file plugin class
*/

if (!class_exists('LH_Log_sql_queries_to_file_plugin')) {


class LH_Log_sql_queries_to_file_plugin {
    
    private static $instance;
    
    static function return_plugin_namespace(){
        
        return 'lh_log_sql_queries_to_file';
        
    }
    
    static function plugin_name(){
        
        return 'LH Log SQL queries to file';
        
        
    }


    static function curpageurl() {
    	$pageURL = 'http';
    
    	if ((isset($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on")){
    		$pageURL .= "s";
    }
    
    	$pageURL .= "://";
    
    	if (($_SERVER["SERVER_PORT"] != "80") and ($_SERVER["SERVER_PORT"] != "443")){
    	    
    		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    
    	} else {
    	    
    		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    
        }
    
    	return $pageURL;
    	
    }

    static function get_ip_address() {  
        
         if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
             
                    //whether ip is from the share internet  
                    $ip = $_SERVER['HTTP_CLIENT_IP'];  
                    
            }  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
                    
                    //whether ip is from the proxy
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
         } else {  
            
                //whether ip is from the remote address 
                 $ip = $_SERVER['REMOTE_ADDR'];  
         } 
         
        return $ip;
        
    }  

    static function write_log($log) {
        
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(plugin_basename( __FILE__ ).' - '.print_r($log, true));
            } else {
                error_log(plugin_basename( __FILE__ ).' - '.$log);
            }
        }
    }
    
    
    static function get_all_queries_log_file_path(){
        
        $log_path = WP_CONTENT_DIR . '/all_sql_queries.log';
    
        return apply_filters(self::return_plugin_namespace().'_get_all_queries_file_path', $log_path);
        
    }

    static function get_error_queries_log_file_path(){
        
        $log_path = WP_CONTENT_DIR . '/error_sql_queries.log';
    
        return apply_filters(self::return_plugin_namespace().'_get_all_queries_file_path', $log_path);
        
    }

    static function get_post_requests_log_file_path(){
        
        $log_path = WP_CONTENT_DIR . '/post_requests.log';
    
        return apply_filters(self::return_plugin_namespace().'_get_http_requests_log_file_path', $log_path);
        
    }

    static function get_http_requests_log_file_path(){
        
        $log_path = WP_CONTENT_DIR . '/http_requests.log';
    
        return apply_filters(self::return_plugin_namespace().'_get_http_requests_log_file_path', $log_path);
        
    }

    public function all_queries_sql_logger() {
        global $wp;   
        global $wpdb;
    
        if (file_exists(self::get_all_queries_log_file_path())){
    
            $size = filesize(self::get_all_queries_log_file_path());
    
            //by default clear the log if it is larger than 1MB.
            $size_threshold = apply_filters(self::return_plugin_namespace().'_all_queries_sql_logger_size_threshold', 1048576);
    
            if (($size > $size_threshold) && file_exists(self::get_all_queries_log_file_path())){
            
                @unlink( self::get_all_queries_log_file_path() );
        
                //self::write_log(self::plugin_name().' file size is '.$size.' bytes which is bigger than the threshold of '.$size_threshold.' bytes  and therefore has been deleted');
        
            } 
    
        }
    
        $log_file = fopen(self::get_all_queries_log_file_path(), 'a');
    
        if (!empty($log_file)){

            fwrite($log_file, "START\n" . date("d-m-Y H:i:s:u")." ".$_SERVER['REQUEST_METHOD']." ". self::curpageurl(). " by ".self::get_ip_address()."\n");
 
            $remove = array("\n");
    
            foreach($wpdb->queries as $q) {
                
                $query=str_replace($remove, ' ', $q[0]);
        
                $write = date("d-m-Y H:i:s:u") ." [".str_pad( $q[1] , 21 ,"0" , STR_PAD_RIGHT )." s] ".$query ." "."[Stack: $q[2]" . "]\n";
        
                if (!empty($write)){
        
                    fwrite($log_file, $write);
        
                }
                
            }
            
            fwrite($log_file, "\n\n");
            fclose($log_file);
    
        }
    }


    public function error_queries_sql_logger() {
    
        //WP already stores query errors in this obscure
    	//global variable, so we can see what we've ended
    	//up with just before shutdown
    	
    	global $EZSQL_ERROR;
	
	
	    if(is_array($EZSQL_ERROR) && count($EZSQL_ERROR)) {
	    
            if (file_exists(self::get_error_queries_log_file_path())){
    
                $size = filesize(self::get_error_queries_log_file_path());
    
                //by default clear the log if it is larger than 1MB.
                $size_threshold = apply_filters(self::return_plugin_namespace().'_error_queries_sql_logger_size_threshold', 1048576);
        
                if (($size > $size_threshold) && file_exists(self::get_error_queries_log_file_path())){
            
                    @unlink( self::get_error_queries_log_file_path() );
        
                    //self::write_log(self::plugin_name().' file size is '.$size.' bytes which is bigger than the threshold of '.$size_threshold.' bytes  and therefore has been deleted');
        
        
                } 
    
	        } 
	    
	        $log_file = fopen(self::get_error_queries_log_file_path(), 'a');
	    
	        if (!empty($log_file)){

                fwrite($log_file, "START " . date("d-m-Y H:i:s:u")." ".$_SERVER['REQUEST_METHOD']." ". self::curpageurl(). "\n\n");
 
                $remove = array("\n");
   			    //and lastly, add the error messages with some line separations for readability
   			    
			    foreach($EZSQL_ERROR AS $error) {
			        
    			    $query_string = str_replace($remove, ' ', $error['query']);
    			    $error_string = str_replace($remove, ' ', $error['error_str']);
    			    $write =  "";
    			    $write .=  "Query String " . $query_string. "\n";
    			    $write .=  "Error String " . $error_string. "\n\n";
			    
	                if (!empty($write)){
        
                        fwrite($log_file, $write);
        
                    }

			    }
    
    
    
    
    
                fwrite($log_file, "\n\n\n\n");
                fclose($log_file);
                
            }
	    
	    }
    
    }


public function post_request_logger() {
    
    
    if (file_exists(self::get_post_requests_log_file_path())){
        
        
        $size = filesize(self::get_post_requests_log_file_path());
    
     //by default clear the log if it is larger than 1MB.
    $size_threshold = apply_filters(self::return_plugin_namespace().'_post_request_logger_size_threshold', 1048576);
    
    if (($size > $size_threshold) && file_exists(self::get_post_requests_log_file_path())){
            
        @unlink( self::get_post_requests_log_file_path() );
        
        //self::write_log(self::plugin_name().' file size is '.$size.' bytes which is bigger than the threshold of '.$size_threshold.' bytes  and therefore has been deleted');
        
        
        } 
        
        
    } 
    
    $log_file = fopen(self::get_post_requests_log_file_path(), 'a');
	    
	    if (!empty($log_file)){
	        
	        fwrite($log_file, "START " . date("d-m-Y H:i:s:u")." ". self::curpageurl()." returned ".http_response_code() ." requested by ".self::get_ip_address()."\n");
	        
	        $write = print_r($_POST, true);
	        
	        
	        
	        if (!empty($write) && !empty($_POST)){
        
        fwrite($log_file, $write);
        
        }
        
        $write = file_get_contents('php://input');
        
        if (!empty($write)){
        
        fwrite($log_file, $write);
        
        }
	        
	        
	        fwrite($log_file, "\n\n\n\n");
            fclose($log_file);
	    }
	        
    
    
}

// Source: https://gist.github.com/hinnerk-a/2846011

public function http_requests_logger( $response, $args, $url ) {
    
    	        if (file_exists(self::get_http_requests_log_file_path())){
    
     $size = filesize(self::get_http_requests_log_file_path());
    
     //by default clear the log if it is larger than 1MB.
    $size_threshold = apply_filters(self::return_plugin_namespace().'_http_requests_logger_size_threshold', 1048576);
    
       if (($size > $size_threshold) && file_exists(self::get_http_requests_log_file_path())){
            
        @unlink( self::get_http_requests_log_file_path() );
        
        //self::write_log(self::plugin_name().' file size is '.$size.' bytes which is bigger than the threshold of '.$size_threshold.' bytes  and therefore has been deleted');
        
        
        } 
    
	   } 
    
    
    
    
 

	// parse request and response body to a hash for human readable log output
	$log_response = $response;
	if ( isset( $args['body'] ) && is_string($args['body']) ) {
		parse_str( $args['body'], $args['body_parsed'] );
	} elseif (is_array($args['body'])){
	
	$args['body_parsed'] = $args['body'];
}
	if ( isset( $log_response['body'] ) ) {
		parse_str( $log_response['body'], $log_response['body_parsed'] );
	}
	
	$do_log = apply_filters(self::return_plugin_namespace().'_http_requests_do_log', true, $response, $args, $url );
	
	if (!empty($do_log)){
	
	
	// write into logfile
	file_put_contents( self::get_http_requests_log_file_path(), date( 'c' )." ".$url." ".json_encode($args)." ".self::curpageurl()."\n"  , FILE_APPEND );
	//file_put_contents(self::get_http_requests_log_file_path(), sprintf("%s,%s",print_r($log_response,true) ));
	
	}
	
	return $response;
}

    public function plugin_init(){
        
        //load translations
        load_plugin_textdomain( self::return_plugin_namespace(), false, basename( dirname( __FILE__ ) ) . '/languages' ); 
            
        //this action logs all queries to a file
        add_action('shutdown', array($this,'all_queries_sql_logger'));
        
        //this action logs queries that had an error to a file
        add_action('shutdown', array($this,'error_queries_sql_logger'));
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
            //this action logs queries that had an error to a file
            add_action('shutdown', array($this,'post_request_logger'));
        
        }
        
        //log all http requests
        add_filter( 'http_response', array($this,'http_requests_logger'), 10, 3 );
        
    
    
    }
    
    
    /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
     
    public static function get_instance(){
        
        if (null === self::$instance) {
            
            self::$instance = new self();
            
        }
 
        return self::$instance;
        
    }
    




    public function __construct() {
        
        //run our hooks on plugins loaded to as we may need checks       
        add_action( 'plugins_loaded', array($this,'plugin_init'));
        
    }
    
    
}

$lh_log_sql_queries_to_file_instance = LH_Log_sql_queries_to_file_plugin::get_instance();


}




?>