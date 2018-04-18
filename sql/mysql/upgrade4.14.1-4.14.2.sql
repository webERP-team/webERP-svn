ALTER TABLE `banktrans` ADD `chequeno` VARCHAR(16) NOT NULL DEFAULT '' AFTER `currcode`;
ALTER TABLE `supptrans` ADD `chequeno` VARCHAR(16) NOT NULL DEFAULT '' AFTER `hold`;
ALTER TABLE `supptrans` ADD `void` TINYINT(1) NOT NULL DEFAULT 0 AFTER `chequeno`;
ALTER table `supptrans` DROP KEY `TypeTransNo`;
ALTER table `supptrans` ADD KEY `TypeTransNo`(`transno`,`type`);

ALTER TABLE `pcexpenses` ADD COLUMN `taxcatid` TINYINT(4) NOT NULL DEFAULT 1 AFTER `tag`;
ALTER TABLE `pctabs` ADD COLUMN `defaulttag` TINYINT(4) NOT NULL DEFAULT 0 AFTER `glaccountpcash`;
ALTER TABLE `pctabs` ADD COLUMN `taxgroupid` TINYINT(4) NOT NULL DEFAULT 1 AFTER `defaulttag`;
ALTER TABLE `pctabs` ADD COLUMN `authorizerexpenses` VARCHAR(20) NOT NULL AFTER `authorizer`;
UPDATE `pctabs` SET authorizerexpenses=authorizer;
ALTER TABLE `pcashdetails` ADD COLUMN `tag` INT(11) NOT NULL DEFAULT 0 AFTER `tabcode`;
INSERT INTO `scripts` (`script`, `pagesecurity`, `description`) VALUES ('PcAuthorizeCash.php', '6', 'Authorisation of assigned cash');
INSERT IGNORE INTO `scripts` (`script`, `pagesecurity`, `description`) VALUES ('Z_RemovePurchaseBackOrders.php', '1', 'Removes all purchase order back orders');
CREATE TABLE `pcashdetailtaxes` (
	`counterindex` INT(20) NOT NULL AUTO_INCREMENT,
	`pccashdetail` INT(20) NOT NULL DEFAULT 0,
	`calculationorder` TINYINT(4) NOT NULL DEFAULT 0,
	`description` VARCHAR(40) NOT NULL DEFAULT '',
	`taxauthid` TINYINT(4) NOT NULL DEFAULT '0',
	`purchtaxglaccount` VARCHAR(20) NOT NULL DEFAULT '',
	`taxontax` TINYINT(4) NOT NULL DEFAULT 0,
	`taxrate` DOUBLE NOT NULL DEFAULT 0.0,
	`amount` DOUBLE NOT NULL DEFAULT 0.0,
	PRIMARY KEY(counterindex)
) Engine=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `custbranch` CHANGE `lat` `lat` FLOAT(12,8) NOT NULL DEFAULT '0.00000000';
ALTER TABLE `custbranch` CHANGE `lng` `lng` FLOAT(12,8) NOT NULL DEFAULT '0.00000000';

ALTER TABLE pcashdetails MODIFY receipt text COMMENT 'Column redundant. Replaced by receipt file upload. Nov 2017.';
INSERT INTO `scripts` (`script` ,`pagesecurity` ,`description`) VALUES ('BankAccountBalances.php',  '1',  'Shows bank accounts authorised for with balances');

