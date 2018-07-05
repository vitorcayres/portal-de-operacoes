<?php

namespace App\Libraries;

use \SlimSession\Helper;

class Ajax extends UserManagerPlatform {

    public function __construct($container){
        $this->container = $container;
    }    

    public function loadtable($hostname, $token, $endpoint){

    	$request = $_REQUEST;

    	$start = ($request['start'] == 0)? 1 : $request['start'];
    	$length = ($request['length'] == 0)? 1 : $request['length'];
    	$page = (int)($start / $request['length']) + 1;

    	$rows = UserManagerPlatform::GET($request['hostname'], $request['token'], '/'.$request['endpoint'] .'?page='. $page . '&limit='. $length);

		$json_data = array( "draw" =>  intval($request['draw']),
							"recordsTotal" => $rows->total,
							"recordsFiltered" => $rows->total,
							"data"	=>	(!empty($rows->data))? $rows->data : []);
		echo json_encode($json_data);
    }

}