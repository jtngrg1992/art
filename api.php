<?php
include('connection.php');
if($_SERVER['REQUEST_METHOD']=="GET"){
	$response['success']="false";
	$response['data']=0;
	$keys=array_keys($_GET);
	foreach($keys as $k){
			if($k=='artist'){  //searching for paintings using artist name as criteria
				$skip=0;
				$page=0;
				if(isset($_GET['page'])){
					$page=$_GET['page'];
					if($page>0)
						$page=$page-1;
					if($page<0)
						$page=0;
					}
					$skip=5*$page;
				
				$artist=explode(",",$_GET['artist']);
				$response['success']="true";
				$response['data']=array();

				foreach($artist as $name){
					$search=array('ARTIST'=>$name);
					// $count=$coll->find(array('ARTIST'=>$name))->count();
					$dat=$coll->find($search)->skip($skip)->limit(5);
					
					foreach($dat as $id=>$value){
						$response['data'][]=$value;
					}
					// $response['count']=$count;
				}

				}//--ends--
			if ($k=='artists') { //sending away list of all artists
				$response['success']="true";
				$response['data']=array();
				// $search=array("distinct" => "paintings", "key" => "ARTIST");
				// $dat=$db->command($search);
				$dat=$coll2->find();
				foreach($dat as $id=>$val){
					// print_r($artist);
					$response['data'][]=$val;
				}
					}//--ends--
			if ($k=='paintings') { //sending away list of all paintings
				$skip=0;
				$page=0;
				if(isset($_GET['page'])){
					$page=$_GET['page'];
					if($page>0)
						$page=$page-1;
					if($page<0)
						$page=0;
				}
					$skip=5*$page;
				
				$response['success']="true";
				$response['data']=array();
				$dat=$coll->find()->skip($skip)->limit(5);
				foreach($dat as $id=>$val)
					$response['data'][]=$val;
			}
			if ($k=='markok'){
				$data=explode(',',$_GET['markok']);
				$id=(int)$data[0];
				$time=$data[1];
				$search=array("ID"=>$id);
				$newdata=array('$set'=>array("OKFlag"=>1,"TIMESTAMP"=>$time));
				$coll->update($search,$newdata);
				$response['success']="true";
				
			}
			if ($k=='marknotok'){
				$data=explode(',',$_GET['marknotok']);
				$id=(int)$data[0];
				$time=$data[1];
				$search=array("ID"=>$id);
				$newdata=array('$set'=>array("OKFlag"=>0,"TIMESTAMP"=>$time));
				$coll->update($search,$newdata);
				$response['success']="true";
			}

			if ($k=="save"){
				$data=explode('|',$_GET['save']);
				
				$new=json_decode($data[0]);
				print_r($new);
				$id=$new->ID;
				$time=$data[1];
				$search=array('ID'=>$id);
				unset($new->_id);
				unset($new->{'$$hashKey'});
				$newdata=(array)$new;
				$newdata['OKFlag']=2;
				$up=array('$set'=>$newdata);
				$coll->update($search,$up);
				$response['success']="true";
				
			}

			if ($k=="artistinfo"){
				$response['success']="true";
				$response['data']=array();
				$id=(int)$_GET['id'];
				$search=array('ID'=>$id);
				$dat=$coll2->find($search);
				foreach($dat as $id=>$val)
					$response['data'][]=$val;

}

			if ($k=="artinfo"){
				if(!isset($_GET['id'])){
					$respnse['data']="Please provide an artist ID";
					break;
				}
				else{
					$skip=0;
					$page=0;
					if(isset($_GET['page'])){
						$page=$_GET['page'];
						if($page>0)
							$page=$page-1;
						if($page<0)
							$page=0;
						}
						$skip=5*$page;

					$response['success']="true";
					$response['data']=array();
					$response['count']=0;
					$response['pages']=0;
					$id=(int)$_GET['id'];
					$search=array('ARTIST_ID'=>$id);
					$dat=$coll->find($search)->skip($skip)->limit(5);
					$response['count']=$coll->count($search);
					$response['pages']=$response['count']/5;
					foreach($dat as $id=>$val)
						$response['data'][]=$val;
				}
			}

			}
	echo json_encode($response);
	$c->close();
}

if($_SERVER['REQUEST_METHOD']=="POST"){
	$response['success']="false";
	$data=json_decode((file_get_contents('php://input')));
	$keys=array_keys($_GET);
	foreach ($keys as $k){
		if($k=="convert"){
			foreach($data as $x) {
			$usd = $x->usdprice;
			$max_usd_price = $x->maxusdprice;
		$min_usd_price = $x->minusdprice;
		$inr = $x->inrprice;
		$max_inr_price = $x->maxinrprice;
		$min_inr_price = $x->mininrprice;
		$newdata = array('$set' => array("USD_Price" => (float)$usd, "USD_Max_Price" => (float)$max_usd_price, "USD_Min_Price" => (float)$min_usd_price, "INR_Price" => (float)$inr, "INR_Max_Price" => (float)$max_inr_price, "INR_min_price" => (float)$min_inr_price));
		$id = $x->id;
		$search = array("ID" => $id);
		$coll->update($search, $newdata);
	}

		}

		else if ($k="save"){
			$id=$data->ID;
			// $time=$data[1];
			$search=array('ID'=>$id);
			unset($data->_id);
			unset($data->{'$$hashKey'});
			$newdata=(array)$data;
			$newdata['OKFlag']=2;
			
			// print_r($newdata);
			$up=array('$set'=>$newdata);
			$coll->update($search,$up);
			$response['success']="true";
			$response['data']=$newdata;
			
			}
	}
	
	echo json_encode($response);
}



