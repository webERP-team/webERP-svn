ALTER TABLE `suppliers` ADD COLUMN `defaultgl` VARCHAR(20) NOT NULL DEFAULT '1' AFTER `url`;
ALTER TABLE `suppliers` ADD COLUMN `defaultshipper` INT(11) NOT NULL DEFAULT '0' AFTER `url`;
INSERT INTO `scripts` (`script`, `pagesecurity`, `description`) VALUES ('Z_FixGLTransPeriods', '15', 'Fixes periods where GL transactions were not created correctly');

ALTER TABLE `stockmaster` DROP COLUMN `appendfile`;

-- Updates scripts description:
UPDATE `scripts` SET `description` = 'Defines the settings applicable for the company, including name, address, tax authority reference, whether GL integration used, etc.' WHERE `scripts`.`script` = 'CompanyPreferences.php';
UPDATE `scripts` SET `description` = 'Sets the main system configuration parameters' WHERE `scripts`.`script` = 'SystemParameters.php';
UPDATE `scripts` SET `description` = 'Adds or removes security roles by a system administrator' WHERE `scripts`.`script` = 'WWW_Access.php';
UPDATE `scripts` SET `description` = 'Changes the security token of a script' WHERE `scripts`.`script` = 'PageSecurity.php';
UPDATE `scripts` SET `description` = 'Sets the configuration for geocoding of customers and suppliers' WHERE `scripts`.`script` = 'GeocodeSetup.php';
UPDATE `scripts` SET `description` = 'Customizes the form layout without requiring the use of scripting or technical development' WHERE `scripts`.`script` = 'FormDesigner.php';
UPDATE `scripts` SET `description` = 'Sets the SMTP server' WHERE `scripts`.`script` = 'SMTPServer.php';
UPDATE `scripts` SET `description` = 'Creates a report of the ad-valorem tax -GST/VAT- for the period selected from accounts payable and accounts receivable data' WHERE `scripts`.`script` = 'Tax.php';

INSERT INTO config VALUES ('ShortcutMenu','0');

INSERT INTO config VALUES ('LastDayofWeek','0');

CREATE TABLE `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `surname` varchar(20) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `stockid` varchar(20) NOT NULL COMMENT 'FK with stockmaster',
  `manager` int(11) COMMENT 'an employee also in this table',
  `normalhours` double NOT NULL DEFAULT '40',
  `userid` varchar(20) NOT NULL DEFAULT '' COMMENT 'loose FK with www-users will have some employees who are not users',
  `email` varchar(55) NOT NULL DEFAULT '',
  KEY `surname` (`surname`),
  KEY `firstname` (`firstname`),
  KEY `stockid` (`stockid`),
  KEY `manager` (`manager`),
<<<<<<< HEAD
  KEY `userid` (`userid`);
=======
  KEY `userid` (`userid`),
>>>>>>> upstream/master
  CONSTRAINT `stk_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `scripts` (`script`, `pagesecurity`, `description`) VALUES ('Employees.php', '20', 'Employees requiring time-sheets maintenance and entry ');
INSERT INTO `scripts` (`script`, `pagesecurity`, `description`) VALUES ('Timesheets.php', '1', 'Entry of Timesheets');
INSERT INTO `securitytokens` (`tokenid`, `tokenname`) VALUES ('20', 'Timesheet administrator');
INSERT INTO `securitygroups` (`secroleid`, `tokenid`) VALUES ('8', '20');
INSERT INTO `securitygroups` (`secroleid`, `tokenid`) VALUES ('9', '20');


CREATE TABLE `timesheets` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `wo` int(11) NOT NULL COMMENT 'loose FK with workorders',
  `employeeid` INT NOT NULL,
  `weekending` DATE NOT NULL DEFAULT '1900-01-01',
  `workcentre` varchar(5) NOT NULL COMMENT 'loose FK with workcentres',
  `day1` double NOT NULL default 0,
  `day2` double NOT NULL default 0,
  `day3` double NOT NULL default 0,
  `day4` double NOT NULL default 0,
  `day5` double NOT NULL default 0,
  `day6` double NOT NULL default 0,
  `day7` double NOT NULL default 0,
  `status` tinyint(4) NOT NULL default 0 COMMENT '0=entered 1=submitted 2=approved',
  KEY `workcentre` (`workcentre`),
  KEY `employees` (`employeeid`),
  KEY `wo` (`wo`),
  KEY `weekending` (`weekending`),
  CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`employeeid`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- THIS IS THE LAST SQL QUERY. Updates database version number:
UPDATE config SET confvalue='4.15.1' WHERE confname='VersionNumber';
