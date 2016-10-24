-- --------------------------------------------------------
-- Host:                         sexiauditor.sexibyt.es
-- Server version:               10.0.26-MariaDB-0+deb8u1 - (Debian)
-- Server OS:                    debian-linux-gnu
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for sexiauditor
CREATE DATABASE IF NOT EXISTS `sexiauditor` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `sexiauditor`;


-- Dumping structure for table sexiauditor.clusters
CREATE TABLE IF NOT EXISTS `clusters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vcenter` int(11) NOT NULL,
  `moref` varchar(100) CHARACTER SET utf8 NOT NULL,
  `cluster_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `dasenabled` tinyint(1) NOT NULL,
  `lastconfigissuetime` datetime NOT NULL,
  `lastconfigissue` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `isAdmissionEnable` tinyint(1) NOT NULL,
  `admissionModel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `admissionThreshold` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `admissionValue` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `firstseen` datetime NOT NULL,
  `lastseen` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vcenter` (`vcenter`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table sexiauditor.clusters: ~76 rows (approximately)
DELETE FROM `clusters`;
/*!40000 ALTER TABLE `clusters` DISABLE KEYS */;
INSERT INTO `clusters` (`id`, `vcenter`, `moref`, `cluster_name`, `dasenabled`, `lastconfigissuetime`, `lastconfigissue`, `isAdmissionEnable`, `admissionModel`, `admissionThreshold`, `admissionValue`, `firstseen`, `lastseen`) VALUES
	(1, 0, '', 'Standalone', 0, '0000-00-00 00:00:00', NULL, 0, '', '', '', '0000-00-00 00:00:00', '2099-01-01 00:00:01');
/*!40000 ALTER TABLE `clusters` ENABLE KEYS */;


-- Dumping structure for table sexiauditor.config
CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `configid` varchar(50) CHARACTER SET utf8 NOT NULL,
  `type` int(11) NOT NULL,
  `label` varchar(255) CHARACTER SET utf8 NOT NULL,
  `value` varchar(50) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `configid` (`configid`),
  CONSTRAINT `config_ibfk_1` FOREIGN KEY (`type`) REFERENCES `configtype` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table sexiauditor.config: ~27 rows (approximately)
DELETE FROM `config`;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` (`id`, `configid`, `type`, `label`, `value`) VALUES
	(1, 'dailySchedule', 8, 'Choose the hour used for daily schedule (appliance use UTC format)', '4'),
	(2, 'weeklySchedule', 9, 'Choose the day used for weekly schedule', '4'),
	(3, 'monthlySchedule', 10, 'Choose the day used for monthly schedule (beware of short/long months)', '6'),
	(4, 'powerSystemInfo', 7, 'Choose the desired Power Management Policy for ESX', 'off'),
	(5, 'thresholdHistory', 4, 'Number of days before data is purged (0 to disabled)', '15'),
	(6, 'thresholdCPURatio', 4, 'Threshold for vCPU/pCPU ratio check', '0'),
	(7, 'lang', 6, 'Language of appliance', 'en'),
	(8, 'showPlainLicense', 1, 'Display plain license in License Report instead of \'####\'', 'disable'),
	(9, 'vcSessionAge', 4, 'Number of days before a session is defined as \'old\'', '7'),
	(10, 'hostSSHPolicy', 5, 'Choose the desired policy for SSH service', 'on'),
	(11, 'hostShellPolicy', 5, 'Choose the desired policy for Shell service', 'on'),
	(12, 'datastoreFreeSpaceThreshold', 4, 'Datastore free space threshold %', '30'),
	(13, 'datastoreOverallocation', 4, 'Datastore OverAllocation %', '100'),
	(14, 'networkDVSVSSportsfree', 4, 'Threshold of free port for DVS needed', '40'),
	(15, 'vmSnapshotAge', 4, 'Number of days before a snapshot is defined as \'old\'', '15'),
	(16, 'timeToBuildCount', 4, 'Number of entries \'Time To Build\' page will display (0 for all)', '100'),
	(17, 'smtpAddress', 2, 'SMTP server address (IP, or FQDN) to forward email to', 'smtp.sexibyt.es'),
	(18, 'senderMail', 3, 'Sender email to be used for report export feature', 'sender@sexibyt.es'),
	(19, 'recipientMail', 3, 'Recipient email to be used for report export feature', 'frederic@sexibyt.es'),
	(20, 'pdfAuthor', 2, 'Username that will be used as the \'Author\' of generated PDFs', 'Gordon Freeman'),
	(21, 'showEmpty', 1, 'Show checks that return empty values (ie when there is nothing to report)', 'enable'),
	(22, 'showAuthors', 1, 'Show authors page of generated PDF\'s, be aware that disabling it may kill some kitten...', 'enable'),
	(23, 'sexigrafNode', 2, 'SexiGraf node IP or FQDN (used for Capacity Planning)', 'sexigraf.sexibyt.es'),
	(24, 'capacityPlanningDays', 4, 'Number of days Capacity Planning will used for computationcalculation of \'Days Left\' value', '3'),
	(25, 'showInfinite', 1, 'Does Capacity Planning display clusters with \'Infinite\' days left ?', 'enable'),
	(26, 'showDebug', 1, 'Display debug log in log files (careful, it is really verbose)', 'enable'),
	(27, 'anonymousROInventory', 1, 'Will allow anonymous access to a read-only inventory (on URL /roinv.php)', 'enable');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;


