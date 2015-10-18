<?php

require 'Slim/Slim.php';

$app = new Slim();
$app->get('/roles/:id', 'getRoles');//id stands for user_id
$app->get('/wfstart', 'getStartingWf');
$app->post('/start-event', 'addEvent');
$app->get('/notifications/:id', 'getNotifications');//id stands for user_id
$app->run();

function getStartingWf() {
	$sql = "select wf_id, wf_description FROM workflows WHERE wf_parent_id IS NULL ORDER BY wf_description";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$wfs = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($wfs);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getRoles($id) {
	$sql = "SELECT userroles.role_id,roles.role_description FROM userroles JOIN roles ON userroles.role_id=roles.role_id WHERE userroles.user_id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql); 
		$stmt->bindParam("id", $id); 
		$stmt->execute();
		$roles = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($roles);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getConnection() {
	$dbhost="mysql.hostinger.in";
	$dbuser="u833845488_2k6";
	$dbpass="12345678912345";
	$dbname="u833845488_2k6";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

function addEvent() {
	$request = Slim::getInstance()->request();
	$uid = $request->params('uid');
	$wfid = $request->params('wfid');
	$msg = $request->params('msg');
	//$event = json_decode($request->getBody());
	$sql = "INSERT INTO `events` (user_id, wf_id, event_msg) VALUES (:uid, :wfid, :msg)";
	try {
		$dbT = getConnt();
		$stmt = $dbT->prepare($sql); 
		$stmt->bindParam("uid", $uid);
		$stmt->bindParam("wfid", $wfid);
		$stmt->bindParam("msg", $msg);
		$stmt->execute();
		$event_id = $dbT->lastInsertId();
		$response = 'Event-'.$event_id.' has been created with workflow-'.$wfid;
		echo json_encode($response);
		$dbT = null;
	} catch(PDOException $e) {
		//error_log($e->getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getNotifications($id) {
	$sql = "SELECT notice_msg FROM notifications WHERE user_id=:id ORDER BY notification_id DESC";
	try {
		$dbT = getConnt();
		$stmt = $dbT->prepare($sql); 
		$stmt->bindParam("id", $id); 
		$stmt->execute();
		$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
		$dbT = null;
		echo json_encode($wines);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getConnt() {
	$dbhost="mysql.hostinger.in";
	$dbuser="u833845488_2k6t";
	$dbpass="12345678912345";
	$dbname="u833845488_2k6t";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

?>
