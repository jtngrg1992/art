<?php
	include('connection.php');
	if($_SERVER['REQUEST_METHOD']=="GET"){
		$response['success']="false";
		$response['data']=0;
		$keys=array_keys($_GET);
		foreach($keys as $k){
			if($k=='artist'){  //searching for paintings using artist name as criteria
				// $skip=0;
				// if($_GET['page']){
				// 	$page=$_GET['page'];
				// 	if($page>0)
				// 		$page=$page-1;
				// 	$skip=5*$page;
				// }
				$artist=explode(",",$_GET['artist']);
				$response['success']="true";
				$response['data']=array();
				foreach($artist as $name){
					$search=array('ARTIST'=>$name);
					$dat=$coll->find($search);
					foreach($dat as $id=>$value){
					$response['data'][]=$value;
					}
		 }
		
				}//--ends--
			if ($k=='artists') { //sending away list of all artists
					$response['success']="true";
					$response['data']=array();
					$search=array("distinct" => "paintings", "key" => "ARTIST");
					$dat=$db->command($search);
					foreach($dat['values'] as $artist){
						$response['data'][]=$artist;
					 }
					}//--ends--
			if ($k=='paintings') { //sending away list of all paintings
					$skip=0;
					if($_GET['page']){
					$page=$_GET['page'];
					if($page>0)
						$page=$page-1;
					$skip=5*$page;
				}
					$response['success']="true";
					$response['data']=array();
					$dat=$coll->find()->skip($skip)->limit(5);
					foreach($dat as $id=>$val)
						$response['data'][]=$val;
				}	

		}
		 echo json_encode($response);
	}

	if($_SERVER['REQUEST_METHOD']=="POST"){
		$data=json_decode((file_get_contents('php://input')));
		foreach($data as $x) {
			$usd = $x->usdprice;
			$max_usd_price = $x->maxusdprice;
			$min_usd_price = $x->minusdprice;
			$inr = $x->inrprice;
			$max_inr_price = $x->maxinrprice;
			$min_inr_price = $x->mininrprice;
			$newdata = array('$set' => array("USD_Price" => "$usd", "USD_Max_Price" => "$max_usd_price", "USD_Min_Price" => "$min_usd_price", "INR_Price" => "$inr", "INR_Max_Price" => "$max_inr_price", "INR_min_price" => "$min_inr_price"));
			$id = $x->id;
			$search = array("ID" => $id);
			$coll->update($search, $newdata);
		}
	}

	