ALTER TABLE `stockserialitems` ADD `createdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP, ADD INDEX ( `createdate` );
UPDATE stockserialitems SET createdate = NULL;

UPDATE stockserialitems as stockserialitems SET createdate =
(SELECT  trandate FROM (select trandate, stockserialitems.serialno, stockserialitems.stockid FROM stockserialitems
LEFT JOIN stockserialmoves ON stockserialitems.serialno=stockserialmoves.serialno
LEFT JOIN stockmoves ON stockserialmoves.stockmoveno=stockmoves.stkmoveno
GROUP BY stockserialitems.stockid, stockserialitems.serialno
ORDER BY trandate) AS ssi
WHERE ssi.serialno=stockserialitems.serialno
AND ssi.stockid=stockserialitems.stockid);

INSERT INTO `scripts` ( `script` , `pagesecurity` , `description` ) VALUES ('GeneratePickingList.php', '11', 'Generate Picking List');
INSERT INTO `scripts` ( `script` , `pagesecurity` , `description` ) VALUES ('PickingLists.php', '11', 'Picking List Maintenance');
INSERT INTO `scripts` ( `script` , `pagesecurity` , `description` ) VALUES ('PickingListsControlled.php', '11', 'Picking List Maintenance - Controlled');
INSERT INTO `scripts` ( `script` , `pagesecurity` , `description` ) VALUES ('SelectPickingLists.php', '11', 'Picking List Lists');
INSERT INTO `scripts` ( `script` , `pagesecurity` , `description` ) VALUES ('PDFAck.php', '15', '');
INSERT INTO `scripts` ( `script` , `pagesecurity` , `description` ) VALUES ('PDFShipLabel.php', '15', '');

ALTER TABLE `salesorders` ADD `internalcomment` BLOB NULL DEFAULT NULL;

INSERT INTO `config`(`confname`, `confvalue`) VALUES ('MaxSerialItemsIssued','50');

CREATE TABLE IF NOT EXISTS pickreq (
	`prid` int not null auto_increment,
	`initiator` varchar(20) not null default '',
	`shippedby` varchar(20) not null default '',
	`initdate` date not null default '0000-00-00',
	`requestdate` date not null default '0000-00-00',
	`shipdate` date not null default '0000-00-00',
	`status` varchar(12) not null default '',
	`comments` text default null,
	`closed` tinyint not null default '0',
	`loccode` varchar(5) not null default '',
	`orderno` int not null default '1',
	`consignment` varchar(15) NOT NULL DEFAULT '',
	`packages` int(11) NOT NULL DEFAULT '1' COMMENT 'number of cartons',
	PRIMARY KEY (`prid`),
	key (`orderno`),
	key (`requestdate`),
	key (`shipdate`),
	key (`status`),
	key (`closed`),
	key (`loccode`),
	CONSTRAINT FOREIGN KEY(`loccode`) REFERENCES `locations`(`loccode`),
	constraint foreign key (`orderno`) REFERENCES salesorders(`orderno`)
) Engine=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS pickreqdetails (
	`detailno` int not null auto_increment,
	`prid` int not null default '1',
	`orderlineno` int not null default '0',
	`stockid` varchar(20) not null default '',
	`qtyexpected` double not null default '0',
	`qtypicked` double not null default '0',
	`invoicedqty` double not null default '0',
	`shipqty` double not null default '0',
	PRIMARY KEY (`detailno`),
	key (`prid`),
	key (`stockid`),
	constraint foreign key (`stockid`) REFERENCES stockmaster(`stockid`),
	constraint foreign key (`prid`) REFERENCES pickreq(`prid`)
) Engine=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS pickserialdetails (
	`serialmoveid` int not null auto_increment,
	`detailno` int not null default '1',
	`stockid` varchar(20) not null default '',
	`serialno` varchar(30) not null default '',
	`moveqty` double not null default '0',
	PRIMARY KEY (`serialmoveid`),
	key (`detailno`),
	key (`stockid`,`serialno`),
	key (`serialno`),
	CONSTRAINT FOREIGN KEY (`detailno`) REFERENCES pickreqdetails (`detailno`),
	CONSTRAINT FOREIGN KEY (`stockid`,`serialno`) REFERENCES `stockserialitems`(`stockid`,`serialno`)
) Engine=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO pickreq (prid, initdate, requestdate, shipdate, orderno, closed, loccode )
     SELECT pickinglists.pickinglistno, dateprinted, pickinglistdate, deliverynotedate, pickinglists.orderno, IF(qtyexpected = qtypicked, 1, 0), fromstkloc
       FROM pickinglists
           JOIN pickinglistdetails ON pickinglists.pickinglistno = pickinglistdetails.pickinglistno
           JOIN salesorders ON pickinglists.orderno = salesorders.orderno;

INSERT INTO pickreqdetails (prid, orderlineno, stockid, qtyexpected, qtypicked, invoicedqty, shipqty )
     SELECT pickinglistdetails.pickinglistno, pickinglistdetails.orderlineno, stkcode, qtyexpected, qtypicked, qtypicked, qtypicked
       FROM pickinglistdetails
           JOIN pickinglists ON pickinglistdetails.pickinglistno = pickinglists.pickinglistno
           JOIN salesorderdetails ON salesorderdetails.orderno = pickinglists.orderno;

INSERT INTO `scripts` ( `script` , `pagesecurity` , `description` ) VALUES ('Z_ChangeSalesmanCode.php', '15', '');

CREATE TABLE `pcreceipts` (
	`counterindex` INT(20) NOT NULL AUTO_INCREMENT,
	`pccashdetail` INT(20) NOT NULL DEFAULT 0 COMMENT 'Expenses record identity',
	`hashfile` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'MD5 hash of uploaded receipt file',
	`type` varchar(80) NOT NULL DEFAULT '' COMMENT 'Mime type of uploaded receipt file',
	`extension` varchar(4) NOT NULL DEFAULT '' COMMENT 'File extension of uploaded receipt',
	`size` int(20) NOT NULL DEFAULT 0 COMMENT 'File size of uploaded receipt',
	PRIMARY KEY (`counterindex`),
	CONSTRAINT `pcreceipts_ibfk_1` FOREIGN KEY (`pccashdetail`) REFERENCES `pcashdetails` (`counterindex`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE pcashdetails ADD COLUMN purpose text NULL AFTER posted;

INSERT INTO `scripts` ( `script` , `pagesecurity` , `description` ) VALUES ('GLAccountGraph.php', '8', '');


UPDATE config SET confvalue='4.15' WHERE confname='VersionNumber';


