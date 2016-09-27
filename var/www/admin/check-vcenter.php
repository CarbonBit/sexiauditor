<?php
require("session.php");
$title = "vCenter Checks";
$additionalStylesheet = array(  'css/jquery.dataTables.min.css',
                                'css/whhg.css',
                                'css/bootstrap-datetimepicker.css');
$additionalScript = array(  'js/jquery.dataTables.min.js',
                            'js/jszip.min.js',
                            'js/dataTables.autoFill.min.js',
                            'js/dataTables.bootstrap.min.js',
                            'js/dataTables.buttons.min.js',
                            'js/autoFill.bootstrap.min.js',
                            'js/buttons.bootstrap.min.js',
                            'js/buttons.colVis.min.js',
                            'js/buttons.html5.min.js',
                            'js/file-size.js',
                            'js/moment.js',
                            'js/bootstrap-datetimepicker.js');
require("header.php");
require("helper.php");

try
{
  
  # Main class loading
  $check = new SexiCheck();
  # Header generation
  $check->displayHeader($_SERVER['SCRIPT_NAME']);
  
}
catch (Exception $e)
{
  
  # Any exception will be ending the script, we want exception-free run
  # CSS hack for navbar margin removal
  echo '  <style>#wrapper { margin-bottom: 0px !important; }</style>'."\n";
  require("exception.php");
  exit;
  
} # END try

if ($check->getModuleSchedule('vcSessionAge') != 'off')
{
  
  $vcSessionAge = ($check->getConfig('vcSessionAge') != 'undefined') ? $check->getConfig('vcSessionAge') : 0;
  $check->displayCheck([  'sqlQuery' => "SELECT DATEDIFF('" . $check->getSelectedDate() . "', main.lastActiveTime) as age, v.vcname as vcenter, main.lastActiveTime, main.userName, main.ipAddress, main.userAgent FROM sessions main INNER JOIN vcenters v ON main.vcenter = v.id WHERE main.lastActiveTime < '" . $check->getSelectedDate() . "' - INTERVAL $vcSessionAge DAY AND userName NOT LIKE '%vpxd-extension%' AND userName NOT LIKE '%vsphere-webclient%'",
                          "id" => "VCSESSIONAGE",
                          'thead' => array('Session Age', 'Last ActiveTime', 'UserName', 'ipAddress', 'UserAgent', 'vCenter'),
                          'tbody' => array('"<td>".round($entry["age"])."</td>"', '"<td>".$entry["lastActiveTime"]."</td>"', '"<td>".$entry["userName"]."</td>"', '"<td>".$entry["ipAddress"]."</td>"', '"<td>".$this->getUserAgent($entry["userAgent"])."</td>"', '"<td>".$entry["vcenter"]."</td>"'),
                          'order' => '[ 0, "desc" ]',
                          'columnDefs' => '{ "orderable": false, className: "dt-body-center", "targets": [ 4 ] }']);

} # END if ($check->getModuleSchedule('vcSessionAge') != 'off')

if ($check->getModuleSchedule('vcLicenceReport') != 'off')
{
  
  $check->displayCheck([  'sqlQuery' => "SELECT v.vcname as vcenter, main.name, main.costUnit, main.total, main.used, main.licenseKey FROM licenses main INNER JOIN vcenters v ON main.vcenter = v.id WHERE true",
                          "id" => "VCLICENCEREPORT",
                          'thead' => array('Name', 'Unit', 'Total', 'Used', 'licenseKey', 'vCenter'),
                          'tbody' => array('"<td>".$entry["name"]."</td>"', '"<td>".$entry["costUnit"]."</td>"', '"<td>".$entry["total"]."</td>"', '"<td>".$entry["used"]."</td>"', '"<td>".'.(($check->getConfig('showPlainLicense') == 'disable') ? 'substr($entry["licenseKey"], 0, 5) . "-#####-#####-#####-" . substr($entry["licenseKey"], -5)' : '$entry["licenseKey"]').'."</td>"', '"<td>".$entry["vcenter"]."</td>"')]);

} # END if ($check->getModuleSchedule('vcLicenceReport') != 'off')

if ($check->getModuleSchedule('vcPermissionReport') != 'off')
{
  
  $check->displayCheck([  'sqlQuery' => "SELECT main.id FROM permissions AS main INNER JOIN vcenters v ON main.vcenter = v.id WHERE true",
                          "id" => "VCPERMISSIONREPORT",
                          "typeCheck" => 'ssp',
                          'thead' => array('Inventory Path', 'Principal', 'Role', 'vCenter'),
                          'columnDefs' => '{ "visible": false, "targets": [ 4 ] }']);

} # END if ($check->getModuleSchedule('vcPermissionReport') != 'off')

if ($check->getModuleSchedule('vcCertificatesReport') != 'off')
{
  
  $check->displayCheck([  'sqlQuery' => "SELECT v.vcname as vcenter, main.type, main.url, main.start, main.end, DATEDIFF(main.end, '" . $check->getSelectedDate() . "') as expiry FROM certificates main INNER JOIN vcenters v ON main.vcenter = v.id WHERE true",
                          "id" => "VCCERTIFICATESREPORT",
                          'thead' => array('Type', 'URL', 'Trust Start', 'Trust End', 'Expiry (d)', 'vCenter'),
                          'tbody' => array('"<td>".$entry["type"]."</td>"', '"<td>".$entry["url"]."</td>"', '"<td>".$entry["start"]."</td>"', '"<td>".$entry["end"]."</td>"', '"<td>".$entry["expiry"]."</td>"', '"<td>".$entry["vcenter"]."</td>"'),
                          'order' => '[ 4, "asc" ]']);

} # END if ($check->getModuleSchedule('vcCertificatesReport') != 'off')

?>
	</div>
<?php require("footer.php"); ?>
