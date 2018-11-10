INSERT INTO  `systypes` (`typeid` ,`typename` ,`typeno`) VALUES ('600',  'Auto Supplier Number',  '0');
INSERT INTO config (confname, confvalue) VALUES ('AutoSupplierNo', '0');
DELETE FROM config WHERE confname='DefaultTheme';
INSERT INTO `scripts` (`script` ,`pagesecurity` ,`description` ) VALUES ('PDFWOPrint.php', '11', 'Produces W/O Paperwork');
INSERT INTO `scripts` (`script` ,`pagesecurity` ,`description` ) VALUES ('PDFFGLabel.php', '11', 'Produces FG Labels');
INSERT INTO `scripts` (`script` ,`pagesecurity` ,`description` ) VALUES ('PDFQALabel.php', '2', 'Produces a QA label on receipt of stock');
INSERT INTO `scripts` (`script` ,`pagesecurity` ,`description` ) VALUES ('CustItem.php', '11', 'Customer Items');
ALTER TABLE `woitems` ADD `comments` LONGBLOB NULL DEFAULT NULL ;
ALTER TABLE  `www_users` CHANGE  `modulesallowed`  `modulesallowed` VARCHAR( 25 ) NOT NULL;
INSERT INTO scripts VALUES ('CostUpdate','10','NB Not a script but allows users to maintain item costs from withing StockCostUpdate.php');
CREATE TABLE `custitem` (
  `debtorno` char(10) NOT NULL DEFAULT '',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `cust_part` varchar(20) NOT NULL DEFAULT '',
  `cust_description` varchar(30) NOT NULL DEFAULT '',
  `customersuom` char(50) NOT NULL DEFAULT '',
  `conversionfactor` double NOT NULL DEFAULT '1',
  PRIMARY KEY (`debtorno`,`stockid`),
  KEY `StockID` (`stockid`),
  KEY `Debtorno` (`debtorno`),
  CONSTRAINT ` custitem _ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  CONSTRAINT ` custitem _ibfk_2` FOREIGN KEY (`debtorno`) REFERENCES `debtorsmaster` (`debtorno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER table pricematrix ADD column currabrev char(3) NOT NULL DEFAULT '';
ALTER table pricematrix ADD column startdate date NOT NULL DEFAULT '0000-00-00';
ALTER table pricematrix ADD column enddate date NOT NULL DEFAULT '9999-12-31';
ALTER table pricematrix DROP PRIMARY KEY;
ALTER table pricematrix ADD PRIMARY KEY (`salestype`,`stockid`,`currabrev`,`quantitybreak`,`startdate`,`enddate`);
ALTER table pricematrix DROP KEY `DiscountCategory`;
ALTER table pricematrix ADD KEY currabrev(`currabrev`);
ALTER table pricematrix ADD KEY stockid(`stockid`);
ALTER TABLE  `debtortrans` CHANGE  `consignment`  `consignment` VARCHAR( 20 ) NOT NULL DEFAULT  '';
ALTER TABLE `workorders` ADD `closecomments` LONGBLOB NULL DEFAULT NULL ;

CREATE TABLE IF NOT EXISTS `relateditems` (
  `stockid` varchar(20) CHARACTER SET utf8 NOT NULL,
  `related` varchar(20) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`stockid`,`related`),
  UNIQUE KEY `Related` (`related`,`stockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO  `scripts` (`script` ,`pagesecurity` ,`description`) VALUES ('RelatedItemsUpdate.php',  '2',  'Maintains Related Items');
INSERT INTO scripts VALUES('Z_ImportDebtors.php',15,'Import debtors by csv file');
ALTER table purchorders MODIFY tel varchar(30) NOT NULL DEFAULT '';

ALTER TABLE banktrans DROP KEY ref_10, DROP KEY ref_9, DROP KEY ref_8, DROP KEY ref_7;
ALTER TABLE banktrans DROP KEY ref_6, DROP KEY ref_5, DROP KEY ref_4, DROP KEY ref_3, DROP KEY ref_2;
ALTER TABLE gltrans DROP KEY tag_2, DROP KEY tag_3, DROP KEY tag_4;
ALTER TABLE mrpdemandtypes DROP KEY mrpdemandtype;
ALTER TABLE salescatprod DROP KEY manufacturers_id_2;
ALTER TABLE salescatprod DROP KEY manufacturers_id;
ALTER TABLE stockrequest DROP KEY departmentid_2;
ALTER TABLE stockrequest DROP KEY loccode_2;
ALTER TABLE stockrequestitems DROP KEY stockid_2, DROP KEY dispatchid_2;
INSERT INTO scripts VALUES('Dashboard.php',1,'Display outstanding debtors, creditors etc');

INSERT INTO `scripts` ( `script` , `pagesecurity` , `description` ) VALUES ('Z_MakeLocUsers.php', '15', 'Create User Location records');
INSERT INTO `scripts` ( `script` , `pagesecurity` , `description` ) VALUES ('LocationUsers.php', '15', 'User Location Maintenance');
INSERT INTO `scripts` ( `script` , `pagesecurity` , `description` ) VALUES ('AgedControlledInventory.php', '11', 'Report of Controlled Items and their age');

CREATE TABLE IF NOT EXISTS `locationusers` (
  `loccode` varchar(5) NOT NULL,
  `userid` varchar(20) NOT NULL,
  `canview` tinyint(4) NOT NULL DEFAULT '0',
  `canupd` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`loccode`,`userid`),
  KEY `UserId` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO locationusers (userid, loccode, canview, canupd)
		SELECT www_users.userid, locations.loccode,1,1
		FROM www_users CROSS JOIN locations
		LEFT JOIN locationusers
		ON www_users.userid = locationusers.userid
		AND locations.loccode = locationusers.loccode
        WHERE locationusers.userid IS NULL;

-- ALTER TABLE  `mrpparameters` ADD  `userldemands` VARCHAR( 5 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'use RL requirements or not' AFTER  `usemrpdemands`;

INSERT INTO `scripts` ( `script` , `pagesecurity` , `description` ) VALUES ('WOCanBeProducedNow.php', '4', 'List of WO items that can be produced with available stock in location');
INSERT INTO `scripts` ( `script` , `pagesecurity` , `description` ) VALUES ('PrintWOItemSlip.php', '4', 'PDF WO Item production Slip ');

ALTER TABLE  `locations` ADD  `usedforwo` TINYINT( 4 ) NOT NULL DEFAULT  '1' AFTER  `internalrequest`;
ALTER TABLE  `bankaccounts` ADD  `importformat` VARCHAR( 10 ) NOT NULL DEFAULT  '';
ALTER TABLE  `audittrail` ADD INDEX (  `transactiondate` );
ALTER TABLE stockmoves MODIFY price DECIMAL(21,5) NOT NULL DEFAULT '0.00000';

INSERT INTO `scripts` ( `script` , `pagesecurity` , `description` ) VALUES ('SalesTopCustomersInquiry.php', 2, 'Shows the sales to the top customers');
ALTER TABLE  `stockmoves` ADD  `userid` VARCHAR( 20 ) NOT NULL , ADD INDEX (  `userid` ) ;

UPDATE config SET confvalue='4.11.5' WHERE confname='VersionNumber';