-- Dumping structure for table sexiauditor.configtype
CREATE TABLE IF NOT EXISTS `configtype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table sexiauditor.configtype: ~10 rows (approximately)
DELETE FROM `configtype`;
/*!40000 ALTER TABLE `configtype` DISABLE KEYS */;
INSERT INTO `configtype` (`id`, `type`) VALUES
	(1, 'boolean'),
	(2, 'text'),
	(3, 'email'),
	(4, 'number'),
	(5, 'servicePolicy'),
	(6, 'language'),
	(7, 'powerList'),
	(8, 'off'),
	(9, 'weekly'),
	(10, 'monthly');
/*!40000 ALTER TABLE `configtype` ENABLE KEYS */;


-- Dumping structure for table sexiauditor.moduleCategory
CREATE TABLE IF NOT EXISTS `moduleCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(50) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table sexiauditor.moduleCategory: ~8 rows (approximately)
DELETE FROM `moduleCategory`;
/*!40000 ALTER TABLE `moduleCategory` DISABLE KEYS */;
INSERT INTO `moduleCategory` (`id`, `category`) VALUES
	(1, 'VSAN'),
	(2, 'vCenter'),
	(3, 'Cluster'),
	(4, 'Host'),
	(5, 'Datastore'),
	(6, 'Network'),
	(7, 'Virtual Machine'),
	(8, 'Global');
/*!40000 ALTER TABLE `moduleCategory` ENABLE KEYS */;


