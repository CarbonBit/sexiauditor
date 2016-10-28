<?php
require("session.php");
$title = "Cluster Checks";
$additionalStylesheet = array(  'css/jquery.dataTables.min.css',
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

if ($check->getModuleSchedule('clusterConfigurationIssues') != 'off' && $check->getModuleSchedule('inventory') != 'off')
{
  
  $check->displayCheck([  'sqlQuery' => "SELECT main.cluster_name, main.dasenabled, main.lastconfigissue, main.lastconfigissuetime, v.vcname as vcenter FROM clusters main INNER JOIN vcenters v ON main.vcenter = v.id WHERE main.lastconfigissue NOT LIKE '0'",
                          "id" => "CLUSTERCONFIGURATIONISSUES",
                          'thead' => array('Cluster Name', 'HA Status', 'Last Config Issue', 'Time', 'vCenter'),
                          'tbody' => array('"<td>".$entry["cluster_name"]."</td>"', '"<td>".(($entry["dasenabled"] == "1") ? "<i class=\"glyphicon glyphicon-ok-sign text-success\"></i>" : "<i class=\"glyphicon glyphicon-remove-sign text-danger\"></i>")."</td>"', '"<td>".$entry["lastconfigissue"]."</td>"', '"<td>".$entry["lastconfigissuetime"]."</td>"', '"<td>".$entry["vcenter"]."</td>"'),
                          'columnDefs' => '{ "orderable": false, className: "dt-body-center", "targets": [ 4 ] }']);

} # END if ($check->getModuleSchedule('clusterConfigurationIssues') != 'off' && $check->getModuleSchedule('inventory') != 'off')

if ($check->getModuleSchedule('alarms') != 'off')
{
  
  $check->displayCheck([  'sqlQuery' => "SELECT main.alarm_name, main.status, main.time, main.entityMoRef, v.vcname as vcenter, c.cluster_name as entity FROM alarms main INNER JOIN vcenters v ON main.vcenter = v.id INNER JOIN clusters c ON main.entityMoRef = c.moref WHERE main.entityMoRef LIKE 'ClusterComputeResource%'",
                          "id" => "ALARMSCLUSTER",
                          'thead' => array('Status', 'Alarm', 'Date', 'Name', 'vCenter'),
                          'tbody' => array('"<td>" . $this->alarmStatus[(string) $entry["status"]] . "</td>"', '"<td>" . $entry["name"] . "</td>"', '"<td>" . $entry["time"] . "</td>"', '"<td>" . $entry["entity"] . "</td>"', '"<td>" . $entry["vcenter"] . "</td>"'),
                          'order' => '[ 1, "asc" ]',
                          'columnDefs' => '{ "orderable": false, className: "dt-body-right", "targets": [ 0 ] }']);

} # END if ($check->getModuleSchedule('alarms') != 'off')

if ($check->getModuleSchedule('clusterHAStatus') != 'off' && $check->getModuleSchedule('inventory') != 'off')
{
  
  $check->displayCheck([  'sqlQuery' => "SELECT main.cluster_name, v.vcname as vcenter FROM clusters main INNER JOIN vcenters v ON main.vcenter = v.id WHERE main.dasenabled NOT LIKE '1'",
                          "id" => "CLUSTERHASTATUS",
                          'thead' => array('Cluster Name', 'HA Status', 'vCenter'),
                          'tbody' => array('"<td>".$entry["cluster_name"]."</td>"', '"<td class=\"text-danger\"><i class=\"glyphicon glyphicon-remove-sign\"></i> no HA</td>"', '"<td>".$entry["vcenter"]."</td>"')]);

} # END if ($check->getModuleSchedule('clusterHAStatus') != 'off' && $check->getModuleSchedule('inventory') != 'off')

if ($check->getModuleSchedule('clusterAdmissionControl') != 'off' && $check->getModuleSchedule('inventory') != 'off')
{
  
  $check->displayCheck([  'sqlQuery' => "SELECT main.id FROM clusters main INNER JOIN vcenters v ON main.vcenter = v.id WHERE main.dasenabled = 1 AND (main.isAdmissionEnable = 0 OR (main.isAdmissionEnable = 1 AND main.admissionValue < main.admissionThreshold))",
                          "id" => "CLUSTERADMISSIONCONTROL",
                          "typeCheck" => 'ssp',
                          'thead' => array('Cluster Name', 'isAdmissionEnable', 'admissionThreshold', 'admissionValue', 'vCenter'),
                          'columnDefs' => '{ "orderable": false, className: "dt-body-center", "targets": [ 1, 2, 3 ] }']);

} # END if ($check->getModuleSchedule('clusterAdmissionControl') != 'off' && $check->getModuleSchedule('inventory') != 'off')

if ($check->getModuleSchedule('clusterMembersLUNPathCountMismatch') != 'off' && $check->getModuleSchedule('inventory') != 'off')
{
  
  $check->displayCheck([  'sqlQuery' => "SELECT main.id as clusterId, main.cluster_name as cluster, h.host_name, h.datastorecount, v.vcname as vcenter FROM hosts h INNER JOIN clusters main ON h.cluster = main.id INNER JOIN vcenters v ON h.vcenter = v.id WHERE main.id <> 1",
                          "id" => "CLUSTERDATASTORECONSISTENCY",
                          'typeCheck' => 'majorityPerCluster',
                          'majorityProperty' => 'datastorecount',
                          'thead' => array('Cluster Name', 'Majority Datastore Count', 'Host Name', 'Datastore Count', 'vCenter'),
                          'tbody' => array('"<td>" . $entry["cluster"] . "</td>"', '"<td>" . ($hMajority[$entry["clusterId"]]) . "</td>"', '"<td>" . $entry["host_name"] . "</td>"', '"<td>" . $entry["datastorecount"] . "</td>"', '"<td>" . $entry["vcenter"] . "</td>"')]);

} # END if ($check->getModuleSchedule('clusterMembersLUNPathCountMismatch') != 'off' && $check->getModuleSchedule('inventory') != 'off')

?>
    <h2 class="not-available"><i class="glyphicon glyphicon-remove-sign"></i> Cluster Members Overcommit <small>(Soon)</small></h2>
<?php

if ($check->getModuleSchedule('clusterMembersVersion') != 'off' && $check->getModuleSchedule('inventory') != 'off')
{
  
  $check->displayCheck([  'sqlQuery' => "SELECT main.cluster_name, COUNT(DISTINCT h.esxbuild) as multipleBuild, GROUP_CONCAT(DISTINCT h.esxbuild SEPARATOR ',') as esxbuilds, v.vcname as vcenter FROM clusters main INNER JOIN hosts h ON main.id = h.cluster INNER JOIN vcenters v ON main.vcenter = v.id WHERE true",
                          "sqlQueryGroupBy" =>  "main.cluster_name",
                          "id" => "CLUSTERMEMBERSVERSION",
                          'mismatchProperty' => 'esxbuild',
                          'thead' => array('Cluster Name', 'Compliance', 'Build Number', 'vCenter'),
                          'tbody' => array('"<td>" . $entry["cluster_name"] . "</td>"', '"<td>" . (($entry["multipleBuild"] == 1) ? "<i class=\"glyphicon glyphicon-ok-sign text-success\"></i>" : "<i class=\"glyphicon glyphicon-remove-sign text-danger\"></i>") . "</td>"', '"<td>" . $entry["esxbuilds"] . "</td>"', '"<td>" . $entry["vcenter"] . "</td>"'),
                          'columnDefs' => '{ "orderable": false, className: "dt-body-center", "targets": [ 1 ] }']);

} # END if ($check->getModuleSchedule('clusterMembersVersion') != 'off' && $check->getModuleSchedule('inventory') != 'off')

if ($check->getModuleSchedule('clusterMembersLUNPathCountMismatch') != 'off' && $check->getModuleSchedule('inventory') != 'off')
{
  
  $check->displayCheck([  'sqlQuery' => "SELECT main.id as clusterId, main.cluster_name as cluster, h.host_name, h.lunpathcount, v.vcname as vcenter FROM hosts h INNER JOIN clusters main ON h.cluster = main.id INNER JOIN vcenters v ON h.vcenter = v.id WHERE main.id <> 1",
                          "id" => "CLUSTERMEMBERSLUNPATHCOUNTMISMATCH",
                          'typeCheck' => 'majorityPerCluster',
                          'majorityProperty' => 'lunpathcount',
                          'thead' => array('Cluster Name', 'Majority Path Count', 'Host Name', 'LUN Path Count', 'vCenter'),
                          'tbody' => array('"<td>" . $entry["cluster"] . "</td>"', '"<td>" . ($hMajority[$entry["clusterId"]]) . "</td>"', '"<td>" . $entry["host_name"] . "</td>"', '"<td>" . $entry["lunpathcount"] . "</td>"', '"<td>" . $entry["vcenter"] . "</td>"')]);

} # END if ($check->getModuleSchedule('clusterMembersLUNPathCountMismatch') != 'off' && $check->getModuleSchedule('inventory') != 'off')

if ($check->getModuleSchedule('clusterCPURatio') != 'off' && $check->getModuleSchedule('inventory') != 'off')
{

  $check->displayCheck([  'sqlQuery' => "SELECT SUM(main.numcpu) as vcpu, vc.vcname as vcenter, c.cluster_name, (SELECT SUM(h.numcpucore) FROM hosts h WHERE h.cluster = c.id) as pcpu FROM vms AS main INNER JOIN vcenters AS vc ON main.vcenter = vc.id INNER JOIN hosts AS h ON h.id = main.host INNER JOIN clusters AS c ON c.id = h.cluster  WHERE main.id IN (SELECT MAX(id) FROM vms WHERE lastseen > '" . $check->getSelectedDate() . "' GROUP BY vcenter, moref) AND c.id <> 1",
                          "sqlQueryGroupBy" => "c.id",
                          "id" => "CLUSTERCPURATIO",
                          'thead' => array('Cluster Name', 'pCPU', 'vCPU', 'CPU ratio', 'vCenter'),
                          'tbody' => array('"<td>".$entry["cluster_name"]."</td>"', '"<td>".$entry["pcpu"]."</td>"', '"<td>".$entry["vcpu"]."</td>"', '"<td>".round($entry["vcpu"]/$entry["pcpu"],0)." : 1</td>"', '"<td>".$entry["vcenter"]."</td>"')]);

} # END if ($check->getModuleSchedule('clusterCPURatio') != 'off' && $check->getModuleSchedule('inventory') != 'off')

if ($check->getModuleSchedule('clusterTPSSavings') != 'off' && $check->getModuleSchedule('inventory') != 'off')
{
  
  $check->displayCheck([  'sqlQuery' => "SELECT c.cluster_name FROM hosts main INNER JOIN clusters c ON main.cluster = c.id WHERE true",
                          "sqlQueryGroupBy" => "c.cluster_name",
                          "id" => "CLUSTERTPSSAVINGS",
                          "typeCheck" => 'ssp',
                          'thead' => array('Cluster Name', 'Total Memory', 'TPS Savings', 'Percentage Saved', 'vCenter'),
                          'columnDefs' => '{ "searchable": false, "targets": [ 1, 2, 3 ] }']);

} # END if ($check->getModuleSchedule('clusterTPSSavings') != 'off' && $check->getModuleSchedule('inventory') != 'off')

?>
    <h2 class="not-available"><i class="glyphicon glyphicon-remove-sign"></i> Cluster AutoSlotSize <small>(Soon)</small></h2>
    <h2 class="not-available"><i class="glyphicon glyphicon-remove-sign"></i> Cluster Profile <small>(Soon)</small></h2>
  </div>
<?php require("footer.php"); ?>
