<?php
include('httpful.phar');
echo "Strating now: ".date("Y-m-d h:i:sa").PHP_EOL;
echo '====== Replicating Notifications ======'.PHP_EOL;
$uri = "http://2k6.esy.es/dbsync/max-noticet";
$response = \Httpful\Request::get($uri)->send();
 $obj = json_decode($response->body);
$Mnt = $obj[0]->Mnt;
$uri = "http://2k6.esy.es/dbsync/noticer/".($Mnt+1);
$response = \Httpful\Request::get($uri)->send();
$obj = json_decode($response->body);
 if (count($obj)==0)
	echo "DB Updated: Nothing to Replicate. ".date("Y-m-d h:i:sa").PHP_EOL;
echo '====== Replicating Events ======'.PHP_EOL;
while(1) {
$uri = "http://2k6.esy.es/dbsync/max-eventr";
$response = \Httpful\Request::get($uri)->send();
 $obj = json_decode($response->body);
$Mer = $obj[0]->Mer;
$uri = "http://2k6.esy.es/dbsync/eventt/".($Mer+1);
$response = \Httpful\Request::get($uri)->send();
$obj = json_decode($response->body);
 if (count($obj)==0){
	echo "DB Updated: Nothing to Replicate. ".date("Y-m-d h:i:sa").PHP_EOL;
	exit;
}else{
	//echo print_r($obj);
	$eid = $obj[0]->event_id;
	$uid = $obj[0]->user_id;
	$wfid = $obj[0]->wf_id;
	$msg = $obj[0]->event_msg;
	$uri = "http://2k6.esy.es/dbsync/eventr?"."eid=".$eid."&uid=".$uid."&wfid=".$wfid."&msg=".$msg;
	echo "Replicating Event-".$eid.PHP_EOL;
	\Httpful\Request::post($uri)->send();
}}
?>