-- Dumping structure for table sexiauditor.modules
CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(50) CHARACTER SET utf8 NOT NULL,
  `type` varchar(10) CHARACTER SET utf8 NOT NULL,
  `displayName` varchar(100) CHARACTER SET utf8 NOT NULL,
  `version` decimal(10,0) NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 NOT NULL,
  `category_id` int(11) NOT NULL,
  `schedule` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module` (`module`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table sexiauditor.modules: ~63 rows (approximately)
DELETE FROM `modules`;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` (`id`, `module`, `type`, `displayName`, `version`, `description`, `category_id`, `schedule`) VALUES
	(1, 'inventory', 'action', 'Inventory', 1, 'Virtual Machine inventory with a lot of properties. Hosts and Datastores info will be retrieved as well (used in main page stats page).', 8, 'hourly'),
	(2, 'vcSessionAge', 'report', 'Session Age', 1, 'Display vCenter session that are older than (x)days.', 2, 'daily'),
	(3, 'vcLicenceReport', 'report', 'License Report', 1, 'Display licence consumption based on vCenter licence defined.', 2, 'daily'),
	(4, 'vcPermissionReport', 'report', 'Permission report', 1, 'Display permission listing with user/role combination.', 2, 'daily'),
	(5, 'vcTerminateSession', 'action', 'Terminate session', 1, '[Action] Kill vCenter session older than (x)days.', 2, 'off'),
	(6, 'vcCertificatesReport', 'report', 'Certificate Report', 1, 'Display soon-to-be-expired certificates for all vCenter components (SSO, WebClient, ...).', 2, 'daily'),
	(7, 'clusterConfigurationIssues', 'report', 'Configuration Issues', 1, 'Configuration Issues report.', 3, 'daily'),
	(8, 'clusterAdmissionControl', 'report', 'Admission Control', 1, 'Admission Control report.', 3, 'daily'),
	(9, 'clusterHAStatus', 'report', 'Cluster Without HA', 1, 'Display cluster without HA.', 3, 'daily'),
	(10, 'clusterDatastoreConsistency', 'report', 'Datastore Consistency', 1, 'Datastore Consistency report.', 3, 'daily'),
	(11, 'clusterMembersVersion', 'report', 'Members Version', 1, 'Members Version Report.', 3, 'daily'),
	(13, 'clusterMembersLUNPathCountMismatch', 'report', 'Members LUN Path count mismatch', 1, 'Members LUN Path count mismatch Report.', 3, 'daily'),
	(14, 'clusterCPURatio', 'report', 'vCPU pCPU table', 1, 'vCPU pCPU table Report.', 3, 'daily'),
	(15, 'clusterTPSSavings', 'report', 'TPS savings', 1, 'TPS savings Report.', 3, 'daily'),
	(16, 'clusterAutoSlotSize', 'action', 'AutoSlotSize', 0, 'AutoSlotSize.', 3, 'off'),
	(17, 'clusterProfile', 'report', 'Profile', 0, 'Profile Report.', 3, 'off'),
	(18, 'hostLUNPathDead', 'report', 'LUN Path Dead', 1, 'LUN Path Dead report.', 4, 'daily'),
	(19, 'hostProfileCompliance', 'report', 'Profile Compliance', 0, 'Profile Compliance report.', 4, 'off'),
	(20, 'hostLocalSwapDatastoreCompliance', 'report', 'LocalSwapDatastore Compliance', 0, 'LocalSwapDatastore Compliance report.', 4, 'off'),
	(21, 'hostSshShell', 'report', 'SSH/shell check', 1, 'SSH/shell check report.', 4, 'daily'),
	(22, 'hostNTPCheck', 'report', 'NTP Check', 1, 'NTP Check report.', 4, 'daily'),
	(23, 'hostDNSCheck', 'report', 'DNS Check', 1, 'DNS Check report.', 4, 'daily'),
	(24, 'hostSyslogCheck', 'report', 'Syslog Check', 1, 'Syslog Check report.', 4, 'daily'),
	(25, 'hostConfigurationIssues', 'report', 'configuration issues', 1, 'configuration issues report.', 4, 'daily'),
	(26, 'hostHardwareStatus', 'report', 'Hardware Status', 1, 'Hardware Status report.', 4, 'daily'),
	(27, 'hostRebootrequired', 'report', 'Reboot required', 1, 'Reboot required report.', 4, 'daily'),
	(28, 'hostFQDNHostnameMismatch', 'report', 'FQDN/hostname mismatch', 1, 'FQDN/hostname mismatch report.', 4, 'daily'),
	(29, 'hostMaintenanceMode', 'report', 'Maintenance Mode', 1, 'maintenance mode report.', 4, 'daily'),
	(30, 'hostballooningzipswap', 'report', 'ballooning/zip/swap', 1, 'ballooning/zip/swap report.', 4, 'daily'),
	(31, 'hostPowerManagementPolicy', 'report', 'PowerManagement Policy', 1, 'PowerManagement Policy report.', 4, 'daily'),
	(32, 'hostBundlebackup', 'action', 'Bundle backup', 1, 'Bundle backup report.', 4, 'off'),
	(33, 'datastoreSpacereport', 'report', 'Space report', 1, 'Space report.', 5, 'daily'),
	(34, 'datastoreOrphanedVMFilesreport', 'report', 'Orphaned VM Files report', 1, 'Will display all files that does not belong to your vSphere platforms. Beware that this module can take a while as it will scan all datastores files.', 5, 'off'),
	(35, 'datastoreOverallocation', 'report', 'Overallocation', 1, 'Overallocation report.', 5, 'daily'),
	(36, 'datastoreSIOCdisabled', 'report', 'SIOC disabled', 1, 'SIOC disabled report.', 5, 'daily'),
	(37, 'datastoremaintenancemode', 'report', 'maintenance mode', 1, 'maintenance mode report.', 5, 'daily'),
	(38, 'datastoreAccessible', 'report', 'Accessible', 1, 'Accessible report.', 5, 'daily'),
	(39, 'networkDVSportsfree', 'report', 'DVS ports free', 1, 'DVS ports free report.', 6, 'daily'),
	(40, 'networkDVPGAutoExpand', 'action', 'DVPG AutoExpand', 0, 'DVPG AutoExpand action.', 6, 'off'),
	(41, 'networkDVSprofile', 'report', 'DVS profile', 0, 'DVS profile report.', 6, 'off'),
	(42, 'vmSnapshotsage', 'report', 'Snapshots age', 1, 'Snapshots age report.', 7, 'daily'),
	(43, 'vmphantomsnapshot', 'report', 'phantom snapshot', 1, 'phantom snapshot report.', 7, 'daily'),
	(44, 'vmconsolidationneeded', 'report', 'consolidation needed', 1, 'consolidation needed report.', 7, 'daily'),
	(45, 'vmcpuramhddreservation', 'report', 'cpu/ram/hdd reservation', 1, 'cpu/ram/hdd reservation report.', 7, 'daily'),
	(46, 'vmcpuramhddlimits', 'report', 'cpu/ram/hdd limits', 1, 'cpu/ram/hdd limits report.', 7, 'daily'),
	(47, 'vmcpuramhotadd', 'report', 'cpu/ram hot-add', 1, 'cpu/ram hot-add report.', 7, 'daily'),
	(48, 'vmToolsPivot', 'report', 'VM Tools Pivot Table', 1, 'Will display a list of all vmtools version group by count.', 7, 'daily'),
	(49, 'vmvHardwarePivot', 'report', 'vHardware Pivot Table', 1, 'Will display a list of all guest hardware version (VHW) group by count.', 7, 'daily'),
	(50, 'vmballoonzipswap', 'report', 'balloon/zip/swap', 1, 'balloon/zip/swap report.', 7, 'daily'),
	(51, 'vmmultiwritermode', 'report', 'multiwriter mode', 1, 'multiwriter mode report.', 7, 'daily'),
	(52, 'vmNonpersistentmode', 'report', 'Non persistent mode', 1, 'Non persistent mode report.', 7, 'daily'),
	(53, 'vmscsibussharing', 'report', 'scsi bus sharing', 1, 'scsi bus sharing report.', 7, 'daily'),
	(54, 'vmInvalidOrInaccessible', 'report', 'VM Invalid Or Inaccessible', 1, 'This module will display VMs that are marked as inaccessible or invalid.', 7, 'daily'),
	(55, 'vmInconsistent', 'report', 'Inconsistent Folder', 1, 'The following VMs are not stored in folders consistent to their names, this may cause issues when trying to locate them from the datastore manually.', 7, 'daily'),
	(56, 'vmRemovableConnected', 'report', 'Removable Connected', 1, 'This module will display VM that have removable devices (floppy, CD-Rom, ...) connected.', 7, 'daily'),
	(57, 'vmGuestIdMismatch', 'report', 'GuestId mismatch', 1, 'GuestId mismatch report.', 7, 'daily'),
	(58, 'vmPoweredOff', 'report', 'Powered Off', 1, 'This module will display VM that are Powered Off. This can be useful to check if this state is expected.', 7, 'daily'),
	(59, 'vmGuestPivot', 'report', 'GuestID Pivot Table', 1, 'Will display a list of all guest OS group by count.', 7, 'daily'),
	(60, 'vmMisnamed', 'report', 'Misnamed based on FQDN', 1, 'Will display VM that have FQDN mismatched with the VM object name.', 7, 'daily'),
	(61, 'VSANHealthCheck', 'report', 'VSAN Health Check', 0, 'Display VSAN information about Health Check.', 1, 'off'),
	(62, 'alarms', 'report', 'Alarms', 1, 'Will display triggered alarms on objects level with status and time of creation.', 8, 'daily'),
	(63, 'hostBuildPivot', 'report', 'Host ESXBuild Pivot Table', 1, 'Will display a list of all ESX build group by count.', 7, 'daily'),
	(64, 'capacityPlanningReport', 'action', 'Capacity Planning Report', 1, 'Send Capacity Planning Report by mail', 8, 'hourly'),
	(999, 'mailAlert', 'action', 'Mail Alert', 0, 'Will send an email with all defective checks', 8, 'daily');
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;


-- Dumping structure for table sexiauditor.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(10) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table sexiauditor.roles: ~2 rows (approximately)
DELETE FROM `roles`;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `role`) VALUES
	(1, 'admin'),
	(2, 'reader');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;


-- Dumping structure for table sexiauditor.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 NOT NULL,
  `displayname` varchar(255) CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `role` int(11) NOT NULL,
  `password` char(128) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table sexiauditor.users: ~6 rows (approximately)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `username`, `displayname`, `email`, `role`, `password`) VALUES
	(1, 'admin', 'Administrator', 'admin@dev.null', 1, '80b0306a724e92e0246b1cddc262988dc9577cd81825e988fcac997c61a9ff2145ab1d16faa48c68c485afd21b698afd85b65e0e940e92c0f52bf1deede67cfd'),
	(2, 'reader', 'Kindle', 'dev@dev.null', 2, '1f40fc92da241694750979ee6cf582f2d5d7d28e18335de05abc54d0560e0f5302860c652bf08d560252aa5e74210546f369fbbbce8c12cfc7957b2652fe9a75');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
