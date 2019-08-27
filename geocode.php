<?php
// geocode.php

//$PageSecurity = 3;
$Title = _('Geocode Generate');

include ('includes/session.php');
include ('includes/header.php');
//include ('includes/SQL_CommonFunctions.inc');

$sql = "SELECT * FROM geocode_param WHERE 1";
$ErrMsg = _('An error occurred in retrieving the information');
$resultgeo = DB_query($sql, $ErrMsg);
$row = DB_fetch_array($resultgeo);

$api_key = $row['geocode_key'];
$center_long = $row['center_long'];
$center_lat = $row['center_lat'];
$map_height = $row['map_height'];
$map_width = $row['map_width'];
$map_host = $row['map_host'];

define("MAPS_HOST", $map_host);
define("KEY", $api_key);

echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/maintenance.png" title="' . _('Geocode Setup') . '" alt="" />' . ' ' . _('Geocoding of Customers and Suppliers')  . '</p>';

// select all the customer branches
$sql = "SELECT * FROM custbranch WHERE 1";
$ErrMsg = _('An error occurred in retrieving the information');
$result = DB_query($sql, $ErrMsg);

// select all the suppliers
$sql = "SELECT * FROM suppliers WHERE 1";
$ErrMsg = _('An error occurred in retrieving the information');
$result2 = DB_query($sql, $ErrMsg);

// Initialize delay in geocode speed
$delay = 0;
$base_url = "http://" . MAPS_HOST . "/maps/api/geocode/xml?address=";

// Iterate through the customer branch rows, geocoding each address


while ($row = DB_fetch_array($result)) {
  $geocode_pending = true;

  while ($geocode_pending) {
    $address = urlencode($row["braddress1"] . "," . $row["braddress2"] . "," . $row["braddress3"] . "," . $row["braddress4"]);
    $id = $row["branchcode"];
    $debtorno =$row["debtorno"];
    $request_url = $base_url . $address . ',&sensor=true';

    echo '<br \>', _('Customer Code'), ': ', $id;


    $xml = simplexml_load_string(utf8_encode(file_get_contents($request_url))) or die("url not loading");
//    $xml = simplexml_load_file($request_url) or die("url not loading");

    $status = $xml->status;

    if (strcmp($status, "OK") == 0) {
      // Successful geocode
      $geocode_pending = false;
      $coordinates = $xml->GeocodeResponse->result->geometry->location;
      $coordinatesSplit = explode(",", $coordinates);
      // Format: Longitude, Latitude, Altitude
      $lat = $xml->result->geometry->location->lat;
      $lng = $xml->result->geometry->location->lng;

      $query = sprintf("UPDATE custbranch " .
             " SET lat = '%s', lng = '%s' " .
             " WHERE branchcode = '%s' " .
 	     " AND debtorno = '%s' LIMIT 1;",
             ($lat),
             ($lng),
             ($id),
             ($debtorno));

      $update_result = DB_query($query);

      if ($update_result==1) {
      echo '<br />'. 'Address: ' . $address . ' updated to geocode.';
      echo '<br />'. 'Received status ' . $status . '<br />';
	}
    } else {
      // failure to geocode
      $geocode_pending = false;
      echo '<br />' . 'Address: ' . $address . _('failed to geocode.');
      echo 'Received status ' . $status . '<br />';
    }
    usleep($delay);
  }
}

// Iterate through the Supplier rows, geocoding each address
while ($row2 = DB_fetch_array($result2)) {
  $geocode_pending = true;

  while ($geocode_pending) {
    $address = $row2["address1"] . ",+" . $row2["address2"] . ",+" . $row2["address3"] . ",+" . $row2["address4"];
    $address = urlencode($row2["address1"] . "," . $row2["address2"] . "," . $row2["address3"] . "," . $row2["address4"]);
    $id = $row2["supplierid"];
    $request_url = $base_url . $address . ',&sensor=true';

    echo '<p>' . _('Supplier Code: ') . $id;

    $xml = simplexml_load_string(utf8_encode(file_get_contents($request_url))) or die("url not loading");
//    $xml = simplexml_load_file($request_url) or die("url not loading");

    $status = $xml->status;

    if (strcmp($status, "OK") == 0) {
      // Successful geocode
      $geocode_pending = false;
      $coordinates = $xml->GeocodeResponse->result->geometry->location;
      $coordinatesSplit = explode(",", $coordinates);
      // Format: Longitude, Latitude, Altitude
      $lat = $xml->result->geometry->location->lat;
      $lng = $xml->result->geometry->location->lng;


      $query = sprintf("UPDATE suppliers " .
             " SET lat = '%s', lng = '%s' " .
             " WHERE supplierid = '%s' LIMIT 1;",
             ($lat),
             ($lng),
             ($id));

      $update_result = DB_query($query);

      if ($update_result==1) {
      echo '<br />' . 'Address: ' . $address . ' updated to geocode.';
      echo '<br />' . 'Received status ' . $status . '<br />';
      }
    } else {
      // failure to geocode
      $geocode_pending = false;
      echo '<br />' . 'Address: ' . $address . ' failed to geocode.';
      echo '<br />' . 'Received status ' . $status . '<br />';
    }
    usleep($delay);
  }
}
echo '<br /><div class="centre"><a href="' . $RootPath . '/GeocodeSetup.php">' . _('Go back to Geocode Setup') . '</a></div>';
include ('includes/footer.php');
?>