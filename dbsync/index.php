<?php

require 'Slim/Slim.php';

$app = new Slim();
$app->get('/eventt/:eid', function($eid){
								//to get event fom T-db by event_id
								$sql = "SELECT event_id, user_id, wf_id, event_msg FROM `events` WHERE event_id=:eid";
								try {
									$dbT = getConnt();
									$stmt = $dbT->prepare($sql); 
									$stmt->bindParam("eid", $eid); 
									$stmt->execute();
									$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
									$dbT = null;
									echo json_encode($wines);
								} catch(PDOException $e) {
									echo '{"error":{"text":'. $e->getMessage() .'}}'; 
								}
							}
		);
$app->get('/max-eventr', function(){
								//to get maximum event_id from R-db
								$sql = "SELECT COALESCE(MAX(event_id),0) AS Mer FROM eventr";
								try {
									$db = getConnection();
									$stmt = $db->query($sql); 
									$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
									$db = null;
									echo json_encode($wines);
								} catch(PDOException $e) {
									echo '{"error":{"text":'. $e->getMessage() .'}}'; 
								}
							}
		);
$app->get('/max-noticet', function(){
								//to get maximum notice_id from T-db
								$sql = "SELECT COALESCE(MAX(notification_id),0) AS Mnt FROM notifications";
								try {
									$dbT = getConnt();
									$stmt = $dbT->query($sql); 
									$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
									$dbT = null;
									echo json_encode($wines);
								} catch(PDOException $e) {
									echo '{"error":{"text":'. $e->getMessage() .'}}'; 
								}
							}
		  );
$app->get('/noticer/:nid', function($nid){
									//to get notification fom R-db by notice_id
									$sql = "SELECT user_id, notice_msg FROM `events` WHERE notice_id=:nid";
									try {
										$db = getConnection();
										$stmt = $db->prepare($sql); 
										$stmt->bindParam("nid", $nid); 
										$stmt->execute();
										$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
										$db = null;
										echo json_encode($wines);
									} catch(PDOException $e) {
										echo '{"error":{"text":'. $e->getMessage() .'}}'; 
									}
								}
		  );
$app->post('/eventr', function(){
							//insert into R-db:eventr Table
							$request = Slim::getInstance()->request();
							$eid = $request->params('eid');
							$uid = $request->params('uid');
							$wfid = $request->params('wfid');
							$msg = $request->params('msg');
							$wine = json_decode($request->getBody());
							$sql = "INSERT INTO eventr (event_id, user_id, wf_id, event_msg) VALUES (:eid, :uid, :wfid, :msg)";
							try {
								$db = getConnection();
								$stmt = $db->prepare($sql);
								$stmt->bindParam("eid", $eid);
								$stmt->bindParam("uid", $uid);
								$stmt->bindParam("wfid", $wfid);
								$stmt->bindParam("msg", $msg);
								$stmt->execute();
								$response = 'event-id: '.$eid.' replicated';
								echo json_encode($response);
								$db = null;
							} catch(PDOException $e) {
								echo '{"error":{"text":'. $e->getMessage() .'}}'; 
							}
						}
		   );
$app->post('/noticet', function(){
								//insert into T-db:notification Table
								$request = Slim::getInstance()->request();
								$nid = $request->params('nid');
								$uid = $request->params('uid');
								$msg = $request->params('msg');
								$wine = json_decode($request->getBody());
								$sql = "INSERT INTO eventr (notice_id, user_id, notice_msg) VALUES (:nid, :uid, :msg)";
								try {
									$dbT = getConnt();
									$stmt = $dbT->prepare($sql);
									$stmt->bindParam("nid", $nid);
									$stmt->bindParam("uid", $uid);
									$stmt->bindParam("msg", $msg);
									$stmt->execute();
									$response = 'notice-id: '.$nid.' replicated';
									echo json_encode($response);
									$dbT = null;
								} catch(PDOException $e) {
									echo '{"error":{"text":'. $e->getMessage() .'}}'; 
								}
							}
		   );
$app->run();

function getConnection() {
	$dbhost="mysql.hostinger.in";
	$dbuser="u833845488_2k6";
	$dbpass="12345678912345";
	$dbname="u833845488_2k6";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
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

