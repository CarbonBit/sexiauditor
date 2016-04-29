<?php require("session.php"); ?>
<?php
$isAdminPage = true;
$title = "Module Selector";
require("header.php");
require("helper.php");

$xmlFile = "conf/modules.xml";
if (is_writable($xmlFile)) {
	$xml = simplexml_load_file($xmlFile);
} else {
	echo '  <div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span> File ' . $xmlFile . ' is not existant or not writeable</div>';
	require("footer.php");
	exit;
}

$xmlSettingsFile = "conf/moduleschedules.xml";
if (is_writable($xmlSettingsFile)) {
	$xmlSettings = simplexml_load_file($xmlSettingsFile);
} else {
	echo '  <div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span> File ' . $xmlSettingsFile . ' is not existant or not writeable</div>';
	require("footer.php");
	exit;
}

# settings initialisation, create missing ones
# useful for managing upgrade/adding new module
foreach ($xml->xpath('//modules/category/module') as $module) {
	if ($xmlSettings->xpath('//modules/module/id[.="' . $module->id . '"]') == null) {
		$newModule = $xmlSettings->addChild('module');
		$newModule->addChild('id', $module->id);
		$newModule->addChild('schedule', 'off');
	}
}

# just adding some fancy format output to avoid oneline xml file
$domxml = new DOMDocument('1.0');
$domxml->preserveWhiteSpace = false;
$domxml->formatOutput = true;
$domxml->loadXML($xmlSettings->asXML());
if (!$domxml->save($xmlSettingsFile)) {
	echo '  <div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span><span class="sr-only">Error:</span> There was an issue saving file ' . $xmlSettingsFile . '</div>';
	require("footer.php");
	exit;
}
?>
<!--override default settings to display custom color -->
<style>
.btn-danger, .btn-success {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
.modulePath {
    font-style: italic;
    font-size: small;
}
</style>
	<div class="container"><br/>
		<div class="panel panel-primary">
			<div class="panel-heading"><h3 class="panel-title">Modules Selector Notes</h3></div>
			<div class="panel-body"><ul>
				<li>This page can be used to enable/disable SexiAuditor modules.</li>
				<li>Module with <i class="glyphicon glyphicon-bookmark glyphicon-danger"></i> sign represent action module, ie does not generate report but realise some currative actions.</li>
				<li>Please refer to the <a href="http://www.sexiauditor.fr/">project website</a> and documentation for more information.</li>
			</ul></div>
	    </div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	foreach (array_keys($_POST) as $postKey) {
		$xmlSettings->xpath('//modules/module/id[.="' . preg_replace('/schedule-(\w+)/i', '${1}', $postKey) . '"]/parent::*')[0]->schedule = $_POST[$postKey];
	}

	if ($xmlSettings->asXML( $xmlSettingsFile )) {
		echo '		<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span><span class="sr-only">Success:</span> Settings successfully saved</div>';
		echo "		<script type=\"text/javascript\">$(window).load(function(){ setTimeout(function(){ $('.alert').fadeOut() }, 2000); });</script>";
	} else {
        echo '		<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span><span class="sr-only">Error:</span> There was an error during settings update</div>';
		// reloading previous file
		$xmlSettings = simplexml_load_file($xmlSettingsFile);
	}
}
?>
		<form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<?php

$tablist = '                <ul class="nav nav-tabs" role="tablist">' . "\n";
$modulelist = '		<div class="tab-content">' . "\n";
$firstmodule = true;
$firstCategory = "";

foreach ($xml->category as $category) {
	$tablist = $tablist . '			<li role="presentation"' . ($firstmodule ? ' class="active"' : '') . '><a href="#' . str_replace(" ", "", strtolower($category->name)) . '" aria-controls="' . str_replace(" ", "", strtolower($category->name)) . '" role="tab" data-toggle="tab">' . $category->name . '</a></li>' . "\n";
	$modulelist = $modulelist . '			<div role="tabpanel" class="tab-pane fade' . ($firstmodule ? ' in active' : '') . '" id="' . str_replace(" ", "", strtolower($category->name)) . '">
				<table class="table table-hover table-noborder"><thead><th>Module</th><th>Description</th><th>Schedule</th></thead><tbody>' . "\n";
	$firstmodule = false;
	foreach ($category->module as $module) {
		$modulelist = $modulelist . '				<tr>
		                        <td class="col-sm-2"><b>' . ($module->type == "action" ? "<i class=\"glyphicon glyphicon-bookmark glyphicon-danger\"></i> " : "") . $module->name . '</b><br />version ' . $module->version . '</td>
                		        <td class="col-sm-6">' .  $module->description . '<br /><span class="modulePath">subroutine ' . $module->id . '()</span></td>
	                	        <td class="col-sm-4">
<div class="btn-group" data-toggle="buttons">
	<button name="radio" class="btn btn-danger' . (($xmlSettings->xpath('//modules/module/id[.="' . $module->id . '"]/parent::*')[0]->schedule == "off") ? ' active' : '') . '"><input type="radio" name="schedule-' . $module->id . '" value="off">Off</button>';
        if ($module->type == "action") {
            $modulelist = $modulelist . '	<button name="radio" class="btn btn-success' . (($xmlSettings->xpath('//modules/module/id[.="' . $module->id . '"]/parent::*')[0]->schedule == "monthly") ? ' active' : '') . '"><input type="radio" name="schedule-' . $module->id . '" value="monthly">Monthly</button>
	<button name="radio" class="btn btn-success' . (($xmlSettings->xpath('//modules/module/id[.="' . $module->id . '"]/parent::*')[0]->schedule == "weekly") ? ' active' : '') . '"><input type="radio" name="schedule-' . $module->id . '" value="weekly">Weekly</button>
	<button name="radio" class="btn btn-success' . (($xmlSettings->xpath('//modules/module/id[.="' . $module->id . '"]/parent::*')[0]->schedule == "daily") ? ' active' : '') . '"><input type="radio" name="schedule-' . $module->id . '" value="daily">Daily</button>
	<button name="radio" class="btn btn-success' . (($xmlSettings->xpath('//modules/module/id[.="' . $module->id . '"]/parent::*')[0]->schedule == "hourly") ? ' active' : '') . '"><input type="radio" name="schedule-' . $module->id . '" value="hourly">Hourly</button>';
        } else {
            $modulelist = $modulelist . '
    <button name="radio" class="btn btn-success' . (($xmlSettings->xpath('//modules/module/id[.="' . $module->id . '"]/parent::*')[0]->schedule == "daily") ? ' active' : '') . '"><input type="radio" name="schedule-' . $module->id . '" value="daily">Daily</button>';
        }
        $modulelist = $modulelist . '
</div>
</td>
		                </tr>' . "\n";
	}
	$modulelist = $modulelist . '              			</tbody></table>
			</div>' . "\n";
}

$tablist .= "                </ul>\n";

echo $tablist;
echo $modulelist;

?>
		<input class="btn btn-success" type="submit" value="Save schedule settings">
		</form>
		</div>
	</div>
<?php require("footer.php"); ?>
