<?php


$PageSecurity = 1;
$PathPrefix= $_SERVER['HTTP_HOST'].$RootPath.'/../../';
//include('../../includes/session.php');
include('../../xmlrpc/lib/xmlrpc.inc');
include('../../api/api_errorcodes.php');

$Title = 'API documentation';

echo '<html xmlns="http://www.w3.org/1999/xhtml"><head><title>' . $Title . '</title>';
echo '<link REL="shortcut icon" HREF="'. $RootPath.'/favicon.ico">';
echo '<link REL="icon" HREF="' . $RootPath.'/favicon.ico">';
echo '<meta http-equiv="Content-Type" content="text/html; charset=' . _('iso-8859-1') . '">';
echo '<link href="'.$RootPath. '/../../css/'. $_SESSION['Theme'] .'/default.css" REL="stylesheet" TYPE="text/css">';
echo '</head>';

echo '<body>';

$weberpuser = $_SESSION['UserID'];
$sql="SELECT password FROM www_users WHERE userid='" . $weberpuser . "'";
$result=DB_query($sql);
$myrow=DB_fetch_array($result);
$weberppassword = $myrow[0];

$ServerURL = "http://". $_SERVER['HTTP_HOST'].$RootPath."/../../api/api_xml-rpc.php";
$DebugLevel = 0; //Set to 0,1, or 2 with 2 being the highest level of debug info

$msg = new xmlrpcmsg("system.listMethods", array());

$client = new xmlrpc_client($ServerURL);
$client->setDebug($DebugLevel);

$response = $client->send($msg);
$answer = php_xmlrpc_decode($response->value());

for ($i=0; $i<sizeof($answer); $i++) {
	echo '<p><table border=1><tr><th colspan=3><h4>'._('Method name')._('  -  ').'<b>'.$answer[$i].'</b></h4></th></tr>';
	$method = php_xmlrpc_encode($answer[$i]);
	$msg = new xmlrpcmsg("system.methodHelp", array($method));

	$client = new xmlrpc_client($ServerURL);
	$client->setDebug($DebugLevel);

	$response = $client->send($msg);
	$signature = php_xmlrpc_decode($response->value());
	echo $signature.'<br />';
}

echo '</body>';

?>
