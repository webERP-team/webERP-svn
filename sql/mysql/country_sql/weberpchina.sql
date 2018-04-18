-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.27 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             7.0.0.4140
-- Date/time:                    2013-11-19 10:25:13
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
SET FOREIGN_KEY_CHECKS=0;

-- Dumping structure for table weberpcn.accountgroups
DROP TABLE IF EXISTS `accountgroups`;
CREATE TABLE IF NOT EXISTS `accountgroups` (
  `groupname` char(30) NOT NULL DEFAULT '',
  `sectioninaccounts` int(11) NOT NULL DEFAULT '0',
  `pandl` tinyint(4) NOT NULL DEFAULT '1',
  `sequenceintb` smallint(6) NOT NULL DEFAULT '0',
  `parentgroupname` varchar(30) NOT NULL,
  PRIMARY KEY (`groupname`),
  KEY `SequenceInTB` (`sequenceintb`),
  KEY `sectioninaccounts` (`sectioninaccounts`),
  KEY `parentgroupname` (`parentgroupname`),
  CONSTRAINT `accountgroups_ibfk_1` FOREIGN KEY (`sectioninaccounts`) REFERENCES `accountsection` (`sectionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.accountgroups: ~64 rows (approximately)
/*!40000 ALTER TABLE `accountgroups` DISABLE KEYS */;
INSERT INTO `accountgroups` (`groupname`, `sectioninaccounts`, `pandl`, `sequenceintb`, `parentgroupname`) VALUES
	('A地销售费用', 2, 1, 800, '费用'),
	('主营业务收入', 1, 1, 600, '营业收入'),
	('其它应付款', 30, 0, 300, '流动负债'),
	('其它应收款', 20, 0, 120, '流动资产'),
	('其它流动负债', 30, 0, 300, '流动负债'),
	('其它非流动负债', 30, 0, 400, '非流动负债'),
	('其它非流动资产', 20, 0, 200, '非流动资产'),
	('利润', 5, 1, 900, ''),
	('制造费用', 2, 1, 700, '成本'),
	('固定资产原价', 20, 0, 200, '非流动资产'),
	('固定资产清理', 20, 0, 200, '非流动资产'),
	('坏账准备', 20, 0, 120, '流动资产'),
	('存货', 20, 0, 120, '流动资产'),
	('实收资本（或股本）', 50, 0, 500, '所有者权益'),
	('工程物资', 20, 0, 200, '非流动资产'),
	('应交税费', 30, 0, 300, '流动负债'),
	('应付利息', 30, 0, 300, '流动负债'),
	('应付利润', 30, 0, 300, '流动负债'),
	('应付票据', 30, 0, 300, '流动负债'),
	('应付职工薪酬', 30, 0, 300, '流动负债'),
	('应付账款', 30, 0, 300, '流动负债'),
	('应收利息', 20, 0, 120, '流动资产'),
	('应收票据', 20, 0, 120, '流动资产'),
	('应收股利', 20, 0, 120, '流动资产'),
	('应收账款', 20, 0, 120, '流动资产'),
	('开发支出', 20, 0, 200, '非流动资产'),
	('成本', 2, 1, 700, ''),
	('所得税费用', 2, 1, 800, '费用'),
	('所有者权益', 50, 0, 500, ''),
	('投资收益', 1, 1, 600, '收入'),
	('损益', 1, 1, 600, ''),
	('收入', 1, 1, 600, ''),
	('无形资产', 20, 0, 200, '非流动资产'),
	('未分配利润', 50, 0, 500, '所有者权益'),
	('本年利润', 5, 1, 900, '利润'),
	('流动负债', 30, 0, 300, ''),
	('流动资产', 20, 0, 120, ''),
	('生产性生物资产', 20, 0, 200, '非流动资产'),
	('生产成本', 2, 1, 700, '成本'),
	('盈余公积', 50, 0, 500, '所有者权益'),
	('短期借款', 30, 0, 300, '流动负债'),
	('短期投资', 20, 0, 120, '流动资产'),
	('管理费用', 2, 1, 800, '费用'),
	('累计折旧', 20, 0, 200, '非流动资产'),
	('营业外支出', 2, 1, 800, '费用'),
	('营业外收收入', 1, 1, 600, '收入'),
	('营业成本', 2, 1, 800, '费用'),
	('营业收入', 1, 1, 600, '收入'),
	('营业税金及附加', 2, 1, 800, '费用'),
	('财务费用', 2, 1, 800, '费用'),
	('货币资金', 20, 0, 120, '流动资产'),
	('费用', 2, 1, 800, ''),
	('资产', 20, 0, 100, ''),
	('资本公积', 50, 0, 500, '所有者权益'),
	('递延收益', 30, 0, 400, '非流动负债'),
	('长期侍摊费用', 20, 0, 200, '非流动资产'),
	('长期债券投资', 20, 0, 200, '非流动资产'),
	('长期应付款', 30, 0, 400, '非流动负债'),
	('长期股权投资', 20, 0, 200, '非流动资产'),
	('长期贷款', 30, 0, 400, '非流动负债'),
	('非流动负债', 30, 0, 400, ''),
	('非流动资产', 20, 0, 200, ''),
	('预付账款', 20, 0, 120, '流动资产'),
	('预收账款', 30, 0, 300, '流动负债');
/*!40000 ALTER TABLE `accountgroups` ENABLE KEYS */;


-- Dumping structure for table weberpcn.accountsection
DROP TABLE IF EXISTS `accountsection`;
CREATE TABLE IF NOT EXISTS `accountsection` (
  `sectionid` int(11) NOT NULL DEFAULT '0',
  `sectionname` text NOT NULL,
  PRIMARY KEY (`sectionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.accountsection: ~6 rows (approximately)
/*!40000 ALTER TABLE `accountsection` DISABLE KEYS */;
INSERT INTO `accountsection` (`sectionid`, `sectionname`) VALUES
	(1, '收入'),
	(2, '费用'),
	(5, '利润'),
	(20, '资产'),
	(30, '负债'),
	(50, '所有者权益');
/*!40000 ALTER TABLE `accountsection` ENABLE KEYS */;


-- Dumping structure for table weberpcn.areas
DROP TABLE IF EXISTS `areas`;
CREATE TABLE IF NOT EXISTS `areas` (
  `areacode` char(3) NOT NULL,
  `areadescription` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`areacode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.areas: ~0 rows (approximately)
/*!40000 ALTER TABLE `areas` DISABLE KEYS */;
/*!40000 ALTER TABLE `areas` ENABLE KEYS */;


-- Dumping structure for table weberpcn.assetmanager
DROP TABLE IF EXISTS `assetmanager`;
CREATE TABLE IF NOT EXISTS `assetmanager` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `serialno` varchar(30) NOT NULL DEFAULT '',
  `location` varchar(15) NOT NULL DEFAULT '',
  `cost` double NOT NULL DEFAULT '0',
  `depn` double NOT NULL DEFAULT '0',
  `datepurchased` date NOT NULL DEFAULT '0000-00-00',
  `disposalvalue` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.assetmanager: ~0 rows (approximately)
/*!40000 ALTER TABLE `assetmanager` DISABLE KEYS */;
/*!40000 ALTER TABLE `assetmanager` ENABLE KEYS */;


-- Dumping structure for table weberpcn.audittrail
DROP TABLE IF EXISTS `audittrail`;
CREATE TABLE IF NOT EXISTS `audittrail` (
  `transactiondate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userid` varchar(20) NOT NULL DEFAULT '',
  `querystring` text,
  KEY `UserID` (`userid`),
  CONSTRAINT `audittrail_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `www_users` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Dumping structure for table weberpcn.bankaccounts
DROP TABLE IF EXISTS `bankaccounts`;
CREATE TABLE IF NOT EXISTS `bankaccounts` (
  `accountcode` varchar(20) NOT NULL DEFAULT '0',
  `currcode` char(3) NOT NULL,
  `invoice` smallint(2) NOT NULL DEFAULT '0',
  `bankaccountcode` varchar(50) NOT NULL DEFAULT '',
  `bankaccountname` char(50) NOT NULL DEFAULT '',
  `bankaccountnumber` char(50) NOT NULL DEFAULT '',
  `bankaddress` char(50) DEFAULT NULL,
  PRIMARY KEY (`accountcode`),
  KEY `currcode` (`currcode`),
  KEY `BankAccountName` (`bankaccountname`),
  KEY `BankAccountNumber` (`bankaccountnumber`),
  CONSTRAINT `bankaccounts_ibfk_1` FOREIGN KEY (`accountcode`) REFERENCES `chartmaster` (`accountcode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.bankaccounts: ~0 rows (approximately)
/*!40000 ALTER TABLE `bankaccounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `bankaccounts` ENABLE KEYS */;


-- Dumping structure for table weberpcn.banktrans
DROP TABLE IF EXISTS `banktrans`;
CREATE TABLE IF NOT EXISTS `banktrans` (
  `banktransid` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) NOT NULL DEFAULT '0',
  `transno` bigint(20) NOT NULL DEFAULT '0',
  `bankact` varchar(20) NOT NULL DEFAULT '0',
  `ref` varchar(50) NOT NULL DEFAULT '',
  `amountcleared` double NOT NULL DEFAULT '0',
  `exrate` double NOT NULL DEFAULT '1' COMMENT 'From bank account currency to payment currency',
  `functionalexrate` double NOT NULL DEFAULT '1' COMMENT 'Account currency to functional currency',
  `transdate` date NOT NULL DEFAULT '0000-00-00',
  `banktranstype` varchar(30) NOT NULL DEFAULT '',
  `amount` double NOT NULL DEFAULT '0',
  `currcode` char(3) NOT NULL DEFAULT '',
  PRIMARY KEY (`banktransid`),
  KEY `BankAct` (`bankact`,`ref`),
  KEY `TransDate` (`transdate`),
  KEY `TransType` (`banktranstype`),
  KEY `Type` (`type`,`transno`),
  KEY `CurrCode` (`currcode`),
  KEY `ref` (`ref`),
  CONSTRAINT `banktrans_ibfk_1` FOREIGN KEY (`type`) REFERENCES `systypes` (`typeid`),
  CONSTRAINT `banktrans_ibfk_2` FOREIGN KEY (`bankact`) REFERENCES `bankaccounts` (`accountcode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.banktrans: ~0 rows (approximately)
/*!40000 ALTER TABLE `banktrans` DISABLE KEYS */;
/*!40000 ALTER TABLE `banktrans` ENABLE KEYS */;


-- Dumping structure for table weberpcn.bom
DROP TABLE IF EXISTS `bom`;
CREATE TABLE IF NOT EXISTS `bom` (
  `parent` char(20) NOT NULL DEFAULT '',
  `component` char(20) NOT NULL DEFAULT '',
  `workcentreadded` char(5) NOT NULL DEFAULT '',
  `loccode` char(5) NOT NULL DEFAULT '',
  `effectiveafter` date NOT NULL DEFAULT '0000-00-00',
  `effectiveto` date NOT NULL DEFAULT '9999-12-31',
  `quantity` double NOT NULL DEFAULT '1',
  `autoissue` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`parent`,`component`,`workcentreadded`,`loccode`),
  KEY `Component` (`component`),
  KEY `EffectiveAfter` (`effectiveafter`),
  KEY `EffectiveTo` (`effectiveto`),
  KEY `LocCode` (`loccode`),
  KEY `Parent` (`parent`,`effectiveafter`,`effectiveto`,`loccode`),
  KEY `Parent_2` (`parent`),
  KEY `WorkCentreAdded` (`workcentreadded`),
  CONSTRAINT `bom_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `stockmaster` (`stockid`),
  CONSTRAINT `bom_ibfk_2` FOREIGN KEY (`component`) REFERENCES `stockmaster` (`stockid`),
  CONSTRAINT `bom_ibfk_3` FOREIGN KEY (`workcentreadded`) REFERENCES `workcentres` (`code`),
  CONSTRAINT `bom_ibfk_4` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.bom: ~0 rows (approximately)
/*!40000 ALTER TABLE `bom` DISABLE KEYS */;
/*!40000 ALTER TABLE `bom` ENABLE KEYS */;


-- Dumping structure for table weberpcn.chartdetails
DROP TABLE IF EXISTS `chartdetails`;
CREATE TABLE IF NOT EXISTS `chartdetails` (
  `accountcode` varchar(20) NOT NULL DEFAULT '0',
  `period` smallint(6) NOT NULL DEFAULT '0',
  `budget` double NOT NULL DEFAULT '0',
  `actual` double NOT NULL DEFAULT '0',
  `bfwd` double NOT NULL DEFAULT '0',
  `bfwdbudget` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`accountcode`,`period`),
  KEY `Period` (`period`),
  CONSTRAINT `chartdetails_ibfk_1` FOREIGN KEY (`accountcode`) REFERENCES `chartmaster` (`accountcode`),
  CONSTRAINT `chartdetails_ibfk_2` FOREIGN KEY (`period`) REFERENCES `periods` (`periodno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.chartdetails: ~0 rows (approximately)
/*!40000 ALTER TABLE `chartdetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `chartdetails` ENABLE KEYS */;


-- Dumping structure for table weberpcn.chartmaster
DROP TABLE IF EXISTS `chartmaster`;
CREATE TABLE IF NOT EXISTS `chartmaster` (
  `accountcode` varchar(20) NOT NULL DEFAULT '0',
  `accountname` char(50) NOT NULL DEFAULT '',
  `group_` char(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`accountcode`),
  KEY `AccountName` (`accountname`),
  KEY `Group_` (`group_`),
  CONSTRAINT `chartmaster_ibfk_1` FOREIGN KEY (`group_`) REFERENCES `accountgroups` (`groupname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.chartmaster: ~107 rows (approximately)
/*!40000 ALTER TABLE `chartmaster` DISABLE KEYS */;
INSERT INTO `chartmaster` (`accountcode`, `accountname`, `group_`) VALUES
	('100101', 'A地库存现金', '货币资金'),
	('100102', 'B地库存现金', '货币资金'),
	('100201', 'A地农行基本户', '货币资金'),
	('100202', 'A地农行专户', '货币资金'),
	('1121', '应收票据', '应收票据'),
	('1122', '应收账款', '应收账款'),
	('1123', '预付账款', '预付账款'),
	('1131', '应收股利', '应收股利'),
	('1132', '应收利息', '应收利息'),
	('122110', '其他应收款-蔡某某', '其它应收款'),
	('122111', '其他应收款-B地办事处', '其它应收款'),
	('1401', '材料采购', '存货'),
	('1403', '原材料', '存货'),
	('1404', '材料成本差异', '存货'),
	('1405', '库存商品', '存货'),
	('1406', '在产品', '存货'),
	('1407', '虚拟物料', '存货'),
	('1511', '长期股权投资', '长期股权投资'),
	('1601', '固定资产', '非流动资产'),
	('1602', '累计折旧', '非流动资产'),
	('1603', '固定资产减值准备', '非流动资产'),
	('160401', '建筑工程', '非流动资产'),
	('1605', '工程物资', '非流动资产'),
	('1606', '固定资产清理', '非流动资产'),
	('1701', '无形资产', '非流动资产'),
	('1702', '累计摊销', '非流动资产'),
	('1801', '长期待摊费用', '非流动资产'),
	('1901', '待处理财产损溢', '非流动资产'),
	('200101', '短期借款-工商银行', '短期借款'),
	('2201', '应付票据', '流动负债'),
	('2202', '应付账款', '应付账款'),
	('220201', '应付账款-暂估', '应付账款'),
	('2203', '预收账款', '流动负债'),
	('2211', '应付职工薪酬', '流动负债'),
	('2221', '应交税费', '流动负债'),
	('2231', '应付利息', '流动负债'),
	('2232', '应付股利', '流动负债'),
	('2241', '其它应付款', '流动负债'),
	('224101', '其他应付款-某公司', '其它应付款'),
	('224102', '其他应付款-张某某', '其它应付款'),
	('2501', '长期借款', '非流动负债'),
	('2701', '长期应付款', '非流动负债'),
	('2801', '预计负债', '非流动负债'),
	('2901', '递延所得税负债', '非流动负债'),
	('4001', '实收资本', '所有者权益'),
	('4002', '资本公积', '所有者权益'),
	('4101', '盈余公积', '所有者权益'),
	('4103', '本年利润', '所有者权益'),
	('4104', '利润分配', '所有者权益'),
	('5001', '生产成本', '成本'),
	('500101', '生产成本-原材料', '生产成本'),
	('500102', '生成成本-人工费', '生产成本'),
	('500103', '生产成本-制造费用', '生产成本'),
	('510101', '制造费用—物料消耗', '制造费用'),
	('510102', '制造费用—修理费', '制造费用'),
	('510103', '制造费用—固定资产折旧费', '制造费用'),
	('510104', '制造费用—办公费', '制造费用'),
	('510105', '制造费用—水电费', '制造费用'),
	('510106', '制造费用—停工损失', '制造费用'),
	('510107', '制造费用—福利费', '制造费用'),
	('5301', '研发支出', '成本'),
	('5402', '工程结算', '成本'),
	('6001', '主营业务收入-A地', '收入'),
	('6011', '利息收入', '损益'),
	('6021', '手续费及佣金收入', '损益'),
	('6041', '租赁收入', '损益'),
	('6051', '其它业务收入', '营业收入'),
	('6061', '汇兑损益', '损益'),
	('6111', '投资收益', '损益'),
	('6301', '营业外收入', '营业收入'),
	('6401', '主营业务成本', '成本'),
	('6402', '其它业务成本', '费用'),
	('6403', '营业税金及附加', '损益'),
	('6411', '利息支出', '损益'),
	('6421', '手续费及佣金支出', '损益'),
	('660201', '管理费用-工资', '管理费用'),
	('660202', '管理费用-固定资产折旧费', '管理费用'),
	('660203', '管理费用-办公费', '管理费用'),
	('660204', '管理费用-水电费', '管理费用'),
	('660205', '管理费用-差旅费', '管理费用'),
	('660206', '管理费用-招待费', '管理费用'),
	('660207', '管理费用-福利费', '管理费用'),
	('660208', '管理费用-车辆使用费', '管理费用'),
	('660209', '管理费用-税费', '管理费用'),
	('660210', '管理费用-送礼', '管理费用'),
	('660211', '管理费用—电话费', '管理费用'),
	('660301', '财务费用-利息支出', '财务费用'),
	('660302', '财务费用-金融手续费', '财务费用'),
	('660303', '财务费用-利息收入', '财务费用'),
	('660304', '财务费用-汇兑损益', '财务费用'),
	('660401', 'A地销售费用-工资', 'A地销售费用'),
	('660402', 'A地销售费用-固定资产折旧费', 'A地销售费用'),
	('660403', 'A地销售费用-办公费', 'A地销售费用'),
	('660404', 'A地销售费用-水电费', 'A地销售费用'),
	('660405', 'A地销售费用-差旅费', 'A地销售费用'),
	('660406', 'A地销售费用-招待费', 'A地销售费用'),
	('660407', 'A地销售费用-广告宣传费', 'A地销售费用'),
	('660408', 'A地销售费用-邮寄费', 'A地销售费用'),
	('660409', 'A地销售费用-托运费', 'A地销售费用'),
	('660410', 'A地销售费用-车辆使用费', 'A地销售费用'),
	('660411', 'A地销售费用-维修费', 'A地销售费用'),
	('660412', 'A地销售费用-电话费', 'A地销售费用'),
	('6701', '存货盘点损益', '损益'),
	('6702', '资产减值损失', '损益'),
	('6711', '营业外支出', '损益'),
	('6801', '所得税费用', '费用'),
	('6901', '以前年度损益调整', '损益');
/*!40000 ALTER TABLE `chartmaster` ENABLE KEYS */;


-- Dumping structure for table weberpcn.cogsglpostings
DROP TABLE IF EXISTS `cogsglpostings`;
CREATE TABLE IF NOT EXISTS `cogsglpostings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area` char(3) NOT NULL DEFAULT '',
  `stkcat` varchar(6) NOT NULL DEFAULT '',
  `glcode` varchar(20) NOT NULL DEFAULT '0',
  `salestype` char(2) NOT NULL DEFAULT 'AN',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Area_StkCat` (`area`,`stkcat`,`salestype`),
  KEY `Area` (`area`),
  KEY `StkCat` (`stkcat`),
  KEY `GLCode` (`glcode`),
  KEY `SalesType` (`salestype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.cogsglpostings: ~0 rows (approximately)
/*!40000 ALTER TABLE `cogsglpostings` DISABLE KEYS */;
/*!40000 ALTER TABLE `cogsglpostings` ENABLE KEYS */;


-- Dumping structure for table weberpcn.companies
DROP TABLE IF EXISTS `companies`;
CREATE TABLE IF NOT EXISTS `companies` (
  `coycode` int(11) NOT NULL DEFAULT '1',
  `coyname` varchar(50) NOT NULL DEFAULT '',
  `gstno` varchar(20) NOT NULL DEFAULT '',
  `companynumber` varchar(20) NOT NULL DEFAULT '0',
  `regoffice1` varchar(40) NOT NULL DEFAULT '',
  `regoffice2` varchar(40) NOT NULL DEFAULT '',
  `regoffice3` varchar(40) NOT NULL DEFAULT '',
  `regoffice4` varchar(40) NOT NULL DEFAULT '',
  `regoffice5` varchar(20) NOT NULL DEFAULT '',
  `regoffice6` varchar(15) NOT NULL DEFAULT '',
  `telephone` varchar(25) NOT NULL DEFAULT '',
  `fax` varchar(25) NOT NULL DEFAULT '',
  `email` varchar(55) NOT NULL DEFAULT '',
  `currencydefault` varchar(4) NOT NULL DEFAULT '',
  `debtorsact` varchar(20) NOT NULL DEFAULT '70000',
  `pytdiscountact` varchar(20) NOT NULL DEFAULT '55000',
  `creditorsact` varchar(20) NOT NULL DEFAULT '80000',
  `payrollact` varchar(20) NOT NULL DEFAULT '84000',
  `grnact` varchar(20) NOT NULL DEFAULT '72000',
  `exchangediffact` varchar(20) NOT NULL DEFAULT '65000',
  `purchasesexchangediffact` varchar(20) NOT NULL DEFAULT '0',
  `retainedearnings` varchar(20) NOT NULL DEFAULT '90000',
  `gllink_debtors` tinyint(1) DEFAULT '1',
  `gllink_creditors` tinyint(1) DEFAULT '1',
  `gllink_stock` tinyint(1) DEFAULT '1',
  `freightact` varchar(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`coycode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.companies: ~1 rows (approximately)
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` (`coycode`, `coyname`, `gstno`, `companynumber`, `regoffice1`, `regoffice2`, `regoffice3`, `regoffice4`, `regoffice5`, `regoffice6`, `telephone`, `fax`, `email`, `currencydefault`, `debtorsact`, `pytdiscountact`, `creditorsact`, `payrollact`, `grnact`, `exchangediffact`, `purchasesexchangediffact`, `retainedearnings`, `gllink_debtors`, `gllink_creditors`, `gllink_stock`, `freightact`) VALUES
	(1, 'webERP演示公司', 'not entered yet', '', '123 Web Way', 'PO Box 123', 'Queen Street', 'Melbourne', 'Victoria 3043', 'Australia', '+61 3 4567 8901', '+61 3 4567 8902', 'weberp@weberpdemo.com', 'CNY', '1122', '660407', '2202', '2211', '220201', '660304', '660304', '4103', 1, 1, 1, '660409');
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;


-- Dumping structure for table weberpcn.config
DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
  `confname` varchar(35) NOT NULL DEFAULT '',
  `confvalue` text NOT NULL,
  PRIMARY KEY (`confname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.config: ~176 rows (approximately)
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` (`confname`, `confvalue`) VALUES
	('AllowOrderLineItemNarrative', '1'),
	('AllowSalesOfZeroCostItems', '0'),
	('AutoAuthorisePO', '1'),
	('AutoCreateWOs', '1'),
	('AutoDebtorNo', '0'),
	('AutoIssue', '1'),
	('CheckCreditLimits', '1'),
	('Check_Price_Charged_vs_Order_Price', '1'),
	('Check_Qty_Charged_vs_Del_Qty', '1'),
	('CountryOfOperation', 'US'),
	('CreditingControlledItems_MustExist', '0'),
	('DB_Maintenance', '0'),
	('DB_Maintenance_LastRun', '2013-05-25'),
	('DefaultBlindPackNote', '1'),
	('DefaultCreditLimit', '1000'),
	('DefaultCustomerType', '1'),
	('DefaultDateFormat', 'd/m/Y'),
	('DefaultDisplayRecordsMax', '50'),
	('DefaultFactoryLocation', 'MEL'),
	('DefaultPriceList', 'DE'),
	('DefaultSupplierType', '1'),
	('DefaultTaxCategory', '1'),
	('DefaultTheme', 'silverwolf'),
	('Default_Shipper', '1'),
	('DefineControlledOnWOEntry', '1'),
	('DispatchCutOffTime', '14'),
	('DoFreightCalc', '0'),
	('EDIHeaderMsgId', 'D:01B:UN:EAN010'),
	('EDIReference', 'WEBERP'),
	('EDI_Incoming_Orders', 'companies/weberpdemo/EDI_Incoming_Orders'),
	('EDI_MsgPending', 'companies/weberpdemo/EDI_MsgPending'),
	('EDI_MsgSent', 'companies/weberpdemo/EDI_Sent'),
	('ExchangeRateFeed', 'Google'),
	('Extended_CustomerInfo', '1'),
	('Extended_SupplierInfo', '1'),
	('FactoryManagerEmail', 'manager@company.com'),
	('FreightChargeAppliesIfLessThan', '1000'),
	('FreightTaxCategory', '1'),
	('FrequentlyOrderedItems', '0'),
	('geocode_integration', '0'),
	('HTTPS_Only', '0'),
	('InventoryManagerEmail', 'test@company.com'),
	('InvoicePortraitFormat', '0'),
	('ItemDescriptionLanguages', 'fr_FR.utf8,de_DE.utf8,it_IT.utf8,'),
	('LogPath', ''),
	('LogSeverity', '0'),
	('MaxImageSize', '300'),
	('MonthsAuditTrail', '1'),
	('NumberOfMonthMustBeShown', '6'),
	('NumberOfPeriodsOfStockUsage', '12'),
	('OverChargeProportion', '30'),
	('OverReceiveProportion', '20'),
	('PackNoteFormat', '1'),
	('PageLength', '48'),
	('part_pics_dir', 'companies/weberpdemo/part_pics'),
	('PastDueDays1', '30'),
	('PastDueDays2', '60'),
	('PO_AllowSameItemMultipleTimes', '1'),
	('ProhibitJournalsToControlAccounts', '1'),
	('ProhibitNegativeStock', '0'),
	('ProhibitPostingsBefore', '2012-11-30'),
	('PurchasingManagerEmail', 'test@company.com'),
	('QuickEntries', '10'),
	('RadioBeaconFileCounter', '/home/RadioBeacon/FileCounter'),
	('RadioBeaconFTP_user_name', 'RadioBeacon ftp server user name'),
	('RadioBeaconHomeDir', '/home/RadioBeacon'),
	('RadioBeaconStockLocation', 'BL'),
	('RadioBraconFTP_server', '192.168.2.2'),
	('RadioBreaconFilePrefix', 'ORDXX'),
	('RadionBeaconFTP_user_pass', 'Radio Beacon remote ftp server password'),
	('reports_dir', 'companies/weberpdemo/reportwriter'),
	('RequirePickingNote', '0'),
	('RomalpaClause', 'Ownership will not pass to the buyer until the goods have been paid for in full.'),
	('ShopAboutUs', 'This web-shop software has been developed by Logic Works Ltd for webERP. For support contact Phil Daintree by rn&lt;a href=&quot;mailto:support@logicworks.co.nz&quot;&gt;email&lt;/a&gt;rn'),
	('ShopAllowBankTransfer', '1'),
	('ShopAllowCreditCards', '1'),
	('ShopAllowPayPal', '1'),
	('ShopAllowSurcharges', '1'),
	('ShopBankTransferSurcharge', '0.0'),
	('ShopBranchCode', 'ANGRY'),
	('ShopContactUs', 'For support contact Logic Works Ltd by rn&lt;a href=&quot;mailto:support@logicworks.co.nz&quot;&gt;email&lt;/a&gt;'),
	('ShopCreditCardBankAccount', '1030'),
	('ShopCreditCardGateway', 'SwipeHQ'),
	('ShopCreditCardSurcharge', '2.5'),
	('ShopDebtorNo', 'ANGRY'),
	('ShopFreightPolicy', 'Shipping information'),
	('ShopManagerEmail', 'shopmanager@yourdomain.com'),
	('ShopMode', 'live'),
	('ShopName', 'webERP Demo Store'),
	('ShopPayFlowMerchant', ''),
	('ShopPayFlowPassword', ''),
	('ShopPayFlowUser', ''),
	('ShopPayFlowVendor', ''),
	('ShopPayPalBankAccount', '1040'),
	('ShopPayPalPassword', ''),
	('ShopPayPalProPassword', ''),
	('ShopPayPalProSignature', ''),
	('ShopPayPalProUser', ''),
	('ShopPayPalSignature', ''),
	('ShopPayPalSurcharge', '3.4'),
	('ShopPayPalUser', ''),
	('ShopPrivacyStatement', '&lt;h2&gt;We are committed to protecting your privacy.&lt;/h2&gt;&lt;p&gt;We recognise that your personal information is confidential and we understand that it is important for you to know how we treat your personal information. Please read on for more information about our Privacy Policy.&lt;/p&gt;&lt;ul&gt;&lt;li&gt;&lt;h2&gt;1. What information do we collect and how do we use it?&lt;/h2&gt;&lt;br /&gt;We use the information it collects from you for the following purposes:&lt;ul&gt;&lt;li&gt;To assist us in providing you with a quality service&lt;/li&gt;&lt;li&gt;To respond to, and process, your request&lt;/li&gt;&lt;li&gt;To notify competition winners or fulfil promotional obligations&lt;/li&gt;&lt;li&gt;To inform you of, and provide you with, new and existing products and services offered by us from time to time &lt;/li&gt;&lt;/ul&gt;&lt;p&gt;Any information we collect will not be used in ways that you have not consented to.&lt;/p&gt;&lt;p&gt;If you send us an email, we will store your email address and the contents of the email. This information will only be used for the purpose for which you have provided it. Electronic mail submitted to us is handled and saved according to the provisions of the the relevant statues.&lt;/p&gt;&lt;p&gt;When we offer contests and promotions, customers who choose to enter are asked to provide personal information. This information may then be used by us to notify winners, or to fulfil promotional obligations.&lt;/p&gt;&lt;p&gt;We may use the information we collect to occasionally notify you about important functionality changes to our website, new and special offers we think you will find valuable. If at any stage you no longer wish to receive these notifications you may opt out by sending us an email.&lt;/p&gt;&lt;p&gt;We do monitor this website in order to identify user trends and to improve the site if necessary. Any of this information, such as the type of site browser your computer has, will be used only in aggregate form and your individual details will not be identified.&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;h2&gt;2. How do we store and protect your personal information and who has access to that information?&lt;/h2&gt;&lt;p&gt;As required by statute, we follow strict procedures when storing and using the information you have provided.&lt;/p&gt;&lt;p&gt;We do not sell, trade or rent your personal information to others. We may provide aggregate statistics about our customers and website trends. However, these statistics will not have any personal information which would identify you.&lt;/p&gt;&lt;p&gt;Only specific employees within our company are able to access your personal data.&lt;/p&gt;&lt;p&gt;This policy means that we may require proof of identity before we disclose any information to you.&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;h2&gt;3. What should I do if I want to change my details or if I donÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢t want to be contacted any more?&lt;/h2&gt;&lt;p&gt;At any stage you have the right to access and amend or update your personal details. If you do not want to receive any communications from us you may opt out by contacting us see &lt;a href=&quot;index.php?Page=ContactUs&quot;&gt;the Contact Us Page&lt;/a&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;h2&gt;4. What happens if we decide to change this Privacy Policy?&lt;/h2&gt;&lt;p&gt;If we change any aspect of our Privacy Policy we will post these changes on this page so that you are always aware of how we are treating your personal information.&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;h2&gt;5. How can you contact us if you have any questions, comments or concerns about our Privacy Policy?&lt;/h2&gt;&lt;p&gt;We welcome any questions or comments you may have please email us via the contact details provided on our &lt;a href=&quot;index.php?Page=ContactUs&quot;&gt;Contact Us Page&lt;/a&gt;&lt;/p&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p&gt;Please also refer to our &lt;a href=&quot;index.php?Page=TermsAndConditions&quot;&gt;Terms and Conditions&lt;/a&gt; for more information.&lt;/p&gt;'),
	('ShopShowOnlyAvailableItems', '0'),
	('ShopShowQOHColumn', '1'),
	('ShopStockLocations', 'MEL,TOR'),
	('ShopSurchargeStockID', 'PAYTSURCHARGE'),
	('ShopSwipeHQAPIKey', ''),
	('ShopSwipeHQMerchantID', ''),
	('ShopTermsConditions', '&lt;p&gt;These terms cover the use of this website. Use includes visits to our sites, purchases on our sites, participation in our database and promotions. These terms of use apply to you when you use our websites. Please read these terms carefully - if you need to refer to them again they can be accessed from the link at the bottom of any page of our websites.&lt;/p&gt;&lt;br /&gt;&lt;ul&gt;&lt;li&gt;&lt;h2&gt;1. Content&lt;/h2&gt;&lt;p&gt;While we endeavour to supply accurate information on this site, errors and omissions may occur. We do not accept any liability, direct or indirect, for any loss or damage which may directly or indirectly result from any advice, opinion, information, representation or omission whether negligent or otherwise, contained on this site. You are solely responsible for the actions you take in reliance on the content on, or accessed, through this site.&lt;/p&gt;&lt;p&gt;We reserve the right to make changes to the content on this site at any time and without notice.&lt;/p&gt;&lt;p&gt;To the extent permitted by law, we make no warranties in relation to the merchantability, fitness for purpose, freedom from computer virus, accuracy or availability of this web site or any other web site.&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;h2&gt;2. Making a contract with us&lt;/h2&gt;&lt;p&gt;When you place an order with us, you are making an offer to buy goods. We will send you an e-mail to confirm that we have received and accepted your order, which indicates that a contract has been made between us. We will take payment from you when we accept your order. In the unlikely event that the goods are no longer available, we will refund your payment to the account it originated from, and advise that the goods are no longer available.&lt;/p&gt;&lt;p&gt;An order is placed on our website via adding a product to the shopping cart and proceeding through our checkout process. The checkout process includes giving us delivery and any other relevant details for your order, entering payment information and submitting your order. The final step consists of a confirmation page with full details of your order, which you are able to print as a receipt of your order. We will also email you with confirmation of your order.&lt;/p&gt;&lt;p&gt;We reserve the right to refuse or cancel any orders that we believe, solely by our own judgement, to be placed for commercial purposes, e.g. any kind of reseller. We also reserve the right to refuse or cancel any orders that we believe, solely by our own judgement, to have been placed fraudulently.&lt;/p&gt;&lt;p&gt;We reserve the right to limit the number of an item customers can purchase in a single transaction.&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;h2&gt;3. Payment options&lt;/h2&gt;&lt;p&gt;We currently accept the following credit cards:&lt;/p&gt;&lt;ul&gt;&lt;li&gt;Visa&lt;/li&gt;&lt;li&gt;MasterCard&lt;/li&gt;&lt;li&gt;American Express&lt;/li&gt;&lt;/ul&gt;You can also pay using PayPal and internet bank transfer. Surcharges may apply for payment by PayPal or credit cards.&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;h2&gt;4. Pricing&lt;/h2&gt;&lt;p&gt;All prices listed are inclusive of relevant taxes.  All prices are correct when published. Please note that we reserve the right to alter prices at any time for any reason. If this should happen after you have ordered a product, we will contact you prior to processing your order. Online and in store pricing may differ.&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;h2&gt;5. Website and Credit Card Security&lt;/h2&gt;&lt;p&gt;We want you to have a safe and secure shopping experience online. All payments via our sites are processed using SSL (Secure Socket Layer) protocol, whereby sensitive information is encrypted to protect your privacy.&lt;/p&gt;&lt;p&gt;You can help to protect your details from unauthorised access by logging out each time you finish using the site, particularly if you are doing so from a public or shared computer.&lt;/p&gt;&lt;p&gt;For security purposes certain transactions may require proof of identification.&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;h2&gt;6. Delivery and Delivery Charges&lt;/h2&gt;&lt;p&gt;We do not deliver to Post Office boxes.&lt;/p&gt;&lt;p&gt;Please note that a signature is required for all deliveries. The goods become the recipientÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢s property and responsibility once they have been signed for at the time of delivery. If goods are lost or damaged in transit, please contact us within 7 business days &lt;a href=&quot;index.php?Page=ContactUs&quot;&gt;see Contact Us page for contact details&lt;/a&gt;. We will use this delivery information to make a claim against our courier company. We will offer you the choice of a replacement or a full refund, once we have received confirmation from our courier company that delivery was not successful.&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;h2&gt;7. Restricted Products&lt;/h2&gt;&lt;p&gt;Some products on our site carry an age restriction, if a product you have selected is R16 or R18 a message will appear in the cart asking you to confirm you are an appropriate age to purchase the item(s).  Confirming this means that you are of an eligible age to purchase the selected product(s).  You are also agreeing that you are not purchasing the item on behalf of a person who is not the appropriate age.&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;h2&gt;8. Delivery Period&lt;/h2&gt;&lt;p&gt;Delivery lead time for products may vary. Deliveries to rural addresses may take longer.  You will receive an email that confirms that your order has been dispatched.&lt;/p&gt;&lt;p&gt;To ensure successful delivery, please provide a delivery address where someone will be present during business hours to sign for the receipt of your package. You can track your order by entering the tracking number emailed to you in the dispatch email at the Courier\\\'s web-site.&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;h2&gt;9. Disclaimer&lt;/h2&gt;&lt;p&gt;Our websites are intended to provide information for people shopping our products and accessing our services, including making purchases via our website and registering on our database to receive e-mails from us.&lt;/p&gt;&lt;p&gt;While we endeavour to supply accurate information on this site, errors and omissions may occur. We do not accept any liability, direct or indirect, for any loss or damage which may directly or indirectly result from any advice, opinion, information, representation or omission whether negligent or otherwise, contained on this site. You are solely responsible for the actions you take in reliance on the content on, or accessed, through this site.&lt;/p&gt;&lt;p&gt;We reserve the right to make changes to the content on this site at any time and without notice.&lt;/p&gt;&lt;p&gt;To the extent permitted by law, we make no warranties in relation to the merchantability, fitness for purpose, freedom from computer virus, accuracy or availability of this web site or any other web site.&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;h2&gt;10. Links&lt;/h2&gt;&lt;p&gt;Please note that although this site has some hyperlinks to other third party websites, these sites have not been prepared by us are not under our control. The links are only provided as a convenience, and do not imply that we endorse, check, or approve of the third party site. We are not responsible for the privacy principles or content of these third party sites. We are not responsible for the availability of any of these links.&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;h2&gt;11. Jurisdiction&lt;/h2&gt;&lt;p&gt;This website is governed by, and is to be interpreted in accordance with, the laws of  ????.&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;h2&gt;12. Changes to this Agreement&lt;/h2&gt;&lt;p&gt;We reserve the right to alter, modify or update these terms of use. These terms apply to your order. We may change our terms and conditions at any time, so please do not assume that the same terms will apply to future orders.&lt;/p&gt;&lt;/li&gt;&lt;/ul&gt;'),
	('ShopTitle', 'Shop Home'),
	('ShowStockidOnImages', '0'),
	('ShowValueOnGRN', '1'),
	('Show_Settled_LastMonth', '1'),
	('SmtpSetting', '0'),
	('SO_AllowSameItemMultipleTimes', '1'),
	('StandardCostDecimalPlaces', '2'),
	('TaxAuthorityReferenceName', ''),
	('UpdateCurrencyRatesDaily', '0'),
	('VersionNumber', '4.11.0'),
	('WeightedAverageCosting', '1'),
	('WikiApp', 'MediaWiki'),
	('WikiPath', 'wiki'),
	('WorkingDaysWeek', '5'),
	('YearEnd', '3');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;


-- Dumping structure for table weberpcn.contractbom
DROP TABLE IF EXISTS `contractbom`;
CREATE TABLE IF NOT EXISTS `contractbom` (
  `contractref` varchar(20) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `workcentreadded` char(5) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '1',
  PRIMARY KEY (`contractref`,`stockid`,`workcentreadded`),
  KEY `Stockid` (`stockid`),
  KEY `ContractRef` (`contractref`),
  KEY `WorkCentreAdded` (`workcentreadded`),
  CONSTRAINT `contractbom_ibfk_1` FOREIGN KEY (`workcentreadded`) REFERENCES `workcentres` (`code`),
  CONSTRAINT `contractbom_ibfk_3` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.contractbom: ~0 rows (approximately)
/*!40000 ALTER TABLE `contractbom` DISABLE KEYS */;
/*!40000 ALTER TABLE `contractbom` ENABLE KEYS */;


-- Dumping structure for table weberpcn.contractcharges
DROP TABLE IF EXISTS `contractcharges`;
CREATE TABLE IF NOT EXISTS `contractcharges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contractref` varchar(20) NOT NULL,
  `transtype` smallint(6) NOT NULL DEFAULT '20',
  `transno` int(11) NOT NULL DEFAULT '0',
  `amount` double NOT NULL DEFAULT '0',
  `narrative` text NOT NULL,
  `anticipated` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `contractref` (`contractref`,`transtype`,`transno`),
  KEY `contractcharges_ibfk_2` (`transtype`),
  CONSTRAINT `contractcharges_ibfk_1` FOREIGN KEY (`contractref`) REFERENCES `contracts` (`contractref`),
  CONSTRAINT `contractcharges_ibfk_2` FOREIGN KEY (`transtype`) REFERENCES `systypes` (`typeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.contractcharges: ~0 rows (approximately)
/*!40000 ALTER TABLE `contractcharges` DISABLE KEYS */;
/*!40000 ALTER TABLE `contractcharges` ENABLE KEYS */;


-- Dumping structure for table weberpcn.contractreqts
DROP TABLE IF EXISTS `contractreqts`;
CREATE TABLE IF NOT EXISTS `contractreqts` (
  `contractreqid` int(11) NOT NULL AUTO_INCREMENT,
  `contractref` varchar(20) NOT NULL DEFAULT '0',
  `requirement` varchar(40) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '1',
  `costperunit` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`contractreqid`),
  KEY `ContractRef` (`contractref`),
  CONSTRAINT `contractreqts_ibfk_1` FOREIGN KEY (`contractref`) REFERENCES `contracts` (`contractref`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.contractreqts: ~0 rows (approximately)
/*!40000 ALTER TABLE `contractreqts` DISABLE KEYS */;
/*!40000 ALTER TABLE `contractreqts` ENABLE KEYS */;


-- Dumping structure for table weberpcn.contracts
DROP TABLE IF EXISTS `contracts`;
CREATE TABLE IF NOT EXISTS `contracts` (
  `contractref` varchar(20) NOT NULL DEFAULT '',
  `contractdescription` text NOT NULL,
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `categoryid` varchar(6) NOT NULL DEFAULT '',
  `orderno` int(11) NOT NULL DEFAULT '0',
  `customerref` varchar(20) NOT NULL DEFAULT '',
  `margin` double NOT NULL DEFAULT '1',
  `wo` int(11) NOT NULL DEFAULT '0',
  `requireddate` date NOT NULL DEFAULT '0000-00-00',
  `drawing` varchar(50) NOT NULL DEFAULT '',
  `exrate` double NOT NULL DEFAULT '1',
  PRIMARY KEY (`contractref`),
  KEY `OrderNo` (`orderno`),
  KEY `CategoryID` (`categoryid`),
  KEY `Status` (`status`),
  KEY `WO` (`wo`),
  KEY `loccode` (`loccode`),
  KEY `DebtorNo` (`debtorno`,`branchcode`),
  CONSTRAINT `contracts_ibfk_1` FOREIGN KEY (`debtorno`, `branchcode`) REFERENCES `custbranch` (`debtorno`, `branchcode`),
  CONSTRAINT `contracts_ibfk_2` FOREIGN KEY (`categoryid`) REFERENCES `stockcategory` (`categoryid`),
  CONSTRAINT `contracts_ibfk_3` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.contracts: ~0 rows (approximately)
/*!40000 ALTER TABLE `contracts` DISABLE KEYS */;
/*!40000 ALTER TABLE `contracts` ENABLE KEYS */;


-- Dumping structure for table weberpcn.currencies
DROP TABLE IF EXISTS `currencies`;
CREATE TABLE IF NOT EXISTS `currencies` (
  `currency` char(20) NOT NULL DEFAULT '',
  `currabrev` char(3) NOT NULL DEFAULT '',
  `country` char(50) NOT NULL DEFAULT '',
  `hundredsname` char(15) NOT NULL DEFAULT 'Cents',
  `decimalplaces` tinyint(3) NOT NULL DEFAULT '2',
  `rate` double NOT NULL DEFAULT '1',
  `webcart` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'If 1 shown in weberp cart. if 0 no show',
  PRIMARY KEY (`currabrev`),
  KEY `Country` (`country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.currencies: ~6 rows (approximately)
/*!40000 ALTER TABLE `currencies` DISABLE KEYS */;
INSERT INTO `currencies` (`currency`, `currabrev`, `country`, `hundredsname`, `decimalplaces`, `rate`, `webcart`) VALUES
	('Australian Dollars', 'AUD', 'Australia', 'cents', 2, 1, 1),
	('Swiss Francs', 'CHF', 'Swizerland', 'centimes', 2, 1, 1),
	('人民币', 'CNY', '中国', '分', 2, 1, 1),
	('Euro', 'EUR', 'Euroland', 'cents', 2, 0.44, 1),
	('Pounds', 'GBP', 'England', 'Pence', 2, 0.8, 1),
	('US Dollars', 'USD', 'United States', 'Cents', 2, 0.85, 1);
/*!40000 ALTER TABLE `currencies` ENABLE KEYS */;


-- Dumping structure for table weberpcn.custallocns
DROP TABLE IF EXISTS `custallocns`;
CREATE TABLE IF NOT EXISTS `custallocns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amt` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `datealloc` date NOT NULL DEFAULT '0000-00-00',
  `transid_allocfrom` int(11) NOT NULL DEFAULT '0',
  `transid_allocto` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `DateAlloc` (`datealloc`),
  KEY `TransID_AllocFrom` (`transid_allocfrom`),
  KEY `TransID_AllocTo` (`transid_allocto`),
  CONSTRAINT `custallocns_ibfk_1` FOREIGN KEY (`transid_allocfrom`) REFERENCES `debtortrans` (`id`),
  CONSTRAINT `custallocns_ibfk_2` FOREIGN KEY (`transid_allocto`) REFERENCES `debtortrans` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.custallocns: ~0 rows (approximately)
/*!40000 ALTER TABLE `custallocns` DISABLE KEYS */;
/*!40000 ALTER TABLE `custallocns` ENABLE KEYS */;


-- Dumping structure for table weberpcn.custbranch
DROP TABLE IF EXISTS `custbranch`;
CREATE TABLE IF NOT EXISTS `custbranch` (
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `brname` varchar(40) NOT NULL DEFAULT '',
  `braddress1` varchar(40) NOT NULL DEFAULT '',
  `braddress2` varchar(40) NOT NULL DEFAULT '',
  `braddress3` varchar(40) NOT NULL DEFAULT '',
  `braddress4` varchar(50) NOT NULL DEFAULT '',
  `braddress5` varchar(20) NOT NULL DEFAULT '',
  `braddress6` varchar(40) NOT NULL DEFAULT '',
  `lat` float(10,6) NOT NULL DEFAULT '0.000000',
  `lng` float(10,6) NOT NULL DEFAULT '0.000000',
  `estdeliverydays` smallint(6) NOT NULL DEFAULT '1',
  `area` char(3) NOT NULL,
  `salesman` varchar(4) NOT NULL DEFAULT '',
  `fwddate` smallint(6) NOT NULL DEFAULT '0',
  `phoneno` varchar(20) NOT NULL DEFAULT '',
  `faxno` varchar(20) NOT NULL DEFAULT '',
  `contactname` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(55) NOT NULL DEFAULT '',
  `defaultlocation` varchar(5) NOT NULL DEFAULT '',
  `taxgroupid` tinyint(4) NOT NULL DEFAULT '1',
  `defaultshipvia` int(11) NOT NULL DEFAULT '1',
  `deliverblind` tinyint(1) DEFAULT '1',
  `disabletrans` tinyint(4) NOT NULL DEFAULT '0',
  `brpostaddr1` varchar(40) NOT NULL DEFAULT '',
  `brpostaddr2` varchar(40) NOT NULL DEFAULT '',
  `brpostaddr3` varchar(40) NOT NULL DEFAULT '',
  `brpostaddr4` varchar(50) NOT NULL DEFAULT '',
  `brpostaddr5` varchar(20) NOT NULL DEFAULT '',
  `brpostaddr6` varchar(40) NOT NULL DEFAULT '',
  `specialinstructions` text NOT NULL,
  `custbranchcode` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`branchcode`,`debtorno`),
  KEY `BrName` (`brname`),
  KEY `DebtorNo` (`debtorno`),
  KEY `Salesman` (`salesman`),
  KEY `Area` (`area`),
  KEY `DefaultLocation` (`defaultlocation`),
  KEY `DefaultShipVia` (`defaultshipvia`),
  KEY `taxgroupid` (`taxgroupid`),
  CONSTRAINT `custbranch_ibfk_1` FOREIGN KEY (`debtorno`) REFERENCES `debtorsmaster` (`debtorno`),
  CONSTRAINT `custbranch_ibfk_2` FOREIGN KEY (`area`) REFERENCES `areas` (`areacode`),
  CONSTRAINT `custbranch_ibfk_3` FOREIGN KEY (`salesman`) REFERENCES `salesman` (`salesmancode`),
  CONSTRAINT `custbranch_ibfk_4` FOREIGN KEY (`defaultlocation`) REFERENCES `locations` (`loccode`),
  CONSTRAINT `custbranch_ibfk_6` FOREIGN KEY (`defaultshipvia`) REFERENCES `shippers` (`shipper_id`),
  CONSTRAINT `custbranch_ibfk_7` FOREIGN KEY (`taxgroupid`) REFERENCES `taxgroups` (`taxgroupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.custbranch: ~0 rows (approximately)
/*!40000 ALTER TABLE `custbranch` DISABLE KEYS */;
/*!40000 ALTER TABLE `custbranch` ENABLE KEYS */;


-- Dumping structure for table weberpcn.custcontacts
DROP TABLE IF EXISTS `custcontacts`;
CREATE TABLE IF NOT EXISTS `custcontacts` (
  `contid` int(11) NOT NULL AUTO_INCREMENT,
  `debtorno` varchar(10) NOT NULL,
  `contactname` varchar(40) NOT NULL,
  `role` varchar(40) NOT NULL,
  `phoneno` varchar(20) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `email` varchar(55) NOT NULL,
  PRIMARY KEY (`contid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.custcontacts: ~0 rows (approximately)
/*!40000 ALTER TABLE `custcontacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `custcontacts` ENABLE KEYS */;


-- Dumping structure for table weberpcn.custnotes
DROP TABLE IF EXISTS `custnotes`;
CREATE TABLE IF NOT EXISTS `custnotes` (
  `noteid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `debtorno` varchar(10) NOT NULL DEFAULT '0',
  `href` varchar(100) NOT NULL,
  `note` text NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `priority` varchar(20) NOT NULL,
  PRIMARY KEY (`noteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.custnotes: ~0 rows (approximately)
/*!40000 ALTER TABLE `custnotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `custnotes` ENABLE KEYS */;


-- Dumping structure for table weberpcn.debtorsmaster
DROP TABLE IF EXISTS `debtorsmaster`;
CREATE TABLE IF NOT EXISTS `debtorsmaster` (
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `name` varchar(40) NOT NULL DEFAULT '',
  `address1` varchar(40) NOT NULL DEFAULT '',
  `address2` varchar(40) NOT NULL DEFAULT '',
  `address3` varchar(40) NOT NULL DEFAULT '',
  `address4` varchar(50) NOT NULL DEFAULT '',
  `address5` varchar(20) NOT NULL DEFAULT '',
  `address6` varchar(40) NOT NULL DEFAULT '',
  `currcode` char(3) NOT NULL DEFAULT '',
  `salestype` char(2) NOT NULL DEFAULT '',
  `clientsince` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `holdreason` smallint(6) NOT NULL DEFAULT '0',
  `paymentterms` char(2) NOT NULL DEFAULT 'f',
  `discount` double NOT NULL DEFAULT '0',
  `pymtdiscount` double NOT NULL DEFAULT '0',
  `lastpaid` double NOT NULL DEFAULT '0',
  `lastpaiddate` datetime DEFAULT NULL,
  `creditlimit` double NOT NULL DEFAULT '1000',
  `invaddrbranch` tinyint(4) NOT NULL DEFAULT '0',
  `discountcode` char(2) NOT NULL DEFAULT '',
  `ediinvoices` tinyint(4) NOT NULL DEFAULT '0',
  `ediorders` tinyint(4) NOT NULL DEFAULT '0',
  `edireference` varchar(20) NOT NULL DEFAULT '',
  `editransport` varchar(5) NOT NULL DEFAULT 'email',
  `ediaddress` varchar(50) NOT NULL DEFAULT '',
  `ediserveruser` varchar(20) NOT NULL DEFAULT '',
  `ediserverpwd` varchar(20) NOT NULL DEFAULT '',
  `taxref` varchar(20) NOT NULL DEFAULT '',
  `customerpoline` tinyint(1) NOT NULL DEFAULT '0',
  `typeid` tinyint(4) NOT NULL DEFAULT '1',
  `language_id` varchar(10) NOT NULL DEFAULT 'en_GB.utf8',
  PRIMARY KEY (`debtorno`),
  KEY `Currency` (`currcode`),
  KEY `HoldReason` (`holdreason`),
  KEY `Name` (`name`),
  KEY `PaymentTerms` (`paymentterms`),
  KEY `SalesType` (`salestype`),
  KEY `EDIInvoices` (`ediinvoices`),
  KEY `EDIOrders` (`ediorders`),
  KEY `debtorsmaster_ibfk_5` (`typeid`),
  CONSTRAINT `debtorsmaster_ibfk_1` FOREIGN KEY (`holdreason`) REFERENCES `holdreasons` (`reasoncode`),
  CONSTRAINT `debtorsmaster_ibfk_2` FOREIGN KEY (`currcode`) REFERENCES `currencies` (`currabrev`),
  CONSTRAINT `debtorsmaster_ibfk_3` FOREIGN KEY (`paymentterms`) REFERENCES `paymentterms` (`termsindicator`),
  CONSTRAINT `debtorsmaster_ibfk_4` FOREIGN KEY (`salestype`) REFERENCES `salestypes` (`typeabbrev`),
  CONSTRAINT `debtorsmaster_ibfk_5` FOREIGN KEY (`typeid`) REFERENCES `debtortype` (`typeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.debtorsmaster: ~0 rows (approximately)
/*!40000 ALTER TABLE `debtorsmaster` DISABLE KEYS */;
/*!40000 ALTER TABLE `debtorsmaster` ENABLE KEYS */;


-- Dumping structure for table weberpcn.debtortrans
DROP TABLE IF EXISTS `debtortrans`;
CREATE TABLE IF NOT EXISTS `debtortrans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transno` int(11) NOT NULL DEFAULT '0',
  `type` smallint(6) NOT NULL DEFAULT '0',
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `trandate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `inputdate` datetime NOT NULL,
  `prd` smallint(6) NOT NULL DEFAULT '0',
  `settled` tinyint(4) NOT NULL DEFAULT '0',
  `reference` varchar(20) NOT NULL DEFAULT '',
  `tpe` char(2) NOT NULL DEFAULT '',
  `order_` int(11) NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '0',
  `ovamount` double NOT NULL DEFAULT '0',
  `ovgst` double NOT NULL DEFAULT '0',
  `ovfreight` double NOT NULL DEFAULT '0',
  `ovdiscount` double NOT NULL DEFAULT '0',
  `diffonexch` double NOT NULL DEFAULT '0',
  `alloc` double NOT NULL DEFAULT '0',
  `invtext` text,
  `shipvia` int(11) NOT NULL DEFAULT '0',
  `edisent` tinyint(4) NOT NULL DEFAULT '0',
  `consignment` varchar(15) NOT NULL DEFAULT '',
  `packages` int(11) NOT NULL DEFAULT '1' COMMENT 'number of cartons',
  `salesperson` varchar(4) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `DebtorNo` (`debtorno`,`branchcode`),
  KEY `Order_` (`order_`),
  KEY `Prd` (`prd`),
  KEY `Tpe` (`tpe`),
  KEY `Type` (`type`),
  KEY `Settled` (`settled`),
  KEY `TranDate` (`trandate`),
  KEY `TransNo` (`transno`),
  KEY `Type_2` (`type`,`transno`),
  KEY `EDISent` (`edisent`),
  KEY `salesperson` (`salesperson`),
  CONSTRAINT `debtortrans_ibfk_2` FOREIGN KEY (`type`) REFERENCES `systypes` (`typeid`),
  CONSTRAINT `debtortrans_ibfk_3` FOREIGN KEY (`prd`) REFERENCES `periods` (`periodno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.debtortrans: ~0 rows (approximately)
/*!40000 ALTER TABLE `debtortrans` DISABLE KEYS */;
/*!40000 ALTER TABLE `debtortrans` ENABLE KEYS */;


-- Dumping structure for table weberpcn.debtortranstaxes
DROP TABLE IF EXISTS `debtortranstaxes`;
CREATE TABLE IF NOT EXISTS `debtortranstaxes` (
  `debtortransid` int(11) NOT NULL DEFAULT '0',
  `taxauthid` tinyint(4) NOT NULL DEFAULT '0',
  `taxamount` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`debtortransid`,`taxauthid`),
  KEY `taxauthid` (`taxauthid`),
  CONSTRAINT `debtortranstaxes_ibfk_1` FOREIGN KEY (`taxauthid`) REFERENCES `taxauthorities` (`taxid`),
  CONSTRAINT `debtortranstaxes_ibfk_2` FOREIGN KEY (`debtortransid`) REFERENCES `debtortrans` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.debtortranstaxes: ~0 rows (approximately)
/*!40000 ALTER TABLE `debtortranstaxes` DISABLE KEYS */;
/*!40000 ALTER TABLE `debtortranstaxes` ENABLE KEYS */;


-- Dumping structure for table weberpcn.debtortype
DROP TABLE IF EXISTS `debtortype`;
CREATE TABLE IF NOT EXISTS `debtortype` (
  `typeid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `typename` varchar(100) NOT NULL,
  PRIMARY KEY (`typeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.debtortype: ~0 rows (approximately)
/*!40000 ALTER TABLE `debtortype` DISABLE KEYS */;
/*!40000 ALTER TABLE `debtortype` ENABLE KEYS */;


-- Dumping structure for table weberpcn.debtortypenotes
DROP TABLE IF EXISTS `debtortypenotes`;
CREATE TABLE IF NOT EXISTS `debtortypenotes` (
  `noteid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `typeid` tinyint(4) NOT NULL DEFAULT '0',
  `href` varchar(100) NOT NULL,
  `note` varchar(200) NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `priority` varchar(20) NOT NULL,
  PRIMARY KEY (`noteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.debtortypenotes: ~0 rows (approximately)
/*!40000 ALTER TABLE `debtortypenotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `debtortypenotes` ENABLE KEYS */;


-- Dumping structure for table weberpcn.deliverynotes
DROP TABLE IF EXISTS `deliverynotes`;
CREATE TABLE IF NOT EXISTS `deliverynotes` (
  `deliverynotenumber` int(11) NOT NULL,
  `deliverynotelineno` tinyint(4) NOT NULL,
  `salesorderno` int(11) NOT NULL,
  `salesorderlineno` int(11) NOT NULL,
  `qtydelivered` double NOT NULL DEFAULT '0',
  `printed` tinyint(4) NOT NULL DEFAULT '0',
  `invoiced` tinyint(4) NOT NULL DEFAULT '0',
  `deliverydate` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`deliverynotenumber`,`deliverynotelineno`),
  KEY `deliverynotes_ibfk_2` (`salesorderno`,`salesorderlineno`),
  CONSTRAINT `deliverynotes_ibfk_1` FOREIGN KEY (`salesorderno`) REFERENCES `salesorders` (`orderno`),
  CONSTRAINT `deliverynotes_ibfk_2` FOREIGN KEY (`salesorderno`, `salesorderlineno`) REFERENCES `salesorderdetails` (`orderno`, `orderlineno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.deliverynotes: ~0 rows (approximately)
/*!40000 ALTER TABLE `deliverynotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `deliverynotes` ENABLE KEYS */;


-- Dumping structure for table weberpcn.departments
DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `departmentid` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL DEFAULT '',
  `authoriser` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`departmentid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.departments: ~1 rows (approximately)
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` (`departmentid`, `description`, `authoriser`) VALUES
	(1, '售前部门', 'admin');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;


-- Dumping structure for table weberpcn.discountmatrix
DROP TABLE IF EXISTS `discountmatrix`;
CREATE TABLE IF NOT EXISTS `discountmatrix` (
  `salestype` char(2) NOT NULL DEFAULT '',
  `discountcategory` char(2) NOT NULL DEFAULT '',
  `quantitybreak` int(11) NOT NULL DEFAULT '1',
  `discountrate` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`salestype`,`discountcategory`,`quantitybreak`),
  KEY `QuantityBreak` (`quantitybreak`),
  KEY `DiscountCategory` (`discountcategory`),
  KEY `SalesType` (`salestype`),
  CONSTRAINT `discountmatrix_ibfk_1` FOREIGN KEY (`salestype`) REFERENCES `salestypes` (`typeabbrev`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.discountmatrix: ~0 rows (approximately)
/*!40000 ALTER TABLE `discountmatrix` DISABLE KEYS */;
/*!40000 ALTER TABLE `discountmatrix` ENABLE KEYS */;


-- Dumping structure for table weberpcn.ediitemmapping
DROP TABLE IF EXISTS `ediitemmapping`;
CREATE TABLE IF NOT EXISTS `ediitemmapping` (
  `supporcust` varchar(4) NOT NULL DEFAULT '',
  `partnercode` varchar(10) NOT NULL DEFAULT '',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `partnerstockid` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`supporcust`,`partnercode`,`stockid`),
  KEY `PartnerCode` (`partnercode`),
  KEY `StockID` (`stockid`),
  KEY `PartnerStockID` (`partnerstockid`),
  KEY `SuppOrCust` (`supporcust`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.ediitemmapping: ~0 rows (approximately)
/*!40000 ALTER TABLE `ediitemmapping` DISABLE KEYS */;
/*!40000 ALTER TABLE `ediitemmapping` ENABLE KEYS */;


-- Dumping structure for table weberpcn.edimessageformat
DROP TABLE IF EXISTS `edimessageformat`;
CREATE TABLE IF NOT EXISTS `edimessageformat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partnercode` varchar(10) NOT NULL DEFAULT '',
  `messagetype` varchar(6) NOT NULL DEFAULT '',
  `section` varchar(7) NOT NULL DEFAULT '',
  `sequenceno` int(11) NOT NULL DEFAULT '0',
  `linetext` varchar(70) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `PartnerCode` (`partnercode`,`messagetype`,`sequenceno`),
  KEY `Section` (`section`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.edimessageformat: ~0 rows (approximately)
/*!40000 ALTER TABLE `edimessageformat` DISABLE KEYS */;
/*!40000 ALTER TABLE `edimessageformat` ENABLE KEYS */;


-- Dumping structure for table weberpcn.edi_orders_segs
DROP TABLE IF EXISTS `edi_orders_segs`;
CREATE TABLE IF NOT EXISTS `edi_orders_segs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `segtag` char(3) NOT NULL DEFAULT '',
  `seggroup` tinyint(4) NOT NULL DEFAULT '0',
  `maxoccur` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `SegTag` (`segtag`),
  KEY `SegNo` (`seggroup`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.edi_orders_segs: ~95 rows (approximately)
/*!40000 ALTER TABLE `edi_orders_segs` DISABLE KEYS */;
INSERT INTO `edi_orders_segs` (`id`, `segtag`, `seggroup`, `maxoccur`) VALUES
	(1, 'UNB', 0, 1),
	(2, 'UNH', 0, 1),
	(3, 'BGM', 0, 1),
	(4, 'DTM', 0, 35),
	(5, 'PAI', 0, 1),
	(6, 'ALI', 0, 5),
	(7, 'FTX', 0, 99),
	(8, 'RFF', 1, 1),
	(9, 'DTM', 1, 5),
	(10, 'NAD', 2, 1),
	(11, 'LOC', 2, 99),
	(12, 'FII', 2, 5),
	(13, 'RFF', 3, 1),
	(14, 'CTA', 5, 1),
	(15, 'COM', 5, 5),
	(16, 'TAX', 6, 1),
	(17, 'MOA', 6, 1),
	(18, 'CUX', 7, 1),
	(19, 'DTM', 7, 5),
	(20, 'PAT', 8, 1),
	(21, 'DTM', 8, 5),
	(22, 'PCD', 8, 1),
	(23, 'MOA', 9, 1),
	(24, 'TDT', 10, 1),
	(25, 'LOC', 11, 1),
	(26, 'DTM', 11, 5),
	(27, 'TOD', 12, 1),
	(28, 'LOC', 12, 2),
	(29, 'PAC', 13, 1),
	(30, 'PCI', 14, 1),
	(31, 'RFF', 14, 1),
	(32, 'DTM', 14, 5),
	(33, 'GIN', 14, 10),
	(34, 'EQD', 15, 1),
	(35, 'ALC', 19, 1),
	(36, 'ALI', 19, 5),
	(37, 'DTM', 19, 5),
	(38, 'QTY', 20, 1),
	(39, 'RNG', 20, 1),
	(40, 'PCD', 21, 1),
	(41, 'RNG', 21, 1),
	(42, 'MOA', 22, 1),
	(43, 'RNG', 22, 1),
	(44, 'RTE', 23, 1),
	(45, 'RNG', 23, 1),
	(46, 'TAX', 24, 1),
	(47, 'MOA', 24, 1),
	(48, 'LIN', 28, 1),
	(49, 'PIA', 28, 25),
	(50, 'IMD', 28, 99),
	(51, 'MEA', 28, 99),
	(52, 'QTY', 28, 99),
	(53, 'ALI', 28, 5),
	(54, 'DTM', 28, 35),
	(55, 'MOA', 28, 10),
	(56, 'GIN', 28, 127),
	(57, 'QVR', 28, 1),
	(58, 'FTX', 28, 99),
	(59, 'PRI', 32, 1),
	(60, 'CUX', 32, 1),
	(61, 'DTM', 32, 5),
	(62, 'RFF', 33, 1),
	(63, 'DTM', 33, 5),
	(64, 'PAC', 34, 1),
	(65, 'QTY', 34, 5),
	(66, 'PCI', 36, 1),
	(67, 'RFF', 36, 1),
	(68, 'DTM', 36, 5),
	(69, 'GIN', 36, 10),
	(70, 'LOC', 37, 1),
	(71, 'QTY', 37, 1),
	(72, 'DTM', 37, 5),
	(73, 'TAX', 38, 1),
	(74, 'MOA', 38, 1),
	(75, 'NAD', 39, 1),
	(76, 'CTA', 42, 1),
	(77, 'COM', 42, 5),
	(78, 'ALC', 43, 1),
	(79, 'ALI', 43, 5),
	(80, 'DTM', 43, 5),
	(81, 'QTY', 44, 1),
	(82, 'RNG', 44, 1),
	(83, 'PCD', 45, 1),
	(84, 'RNG', 45, 1),
	(85, 'MOA', 46, 1),
	(86, 'RNG', 46, 1),
	(87, 'RTE', 47, 1),
	(88, 'RNG', 47, 1),
	(89, 'TAX', 48, 1),
	(90, 'MOA', 48, 1),
	(91, 'TDT', 49, 1),
	(92, 'UNS', 50, 1),
	(93, 'MOA', 50, 1),
	(94, 'CNT', 50, 1),
	(95, 'UNT', 50, 1);
/*!40000 ALTER TABLE `edi_orders_segs` ENABLE KEYS */;


-- Dumping structure for table weberpcn.edi_orders_seg_groups
DROP TABLE IF EXISTS `edi_orders_seg_groups`;
CREATE TABLE IF NOT EXISTS `edi_orders_seg_groups` (
  `seggroupno` tinyint(4) NOT NULL DEFAULT '0',
  `maxoccur` int(4) NOT NULL DEFAULT '0',
  `parentseggroup` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`seggroupno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.edi_orders_seg_groups: ~38 rows (approximately)
/*!40000 ALTER TABLE `edi_orders_seg_groups` DISABLE KEYS */;
INSERT INTO `edi_orders_seg_groups` (`seggroupno`, `maxoccur`, `parentseggroup`) VALUES
	(0, 1, 0),
	(1, 9999, 0),
	(2, 99, 0),
	(3, 99, 2),
	(5, 5, 2),
	(6, 5, 0),
	(7, 5, 0),
	(8, 10, 0),
	(9, 9999, 8),
	(10, 10, 0),
	(11, 10, 10),
	(12, 5, 0),
	(13, 99, 0),
	(14, 5, 13),
	(15, 10, 0),
	(19, 99, 0),
	(20, 1, 19),
	(21, 1, 19),
	(22, 2, 19),
	(23, 1, 19),
	(24, 5, 19),
	(28, 200000, 0),
	(32, 25, 28),
	(33, 9999, 28),
	(34, 99, 28),
	(36, 5, 34),
	(37, 9999, 28),
	(38, 10, 28),
	(39, 999, 28),
	(42, 5, 39),
	(43, 99, 28),
	(44, 1, 43),
	(45, 1, 43),
	(46, 2, 43),
	(47, 1, 43),
	(48, 5, 43),
	(49, 10, 28),
	(50, 1, 0);
/*!40000 ALTER TABLE `edi_orders_seg_groups` ENABLE KEYS */;


-- Dumping structure for table weberpcn.emailsettings
DROP TABLE IF EXISTS `emailsettings`;
CREATE TABLE IF NOT EXISTS `emailsettings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host` varchar(30) NOT NULL,
  `port` char(5) NOT NULL,
  `heloaddress` varchar(20) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(30) DEFAULT NULL,
  `timeout` int(11) DEFAULT '5',
  `companyname` varchar(50) DEFAULT NULL,
  `auth` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.emailsettings: ~0 rows (approximately)
/*!40000 ALTER TABLE `emailsettings` DISABLE KEYS */;
/*!40000 ALTER TABLE `emailsettings` ENABLE KEYS */;


-- Dumping structure for table weberpcn.factorcompanies
DROP TABLE IF EXISTS `factorcompanies`;
CREATE TABLE IF NOT EXISTS `factorcompanies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coyname` varchar(50) NOT NULL DEFAULT '',
  `address1` varchar(40) NOT NULL DEFAULT '',
  `address2` varchar(40) NOT NULL DEFAULT '',
  `address3` varchar(40) NOT NULL DEFAULT '',
  `address4` varchar(40) NOT NULL DEFAULT '',
  `address5` varchar(20) NOT NULL DEFAULT '',
  `address6` varchar(15) NOT NULL DEFAULT '',
  `contact` varchar(25) NOT NULL DEFAULT '',
  `telephone` varchar(25) NOT NULL DEFAULT '',
  `fax` varchar(25) NOT NULL DEFAULT '',
  `email` varchar(55) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `factor_name` (`coyname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.factorcompanies: ~0 rows (approximately)
/*!40000 ALTER TABLE `factorcompanies` DISABLE KEYS */;
/*!40000 ALTER TABLE `factorcompanies` ENABLE KEYS */;


-- Dumping structure for table weberpcn.fixedassetcategories
DROP TABLE IF EXISTS `fixedassetcategories`;
CREATE TABLE IF NOT EXISTS `fixedassetcategories` (
  `categoryid` char(6) NOT NULL DEFAULT '',
  `categorydescription` char(20) NOT NULL DEFAULT '',
  `costact` varchar(20) NOT NULL DEFAULT '0',
  `depnact` varchar(20) NOT NULL DEFAULT '0',
  `disposalact` varchar(20) NOT NULL DEFAULT '80000',
  `accumdepnact` varchar(20) NOT NULL DEFAULT '0',
  `defaultdepnrate` double NOT NULL DEFAULT '0.2',
  `defaultdepntype` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`categoryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.fixedassetcategories: ~0 rows (approximately)
/*!40000 ALTER TABLE `fixedassetcategories` DISABLE KEYS */;
/*!40000 ALTER TABLE `fixedassetcategories` ENABLE KEYS */;


-- Dumping structure for table weberpcn.fixedassetlocations
DROP TABLE IF EXISTS `fixedassetlocations`;
CREATE TABLE IF NOT EXISTS `fixedassetlocations` (
  `locationid` char(6) NOT NULL DEFAULT '',
  `locationdescription` char(20) NOT NULL DEFAULT '',
  `parentlocationid` char(6) DEFAULT '',
  PRIMARY KEY (`locationid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.fixedassetlocations: ~0 rows (approximately)
/*!40000 ALTER TABLE `fixedassetlocations` DISABLE KEYS */;
/*!40000 ALTER TABLE `fixedassetlocations` ENABLE KEYS */;


-- Dumping structure for table weberpcn.fixedassets
DROP TABLE IF EXISTS `fixedassets`;
CREATE TABLE IF NOT EXISTS `fixedassets` (
  `assetid` int(11) NOT NULL AUTO_INCREMENT,
  `serialno` varchar(30) NOT NULL DEFAULT '',
  `barcode` varchar(20) NOT NULL,
  `assetlocation` varchar(6) NOT NULL DEFAULT '',
  `cost` double NOT NULL DEFAULT '0',
  `accumdepn` double NOT NULL DEFAULT '0',
  `datepurchased` date NOT NULL DEFAULT '0000-00-00',
  `disposalproceeds` double NOT NULL DEFAULT '0',
  `assetcategoryid` varchar(6) NOT NULL DEFAULT '',
  `description` varchar(50) NOT NULL DEFAULT '',
  `longdescription` text NOT NULL,
  `depntype` int(11) NOT NULL DEFAULT '1',
  `depnrate` double NOT NULL,
  `disposaldate` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`assetid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.fixedassets: ~0 rows (approximately)
/*!40000 ALTER TABLE `fixedassets` DISABLE KEYS */;
/*!40000 ALTER TABLE `fixedassets` ENABLE KEYS */;


-- Dumping structure for table weberpcn.fixedassettasks
DROP TABLE IF EXISTS `fixedassettasks`;
CREATE TABLE IF NOT EXISTS `fixedassettasks` (
  `taskid` int(11) NOT NULL AUTO_INCREMENT,
  `assetid` int(11) NOT NULL,
  `taskdescription` text NOT NULL,
  `frequencydays` int(11) NOT NULL DEFAULT '365',
  `lastcompleted` date NOT NULL,
  `userresponsible` varchar(20) NOT NULL,
  `manager` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`taskid`),
  KEY `assetid` (`assetid`),
  KEY `userresponsible` (`userresponsible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.fixedassettasks: ~0 rows (approximately)
/*!40000 ALTER TABLE `fixedassettasks` DISABLE KEYS */;
/*!40000 ALTER TABLE `fixedassettasks` ENABLE KEYS */;


-- Dumping structure for table weberpcn.fixedassettrans
DROP TABLE IF EXISTS `fixedassettrans`;
CREATE TABLE IF NOT EXISTS `fixedassettrans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assetid` int(11) NOT NULL,
  `transtype` tinyint(4) NOT NULL,
  `transdate` date NOT NULL,
  `transno` int(11) NOT NULL,
  `periodno` smallint(6) NOT NULL,
  `inputdate` date NOT NULL,
  `fixedassettranstype` varchar(8) NOT NULL,
  `amount` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `assetid` (`assetid`,`transtype`,`transno`),
  KEY `inputdate` (`inputdate`),
  KEY `transdate` (`transdate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.fixedassettrans: ~0 rows (approximately)
/*!40000 ALTER TABLE `fixedassettrans` DISABLE KEYS */;
/*!40000 ALTER TABLE `fixedassettrans` ENABLE KEYS */;


-- Dumping structure for table weberpcn.freightcosts
DROP TABLE IF EXISTS `freightcosts`;
CREATE TABLE IF NOT EXISTS `freightcosts` (
  `shipcostfromid` int(11) NOT NULL AUTO_INCREMENT,
  `locationfrom` varchar(5) NOT NULL DEFAULT '',
  `destinationcountry` varchar(40) NOT NULL,
  `destination` varchar(40) NOT NULL DEFAULT '',
  `shipperid` int(11) NOT NULL DEFAULT '0',
  `cubrate` double NOT NULL DEFAULT '0',
  `kgrate` double NOT NULL DEFAULT '0',
  `maxkgs` double NOT NULL DEFAULT '999999',
  `maxcub` double NOT NULL DEFAULT '999999',
  `fixedprice` double NOT NULL DEFAULT '0',
  `minimumchg` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`shipcostfromid`),
  KEY `Destination` (`destination`),
  KEY `LocationFrom` (`locationfrom`),
  KEY `ShipperID` (`shipperid`),
  KEY `Destination_2` (`destination`,`locationfrom`,`shipperid`),
  CONSTRAINT `freightcosts_ibfk_1` FOREIGN KEY (`locationfrom`) REFERENCES `locations` (`loccode`),
  CONSTRAINT `freightcosts_ibfk_2` FOREIGN KEY (`shipperid`) REFERENCES `shippers` (`shipper_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.freightcosts: ~0 rows (approximately)
/*!40000 ALTER TABLE `freightcosts` DISABLE KEYS */;
/*!40000 ALTER TABLE `freightcosts` ENABLE KEYS */;


-- Dumping structure for table weberpcn.geocode_param
DROP TABLE IF EXISTS `geocode_param`;
CREATE TABLE IF NOT EXISTS `geocode_param` (
  `geocodeid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `geocode_key` varchar(200) NOT NULL DEFAULT '',
  `center_long` varchar(20) NOT NULL DEFAULT '',
  `center_lat` varchar(20) NOT NULL DEFAULT '',
  `map_height` varchar(10) NOT NULL DEFAULT '',
  `map_width` varchar(10) NOT NULL DEFAULT '',
  `map_host` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`geocodeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.geocode_param: ~0 rows (approximately)
/*!40000 ALTER TABLE `geocode_param` DISABLE KEYS */;
/*!40000 ALTER TABLE `geocode_param` ENABLE KEYS */;


-- Dumping structure for table weberpcn.gltrans
DROP TABLE IF EXISTS `gltrans`;
CREATE TABLE IF NOT EXISTS `gltrans` (
  `counterindex` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) NOT NULL DEFAULT '0',
  `typeno` bigint(16) NOT NULL DEFAULT '1',
  `chequeno` int(11) NOT NULL DEFAULT '0',
  `trandate` date NOT NULL DEFAULT '0000-00-00',
  `periodno` smallint(6) NOT NULL DEFAULT '0',
  `account` varchar(20) NOT NULL DEFAULT '0',
  `narrative` varchar(200) NOT NULL DEFAULT '',
  `amount` double NOT NULL DEFAULT '0',
  `posted` tinyint(4) NOT NULL DEFAULT '0',
  `jobref` varchar(20) NOT NULL DEFAULT '',
  `tag` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`counterindex`),
  KEY `Account` (`account`),
  KEY `ChequeNo` (`chequeno`),
  KEY `PeriodNo` (`periodno`),
  KEY `Posted` (`posted`),
  KEY `TranDate` (`trandate`),
  KEY `TypeNo` (`typeno`),
  KEY `Type_and_Number` (`type`,`typeno`),
  KEY `JobRef` (`jobref`),
  KEY `tag` (`tag`),
  CONSTRAINT `gltrans_ibfk_1` FOREIGN KEY (`account`) REFERENCES `chartmaster` (`accountcode`),
  CONSTRAINT `gltrans_ibfk_2` FOREIGN KEY (`type`) REFERENCES `systypes` (`typeid`),
  CONSTRAINT `gltrans_ibfk_3` FOREIGN KEY (`periodno`) REFERENCES `periods` (`periodno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.gltrans: ~0 rows (approximately)
/*!40000 ALTER TABLE `gltrans` DISABLE KEYS */;
/*!40000 ALTER TABLE `gltrans` ENABLE KEYS */;


-- Dumping structure for table weberpcn.grns
DROP TABLE IF EXISTS `grns`;
CREATE TABLE IF NOT EXISTS `grns` (
  `grnbatch` smallint(6) NOT NULL DEFAULT '0',
  `grnno` int(11) NOT NULL AUTO_INCREMENT,
  `podetailitem` int(11) NOT NULL DEFAULT '0',
  `itemcode` varchar(20) NOT NULL DEFAULT '',
  `deliverydate` date NOT NULL DEFAULT '0000-00-00',
  `itemdescription` varchar(100) NOT NULL DEFAULT '',
  `qtyrecd` double NOT NULL DEFAULT '0',
  `quantityinv` double NOT NULL DEFAULT '0',
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `stdcostunit` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`grnno`),
  KEY `DeliveryDate` (`deliverydate`),
  KEY `ItemCode` (`itemcode`),
  KEY `PODetailItem` (`podetailitem`),
  KEY `SupplierID` (`supplierid`),
  CONSTRAINT `grns_ibfk_1` FOREIGN KEY (`supplierid`) REFERENCES `suppliers` (`supplierid`),
  CONSTRAINT `grns_ibfk_2` FOREIGN KEY (`podetailitem`) REFERENCES `purchorderdetails` (`podetailitem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.grns: ~0 rows (approximately)
/*!40000 ALTER TABLE `grns` DISABLE KEYS */;
/*!40000 ALTER TABLE `grns` ENABLE KEYS */;


-- Dumping structure for table weberpcn.holdreasons
DROP TABLE IF EXISTS `holdreasons`;
CREATE TABLE IF NOT EXISTS `holdreasons` (
  `reasoncode` smallint(6) NOT NULL DEFAULT '1',
  `reasondescription` char(30) NOT NULL DEFAULT '',
  `dissallowinvoices` tinyint(4) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`reasoncode`),
  KEY `ReasonDescription` (`reasondescription`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.holdreasons: ~3 rows (approximately)
/*!40000 ALTER TABLE `holdreasons` DISABLE KEYS */;
INSERT INTO `holdreasons` (`reasoncode`, `reasondescription`, `dissallowinvoices`) VALUES
	(1, '良好', 0),
	(20, '考察', 0),
	(51, '差', 1);
/*!40000 ALTER TABLE `holdreasons` ENABLE KEYS */;


-- Dumping structure for table weberpcn.internalstockcatrole
DROP TABLE IF EXISTS `internalstockcatrole`;
CREATE TABLE IF NOT EXISTS `internalstockcatrole` (
  `categoryid` varchar(6) NOT NULL,
  `secroleid` int(11) NOT NULL,
  PRIMARY KEY (`categoryid`,`secroleid`),
  KEY `internalstockcatrole_ibfk_1` (`categoryid`),
  KEY `internalstockcatrole_ibfk_2` (`secroleid`),
  CONSTRAINT `internalstockcatrole_ibfk_1` FOREIGN KEY (`categoryid`) REFERENCES `stockcategory` (`categoryid`),
  CONSTRAINT `internalstockcatrole_ibfk_2` FOREIGN KEY (`secroleid`) REFERENCES `securityroles` (`secroleid`),
  CONSTRAINT `internalstockcatrole_ibfk_3` FOREIGN KEY (`categoryid`) REFERENCES `stockcategory` (`categoryid`),
  CONSTRAINT `internalstockcatrole_ibfk_4` FOREIGN KEY (`secroleid`) REFERENCES `securityroles` (`secroleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.internalstockcatrole: ~7 rows (approximately)
/*!40000 ALTER TABLE `internalstockcatrole` DISABLE KEYS */;
INSERT INTO `internalstockcatrole` (`categoryid`, `secroleid`) VALUES
	('BCP', 8),
	('BZW', 8),
	('CCP', 8),
	('FMP', 8),
	('RGFY', 8),
	('XNWL', 8),
	('ZZFY', 8);
/*!40000 ALTER TABLE `internalstockcatrole` ENABLE KEYS */;


-- Dumping structure for table weberpcn.labelfields
DROP TABLE IF EXISTS `labelfields`;
CREATE TABLE IF NOT EXISTS `labelfields` (
  `labelfieldid` int(11) NOT NULL AUTO_INCREMENT,
  `labelid` tinyint(4) NOT NULL,
  `fieldvalue` varchar(20) NOT NULL,
  `vpos` double NOT NULL DEFAULT '0',
  `hpos` double NOT NULL DEFAULT '0',
  `fontsize` tinyint(4) NOT NULL,
  `barcode` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`labelfieldid`),
  KEY `labelid` (`labelid`),
  KEY `vpos` (`vpos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.labelfields: ~0 rows (approximately)
/*!40000 ALTER TABLE `labelfields` DISABLE KEYS */;
/*!40000 ALTER TABLE `labelfields` ENABLE KEYS */;


-- Dumping structure for table weberpcn.labels
DROP TABLE IF EXISTS `labels`;
CREATE TABLE IF NOT EXISTS `labels` (
  `labelid` tinyint(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  `pagewidth` double NOT NULL DEFAULT '0',
  `pageheight` double NOT NULL DEFAULT '0',
  `height` double NOT NULL DEFAULT '0',
  `width` double NOT NULL DEFAULT '0',
  `topmargin` double NOT NULL DEFAULT '0',
  `leftmargin` double NOT NULL DEFAULT '0',
  `rowheight` double NOT NULL DEFAULT '0',
  `columnwidth` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`labelid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.labels: ~0 rows (approximately)
/*!40000 ALTER TABLE `labels` DISABLE KEYS */;
/*!40000 ALTER TABLE `labels` ENABLE KEYS */;


-- Dumping structure for table weberpcn.lastcostrollup
DROP TABLE IF EXISTS `lastcostrollup`;
CREATE TABLE IF NOT EXISTS `lastcostrollup` (
  `stockid` char(20) NOT NULL DEFAULT '',
  `totalonhand` double NOT NULL DEFAULT '0',
  `matcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `labcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `oheadcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `categoryid` char(6) NOT NULL DEFAULT '',
  `stockact` varchar(20) NOT NULL DEFAULT '0',
  `adjglact` varchar(20) NOT NULL DEFAULT '0',
  `newmatcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `newlabcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `newoheadcost` decimal(20,4) NOT NULL DEFAULT '0.0000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.lastcostrollup: ~0 rows (approximately)
/*!40000 ALTER TABLE `lastcostrollup` DISABLE KEYS */;
/*!40000 ALTER TABLE `lastcostrollup` ENABLE KEYS */;


-- Dumping structure for table weberpcn.locations
DROP TABLE IF EXISTS `locations`;
CREATE TABLE IF NOT EXISTS `locations` (
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `locationname` varchar(50) NOT NULL DEFAULT '',
  `deladd1` varchar(40) NOT NULL DEFAULT '',
  `deladd2` varchar(40) NOT NULL DEFAULT '',
  `deladd3` varchar(40) NOT NULL DEFAULT '',
  `deladd4` varchar(40) NOT NULL DEFAULT '',
  `deladd5` varchar(20) NOT NULL DEFAULT '',
  `deladd6` varchar(15) NOT NULL DEFAULT '',
  `tel` varchar(30) NOT NULL DEFAULT '',
  `fax` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(55) NOT NULL DEFAULT '',
  `contact` varchar(30) NOT NULL DEFAULT '',
  `taxprovinceid` tinyint(4) NOT NULL DEFAULT '1',
  `cashsalecustomer` varchar(10) DEFAULT '',
  `managed` int(11) DEFAULT '0',
  `cashsalebranch` varchar(10) DEFAULT '',
  `internalrequest` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Allow (1) or not (0) internal request from this location',
  PRIMARY KEY (`loccode`),
  UNIQUE KEY `locationname` (`locationname`),
  KEY `taxprovinceid` (`taxprovinceid`),
  CONSTRAINT `locations_ibfk_1` FOREIGN KEY (`taxprovinceid`) REFERENCES `taxprovinces` (`taxprovinceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.locations: ~1 rows (approximately)
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` (`loccode`, `locationname`, `deladd1`, `deladd2`, `deladd3`, `deladd4`, `deladd5`, `deladd6`, `tel`, `fax`, `email`, `contact`, `taxprovinceid`, `cashsalecustomer`, `managed`, `cashsalebranch`, `internalrequest`) VALUES
	('MEL', '易耗品仓库', '1234 Collins Street', 'Melbourne', 'Victoria 2345', '', '', 'Australia', '+61 3 56789012', '+61 3 56789013', 'jacko@webdemo.com', 'Jack Roberts', 1, 'KES', 0, 'KES', 1);
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;


-- Dumping structure for table weberpcn.locstock
DROP TABLE IF EXISTS `locstock`;
CREATE TABLE IF NOT EXISTS `locstock` (
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '0',
  `reorderlevel` bigint(20) NOT NULL DEFAULT '0',
  `bin` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`loccode`,`stockid`),
  KEY `StockID` (`stockid`),
  KEY `bin` (`bin`),
  CONSTRAINT `locstock_ibfk_1` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`),
  CONSTRAINT `locstock_ibfk_2` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.locstock: ~1 rows (approximately)



-- Dumping structure for table weberpcn.loctransfers
DROP TABLE IF EXISTS `loctransfers`;
CREATE TABLE IF NOT EXISTS `loctransfers` (
  `reference` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `shipqty` double NOT NULL DEFAULT '0',
  `recqty` double NOT NULL DEFAULT '0',
  `shipdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `recdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `shiploc` varchar(7) NOT NULL DEFAULT '',
  `recloc` varchar(7) NOT NULL DEFAULT '',
  KEY `Reference` (`reference`,`stockid`),
  KEY `ShipLoc` (`shiploc`),
  KEY `RecLoc` (`recloc`),
  KEY `StockID` (`stockid`),
  CONSTRAINT `loctransfers_ibfk_1` FOREIGN KEY (`shiploc`) REFERENCES `locations` (`loccode`),
  CONSTRAINT `loctransfers_ibfk_2` FOREIGN KEY (`recloc`) REFERENCES `locations` (`loccode`),
  CONSTRAINT `loctransfers_ibfk_3` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores Shipments To And From Locations';

-- Dumping data for table weberpcn.loctransfers: ~0 rows (approximately)
/*!40000 ALTER TABLE `loctransfers` DISABLE KEYS */;
/*!40000 ALTER TABLE `loctransfers` ENABLE KEYS */;


-- Dumping structure for table weberpcn.mailgroupdetails
DROP TABLE IF EXISTS `mailgroupdetails`;
CREATE TABLE IF NOT EXISTS `mailgroupdetails` (
  `groupname` varchar(100) NOT NULL,
  `userid` varchar(20) NOT NULL,
  KEY `userid` (`userid`),
  KEY `groupname` (`groupname`),
  CONSTRAINT `mailgroupdetails_ibfk_1` FOREIGN KEY (`groupname`) REFERENCES `mailgroups` (`groupname`),
  CONSTRAINT `mailgroupdetails_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `www_users` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.mailgroupdetails: ~0 rows (approximately)
/*!40000 ALTER TABLE `mailgroupdetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `mailgroupdetails` ENABLE KEYS */;


-- Dumping structure for table weberpcn.mailgroups
DROP TABLE IF EXISTS `mailgroups`;
CREATE TABLE IF NOT EXISTS `mailgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupname` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `groupname` (`groupname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.mailgroups: ~0 rows (approximately)
/*!40000 ALTER TABLE `mailgroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `mailgroups` ENABLE KEYS */;


-- Dumping structure for table weberpcn.manufacturers
DROP TABLE IF EXISTS `manufacturers`;
CREATE TABLE IF NOT EXISTS `manufacturers` (
  `manufacturers_id` int(11) NOT NULL AUTO_INCREMENT,
  `manufacturers_name` varchar(32) NOT NULL,
  `manufacturers_url` varchar(50) NOT NULL DEFAULT '',
  `manufacturers_image` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`manufacturers_id`),
  KEY `manufacturers_name` (`manufacturers_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.manufacturers: ~0 rows (approximately)
/*!40000 ALTER TABLE `manufacturers` DISABLE KEYS */;
/*!40000 ALTER TABLE `manufacturers` ENABLE KEYS */;


-- Dumping structure for table weberpcn.mrpcalendar
DROP TABLE IF EXISTS `mrpcalendar`;
CREATE TABLE IF NOT EXISTS `mrpcalendar` (
  `calendardate` date NOT NULL,
  `daynumber` int(6) NOT NULL,
  `manufacturingflag` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`calendardate`),
  KEY `daynumber` (`daynumber`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.mrpcalendar: 0 rows
/*!40000 ALTER TABLE `mrpcalendar` DISABLE KEYS */;
/*!40000 ALTER TABLE `mrpcalendar` ENABLE KEYS */;


-- Dumping structure for table weberpcn.mrpdemands
DROP TABLE IF EXISTS `mrpdemands`;
CREATE TABLE IF NOT EXISTS `mrpdemands` (
  `demandid` int(11) NOT NULL AUTO_INCREMENT,
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `mrpdemandtype` varchar(6) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '0',
  `duedate` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`demandid`),
  KEY `StockID` (`stockid`),
  KEY `mrpdemands_ibfk_1` (`mrpdemandtype`),
  CONSTRAINT `mrpdemands_ibfk_1` FOREIGN KEY (`mrpdemandtype`) REFERENCES `mrpdemandtypes` (`mrpdemandtype`),
  CONSTRAINT `mrpdemands_ibfk_2` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.mrpdemands: ~0 rows (approximately)
/*!40000 ALTER TABLE `mrpdemands` DISABLE KEYS */;
/*!40000 ALTER TABLE `mrpdemands` ENABLE KEYS */;


-- Dumping structure for table weberpcn.mrpdemandtypes
DROP TABLE IF EXISTS `mrpdemandtypes`;
CREATE TABLE IF NOT EXISTS `mrpdemandtypes` (
  `mrpdemandtype` varchar(6) NOT NULL DEFAULT '',
  `description` char(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`mrpdemandtype`),
  KEY `mrpdemandtype` (`mrpdemandtype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.mrpdemandtypes: ~0 rows (approximately)
/*!40000 ALTER TABLE `mrpdemandtypes` DISABLE KEYS */;
/*!40000 ALTER TABLE `mrpdemandtypes` ENABLE KEYS */;


-- Dumping structure for table weberpcn.mrpplannedorders
DROP TABLE IF EXISTS `mrpplannedorders`;
CREATE TABLE IF NOT EXISTS `mrpplannedorders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `part` char(20) DEFAULT NULL,
  `duedate` date DEFAULT NULL,
  `supplyquantity` double DEFAULT NULL,
  `ordertype` varchar(6) DEFAULT NULL,
  `orderno` int(11) DEFAULT NULL,
  `mrpdate` date DEFAULT NULL,
  `updateflag` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.mrpplannedorders: ~0 rows (approximately)
/*!40000 ALTER TABLE `mrpplannedorders` DISABLE KEYS */;
/*!40000 ALTER TABLE `mrpplannedorders` ENABLE KEYS */;


-- Dumping structure for table weberpcn.offers
DROP TABLE IF EXISTS `offers`;
CREATE TABLE IF NOT EXISTS `offers` (
  `offerid` int(11) NOT NULL AUTO_INCREMENT,
  `tenderid` int(11) NOT NULL DEFAULT '0',
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '0',
  `uom` varchar(15) NOT NULL DEFAULT '',
  `price` double NOT NULL DEFAULT '0',
  `expirydate` date NOT NULL DEFAULT '0000-00-00',
  `currcode` char(3) NOT NULL DEFAULT '',
  PRIMARY KEY (`offerid`),
  KEY `offers_ibfk_1` (`supplierid`),
  KEY `offers_ibfk_2` (`stockid`),
  CONSTRAINT `offers_ibfk_1` FOREIGN KEY (`supplierid`) REFERENCES `suppliers` (`supplierid`),
  CONSTRAINT `offers_ibfk_2` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.offers: ~0 rows (approximately)
/*!40000 ALTER TABLE `offers` DISABLE KEYS */;
/*!40000 ALTER TABLE `offers` ENABLE KEYS */;


-- Dumping structure for table weberpcn.orderdeliverydifferenceslog
DROP TABLE IF EXISTS `orderdeliverydifferenceslog`;
CREATE TABLE IF NOT EXISTS `orderdeliverydifferenceslog` (
  `orderno` int(11) NOT NULL DEFAULT '0',
  `invoiceno` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `quantitydiff` double NOT NULL DEFAULT '0',
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `branch` varchar(10) NOT NULL DEFAULT '',
  `can_or_bo` char(3) NOT NULL DEFAULT 'CAN',
  KEY `StockID` (`stockid`),
  KEY `DebtorNo` (`debtorno`,`branch`),
  KEY `Can_or_BO` (`can_or_bo`),
  KEY `OrderNo` (`orderno`),
  CONSTRAINT `orderdeliverydifferenceslog_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  CONSTRAINT `orderdeliverydifferenceslog_ibfk_2` FOREIGN KEY (`debtorno`, `branch`) REFERENCES `custbranch` (`debtorno`, `branchcode`),
  CONSTRAINT `orderdeliverydifferenceslog_ibfk_3` FOREIGN KEY (`orderno`) REFERENCES `salesorders` (`orderno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.orderdeliverydifferenceslog: ~0 rows (approximately)
/*!40000 ALTER TABLE `orderdeliverydifferenceslog` DISABLE KEYS */;
/*!40000 ALTER TABLE `orderdeliverydifferenceslog` ENABLE KEYS */;


-- Dumping structure for table weberpcn.paymentmethods
DROP TABLE IF EXISTS `paymentmethods`;
CREATE TABLE IF NOT EXISTS `paymentmethods` (
  `paymentid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `paymentname` varchar(15) NOT NULL DEFAULT '',
  `paymenttype` int(11) NOT NULL DEFAULT '1',
  `receipttype` int(11) NOT NULL DEFAULT '1',
  `usepreprintedstationery` tinyint(4) NOT NULL DEFAULT '0',
  `opencashdrawer` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`paymentid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.paymentmethods: ~3 rows (approximately)
/*!40000 ALTER TABLE `paymentmethods` DISABLE KEYS */;
INSERT INTO `paymentmethods` (`paymentid`, `paymentname`, `paymenttype`, `receipttype`, `usepreprintedstationery`, `opencashdrawer`) VALUES
	(1, 'Cheque', 1, 1, 1, 0),
	(2, 'Cash', 1, 1, 0, 0),
	(3, 'Direct Credit', 1, 1, 0, 0);
/*!40000 ALTER TABLE `paymentmethods` ENABLE KEYS */;


-- Dumping structure for table weberpcn.paymentterms
DROP TABLE IF EXISTS `paymentterms`;
CREATE TABLE IF NOT EXISTS `paymentterms` (
  `termsindicator` char(2) NOT NULL DEFAULT '',
  `terms` char(40) NOT NULL DEFAULT '',
  `daysbeforedue` smallint(6) NOT NULL DEFAULT '0',
  `dayinfollowingmonth` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`termsindicator`),
  KEY `DaysBeforeDue` (`daysbeforedue`),
  KEY `DayInFollowingMonth` (`dayinfollowingmonth`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.paymentterms: ~4 rows (approximately)
/*!40000 ALTER TABLE `paymentterms` DISABLE KEYS */;
INSERT INTO `paymentterms` (`termsindicator`, `terms`, `daysbeforedue`, `dayinfollowingmonth`) VALUES
	('20', '次月20日到期', 0, 22),
	('30', '次月底到期', 0, 30),
	('7', '7天内付款', 7, 0),
	('CA', '现金', 1, 0);
/*!40000 ALTER TABLE `paymentterms` ENABLE KEYS */;


-- Dumping structure for table weberpcn.pcashdetails
DROP TABLE IF EXISTS `pcashdetails`;
CREATE TABLE IF NOT EXISTS `pcashdetails` (
  `counterindex` int(20) NOT NULL AUTO_INCREMENT,
  `tabcode` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `codeexpense` varchar(20) NOT NULL,
  `amount` double NOT NULL,
  `authorized` date NOT NULL COMMENT 'date cash assigment was revised and authorized by authorizer from tabs table',
  `posted` tinyint(4) NOT NULL COMMENT 'has (or has not) been posted into gltrans',
  `notes` text NOT NULL,
  `receipt` text COMMENT 'filename or path to scanned receipt or code of receipt to find physical receipt if tax guys or auditors show up',
  PRIMARY KEY (`counterindex`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.pcashdetails: ~0 rows (approximately)
/*!40000 ALTER TABLE `pcashdetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `pcashdetails` ENABLE KEYS */;


-- Dumping structure for table weberpcn.pcexpenses
DROP TABLE IF EXISTS `pcexpenses`;
CREATE TABLE IF NOT EXISTS `pcexpenses` (
  `codeexpense` varchar(20) NOT NULL COMMENT 'code for the group',
  `description` varchar(50) NOT NULL COMMENT 'text description, e.g. meals, train tickets, fuel, etc',
  `glaccount` varchar(20) NOT NULL DEFAULT '0',
  `tag` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeexpense`),
  KEY `glaccount` (`glaccount`),
  CONSTRAINT `pcexpenses_ibfk_1` FOREIGN KEY (`glaccount`) REFERENCES `chartmaster` (`accountcode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.pcexpenses: ~0 rows (approximately)
/*!40000 ALTER TABLE `pcexpenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `pcexpenses` ENABLE KEYS */;


-- Dumping structure for table weberpcn.pctabexpenses
DROP TABLE IF EXISTS `pctabexpenses`;
CREATE TABLE IF NOT EXISTS `pctabexpenses` (
  `typetabcode` varchar(20) NOT NULL,
  `codeexpense` varchar(20) NOT NULL,
  KEY `typetabcode` (`typetabcode`),
  KEY `codeexpense` (`codeexpense`),
  CONSTRAINT `pctabexpenses_ibfk_1` FOREIGN KEY (`typetabcode`) REFERENCES `pctypetabs` (`typetabcode`),
  CONSTRAINT `pctabexpenses_ibfk_2` FOREIGN KEY (`codeexpense`) REFERENCES `pcexpenses` (`codeexpense`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.pctabexpenses: ~0 rows (approximately)
/*!40000 ALTER TABLE `pctabexpenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `pctabexpenses` ENABLE KEYS */;


-- Dumping structure for table weberpcn.pctabs
DROP TABLE IF EXISTS `pctabs`;
CREATE TABLE IF NOT EXISTS `pctabs` (
  `tabcode` varchar(20) NOT NULL,
  `usercode` varchar(20) NOT NULL COMMENT 'code of user employee from www_users',
  `typetabcode` varchar(20) NOT NULL,
  `currency` char(3) NOT NULL,
  `tablimit` double NOT NULL,
  `assigner` varchar(20) NOT NULL COMMENT 'Cash assigner for the tab',
  `authorizer` varchar(20) NOT NULL COMMENT 'code of user from www_users',
  `glaccountassignment` varchar(20) NOT NULL DEFAULT '0',
  `glaccountpcash` varchar(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tabcode`),
  KEY `usercode` (`usercode`),
  KEY `typetabcode` (`typetabcode`),
  KEY `currency` (`currency`),
  KEY `authorizer` (`authorizer`),
  KEY `glaccountassignment` (`glaccountassignment`),
  CONSTRAINT `pctabs_ibfk_1` FOREIGN KEY (`usercode`) REFERENCES `www_users` (`userid`),
  CONSTRAINT `pctabs_ibfk_2` FOREIGN KEY (`typetabcode`) REFERENCES `pctypetabs` (`typetabcode`),
  CONSTRAINT `pctabs_ibfk_3` FOREIGN KEY (`currency`) REFERENCES `currencies` (`currabrev`),
  CONSTRAINT `pctabs_ibfk_4` FOREIGN KEY (`authorizer`) REFERENCES `www_users` (`userid`),
  CONSTRAINT `pctabs_ibfk_5` FOREIGN KEY (`glaccountassignment`) REFERENCES `chartmaster` (`accountcode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.pctabs: ~0 rows (approximately)
/*!40000 ALTER TABLE `pctabs` DISABLE KEYS */;
/*!40000 ALTER TABLE `pctabs` ENABLE KEYS */;


-- Dumping structure for table weberpcn.pctypetabs
DROP TABLE IF EXISTS `pctypetabs`;
CREATE TABLE IF NOT EXISTS `pctypetabs` (
  `typetabcode` varchar(20) NOT NULL COMMENT 'code for the type of petty cash tab',
  `typetabdescription` varchar(50) NOT NULL COMMENT 'text description, e.g. tab for CEO',
  PRIMARY KEY (`typetabcode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.pctypetabs: ~0 rows (approximately)
/*!40000 ALTER TABLE `pctypetabs` DISABLE KEYS */;
/*!40000 ALTER TABLE `pctypetabs` ENABLE KEYS */;


-- Dumping structure for table weberpcn.periods
DROP TABLE IF EXISTS `periods`;
CREATE TABLE IF NOT EXISTS `periods` (
  `periodno` smallint(6) NOT NULL DEFAULT '0',
  `lastdate_in_period` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`periodno`),
  KEY `LastDate_in_Period` (`lastdate_in_period`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.periods: ~2 rows (approximately)
/*!40000 ALTER TABLE `periods` DISABLE KEYS */;
INSERT INTO `periods` (`periodno`, `lastdate_in_period`) VALUES
	(0, '2013-10-31'),
	(1, '2013-11-30');
/*!40000 ALTER TABLE `periods` ENABLE KEYS */;


-- Dumping structure for table weberpcn.pickinglistdetails
DROP TABLE IF EXISTS `pickinglistdetails`;
CREATE TABLE IF NOT EXISTS `pickinglistdetails` (
  `pickinglistno` int(11) NOT NULL DEFAULT '0',
  `pickinglistlineno` int(11) NOT NULL DEFAULT '0',
  `orderlineno` int(11) NOT NULL DEFAULT '0',
  `qtyexpected` double NOT NULL DEFAULT '0',
  `qtypicked` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`pickinglistno`,`pickinglistlineno`),
  CONSTRAINT `pickinglistdetails_ibfk_1` FOREIGN KEY (`pickinglistno`) REFERENCES `pickinglists` (`pickinglistno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.pickinglistdetails: ~0 rows (approximately)
/*!40000 ALTER TABLE `pickinglistdetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `pickinglistdetails` ENABLE KEYS */;


-- Dumping structure for table weberpcn.pickinglists
DROP TABLE IF EXISTS `pickinglists`;
CREATE TABLE IF NOT EXISTS `pickinglists` (
  `pickinglistno` int(11) NOT NULL DEFAULT '0',
  `orderno` int(11) NOT NULL DEFAULT '0',
  `pickinglistdate` date NOT NULL DEFAULT '0000-00-00',
  `dateprinted` date NOT NULL DEFAULT '0000-00-00',
  `deliverynotedate` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`pickinglistno`),
  KEY `pickinglists_ibfk_1` (`orderno`),
  CONSTRAINT `pickinglists_ibfk_1` FOREIGN KEY (`orderno`) REFERENCES `salesorders` (`orderno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.pickinglists: ~0 rows (approximately)
/*!40000 ALTER TABLE `pickinglists` DISABLE KEYS */;
/*!40000 ALTER TABLE `pickinglists` ENABLE KEYS */;


-- Dumping structure for table weberpcn.prices
DROP TABLE IF EXISTS `prices`;
CREATE TABLE IF NOT EXISTS `prices` (
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `typeabbrev` char(2) NOT NULL DEFAULT '',
  `currabrev` char(3) NOT NULL DEFAULT '',
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `price` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `startdate` date NOT NULL DEFAULT '0000-00-00',
  `enddate` date NOT NULL DEFAULT '9999-12-31',
  PRIMARY KEY (`stockid`,`typeabbrev`,`currabrev`,`debtorno`,`branchcode`,`startdate`,`enddate`),
  KEY `CurrAbrev` (`currabrev`),
  KEY `DebtorNo` (`debtorno`),
  KEY `StockID` (`stockid`),
  KEY `TypeAbbrev` (`typeabbrev`),
  CONSTRAINT `prices_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  CONSTRAINT `prices_ibfk_2` FOREIGN KEY (`currabrev`) REFERENCES `currencies` (`currabrev`),
  CONSTRAINT `prices_ibfk_3` FOREIGN KEY (`typeabbrev`) REFERENCES `salestypes` (`typeabbrev`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.prices: ~0 rows (approximately)
/*!40000 ALTER TABLE `prices` DISABLE KEYS */;
/*!40000 ALTER TABLE `prices` ENABLE KEYS */;


-- Dumping structure for table weberpcn.purchdata
DROP TABLE IF EXISTS `purchdata`;
CREATE TABLE IF NOT EXISTS `purchdata` (
  `supplierno` char(10) NOT NULL DEFAULT '',
  `stockid` char(20) NOT NULL DEFAULT '',
  `price` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `suppliersuom` char(50) NOT NULL DEFAULT '',
  `conversionfactor` double NOT NULL DEFAULT '1',
  `supplierdescription` char(50) NOT NULL DEFAULT '',
  `leadtime` smallint(6) NOT NULL DEFAULT '1',
  `preferred` tinyint(4) NOT NULL DEFAULT '0',
  `effectivefrom` date NOT NULL,
  `suppliers_partno` varchar(50) NOT NULL DEFAULT '',
  `minorderqty` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`supplierno`,`stockid`,`effectivefrom`),
  KEY `StockID` (`stockid`),
  KEY `SupplierNo` (`supplierno`),
  KEY `Preferred` (`preferred`),
  CONSTRAINT `purchdata_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  CONSTRAINT `purchdata_ibfk_2` FOREIGN KEY (`supplierno`) REFERENCES `suppliers` (`supplierid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.purchdata: ~0 rows (approximately)
/*!40000 ALTER TABLE `purchdata` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchdata` ENABLE KEYS */;


-- Dumping structure for table weberpcn.purchorderauth
DROP TABLE IF EXISTS `purchorderauth`;
CREATE TABLE IF NOT EXISTS `purchorderauth` (
  `userid` varchar(20) NOT NULL DEFAULT '',
  `currabrev` char(3) NOT NULL DEFAULT '',
  `cancreate` smallint(2) NOT NULL DEFAULT '0',
  `authlevel` double NOT NULL DEFAULT '0',
  `offhold` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`,`currabrev`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.purchorderauth: ~0 rows (approximately)
/*!40000 ALTER TABLE `purchorderauth` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchorderauth` ENABLE KEYS */;


-- Dumping structure for table weberpcn.purchorderdetails
DROP TABLE IF EXISTS `purchorderdetails`;
CREATE TABLE IF NOT EXISTS `purchorderdetails` (
  `podetailitem` int(11) NOT NULL AUTO_INCREMENT,
  `orderno` int(11) NOT NULL DEFAULT '0',
  `itemcode` varchar(20) NOT NULL DEFAULT '',
  `deliverydate` date NOT NULL DEFAULT '0000-00-00',
  `itemdescription` varchar(100) NOT NULL,
  `glcode` varchar(20) NOT NULL DEFAULT '0',
  `qtyinvoiced` double NOT NULL DEFAULT '0',
  `unitprice` double NOT NULL DEFAULT '0',
  `actprice` double NOT NULL DEFAULT '0',
  `stdcostunit` double NOT NULL DEFAULT '0',
  `quantityord` double NOT NULL DEFAULT '0',
  `quantityrecd` double NOT NULL DEFAULT '0',
  `shiptref` int(11) NOT NULL DEFAULT '0',
  `jobref` varchar(20) NOT NULL DEFAULT '',
  `completed` tinyint(4) NOT NULL DEFAULT '0',
  `suppliersunit` varchar(50) DEFAULT NULL,
  `suppliers_partno` varchar(50) NOT NULL DEFAULT '',
  `assetid` int(11) NOT NULL DEFAULT '0',
  `conversionfactor` double NOT NULL DEFAULT '1',
  PRIMARY KEY (`podetailitem`),
  KEY `DeliveryDate` (`deliverydate`),
  KEY `GLCode` (`glcode`),
  KEY `ItemCode` (`itemcode`),
  KEY `JobRef` (`jobref`),
  KEY `OrderNo` (`orderno`),
  KEY `ShiptRef` (`shiptref`),
  KEY `Completed` (`completed`),
  CONSTRAINT `purchorderdetails_ibfk_1` FOREIGN KEY (`orderno`) REFERENCES `purchorders` (`orderno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.purchorderdetails: ~0 rows (approximately)
/*!40000 ALTER TABLE `purchorderdetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchorderdetails` ENABLE KEYS */;


-- Dumping structure for table weberpcn.purchorders
DROP TABLE IF EXISTS `purchorders`;
CREATE TABLE IF NOT EXISTS `purchorders` (
  `orderno` int(11) NOT NULL AUTO_INCREMENT,
  `supplierno` varchar(10) NOT NULL DEFAULT '',
  `comments` longblob,
  `orddate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rate` double NOT NULL DEFAULT '1',
  `dateprinted` datetime DEFAULT NULL,
  `allowprint` tinyint(4) NOT NULL DEFAULT '1',
  `initiator` varchar(20) DEFAULT NULL,
  `requisitionno` varchar(15) DEFAULT NULL,
  `intostocklocation` varchar(5) NOT NULL DEFAULT '',
  `deladd1` varchar(40) NOT NULL DEFAULT '',
  `deladd2` varchar(40) NOT NULL DEFAULT '',
  `deladd3` varchar(40) NOT NULL DEFAULT '',
  `deladd4` varchar(40) NOT NULL DEFAULT '',
  `deladd5` varchar(20) NOT NULL DEFAULT '',
  `deladd6` varchar(15) NOT NULL DEFAULT '',
  `tel` varchar(15) NOT NULL DEFAULT '',
  `suppdeladdress1` varchar(40) NOT NULL DEFAULT '',
  `suppdeladdress2` varchar(40) NOT NULL DEFAULT '',
  `suppdeladdress3` varchar(40) NOT NULL DEFAULT '',
  `suppdeladdress4` varchar(40) NOT NULL DEFAULT '',
  `suppdeladdress5` varchar(20) NOT NULL DEFAULT '',
  `suppdeladdress6` varchar(15) NOT NULL DEFAULT '',
  `suppliercontact` varchar(30) NOT NULL DEFAULT '',
  `supptel` varchar(30) NOT NULL DEFAULT '',
  `contact` varchar(30) NOT NULL DEFAULT '',
  `version` decimal(3,2) NOT NULL DEFAULT '1.00',
  `revised` date NOT NULL DEFAULT '0000-00-00',
  `realorderno` varchar(16) NOT NULL DEFAULT '',
  `deliveryby` varchar(100) NOT NULL DEFAULT '',
  `deliverydate` date NOT NULL DEFAULT '0000-00-00',
  `status` varchar(12) NOT NULL DEFAULT '',
  `stat_comment` text NOT NULL,
  `paymentterms` char(2) NOT NULL DEFAULT '',
  `port` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`orderno`),
  KEY `OrdDate` (`orddate`),
  KEY `SupplierNo` (`supplierno`),
  KEY `IntoStockLocation` (`intostocklocation`),
  KEY `AllowPrintPO` (`allowprint`),
  CONSTRAINT `purchorders_ibfk_1` FOREIGN KEY (`supplierno`) REFERENCES `suppliers` (`supplierid`),
  CONSTRAINT `purchorders_ibfk_2` FOREIGN KEY (`intostocklocation`) REFERENCES `locations` (`loccode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.purchorders: ~0 rows (approximately)
/*!40000 ALTER TABLE `purchorders` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchorders` ENABLE KEYS */;


-- Dumping structure for table weberpcn.recurringsalesorders
DROP TABLE IF EXISTS `recurringsalesorders`;
CREATE TABLE IF NOT EXISTS `recurringsalesorders` (
  `recurrorderno` int(11) NOT NULL AUTO_INCREMENT,
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `customerref` varchar(50) NOT NULL DEFAULT '',
  `buyername` varchar(50) DEFAULT NULL,
  `comments` longblob,
  `orddate` date NOT NULL DEFAULT '0000-00-00',
  `ordertype` char(2) NOT NULL DEFAULT '',
  `shipvia` int(11) NOT NULL DEFAULT '0',
  `deladd1` varchar(40) NOT NULL DEFAULT '',
  `deladd2` varchar(40) NOT NULL DEFAULT '',
  `deladd3` varchar(40) NOT NULL DEFAULT '',
  `deladd4` varchar(40) DEFAULT NULL,
  `deladd5` varchar(20) NOT NULL DEFAULT '',
  `deladd6` varchar(15) NOT NULL DEFAULT '',
  `contactphone` varchar(25) DEFAULT NULL,
  `contactemail` varchar(25) DEFAULT NULL,
  `deliverto` varchar(40) NOT NULL DEFAULT '',
  `freightcost` double NOT NULL DEFAULT '0',
  `fromstkloc` varchar(5) NOT NULL DEFAULT '',
  `lastrecurrence` date NOT NULL DEFAULT '0000-00-00',
  `stopdate` date NOT NULL DEFAULT '0000-00-00',
  `frequency` tinyint(4) NOT NULL DEFAULT '1',
  `autoinvoice` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`recurrorderno`),
  KEY `debtorno` (`debtorno`),
  KEY `orddate` (`orddate`),
  KEY `ordertype` (`ordertype`),
  KEY `locationindex` (`fromstkloc`),
  KEY `branchcode` (`branchcode`,`debtorno`),
  CONSTRAINT `recurringsalesorders_ibfk_1` FOREIGN KEY (`branchcode`, `debtorno`) REFERENCES `custbranch` (`branchcode`, `debtorno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.recurringsalesorders: ~0 rows (approximately)
/*!40000 ALTER TABLE `recurringsalesorders` DISABLE KEYS */;
/*!40000 ALTER TABLE `recurringsalesorders` ENABLE KEYS */;


-- Dumping structure for table weberpcn.recurrsalesorderdetails
DROP TABLE IF EXISTS `recurrsalesorderdetails`;
CREATE TABLE IF NOT EXISTS `recurrsalesorderdetails` (
  `recurrorderno` int(11) NOT NULL DEFAULT '0',
  `stkcode` varchar(20) NOT NULL DEFAULT '',
  `unitprice` double NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `discountpercent` double NOT NULL DEFAULT '0',
  `narrative` text NOT NULL,
  KEY `orderno` (`recurrorderno`),
  KEY `stkcode` (`stkcode`),
  CONSTRAINT `recurrsalesorderdetails_ibfk_1` FOREIGN KEY (`recurrorderno`) REFERENCES `recurringsalesorders` (`recurrorderno`),
  CONSTRAINT `recurrsalesorderdetails_ibfk_2` FOREIGN KEY (`stkcode`) REFERENCES `stockmaster` (`stockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.recurrsalesorderdetails: ~0 rows (approximately)
/*!40000 ALTER TABLE `recurrsalesorderdetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `recurrsalesorderdetails` ENABLE KEYS */;


-- Dumping structure for table weberpcn.reportcolumns
DROP TABLE IF EXISTS `reportcolumns`;
CREATE TABLE IF NOT EXISTS `reportcolumns` (
  `reportid` smallint(6) NOT NULL DEFAULT '0',
  `colno` smallint(6) NOT NULL DEFAULT '0',
  `heading1` varchar(15) NOT NULL DEFAULT '',
  `heading2` varchar(15) DEFAULT NULL,
  `calculation` tinyint(1) NOT NULL DEFAULT '0',
  `periodfrom` smallint(6) DEFAULT NULL,
  `periodto` smallint(6) DEFAULT NULL,
  `datatype` varchar(15) DEFAULT NULL,
  `colnumerator` tinyint(4) DEFAULT NULL,
  `coldenominator` tinyint(4) DEFAULT NULL,
  `calcoperator` char(1) DEFAULT NULL,
  `budgetoractual` tinyint(1) NOT NULL DEFAULT '0',
  `valformat` char(1) NOT NULL DEFAULT 'N',
  `constant` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`reportid`,`colno`),
  CONSTRAINT `reportcolumns_ibfk_1` FOREIGN KEY (`reportid`) REFERENCES `reportheaders` (`reportid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.reportcolumns: ~0 rows (approximately)
/*!40000 ALTER TABLE `reportcolumns` DISABLE KEYS */;
/*!40000 ALTER TABLE `reportcolumns` ENABLE KEYS */;


-- Dumping structure for table weberpcn.reportfields
DROP TABLE IF EXISTS `reportfields`;
CREATE TABLE IF NOT EXISTS `reportfields` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `reportid` int(5) NOT NULL DEFAULT '0',
  `entrytype` varchar(15) NOT NULL DEFAULT '',
  `seqnum` int(3) NOT NULL DEFAULT '0',
  `fieldname` varchar(80) NOT NULL DEFAULT '',
  `displaydesc` varchar(25) NOT NULL DEFAULT '',
  `visible` enum('1','0') NOT NULL DEFAULT '1',
  `columnbreak` enum('1','0') NOT NULL DEFAULT '1',
  `params` text,
  PRIMARY KEY (`id`),
  KEY `reportid` (`reportid`)
) ENGINE=MyISAM AUTO_INCREMENT=1805 DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.reportfields: 0 rows
/*!40000 ALTER TABLE `reportfields` DISABLE KEYS */;
/*!40000 ALTER TABLE `reportfields` ENABLE KEYS */;


-- Dumping structure for table weberpcn.reportheaders
DROP TABLE IF EXISTS `reportheaders`;
CREATE TABLE IF NOT EXISTS `reportheaders` (
  `reportid` smallint(6) NOT NULL AUTO_INCREMENT,
  `reportheading` varchar(80) NOT NULL DEFAULT '',
  `groupbydata1` varchar(15) NOT NULL DEFAULT '',
  `newpageafter1` tinyint(1) NOT NULL DEFAULT '0',
  `lower1` varchar(10) NOT NULL DEFAULT '',
  `upper1` varchar(10) NOT NULL DEFAULT '',
  `groupbydata2` varchar(15) DEFAULT NULL,
  `newpageafter2` tinyint(1) NOT NULL DEFAULT '0',
  `lower2` varchar(10) DEFAULT NULL,
  `upper2` varchar(10) DEFAULT NULL,
  `groupbydata3` varchar(15) DEFAULT NULL,
  `newpageafter3` tinyint(1) NOT NULL DEFAULT '0',
  `lower3` varchar(10) DEFAULT NULL,
  `upper3` varchar(10) DEFAULT NULL,
  `groupbydata4` varchar(15) NOT NULL DEFAULT '',
  `newpageafter4` tinyint(1) NOT NULL DEFAULT '0',
  `upper4` varchar(10) NOT NULL DEFAULT '',
  `lower4` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`reportid`),
  KEY `ReportHeading` (`reportheading`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.reportheaders: ~0 rows (approximately)
/*!40000 ALTER TABLE `reportheaders` DISABLE KEYS */;
/*!40000 ALTER TABLE `reportheaders` ENABLE KEYS */;


-- Dumping structure for table weberpcn.reportlinks
DROP TABLE IF EXISTS `reportlinks`;
CREATE TABLE IF NOT EXISTS `reportlinks` (
  `table1` varchar(25) NOT NULL DEFAULT '',
  `table2` varchar(25) NOT NULL DEFAULT '',
  `equation` varchar(75) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.reportlinks: ~650 rows (approximately)
/*!40000 ALTER TABLE `reportlinks` DISABLE KEYS */;
INSERT INTO `reportlinks` (`table1`, `table2`, `equation`) VALUES
	('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
	('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
	('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
	('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
	('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
	('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
	('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
	('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
	('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
	('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
	('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
	('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
	('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
	('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
	('bom', 'locations', 'bom.loccode=locations.loccode'),
	('locations', 'bom', 'locations.loccode=bom.loccode'),
	('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
	('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
	('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
	('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
	('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
	('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
	('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
	('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
	('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
	('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
	('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
	('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
	('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
	('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
	('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
	('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
	('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
	('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
	('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
	('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
	('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
	('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
	('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
	('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
	('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
	('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
	('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
	('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
	('custbranch', 'areas', 'custbranch.area=areas.areacode'),
	('areas', 'custbranch', 'areas.areacode=custbranch.area'),
	('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
	('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
	('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
	('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
	('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
	('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
	('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
	('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
	('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
	('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
	('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
	('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
	('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
	('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
	('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
	('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
	('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
	('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
	('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
	('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
	('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
	('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
	('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
	('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
	('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
	('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
	('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
	('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
	('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
	('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
	('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
	('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
	('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
	('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
	('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
	('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
	('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
	('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
	('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
	('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
	('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
	('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
	('locstock', 'locations', 'locstock.loccode=locations.loccode'),
	('locations', 'locstock', 'locations.loccode=locstock.loccode'),
	('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
	('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
	('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
	('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
	('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
	('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
	('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
	('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
	('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
	('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
	('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
	('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
	('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
	('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
	('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
	('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
	('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
	('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
	('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
	('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
	('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
	('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
	('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
	('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
	('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
	('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
	('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
	('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
	('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
	('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
	('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
	('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
	('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
	('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
	('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
	('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
	('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
	('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
	('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
	('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
	('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
	('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
	('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
	('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
	('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
	('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
	('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
	('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
	('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
	('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
	('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
	('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
	('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
	('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
	('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
	('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
	('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
	('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
	('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
	('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
	('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
	('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
	('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
	('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
	('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
	('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
	('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
	('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
	('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
	('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
	('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
	('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
	('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
	('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
	('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
	('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
	('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
	('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
	('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
	('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
	('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
	('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
	('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
	('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
	('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
	('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
	('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
	('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
	('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
	('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
	('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
	('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
	('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
	('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
	('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
	('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
	('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
	('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
	('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
	('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
	('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
	('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
	('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
	('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
	('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
	('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
	('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
	('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
	('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
	('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
	('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
	('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
	('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
	('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
	('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
	('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
	('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
	('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
	('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
	('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
	('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
	('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
	('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
	('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
	('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
	('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
	('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
	('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
	('workcentres', 'locations', 'workcentres.location=locations.loccode'),
	('locations', 'workcentres', 'locations.loccode=workcentres.location'),
	('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
	('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
	('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
	('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
	('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
	('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
	('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
	('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
	('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
	('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
	('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
	('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
	('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
	('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
	('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
	('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
	('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
	('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
	('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
	('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
	('bom', 'locations', 'bom.loccode=locations.loccode'),
	('locations', 'bom', 'locations.loccode=bom.loccode'),
	('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
	('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
	('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
	('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
	('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
	('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
	('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
	('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
	('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
	('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
	('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
	('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
	('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
	('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
	('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
	('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
	('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
	('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
	('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
	('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
	('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
	('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
	('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
	('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
	('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
	('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
	('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
	('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
	('custbranch', 'areas', 'custbranch.area=areas.areacode'),
	('areas', 'custbranch', 'areas.areacode=custbranch.area'),
	('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
	('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
	('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
	('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
	('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
	('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
	('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
	('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
	('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
	('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
	('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
	('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
	('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
	('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
	('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
	('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
	('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
	('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
	('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
	('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
	('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
	('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
	('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
	('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
	('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
	('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
	('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
	('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
	('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
	('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
	('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
	('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
	('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
	('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
	('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
	('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
	('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
	('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
	('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
	('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
	('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
	('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
	('locstock', 'locations', 'locstock.loccode=locations.loccode'),
	('locations', 'locstock', 'locations.loccode=locstock.loccode'),
	('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
	('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
	('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
	('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
	('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
	('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
	('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
	('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
	('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
	('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
	('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
	('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
	('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
	('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
	('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
	('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
	('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
	('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
	('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
	('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
	('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
	('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
	('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
	('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
	('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
	('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
	('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
	('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
	('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
	('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
	('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
	('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
	('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
	('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
	('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
	('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
	('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
	('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
	('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
	('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
	('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
	('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
	('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
	('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
	('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
	('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
	('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
	('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
	('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
	('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
	('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
	('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
	('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
	('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
	('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
	('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
	('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
	('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
	('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
	('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
	('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
	('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
	('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
	('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
	('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
	('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
	('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
	('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
	('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
	('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
	('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
	('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
	('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
	('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
	('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
	('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
	('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
	('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
	('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
	('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
	('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
	('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
	('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
	('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
	('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
	('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
	('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
	('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
	('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
	('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
	('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
	('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
	('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
	('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
	('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
	('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
	('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
	('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
	('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
	('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
	('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
	('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
	('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
	('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
	('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
	('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
	('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
	('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
	('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
	('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
	('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
	('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
	('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
	('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
	('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
	('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
	('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
	('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
	('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
	('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
	('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
	('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
	('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
	('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
	('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
	('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
	('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
	('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
	('workcentres', 'locations', 'workcentres.location=locations.loccode'),
	('locations', 'workcentres', 'locations.loccode=workcentres.location'),
	('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
	('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
	('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
	('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
	('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
	('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
	('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
	('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
	('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
	('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
	('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
	('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
	('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
	('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
	('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
	('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
	('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
	('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
	('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
	('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
	('bom', 'locations', 'bom.loccode=locations.loccode'),
	('locations', 'bom', 'locations.loccode=bom.loccode'),
	('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
	('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
	('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
	('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
	('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
	('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
	('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
	('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
	('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
	('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
	('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
	('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
	('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
	('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
	('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
	('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
	('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
	('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
	('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
	('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
	('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
	('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
	('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
	('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
	('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
	('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
	('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
	('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
	('custbranch', 'areas', 'custbranch.area=areas.areacode'),
	('areas', 'custbranch', 'areas.areacode=custbranch.area'),
	('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
	('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
	('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
	('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
	('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
	('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
	('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
	('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
	('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
	('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
	('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
	('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
	('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
	('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
	('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
	('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
	('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
	('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
	('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
	('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
	('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
	('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
	('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
	('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
	('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
	('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
	('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
	('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
	('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
	('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
	('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
	('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
	('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
	('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
	('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
	('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
	('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
	('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
	('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
	('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
	('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
	('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
	('locstock', 'locations', 'locstock.loccode=locations.loccode'),
	('locations', 'locstock', 'locations.loccode=locstock.loccode'),
	('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
	('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
	('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
	('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
	('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
	('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
	('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
	('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
	('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
	('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
	('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
	('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
	('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
	('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
	('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
	('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
	('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
	('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
	('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
	('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
	('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
	('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
	('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
	('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
	('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
	('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
	('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
	('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
	('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
	('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
	('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
	('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
	('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
	('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
	('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
	('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
	('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
	('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
	('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
	('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
	('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
	('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
	('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
	('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
	('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
	('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
	('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
	('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
	('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
	('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
	('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
	('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
	('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
	('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
	('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
	('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
	('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
	('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
	('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
	('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
	('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
	('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
	('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
	('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
	('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
	('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
	('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
	('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
	('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
	('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
	('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
	('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
	('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
	('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
	('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
	('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
	('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
	('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
	('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
	('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
	('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
	('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
	('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
	('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
	('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
	('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
	('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
	('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
	('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
	('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
	('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
	('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
	('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
	('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
	('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
	('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
	('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
	('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
	('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
	('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
	('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
	('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
	('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
	('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
	('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
	('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
	('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
	('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
	('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
	('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
	('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
	('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
	('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
	('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
	('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
	('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
	('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
	('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
	('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
	('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
	('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
	('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
	('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
	('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
	('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
	('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
	('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
	('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
	('workcentres', 'locations', 'workcentres.location=locations.loccode'),
	('locations', 'workcentres', 'locations.loccode=workcentres.location'),
	('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
	('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
	('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
	('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
	('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
	('locations', 'www_users', 'locations.loccode=www_users.defaultlocation');
/*!40000 ALTER TABLE `reportlinks` ENABLE KEYS */;


-- Dumping structure for table weberpcn.reports
DROP TABLE IF EXISTS `reports`;
CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `reportname` varchar(30) NOT NULL DEFAULT '',
  `reporttype` char(3) NOT NULL DEFAULT 'rpt',
  `groupname` varchar(9) NOT NULL DEFAULT 'misc',
  `defaultreport` enum('1','0') NOT NULL DEFAULT '0',
  `papersize` varchar(15) NOT NULL DEFAULT 'A4,210,297',
  `paperorientation` enum('P','L') NOT NULL DEFAULT 'P',
  `margintop` int(3) NOT NULL DEFAULT '10',
  `marginbottom` int(3) NOT NULL DEFAULT '10',
  `marginleft` int(3) NOT NULL DEFAULT '10',
  `marginright` int(3) NOT NULL DEFAULT '10',
  `coynamefont` varchar(20) NOT NULL DEFAULT 'Helvetica',
  `coynamefontsize` int(3) NOT NULL DEFAULT '12',
  `coynamefontcolor` varchar(11) NOT NULL DEFAULT '0,0,0',
  `coynamealign` enum('L','C','R') NOT NULL DEFAULT 'C',
  `coynameshow` enum('1','0') NOT NULL DEFAULT '1',
  `title1desc` varchar(50) NOT NULL DEFAULT '%reportname%',
  `title1font` varchar(20) NOT NULL DEFAULT 'Helvetica',
  `title1fontsize` int(3) NOT NULL DEFAULT '10',
  `title1fontcolor` varchar(11) NOT NULL DEFAULT '0,0,0',
  `title1fontalign` enum('L','C','R') NOT NULL DEFAULT 'C',
  `title1show` enum('1','0') NOT NULL DEFAULT '1',
  `title2desc` varchar(50) NOT NULL DEFAULT 'Report Generated %date%',
  `title2font` varchar(20) NOT NULL DEFAULT 'Helvetica',
  `title2fontsize` int(3) NOT NULL DEFAULT '10',
  `title2fontcolor` varchar(11) NOT NULL DEFAULT '0,0,0',
  `title2fontalign` enum('L','C','R') NOT NULL DEFAULT 'C',
  `title2show` enum('1','0') NOT NULL DEFAULT '1',
  `filterfont` varchar(10) NOT NULL DEFAULT 'Helvetica',
  `filterfontsize` int(3) NOT NULL DEFAULT '8',
  `filterfontcolor` varchar(11) NOT NULL DEFAULT '0,0,0',
  `filterfontalign` enum('L','C','R') NOT NULL DEFAULT 'L',
  `datafont` varchar(10) NOT NULL DEFAULT 'Helvetica',
  `datafontsize` int(3) NOT NULL DEFAULT '10',
  `datafontcolor` varchar(10) NOT NULL DEFAULT 'black',
  `datafontalign` enum('L','C','R') NOT NULL DEFAULT 'L',
  `totalsfont` varchar(10) NOT NULL DEFAULT 'Helvetica',
  `totalsfontsize` int(3) NOT NULL DEFAULT '10',
  `totalsfontcolor` varchar(11) NOT NULL DEFAULT '0,0,0',
  `totalsfontalign` enum('L','C','R') NOT NULL DEFAULT 'L',
  `col1width` int(3) NOT NULL DEFAULT '25',
  `col2width` int(3) NOT NULL DEFAULT '25',
  `col3width` int(3) NOT NULL DEFAULT '25',
  `col4width` int(3) NOT NULL DEFAULT '25',
  `col5width` int(3) NOT NULL DEFAULT '25',
  `col6width` int(3) NOT NULL DEFAULT '25',
  `col7width` int(3) NOT NULL DEFAULT '25',
  `col8width` int(3) NOT NULL DEFAULT '25',
  `col9width` int(3) NOT NULL DEFAULT '25',
  `col10width` int(3) NOT NULL DEFAULT '25',
  `col11width` int(3) NOT NULL DEFAULT '25',
  `col12width` int(3) NOT NULL DEFAULT '25',
  `col13width` int(3) NOT NULL DEFAULT '25',
  `col14width` int(3) NOT NULL DEFAULT '25',
  `col15width` int(3) NOT NULL DEFAULT '25',
  `col16width` int(3) NOT NULL DEFAULT '25',
  `col17width` int(3) NOT NULL DEFAULT '25',
  `col18width` int(3) NOT NULL DEFAULT '25',
  `col19width` int(3) NOT NULL DEFAULT '25',
  `col20width` int(3) NOT NULL DEFAULT '25',
  `table1` varchar(25) NOT NULL DEFAULT '',
  `table2` varchar(25) DEFAULT NULL,
  `table2criteria` varchar(75) DEFAULT NULL,
  `table3` varchar(25) DEFAULT NULL,
  `table3criteria` varchar(75) DEFAULT NULL,
  `table4` varchar(25) DEFAULT NULL,
  `table4criteria` varchar(75) DEFAULT NULL,
  `table5` varchar(25) DEFAULT NULL,
  `table5criteria` varchar(75) DEFAULT NULL,
  `table6` varchar(25) DEFAULT NULL,
  `table6criteria` varchar(75) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`reportname`,`groupname`)
) ENGINE=MyISAM AUTO_INCREMENT=136 DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.reports: 0 rows
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;


-- Dumping structure for table weberpcn.salesanalysis
DROP TABLE IF EXISTS `salesanalysis`;
CREATE TABLE IF NOT EXISTS `salesanalysis` (
  `typeabbrev` char(2) NOT NULL DEFAULT '',
  `periodno` smallint(6) NOT NULL DEFAULT '0',
  `amt` double NOT NULL DEFAULT '0',
  `cost` double NOT NULL DEFAULT '0',
  `cust` varchar(10) NOT NULL DEFAULT '',
  `custbranch` varchar(10) NOT NULL DEFAULT '',
  `qty` double NOT NULL DEFAULT '0',
  `disc` double NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `area` varchar(3) NOT NULL,
  `budgetoractual` tinyint(1) NOT NULL DEFAULT '0',
  `salesperson` char(3) NOT NULL DEFAULT '',
  `stkcategory` varchar(6) NOT NULL DEFAULT '',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `CustBranch` (`custbranch`),
  KEY `Cust` (`cust`),
  KEY `PeriodNo` (`periodno`),
  KEY `StkCategory` (`stkcategory`),
  KEY `StockID` (`stockid`),
  KEY `TypeAbbrev` (`typeabbrev`),
  KEY `Area` (`area`),
  KEY `BudgetOrActual` (`budgetoractual`),
  KEY `Salesperson` (`salesperson`),
  CONSTRAINT `salesanalysis_ibfk_1` FOREIGN KEY (`periodno`) REFERENCES `periods` (`periodno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.salesanalysis: ~0 rows (approximately)
/*!40000 ALTER TABLE `salesanalysis` DISABLE KEYS */;
/*!40000 ALTER TABLE `salesanalysis` ENABLE KEYS */;


-- Dumping structure for table weberpcn.salescat
DROP TABLE IF EXISTS `salescat`;
CREATE TABLE IF NOT EXISTS `salescat` (
  `salescatid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `parentcatid` tinyint(4) DEFAULT NULL,
  `salescatname` varchar(50) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1' COMMENT '1 if active 0 if inactive',
  PRIMARY KEY (`salescatid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.salescat: ~0 rows (approximately)
/*!40000 ALTER TABLE `salescat` DISABLE KEYS */;
/*!40000 ALTER TABLE `salescat` ENABLE KEYS */;


-- Dumping structure for table weberpcn.salescatprod
DROP TABLE IF EXISTS `salescatprod`;
CREATE TABLE IF NOT EXISTS `salescatprod` (
  `salescatid` tinyint(4) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `manufacturers_id` int(11) NOT NULL,
  `featured` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`salescatid`,`stockid`),
  KEY `salescatid` (`salescatid`),
  KEY `stockid` (`stockid`),
  KEY `manufacturer_id` (`manufacturers_id`),
  KEY `manufacturers_id` (`manufacturers_id`),
  KEY `manufacturers_id_2` (`manufacturers_id`),
  CONSTRAINT `salescatprod_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  CONSTRAINT `salescatprod_ibfk_2` FOREIGN KEY (`salescatid`) REFERENCES `salescat` (`salescatid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.salescatprod: ~0 rows (approximately)
/*!40000 ALTER TABLE `salescatprod` DISABLE KEYS */;
/*!40000 ALTER TABLE `salescatprod` ENABLE KEYS */;


-- Dumping structure for table weberpcn.salescattranslations
DROP TABLE IF EXISTS `salescattranslations`;
CREATE TABLE IF NOT EXISTS `salescattranslations` (
  `salescatid` tinyint(4) NOT NULL DEFAULT '0',
  `language_id` varchar(10) NOT NULL DEFAULT 'en_GB.utf8',
  `salescattranslation` varchar(40) NOT NULL,
  PRIMARY KEY (`salescatid`,`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.salescattranslations: ~0 rows (approximately)
/*!40000 ALTER TABLE `salescattranslations` DISABLE KEYS */;
/*!40000 ALTER TABLE `salescattranslations` ENABLE KEYS */;


-- Dumping structure for table weberpcn.salesglpostings
DROP TABLE IF EXISTS `salesglpostings`;
CREATE TABLE IF NOT EXISTS `salesglpostings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area` varchar(3) NOT NULL,
  `stkcat` varchar(6) NOT NULL DEFAULT '',
  `discountglcode` varchar(20) NOT NULL DEFAULT '0',
  `salesglcode` varchar(20) NOT NULL DEFAULT '0',
  `salestype` char(2) NOT NULL DEFAULT 'AN',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Area_StkCat` (`area`,`stkcat`,`salestype`),
  KEY `Area` (`area`),
  KEY `StkCat` (`stkcat`),
  KEY `SalesType` (`salestype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.salesglpostings: ~0 rows (approximately)
/*!40000 ALTER TABLE `salesglpostings` DISABLE KEYS */;
/*!40000 ALTER TABLE `salesglpostings` ENABLE KEYS */;


-- Dumping structure for table weberpcn.salesman
DROP TABLE IF EXISTS `salesman`;
CREATE TABLE IF NOT EXISTS `salesman` (
  `salesmancode` varchar(4) NOT NULL DEFAULT '',
  `salesmanname` char(30) NOT NULL DEFAULT '',
  `smantel` char(20) NOT NULL DEFAULT '',
  `smanfax` char(20) NOT NULL DEFAULT '',
  `commissionrate1` double NOT NULL DEFAULT '0',
  `breakpoint` decimal(10,0) NOT NULL DEFAULT '0',
  `commissionrate2` double NOT NULL DEFAULT '0',
  `current` tinyint(4) NOT NULL COMMENT 'Salesman current (1) or not (0)',
  PRIMARY KEY (`salesmancode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.salesman: ~0 rows (approximately)
/*!40000 ALTER TABLE `salesman` DISABLE KEYS */;
/*!40000 ALTER TABLE `salesman` ENABLE KEYS */;


-- Dumping structure for table weberpcn.salesorderdetails
DROP TABLE IF EXISTS `salesorderdetails`;
CREATE TABLE IF NOT EXISTS `salesorderdetails` (
  `orderlineno` int(11) NOT NULL DEFAULT '0',
  `orderno` int(11) NOT NULL DEFAULT '0',
  `stkcode` varchar(20) NOT NULL DEFAULT '',
  `qtyinvoiced` double NOT NULL DEFAULT '0',
  `unitprice` double NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `estimate` tinyint(4) NOT NULL DEFAULT '0',
  `discountpercent` double NOT NULL DEFAULT '0',
  `actualdispatchdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `narrative` text,
  `itemdue` date DEFAULT NULL COMMENT 'Due date for line item.  Some customers require \r\nacknowledgements with due dates by line item',
  `poline` varchar(10) DEFAULT NULL COMMENT 'Some Customers require acknowledgements with a PO line number for each sales line',
  PRIMARY KEY (`orderlineno`,`orderno`),
  KEY `OrderNo` (`orderno`),
  KEY `StkCode` (`stkcode`),
  KEY `Completed` (`completed`),
  CONSTRAINT `salesorderdetails_ibfk_1` FOREIGN KEY (`orderno`) REFERENCES `salesorders` (`orderno`),
  CONSTRAINT `salesorderdetails_ibfk_2` FOREIGN KEY (`stkcode`) REFERENCES `stockmaster` (`stockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.salesorderdetails: ~0 rows (approximately)
/*!40000 ALTER TABLE `salesorderdetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `salesorderdetails` ENABLE KEYS */;


-- Dumping structure for table weberpcn.salesorders
DROP TABLE IF EXISTS `salesorders`;
CREATE TABLE IF NOT EXISTS `salesorders` (
  `orderno` int(11) NOT NULL,
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `customerref` varchar(50) NOT NULL DEFAULT '',
  `buyername` varchar(50) DEFAULT NULL,
  `comments` longblob,
  `orddate` date NOT NULL DEFAULT '0000-00-00',
  `ordertype` char(2) NOT NULL DEFAULT '',
  `shipvia` int(11) NOT NULL DEFAULT '0',
  `deladd1` varchar(40) NOT NULL DEFAULT '',
  `deladd2` varchar(40) NOT NULL DEFAULT '',
  `deladd3` varchar(40) NOT NULL DEFAULT '',
  `deladd4` varchar(40) DEFAULT NULL,
  `deladd5` varchar(20) NOT NULL DEFAULT '',
  `deladd6` varchar(15) NOT NULL DEFAULT '',
  `contactphone` varchar(25) DEFAULT NULL,
  `contactemail` varchar(40) DEFAULT NULL,
  `deliverto` varchar(40) NOT NULL DEFAULT '',
  `deliverblind` tinyint(1) DEFAULT '1',
  `freightcost` double NOT NULL DEFAULT '0',
  `fromstkloc` varchar(5) NOT NULL DEFAULT '',
  `deliverydate` date NOT NULL DEFAULT '0000-00-00',
  `confirmeddate` date NOT NULL DEFAULT '0000-00-00',
  `printedpackingslip` tinyint(4) NOT NULL DEFAULT '0',
  `datepackingslipprinted` date NOT NULL DEFAULT '0000-00-00',
  `quotation` tinyint(4) NOT NULL DEFAULT '0',
  `quotedate` date NOT NULL DEFAULT '0000-00-00',
  `poplaced` tinyint(4) NOT NULL DEFAULT '0',
  `salesperson` varchar(4) NOT NULL,
  PRIMARY KEY (`orderno`),
  KEY `DebtorNo` (`debtorno`),
  KEY `OrdDate` (`orddate`),
  KEY `OrderType` (`ordertype`),
  KEY `LocationIndex` (`fromstkloc`),
  KEY `BranchCode` (`branchcode`,`debtorno`),
  KEY `ShipVia` (`shipvia`),
  KEY `quotation` (`quotation`),
  KEY `poplaced` (`poplaced`),
  KEY `salesperson` (`salesperson`),
  CONSTRAINT `salesorders_ibfk_1` FOREIGN KEY (`branchcode`, `debtorno`) REFERENCES `custbranch` (`branchcode`, `debtorno`),
  CONSTRAINT `salesorders_ibfk_2` FOREIGN KEY (`shipvia`) REFERENCES `shippers` (`shipper_id`),
  CONSTRAINT `salesorders_ibfk_3` FOREIGN KEY (`fromstkloc`) REFERENCES `locations` (`loccode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.salesorders: ~0 rows (approximately)
/*!40000 ALTER TABLE `salesorders` DISABLE KEYS */;
/*!40000 ALTER TABLE `salesorders` ENABLE KEYS */;


-- Dumping structure for table weberpcn.salestypes
DROP TABLE IF EXISTS `salestypes`;
CREATE TABLE IF NOT EXISTS `salestypes` (
  `typeabbrev` char(2) NOT NULL DEFAULT '',
  `sales_type` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`typeabbrev`),
  KEY `Sales_Type` (`sales_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.salestypes: ~0 rows (approximately)
/*!40000 ALTER TABLE `salestypes` DISABLE KEYS */;
/*!40000 ALTER TABLE `salestypes` ENABLE KEYS */;


-- Dumping structure for table weberpcn.scripts
DROP TABLE IF EXISTS `scripts`;
CREATE TABLE IF NOT EXISTS `scripts` (
  `script` varchar(78) NOT NULL DEFAULT '',
  `pagesecurity` int(11) NOT NULL DEFAULT '1',
  `description` text NOT NULL,
  PRIMARY KEY (`script`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.scripts: ~441 rows (approximately)
/*!40000 ALTER TABLE `scripts` DISABLE KEYS */;
INSERT INTO `scripts` (`script`, `pagesecurity`, `description`) VALUES
	('AccountGroups.php', 10, 'Defines the groupings of general ledger accounts'),
	('AccountSections.php', 10, 'Defines the sections in the general ledger reports'),
	('AddCustomerContacts.php', 3, 'Adds customer contacts'),
	('AddCustomerNotes.php', 3, 'Adds notes about customers'),
	('AddCustomerTypeNotes.php', 3, ''),
	('AgedDebtors.php', 2, 'Lists customer account balances in detail or summary in selected currency'),
	('AgedSuppliers.php', 2, 'Lists supplier account balances in detail or summary in selected currency'),
	('Areas.php', 3, 'Defines the sales areas - all customers must belong to a sales area for the purposes of sales analysis'),
	('AuditTrail.php', 15, 'Shows the activity with SQL statements and who performed the changes'),
	('BankAccounts.php', 10, 'Defines the general ledger code for bank accounts and specifies that bank transactions be created for these accounts for the purposes of reconciliation'),
	('BankMatching.php', 7, 'Allows payments and receipts to be matched off against bank statements'),
	('BankReconciliation.php', 7, 'Displays the bank reconciliation for a selected bank account'),
	('BOMExtendedQty.php', 2, 'Shows the component requirements to make an item'),
	('BOMIndented.php', 2, 'Shows the bill of material indented for each level'),
	('BOMIndentedReverse.php', 2, ''),
	('BOMInquiry.php', 2, 'Displays the bill of material with cost information'),
	('BOMListing.php', 2, 'Lists the bills of material for a selected range of items'),
	('BOMs.php', 9, 'Administers the bills of material for a selected item'),
	('COGSGLPostings.php', 10, 'Defines the general ledger account to be used for cost of sales entries'),
	('CompanyPreferences.php', 10, 'Defines the settings applicable for the company, including name, address, tax authority reference, whether GL integration used etc.'),
	('ConfirmDispatchControlled_Invoice.php', 2, 'Specifies the batch references/serial numbers of items dispatched that are being invoiced'),
	('ConfirmDispatch_Invoice.php', 2, 'Creates sales invoices from entered sales orders based on the quantities dispatched that can be modified'),
	('ContractBOM.php', 6, 'Creates the item requirements from stock for a contract as part of the contract cost build up'),
	('ContractCosting.php', 6, 'Shows a contract cost - the components and other non-stock costs issued to the contract'),
	('ContractOtherReqts.php', 4, 'Creates the other requirements for a contract cost build up'),
	('Contracts.php', 6, 'Creates or modifies a customer contract costing'),
	('CopyBOM.php', 9, 'Allows a bill of material to be copied between items'),
	('CounterReturns.php', 5, 'Allows credits and refunds from the default Counter Sale account for an inventory location'),
	('CounterSales.php', 1, 'Allows sales to be entered against a cash sale customer account defined in the users location record'),
	('CreditItemsControlled.php', 3, 'Specifies the batch references/serial numbers of items being credited back into stock'),
	('CreditStatus.php', 3, 'Defines the credit status records. Each customer account is given a credit status from this table. Some credit status records can prohibit invoicing and new orders being entered.'),
	('Credit_Invoice.php', 3, 'Creates a credit note based on the details of an existing invoice'),
	('Currencies.php', 9, 'Defines the currencies available. Each customer and supplier must be defined as transacting in one of the currencies defined here.'),
	('CustEDISetup.php', 11, 'Allows the set up the customer specified EDI parameters for server, email or ftp.'),
	('CustLoginSetup.php', 15, ''),
	('CustomerAllocations.php', 3, 'Allows customer receipts and credit notes to be allocated to sales invoices'),
	('CustomerBranches.php', 3, 'Defines the details of customer branches such as delivery address and contact details - also sales area, representative etc'),
	('CustomerInquiry.php', 1, 'Shows the customers account transactions with balances outstanding, links available to drill down to invoice/credit note or email invoices/credit notes'),
	('CustomerPurchases.php', 5, 'Shows the purchases a customer has made.'),
	('CustomerReceipt.php', 3, 'Entry of both customer receipts against accounts receivable and also general ledger or nominal receipts'),
	('Customers.php', 3, 'Defines the setup of a customer account, including payment terms, billing address, credit status, currency etc'),
	('CustomerTransInquiry.php', 2, 'Lists in html the sequence of customer transactions, invoices, credit notes or receipts by a user entered date range'),
	('CustomerTypes.php', 15, ''),
	('CustWhereAlloc.php', 2, 'Shows to which invoices a receipt was allocated to'),
	('DailyBankTransactions.php', 8, ''),
	('DailySalesInquiry.php', 2, 'Shows the daily sales with GP in a calendar format'),
	('DebtorsAtPeriodEnd.php', 2, 'Shows the debtors control account as at a previous period end - based on system calendar monthly periods'),
	('DeliveryDetails.php', 1, 'Used during order entry to allow the entry of delivery addresses other than the defaulted branch delivery address and information about carrier/shipping method etc'),
	('Departments.php', 1, 'Create business departments'),
	('DiscountCategories.php', 11, 'Defines the items belonging to a discount category. Discount Categories are used to allow discounts based on quantities across a range of producs'),
	('DiscountMatrix.php', 11, 'Defines the rates of discount applicable to discount categories and the customer groupings to which the rates are to apply'),
	('EDIMessageFormat.php', 10, 'Specifies the EDI message format used by a customer - administrator use only.'),
	('EDIProcessOrders.php', 11, 'Processes incoming EDI orders into sales orders'),
	('EDISendInvoices.php', 15, 'Processes invoiced EDI customer invoices into EDI messages and sends using the customers preferred method either ftp or email attachments.'),
	('EmailConfirmation.php', 2, ''),
	('EmailCustTrans.php', 2, 'Emails selected invoice or credit to the customer'),
	('ExchangeRateTrend.php', 2, 'Shows the trend in exchange rates as retrieved from ECB'),
	('Factors.php', 5, 'Defines supplier factor companies'),
	('FixedAssetCategories.php', 11, 'Defines the various categories of fixed assets'),
	('FixedAssetDepreciation.php', 10, 'Calculates and creates GL transactions to post depreciation for a period'),
	('FixedAssetItems.php', 11, 'Allows fixed assets to be defined'),
	('FixedAssetList.php', 11, ''),
	('FixedAssetLocations.php', 11, 'Allows the locations of fixed assets to be defined'),
	('FixedAssetRegister.php', 11, 'Produces a csv, html or pdf report of the fixed assets over a period showing period depreciation, additions and disposals'),
	('FixedAssetTransfer.php', 11, 'Allows the fixed asset locations to be changed in bulk'),
	('FormDesigner.php', 14, ''),
	('FormMaker.php', 1, 'Allows running user defined Forms'),
	('FreightCosts.php', 11, 'Defines the setup of the freight cost using different shipping methods to different destinations. The system can use this information to calculate applicable freight if the items are defined with the correct kgs and cubic volume'),
	('FTP_RadioBeacon.php', 2, 'FTPs sales orders for dispatch to a radio beacon software enabled warehouse dispatching facility'),
	('geocode.php', 3, ''),
	('GeocodeSetup.php', 3, ''),
	('geocode_genxml_customers.php', 3, ''),
	('geocode_genxml_suppliers.php', 3, ''),
	('geo_displaymap_customers.php', 3, ''),
	('geo_displaymap_suppliers.php', 3, ''),
	('GetStockImage.php', 1, ''),
	('GLAccountCSV.php', 8, 'Produces a CSV of the GL transactions for a particular range of periods and GL account'),
	('GLAccountInquiry.php', 8, 'Shows the general ledger transactions for a specified account over a specified range of periods'),
	('GLAccountReport.php', 8, 'Produces a report of the GL transactions for a particular account'),
	('GLAccounts.php', 10, 'Defines the general ledger accounts'),
	('GLBalanceSheet.php', 8, 'Shows the balance sheet for the company as at a specified date'),
	('GLBudgets.php', 10, 'Defines GL Budgets'),
	('GLCodesInquiry.php', 8, 'Shows the list of general ledger codes defined with account names and groupings'),
	('GLJournal.php', 10, 'Entry of general ledger journals, periods are calculated based on the date entered here'),
	('GLJournalInquiry.php', 15, 'General Ledger Journal Inquiry'),
	('GLProfit_Loss.php', 8, 'Shows the profit and loss of the company for the range of periods entered'),
	('GLTagProfit_Loss.php', 8, ''),
	('GLTags.php', 10, 'Allows GL tags to be defined'),
	('GLTransInquiry.php', 8, 'Shows the general ledger journal created for the sub ledger transaction specified'),
	('GLTrialBalance.php', 8, 'Shows the trial balance for the month and the for the period selected together with the budgeted trial balances'),
	('GLTrialBalance_csv.php', 8, 'Produces a CSV of the Trial Balance for a particular period'),
	('GoodsReceived.php', 11, 'Entry of items received against purchase orders'),
	('GoodsReceivedControlled.php', 11, 'Entry of the serial numbers or batch references for controlled items received against purchase orders'),
	('GoodsReceivedNotInvoiced.php', 2, 'Shows the list of goods received but not yet invoiced, both in supplier currency and home currency. Total in home curency should match the GL Account for Goods received not invoiced. Any discrepancy is due to multicurrency errors.'),
	('ImportBankTrans.php', 11, 'Imports bank transactions'),
	('ImportBankTransAnalysis.php', 11, 'Allows analysis of bank transactions being imported'),
	('index.php', 1, 'The main menu from where all functions available to the user are accessed by clicking on the links'),
	('InternalStockCategoriesByRole.php', 15, 'Maintains the stock categories to be used as internal for any user security role'),
	('InternalStockRequest.php', 1, 'Create an internal stock request'),
	('InternalStockRequestAuthorisation.php', 1, 'Authorise internal stock requests'),
	('InternalStockRequestFulfill.php', 1, 'Fulfill an internal stock request'),
	('InventoryPlanning.php', 2, 'Creates a pdf report showing the last 4 months use of items including as a component of assemblies together with stock quantity on hand, current demand for the item and current quantity on sales order.'),
	('InventoryPlanningPrefSupplier.php', 2, 'Produces a report showing the inventory to be ordered by supplier'),
	('InventoryQuantities.php', 2, ''),
	('InventoryValuation.php', 2, 'Creates a pdf report showing the value of stock at standard cost for a range of product categories selected'),
	('Labels.php', 15, 'Produces item pricing labels in a pdf from a range of selected criteria'),
	('Locations.php', 11, 'Defines the inventory stocking locations or warehouses'),
	('Logout.php', 1, 'Shows when the user logs out of webERP'),
	('MailingGroupMaintenance.php', 15, 'Mainting mailing lists for items to mail'),
	('MailInventoryValuation.php', 1, 'Meant to be run as a scheduled process to email the stock valuation off to a specified person. Creates the same stock valuation report as InventoryValuation.php'),
	('MailSalesReport_csv.php', 15, 'Mailing the sales report'),
	('MaintenanceReminders.php', 1, 'Sends email reminders for scheduled asset maintenance tasks'),
	('MaintenanceTasks.php', 1, 'Allows set up and edit of scheduled maintenance tasks'),
	('MaintenanceUserSchedule.php', 1, 'List users or managers scheduled maintenance tasks and allow to be flagged as completed'),
	('ManualContents.php', 1, ''),
	('Manufacturers.php', 15, 'Maintain brands of sales products'),
	('MaterialsNotUsed.php', 4, 'Lists the items from Raw Material Categories not used in any BOM (thus, not used at all)'),
	('MenuAccess.php', 15, ''),
	('MRP.php', 9, ''),
	('MRPCalendar.php', 9, ''),
	('MRPCreateDemands.php', 9, ''),
	('MRPDemands.php', 9, ''),
	('MRPDemandTypes.php', 9, ''),
	('MRPPlannedPurchaseOrders.php', 2, ''),
	('MRPPlannedWorkOrders.php', 2, ''),
	('MRPReport.php', 2, ''),
	('MRPReschedules.php', 2, ''),
	('MRPShortages.php', 2, ''),
	('NoSalesItems.php', 2, 'Shows the No Selling (worst) items'),
	('OffersReceived.php', 4, ''),
	('OrderDetails.php', 1, 'Shows the detail of a sales order'),
	('OrderEntryDiscountPricing', 13, 'Not a script but an authority level marker - required if the user is allowed to enter discounts and special pricing against a customer order'),
	('OutstandingGRNs.php', 2, 'Creates a pdf showing all GRNs for which there has been no purchase invoice matched off against.'),
	('PageSecurity.php', 15, ''),
	('PaymentAllocations.php', 5, ''),
	('PaymentMethods.php', 15, ''),
	('Payments.php', 5, 'Entry of bank account payments either against an AP account or a general ledger payment - if the AP-GL link in company preferences is set'),
	('PaymentTerms.php', 10, 'Defines the payment terms records, these can be expressed as either a number of days credit or a day in the following month. All customers and suppliers must have a corresponding payment term recorded against their account'),
	('PcAssignCashToTab.php', 6, ''),
	('PcAuthorizeExpenses.php', 6, ''),
	('PcClaimExpensesFromTab.php', 6, ''),
	('PcExpenses.php', 15, ''),
	('PcExpensesTypeTab.php', 15, ''),
	('PcReportTab.php', 6, ''),
	('PcTabs.php', 15, ''),
	('PcTypeTabs.php', 15, ''),
	('PDFBankingSummary.php', 3, 'Creates a pdf showing the amounts entered as receipts on a specified date together with references for the purposes of banking'),
	('PDFChequeListing.php', 3, 'Creates a pdf showing all payments that have been made from a specified bank account over a specified period. This can be emailed to an email account defined in config.php - ie a financial controller'),
	('PDFCustomerList.php', 2, 'Creates a report of the customer and branch information held. This report has options to print only customer branches in a specified sales area and sales person. Additional option allows to list only those customers with activity either under or over a specified amount, since a specified date.'),
	('PDFCustTransListing.php', 3, ''),
	('PDFDeliveryDifferences.php', 3, 'Creates a pdf report listing the delivery differences from what the customer requested as recorded in the order entry. The report calculates a percentage of order fill based on the number of orders filled in full on time'),
	('PDFDIFOT.php', 3, 'Produces a pdf showing the delivery in full on time performance'),
	('PDFGLJournal.php', 15, 'General Ledger Journal Print'),
	('PDFGrn.php', 2, 'Produces a GRN report on the receipt of stock'),
	('PDFLowGP.php', 2, 'Creates a pdf report showing the low gross profit sales made in the selected date range. The percentage of gp deemed acceptable can also be entered'),
	('PDFOrdersInvoiced.php', 3, 'Produces a pdf of orders invoiced based on selected criteria'),
	('PDFOrderStatus.php', 3, 'Reports on sales order status by date range, by stock location and stock category - producing a pdf showing each line items and any quantites delivered'),
	('PDFPeriodStockTransListing.php', 3, 'Allows stock transactions of a specific transaction type to be listed over a single day or period range'),
	('PDFPickingList.php', 2, ''),
	('PDFPriceList.php', 2, 'Creates a pdf of the price list applicable to a given sales type and customer. Also allows the listing of prices specific to a customer'),
	('PDFPrintLabel.php', 10, ''),
	('PDFQuotation.php', 2, ''),
	('PDFQuotationPortrait.php', 2, 'Portrait quotation'),
	('PDFReceipt.php', 2, ''),
	('PDFRemittanceAdvice.php', 2, ''),
	('PDFSellThroughSupportClaim.php', 9, 'Reports the sell through support claims to be made against all suppliers for a given date range.'),
	('PDFStockCheckComparison.php', 2, 'Creates a pdf comparing the quantites entered as counted at a given range of locations against the quantity stored as on hand as at the time a stock check was initiated.'),
	('PDFStockLocTransfer.php', 1, 'Creates a stock location transfer docket for the selected location transfer reference number'),
	('PDFStockNegatives.php', 1, 'Produces a pdf of the negative stocks by location'),
	('PDFStockTransfer.php', 2, 'Produces a report for stock transfers'),
	('PDFSuppTransListing.php', 3, ''),
	('PDFTopItems.php', 2, 'Produces a pdf report of the top items sold'),
	('PeriodsInquiry.php', 2, 'Shows a list of all the system defined periods'),
	('POReport.php', 2, ''),
	('PO_AuthorisationLevels.php', 15, ''),
	('PO_AuthoriseMyOrders.php', 4, ''),
	('PO_Header.php', 4, 'Entry of a purchase order header record - date, references buyer etc'),
	('PO_Items.php', 4, 'Entry of a purchase order items - allows entry of items with lookup of currency cost from Purchasing Data previously entered also allows entry of nominal items against a general ledger code if the AP is integrated to the GL'),
	('PO_OrderDetails.php', 2, 'Purchase order inquiry shows the quantity received and invoiced of purchase order items as well as the header information'),
	('PO_PDFPurchOrder.php', 2, 'Creates a pdf of the selected purchase order for printing or email to one of the supplier contacts entered'),
	('PO_SelectOSPurchOrder.php', 2, 'Shows the outstanding purchase orders for selecting with links to receive or modify the purchase order header and items'),
	('PO_SelectPurchOrder.php', 2, 'Allows selection of any purchase order with links to the inquiry'),
	('Prices.php', 9, 'Entry of prices for a selected item also allows selection of sales type and currency for the price'),
	('PricesBasedOnMarkUp.php', 11, ''),
	('PricesByCost.php', 11, 'Allows prices to be updated based on cost'),
	('Prices_Customer.php', 11, 'Entry of prices for a selected item and selected customer/branch. The currency and sales type is defaulted from the customer\'s record'),
	('PrintCheque.php', 5, ''),
	('PrintCustOrder.php', 2, 'Creates a pdf of the dispatch note - by default this is expected to be on two part pre-printed stationery to allow pickers to note discrepancies for the confirmer to update the dispatch at the time of invoicing'),
	('PrintCustOrder_generic.php', 2, 'Creates two copies of a laser printed dispatch note - both copies need to be written on by the pickers with any discrepancies to advise customer of any shortfall and on the office copy to ensure the correct quantites are invoiced'),
	('PrintCustStatements.php', 2, 'Creates a pdf for the customer statements in the selected range'),
	('PrintCustTrans.php', 1, 'Creates either a html invoice or credit note or a pdf. A range of invoices or credit notes can be selected also.'),
	('PrintCustTransPortrait.php', 1, ''),
	('PrintSalesOrder_generic.php', 2, ''),
	('PurchData.php', 4, 'Entry of supplier purchasing data, the suppliers part reference and the suppliers currency cost of the item'),
	('RecurringSalesOrders.php', 1, ''),
	('RecurringSalesOrdersProcess.php', 1, 'Process Recurring Sales Orders'),
	('ReorderLevel.php', 2, 'Allows reorder levels of inventory to be updated'),
	('ReorderLevelLocation.php', 2, ''),
	('ReportBug.php', 15, ''),
	('ReportCreator.php', 13, 'Report Writer and Form Creator script that creates templates for user defined reports and forms'),
	('ReportletContainer.php', 1, ''),
	('ReportMaker.php', 1, 'Produces reports from the report writer templates created'),
	('reportwriter/admin/ReportCreator.php', 15, 'Report Writer'),
	('ReprintGRN.php', 11, 'Allows selection of a goods received batch for reprinting the goods received note given a purchase order number'),
	('ReverseGRN.php', 11, 'Reverses the entry of goods received - creating stock movements back out and necessary general ledger journals to effect the reversal'),
	('SalesAnalReptCols.php', 2, 'Entry of the definition of a sales analysis report\'s columns.'),
	('SalesAnalRepts.php', 2, 'Entry of the definition of a sales analysis report headers'),
	('SalesAnalysis_UserDefined.php', 2, 'Creates a pdf of a selected user defined sales analysis report'),
	('SalesByTypePeriodInquiry.php', 2, 'Shows sales for a selected date range by sales type/price list'),
	('SalesCategories.php', 11, ''),
	('SalesCategoryDescriptions.php', 15, 'Maintain translations for sales categories'),
	('SalesCategoryPeriodInquiry.php', 2, 'Shows sales for a selected date range by stock category'),
	('SalesGLPostings.php', 10, 'Defines the general ledger accounts used to post sales to based on product categories and sales areas'),
	('SalesGraph.php', 6, ''),
	('SalesInquiry.php', 2, ''),
	('SalesPeople.php', 3, 'Defines the sales people of the business'),
	('SalesTopItemsInquiry.php', 2, 'Shows the top item sales for a selected date range'),
	('SalesTypes.php', 15, 'Defines the sales types - prices are held against sales types they can be considered price lists. Sales analysis records are held by sales type too.'),
	('SecurityTokens.php', 15, 'Administration of security tokens'),
	('SelectAsset.php', 2, 'Allows a fixed asset to be selected for modification or viewing'),
	('SelectCompletedOrder.php', 1, 'Allows the selection of completed sales orders for inquiries - choices to select by item code or customer'),
	('SelectContract.php', 6, 'Allows a contract costing to be selected for modification or viewing'),
	('SelectCreditItems.php', 3, 'Entry of credit notes from scratch, selecting the items in either quick entry mode or searching for them manually'),
	('SelectCustomer.php', 2, 'Selection of customer - from where all customer related maintenance, transactions and inquiries start'),
	('SelectGLAccount.php', 8, 'Selection of general ledger account from where all general ledger account maintenance, or inquiries are initiated'),
	('SelectOrderItems.php', 1, 'Entry of sales order items with both quick entry and part search functions'),
	('SelectProduct.php', 2, 'Selection of items. All item maintenance, transactions and inquiries start with this script'),
	('SelectRecurringSalesOrder.php', 2, ''),
	('SelectSalesOrder.php', 2, 'Selects a sales order irrespective of completed or not for inquiries'),
	('SelectSupplier.php', 2, 'Selects a supplier. A supplier is required to be selected before any AP transactions and before any maintenance or inquiry of the supplier'),
	('SelectWorkOrder.php', 2, ''),
	('SellThroughSupport.php', 9, 'Defines the items, period and quantum of support for which supplier has agreed to provide.'),
	('ShipmentCosting.php', 11, 'Shows the costing of a shipment with all the items invoice values and any shipment costs apportioned. Updating the shipment has an option to update standard costs of all items on the shipment and create any general ledger variance journals'),
	('Shipments.php', 11, 'Entry of shipments from outstanding purchase orders for a selected supplier - changes in the delivery date will cascade into the different purchase orders on the shipment'),
	('Shippers.php', 15, 'Defines the shipping methods available. Each customer branch has a default shipping method associated with it which must match a record from this table'),
	('ShiptsList.php', 2, 'Shows a list of all the open shipments for a selected supplier. Linked from POItems.php'),
	('Shipt_Select.php', 11, 'Selection of a shipment for displaying and modification or updating'),
	('ShopParameters.php', 15, 'Maintain web-store configuration and set up'),
	('SMTPServer.php', 15, ''),
	('SpecialOrder.php', 4, 'Allows for a sales order to be created and an indent order to be created on a supplier for a one off item that may never be purchased again. A dummy part is created based on the description and cost details given.'),
	('StockAdjustments.php', 11, 'Entry of quantity corrections to stocks in a selected location.'),
	('StockAdjustmentsControlled.php', 11, 'Entry of batch references or serial numbers on controlled stock items being adjusted'),
	('StockCategories.php', 11, 'Defines the stock categories. All items must refer to one of these categories. The category record also allows the specification of the general ledger codes where stock items are to be posted - the balance sheet account and the profit and loss effect of any adjustments and the profit and loss effect of any price variances'),
	('StockCheck.php', 2, 'Allows creation of a stock check file - copying the current quantites in stock for later comparison to the entered counts. Also produces a pdf for the count sheets.'),
	('StockCostUpdate.php', 9, 'Allows update of the standard cost of items producing general ledger journals if the company preferences stock GL interface is active'),
	('StockCounts.php', 2, 'Allows entry of stock counts'),
	('StockDispatch.php', 2, ''),
	('StockLocMovements.php', 2, 'Inquiry shows the Movements of all stock items for a specified location'),
	('StockLocStatus.php', 2, 'Shows the stock on hand together with outstanding sales orders and outstanding purchase orders by stock location for all items in the selected stock category'),
	('StockLocTransfer.php', 11, 'Entry of a bulk stock location transfer for many parts from one location to another.'),
	('StockLocTransferReceive.php', 11, 'Effects the transfer and creates the stock movements for a bulk stock location transfer initiated from StockLocTransfer.php'),
	('StockMovements.php', 2, 'Shows a list of all the stock movements for a selected item and stock location including the price at which they were sold in local currency and the price at which they were purchased for in local currency'),
	('StockQties_csv.php', 5, 'Makes a comma separated values (CSV)file of the stock item codes and quantities'),
	('StockQuantityByDate.php', 2, 'Shows the stock on hand for each item at a selected location and stock category as at a specified date'),
	('StockReorderLevel.php', 4, 'Entry and review of the re-order level of items by stocking location'),
	('Stocks.php', 11, 'Defines an item - maintenance and addition of new parts'),
	('StockSerialItemResearch.php', 3, ''),
	('StockSerialItems.php', 2, 'Shows a list of the serial numbers or the batch references and quantities of controlled items. This inquiry is linked from the stock status inquiry'),
	('StockStatus.php', 2, 'Shows the stock on hand together with outstanding sales orders and outstanding purchase orders by stock location for a selected part. Has a link to show the serial numbers in stock at the location selected if the item is controlled'),
	('StockTransferControlled.php', 11, 'Entry of serial numbers/batch references for controlled items being received on a stock transfer. The script is used by both bulk transfers and point to point transfers'),
	('StockTransfers.php', 11, 'Entry of point to point stock location transfers of a single part'),
	('StockUsage.php', 2, 'Inquiry showing the quantity of stock used by period calculated from the sum of the stock movements over that period - by item and stock location. Also available over all locations'),
	('StockUsageGraph.php', 2, ''),
	('SuppContractChgs.php', 5, ''),
	('SuppCreditGRNs.php', 5, 'Entry of a supplier credit notes (debit notes) against existing GRN which have already been matched in full or in part'),
	('SuppFixedAssetChgs.php', 5, ''),
	('SuppInvGRNs.php', 5, 'Entry of supplier invoices against goods received'),
	('SupplierAllocations.php', 5, 'Entry of allocations of supplier payments and credit notes to invoices'),
	('SupplierBalsAtPeriodEnd.php', 2, ''),
	('SupplierContacts.php', 5, 'Entry of supplier contacts and contact details including email addresses'),
	('SupplierCredit.php', 5, 'Entry of supplier credit notes (debit notes)'),
	('SupplierInquiry.php', 2, 'Inquiry showing invoices, credit notes and payments made to suppliers together with the amounts outstanding'),
	('SupplierInvoice.php', 5, 'Entry of supplier invoices'),
	('SupplierPriceList.php', 4, 'Maintain Supplier Price Lists'),
	('Suppliers.php', 5, 'Entry of new suppliers and maintenance of existing suppliers'),
	('SupplierTenderCreate.php', 4, 'Create or Edit tenders'),
	('SupplierTenders.php', 9, ''),
	('SupplierTransInquiry.php', 2, ''),
	('SupplierTypes.php', 4, ''),
	('SuppLoginSetup.php', 15, ''),
	('SuppPaymentRun.php', 5, 'Automatic creation of payment records based on calculated amounts due from AP invoices entered'),
	('SuppPriceList.php', 2, ''),
	('SuppShiptChgs.php', 5, 'Entry of supplier invoices against shipments as charges against a shipment'),
	('SuppTransGLAnalysis.php', 5, 'Entry of supplier invoices against general ledger codes'),
	('SystemCheck.php', 10, ''),
	('SystemParameters.php', 15, ''),
	('Tax.php', 2, 'Creates a report of the ad-valoerm tax - GST/VAT - for the period selected from accounts payable and accounts receivable data'),
	('TaxAuthorities.php', 15, 'Entry of tax authorities - the state intitutions that charge tax'),
	('TaxAuthorityRates.php', 11, 'Entry of the rates of tax applicable to the tax authority depending on the item tax level'),
	('TaxCategories.php', 15, 'Allows for categories of items to be defined that might have different tax rates applied to them'),
	('TaxGroups.php', 15, 'Allows for taxes to be grouped together where multiple taxes might apply on sale or purchase of items'),
	('TaxProvinces.php', 15, 'Allows for inventory locations to be defined so that tax applicable from sales in different provinces can be dealt with'),
	('TopItems.php', 2, 'Shows the top selling items'),
	('UnitsOfMeasure.php', 15, 'Allows for units of measure to be defined'),
	('UpgradeDatabase.php', 15, 'Allows for the database to be automatically upgraded based on currently recorded DBUpgradeNumber config option'),
	('UserSettings.php', 1, 'Allows the user to change system wide defaults for the theme - appearance, the number of records to show in searches and the language to display messages in'),
	('WhereUsedInquiry.php', 2, 'Inquiry showing where an item is used ie all the parents where the item is a component of'),
	('WorkCentres.php', 9, 'Defines the various centres of work within a manufacturing company. Also the overhead and labour rates applicable to the work centre and its standard capacity'),
	('WorkOrderCosting.php', 11, ''),
	('WorkOrderEntry.php', 10, 'Entry of new work orders'),
	('WorkOrderIssue.php', 11, 'Issue of materials to a work order'),
	('WorkOrderReceive.php', 11, 'Allows for receiving of works orders'),
	('WorkOrderStatus.php', 11, 'Shows the status of works orders'),
	('WOSerialNos.php', 10, ''),
	('WWW_Access.php', 15, ''),
	('WWW_Users.php', 15, 'Entry of users and security settings of users'),
	('Z_BottomUpCosts.php', 15, ''),
	('Z_ChangeBranchCode.php', 15, 'Utility to change the branch code of a customer that cascades the change through all the necessary tables'),
	('Z_ChangeCustomerCode.php', 15, 'Utility to change a customer code that cascades the change through all the necessary tables'),
	('Z_ChangeGLAccountCode.php', 15, 'Script to change a GL account code accross all tables necessary'),
	('Z_ChangeLocationCode.php', 15, 'Change a locations code and in all tables where the old code was used to the new code'),
	('Z_ChangeStockCategory.php', 15, ''),
	('Z_ChangeStockCode.php', 15, 'Utility to change an item code that cascades the change through all the necessary tables'),
	('Z_ChangeSupplierCode.php', 15, 'Script to change a supplier code accross all tables necessary'),
	('Z_CheckAllocationsFrom.php', 15, ''),
	('Z_CheckAllocs.php', 2, ''),
	('Z_CheckDebtorsControl.php', 15, 'Inquiry that shows the total local currency (functional currency) balance of all customer accounts to reconcile with the general ledger debtors account'),
	('Z_CheckGLTransBalance.php', 15, 'Checks all GL transactions balance and reports problem ones'),
	('Z_CreateChartDetails.php', 9, 'Utility page to create chart detail records for all general ledger accounts and periods created - needs expert assistance in use'),
	('Z_CreateCompany.php', 15, 'Utility to insert company number 1 if not already there - actually only company 1 is used - the system is not multi-company'),
	('Z_CreateCompanyTemplateFile.php', 15, ''),
	('Z_CurrencyDebtorsBalances.php', 15, 'Inquiry that shows the total foreign currency together with the total local currency (functional currency) balances of all customer accounts to reconcile with the general ledger debtors account'),
	('Z_CurrencySuppliersBalances.php', 15, 'Inquiry that shows the total foreign currency amounts and also the local currency (functional currency) balances of all supplier accounts to reconcile with the general ledger creditors account'),
	('Z_DataExport.php', 15, ''),
	('Z_DeleteCreditNote.php', 15, 'Utility to reverse a customer credit note - a desperate measure that should not be used except in extreme circumstances'),
	('Z_DeleteInvoice.php', 15, 'Utility to reverse a customer invoice - a desperate measure that should not be used except in extreme circumstances'),
	('Z_DeleteOldPrices.php', 15, 'Deletes all old prices'),
	('Z_DeleteSalesTransActions.php', 15, 'Utility to delete all sales transactions, sales analysis the lot! Extreme care required!!!'),
	('Z_DescribeTable.php', 11, ''),
	('Z_ImportChartOfAccounts.php', 11, ''),
	('Z_ImportFixedAssets.php', 15, 'Allow fixed assets to be imported from a csv'),
	('Z_ImportGLAccountGroups.php', 11, ''),
	('Z_ImportGLAccountSections.php', 11, ''),
	('Z_ImportGLTransactions.php', 15, 'Import General Ledger Transactions'),
	('Z_ImportPartCodes.php', 11, 'Allows inventory items to be imported from a csv'),
	('Z_ImportPriceList.php', 15, 'Loads a new price list from a csv file'),
	('Z_ImportStocks.php', 15, ''),
	('Z_index.php', 15, 'Utility menu page'),
	('Z_ItemsWithoutPicture.php', 15, 'Shows the list of curent items without picture in webERP'),
	('Z_MakeNewCompany.php', 15, ''),
	('Z_MakeStockLocns.php', 15, 'Utility to make LocStock records for all items and locations if not already set up.'),
	('Z_poAddLanguage.php', 15, 'Allows a new language po file to be created'),
	('Z_poAdmin.php', 15, 'Allows for a gettext language po file to be administered'),
	('Z_poEditLangHeader.php', 15, ''),
	('Z_poEditLangModule.php', 15, ''),
	('Z_poEditLangRemaining.php', 15, ''),
	('Z_poRebuildDefault.php', 15, ''),
	('Z_PriceChanges.php', 15, 'Utility to make bulk pricing alterations to selected sales type price lists or selected customer prices only'),
	('Z_ReApplyCostToSA.php', 15, 'Utility to allow the sales analysis table to be updated with the latest cost information - the sales analysis takes the cost at the time the sale was made to reconcile with the enteries made in the gl.'),
	('Z_RePostGLFromPeriod.php', 15, 'Utility to repost all general ledger transaction commencing from a specified period. This can take some time in busy environments. Normally GL transactions are posted automatically each time a trial balance or profit and loss account is run'),
	('Z_ReverseSuppPaymentRun.php', 15, 'Utility to reverse an entire Supplier payment run'),
	('Z_SalesIntegrityCheck.php', 15, ''),
	('Z_UpdateChartDetailsBFwd.php', 15, 'Utility to recalculate the ChartDetails table B/Fwd balances - extreme care!!'),
	('Z_Upgrade3.10.php', 15, ''),
	('Z_Upgrade_3.01-3.02.php', 15, ''),
	('Z_Upgrade_3.04-3.05.php', 15, ''),
	('Z_Upgrade_3.05-3.06.php', 15, ''),
	('Z_Upgrade_3.07-3.08.php', 15, ''),
	('Z_Upgrade_3.08-3.09.php', 15, ''),
	('Z_Upgrade_3.09-3.10.php', 15, ''),
	('Z_Upgrade_3.10-3.11.php', 15, ''),
	('Z_Upgrade_3.11-4.00.php', 15, ''),
	('Z_UploadForm.php', 15, 'Utility to upload a file to a remote server'),
	('Z_UploadResult.php', 15, 'Utility to upload a file to a remote server');
/*!40000 ALTER TABLE `scripts` ENABLE KEYS */;


-- Dumping structure for table weberpcn.securitygroups
DROP TABLE IF EXISTS `securitygroups`;
CREATE TABLE IF NOT EXISTS `securitygroups` (
  `secroleid` int(11) NOT NULL DEFAULT '0',
  `tokenid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`secroleid`,`tokenid`),
  KEY `secroleid` (`secroleid`),
  KEY `tokenid` (`tokenid`),
  CONSTRAINT `securitygroups_secroleid_fk` FOREIGN KEY (`secroleid`) REFERENCES `securityroles` (`secroleid`),
  CONSTRAINT `securitygroups_tokenid_fk` FOREIGN KEY (`tokenid`) REFERENCES `securitytokens` (`tokenid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.securitygroups: ~56 rows (approximately)
/*!40000 ALTER TABLE `securitygroups` DISABLE KEYS */;
INSERT INTO `securitygroups` (`secroleid`, `tokenid`) VALUES
	(1, 0),
	(1, 1),
	(1, 2),
	(1, 5),
	(2, 0),
	(2, 1),
	(2, 2),
	(2, 11),
	(3, 0),
	(3, 1),
	(3, 2),
	(3, 3),
	(3, 4),
	(3, 5),
	(3, 11),
	(4, 0),
	(4, 1),
	(4, 2),
	(4, 5),
	(5, 0),
	(5, 1),
	(5, 2),
	(5, 3),
	(5, 11),
	(6, 0),
	(6, 1),
	(6, 2),
	(6, 3),
	(6, 4),
	(6, 5),
	(6, 6),
	(6, 7),
	(6, 8),
	(6, 9),
	(6, 10),
	(6, 11),
	(7, 0),
	(7, 1),
	(8, 0),
	(8, 1),
	(8, 2),
	(8, 3),
	(8, 4),
	(8, 5),
	(8, 6),
	(8, 7),
	(8, 8),
	(8, 9),
	(8, 10),
	(8, 11),
	(8, 12),
	(8, 13),
	(8, 14),
	(8, 15),
	(9, 0),
	(9, 9);
/*!40000 ALTER TABLE `securitygroups` ENABLE KEYS */;


-- Dumping structure for table weberpcn.securityroles
DROP TABLE IF EXISTS `securityroles`;
CREATE TABLE IF NOT EXISTS `securityroles` (
  `secroleid` int(11) NOT NULL AUTO_INCREMENT,
  `secrolename` text NOT NULL,
  PRIMARY KEY (`secroleid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.securityroles: ~9 rows (approximately)
/*!40000 ALTER TABLE `securityroles` DISABLE KEYS */;
INSERT INTO `securityroles` (`secroleid`, `secrolename`) VALUES
	(1, '销售主管'),
	(2, '车间主任'),
	(3, '采购主任'),
	(4, '应付专员'),
	(5, '应收专员'),
	(6, '财务主管'),
	(7, '客户'),
	(8, '系统管理员'),
	(9, '供应商');
/*!40000 ALTER TABLE `securityroles` ENABLE KEYS */;


-- Dumping structure for table weberpcn.securitytokens
DROP TABLE IF EXISTS `securitytokens`;
CREATE TABLE IF NOT EXISTS `securitytokens` (
  `tokenid` int(11) NOT NULL DEFAULT '0',
  `tokenname` text NOT NULL,
  PRIMARY KEY (`tokenid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.securitytokens: ~16 rows (approximately)
/*!40000 ALTER TABLE `securitytokens` DISABLE KEYS */;
INSERT INTO `securitytokens` (`tokenid`, `tokenname`) VALUES
	(0, '首页'),
	(1, '客户自助'),
	(2, '基本报表及查询'),
	(3, '应收管理'),
	(4, '采购管理'),
	(5, '应付管理'),
	(6, '小额现金管理'),
	(7, '银行对帐'),
	(8, '总帐报表及查询'),
	(9, '供应商自助'),
	(10, '财务总帐'),
	(11, '仓库管理'),
	(12, '价格管理'),
	(13, '修改价格'),
	(14, 'Unknown'),
	(15, '系统管理');
/*!40000 ALTER TABLE `securitytokens` ENABLE KEYS */;


-- Dumping structure for table weberpcn.sellthroughsupport
DROP TABLE IF EXISTS `sellthroughsupport`;
CREATE TABLE IF NOT EXISTS `sellthroughsupport` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplierno` varchar(10) NOT NULL,
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `categoryid` char(6) NOT NULL DEFAULT '',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `narrative` varchar(20) NOT NULL DEFAULT '',
  `rebatepercent` double NOT NULL DEFAULT '0',
  `rebateamount` double NOT NULL DEFAULT '0',
  `effectivefrom` date NOT NULL,
  `effectiveto` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `supplierno` (`supplierno`),
  KEY `debtorno` (`debtorno`),
  KEY `effectivefrom` (`effectivefrom`),
  KEY `effectiveto` (`effectiveto`),
  KEY `stockid` (`stockid`),
  KEY `categoryid` (`categoryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.sellthroughsupport: ~0 rows (approximately)
/*!40000 ALTER TABLE `sellthroughsupport` DISABLE KEYS */;
/*!40000 ALTER TABLE `sellthroughsupport` ENABLE KEYS */;


-- Dumping structure for table weberpcn.shipmentcharges
DROP TABLE IF EXISTS `shipmentcharges`;
CREATE TABLE IF NOT EXISTS `shipmentcharges` (
  `shiptchgid` int(11) NOT NULL AUTO_INCREMENT,
  `shiptref` int(11) NOT NULL DEFAULT '0',
  `transtype` smallint(6) NOT NULL DEFAULT '0',
  `transno` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `value` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`shiptchgid`),
  KEY `TransType` (`transtype`,`transno`),
  KEY `ShiptRef` (`shiptref`),
  KEY `StockID` (`stockid`),
  KEY `TransType_2` (`transtype`),
  CONSTRAINT `shipmentcharges_ibfk_1` FOREIGN KEY (`shiptref`) REFERENCES `shipments` (`shiptref`),
  CONSTRAINT `shipmentcharges_ibfk_2` FOREIGN KEY (`transtype`) REFERENCES `systypes` (`typeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.shipmentcharges: ~0 rows (approximately)
/*!40000 ALTER TABLE `shipmentcharges` DISABLE KEYS */;
/*!40000 ALTER TABLE `shipmentcharges` ENABLE KEYS */;


-- Dumping structure for table weberpcn.shipments
DROP TABLE IF EXISTS `shipments`;
CREATE TABLE IF NOT EXISTS `shipments` (
  `shiptref` int(11) NOT NULL DEFAULT '0',
  `voyageref` varchar(20) NOT NULL DEFAULT '0',
  `vessel` varchar(50) NOT NULL DEFAULT '',
  `eta` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `accumvalue` double NOT NULL DEFAULT '0',
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shiptref`),
  KEY `ETA` (`eta`),
  KEY `SupplierID` (`supplierid`),
  KEY `ShipperRef` (`voyageref`),
  KEY `Vessel` (`vessel`),
  CONSTRAINT `shipments_ibfk_1` FOREIGN KEY (`supplierid`) REFERENCES `suppliers` (`supplierid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.shipments: ~0 rows (approximately)
/*!40000 ALTER TABLE `shipments` DISABLE KEYS */;
/*!40000 ALTER TABLE `shipments` ENABLE KEYS */;


-- Dumping structure for table weberpcn.shippers
DROP TABLE IF EXISTS `shippers`;
CREATE TABLE IF NOT EXISTS `shippers` (
  `shipper_id` int(11) NOT NULL AUTO_INCREMENT,
  `shippername` char(40) NOT NULL DEFAULT '',
  `mincharge` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`shipper_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.shippers: ~1 rows (approximately)
/*!40000 ALTER TABLE `shippers` DISABLE KEYS */;
INSERT INTO `shippers` (`shipper_id`, `shippername`, `mincharge`) VALUES
	(1, 'Default Shipper', 0);
/*!40000 ALTER TABLE `shippers` ENABLE KEYS */;


-- Dumping structure for table weberpcn.stockcategory
DROP TABLE IF EXISTS `stockcategory`;
CREATE TABLE IF NOT EXISTS `stockcategory` (
  `categoryid` char(6) NOT NULL DEFAULT '',
  `categorydescription` char(20) NOT NULL DEFAULT '',
  `stocktype` char(1) NOT NULL DEFAULT 'F',
  `stockact` varchar(20) NOT NULL DEFAULT '0',
  `adjglact` varchar(20) NOT NULL DEFAULT '0',
  `issueglact` varchar(20) NOT NULL DEFAULT '0',
  `purchpricevaract` varchar(20) NOT NULL DEFAULT '80000',
  `materialuseagevarac` varchar(20) NOT NULL DEFAULT '80000',
  `wipact` varchar(20) NOT NULL DEFAULT '0',
  `defaulttaxcatid` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`categoryid`),
  KEY `CategoryDescription` (`categorydescription`),
  KEY `StockType` (`stocktype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.stockcategory: ~7 rows (approximately)
/*!40000 ALTER TABLE `stockcategory` DISABLE KEYS */;
INSERT INTO `stockcategory` (`categoryid`, `categorydescription`, `stocktype`, `stockact`, `adjglact`, `issueglact`, `purchpricevaract`, `materialuseagevarac`, `wipact`, `defaulttaxcatid`) VALUES
	('BCP', '半成品', 'F', '1403', '6701', '6701', '6702', '6702', '1403', 14),
	('BZW', '包装物', 'M', '1403', '6701', '6701', '6702', '6702', '1403', 14),
	('CCP', '产成品', 'F', '1405', '6701', '6701', '6702', '6702', '1406', 14),
	('FMP', '贩卖品', 'F', '1405', '6701', '6701', '6702', '6702', '1405', 14),
	('RGFY', '人工费用', 'L', '500102', '500101', '500101', '500101', '500101', '1406', 14),
	('XNWL', '虚拟物料', 'D', '1407', '6701', '6701', '6702', '6702', '1407', 14),
	('ZZFY', '制造费用', 'L', '500103', '500101', '500101', '500101', '500101', '1406', 14);
/*!40000 ALTER TABLE `stockcategory` ENABLE KEYS */;


-- Dumping structure for table weberpcn.stockcatproperties
DROP TABLE IF EXISTS `stockcatproperties`;
CREATE TABLE IF NOT EXISTS `stockcatproperties` (
  `stkcatpropid` int(11) NOT NULL AUTO_INCREMENT,
  `categoryid` char(6) NOT NULL,
  `label` text NOT NULL,
  `controltype` tinyint(4) NOT NULL DEFAULT '0',
  `defaultvalue` varchar(100) NOT NULL DEFAULT '''''',
  `maximumvalue` double NOT NULL DEFAULT '999999999',
  `reqatsalesorder` tinyint(4) NOT NULL DEFAULT '0',
  `minimumvalue` double NOT NULL DEFAULT '-999999999',
  `numericvalue` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`stkcatpropid`),
  KEY `categoryid` (`categoryid`),
  CONSTRAINT `stockcatproperties_ibfk_1` FOREIGN KEY (`categoryid`) REFERENCES `stockcategory` (`categoryid`),
  CONSTRAINT `stockcatproperties_ibfk_2` FOREIGN KEY (`categoryid`) REFERENCES `stockcategory` (`categoryid`),
  CONSTRAINT `stockcatproperties_ibfk_3` FOREIGN KEY (`categoryid`) REFERENCES `stockcategory` (`categoryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.stockcatproperties: ~0 rows (approximately)
/*!40000 ALTER TABLE `stockcatproperties` DISABLE KEYS */;
/*!40000 ALTER TABLE `stockcatproperties` ENABLE KEYS */;


-- Dumping structure for table weberpcn.stockcheckfreeze
DROP TABLE IF EXISTS `stockcheckfreeze`;
CREATE TABLE IF NOT EXISTS `stockcheckfreeze` (
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `qoh` double NOT NULL DEFAULT '0',
  `stockcheckdate` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`stockid`,`loccode`),
  KEY `LocCode` (`loccode`),
  CONSTRAINT `stockcheckfreeze_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  CONSTRAINT `stockcheckfreeze_ibfk_2` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.stockcheckfreeze: ~0 rows (approximately)
/*!40000 ALTER TABLE `stockcheckfreeze` DISABLE KEYS */;
/*!40000 ALTER TABLE `stockcheckfreeze` ENABLE KEYS */;


-- Dumping structure for table weberpcn.stockcounts
DROP TABLE IF EXISTS `stockcounts`;
CREATE TABLE IF NOT EXISTS `stockcounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `qtycounted` double NOT NULL DEFAULT '0',
  `reference` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `StockID` (`stockid`),
  KEY `LocCode` (`loccode`),
  CONSTRAINT `stockcounts_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  CONSTRAINT `stockcounts_ibfk_2` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.stockcounts: ~0 rows (approximately)
/*!40000 ALTER TABLE `stockcounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `stockcounts` ENABLE KEYS */;


-- Dumping structure for table weberpcn.stockdescriptiontranslations
DROP TABLE IF EXISTS `stockdescriptiontranslations`;
CREATE TABLE IF NOT EXISTS `stockdescriptiontranslations` (
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `language_id` varchar(10) NOT NULL DEFAULT 'en_GB.utf8',
  `descriptiontranslation` varchar(50) NOT NULL,
  PRIMARY KEY (`stockid`,`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.stockdescriptiontranslations: ~3 rows (approximately)


-- Dumping structure for table weberpcn.stockitemproperties
DROP TABLE IF EXISTS `stockitemproperties`;
CREATE TABLE IF NOT EXISTS `stockitemproperties` (
  `stockid` varchar(20) NOT NULL,
  `stkcatpropid` int(11) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`stockid`,`stkcatpropid`),
  KEY `stockid` (`stockid`),
  KEY `value` (`value`),
  KEY `stkcatpropid` (`stkcatpropid`),
  CONSTRAINT `stockitemproperties_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  CONSTRAINT `stockitemproperties_ibfk_2` FOREIGN KEY (`stkcatpropid`) REFERENCES `stockcatproperties` (`stkcatpropid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.stockitemproperties: ~0 rows (approximately)
/*!40000 ALTER TABLE `stockitemproperties` DISABLE KEYS */;
/*!40000 ALTER TABLE `stockitemproperties` ENABLE KEYS */;


-- Dumping structure for table weberpcn.stockmaster
DROP TABLE IF EXISTS `stockmaster`;
CREATE TABLE IF NOT EXISTS `stockmaster` (
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `categoryid` varchar(6) NOT NULL DEFAULT '',
  `description` varchar(50) NOT NULL DEFAULT '',
  `longdescription` text NOT NULL,
  `units` varchar(20) NOT NULL DEFAULT 'each',
  `mbflag` char(1) NOT NULL DEFAULT 'B',
  `actualcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `lastcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `materialcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `labourcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `overheadcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `lowestlevel` smallint(6) NOT NULL DEFAULT '0',
  `discontinued` tinyint(4) NOT NULL DEFAULT '0',
  `controlled` tinyint(4) NOT NULL DEFAULT '0',
  `eoq` double NOT NULL DEFAULT '0',
  `volume` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `grossweight` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `barcode` varchar(50) NOT NULL DEFAULT '',
  `discountcategory` char(2) NOT NULL DEFAULT '',
  `taxcatid` tinyint(4) NOT NULL DEFAULT '1',
  `serialised` tinyint(4) NOT NULL DEFAULT '0',
  `appendfile` varchar(40) NOT NULL DEFAULT 'none',
  `perishable` tinyint(1) NOT NULL DEFAULT '0',
  `decimalplaces` tinyint(4) NOT NULL DEFAULT '0',
  `pansize` double NOT NULL DEFAULT '0',
  `shrinkfactor` double NOT NULL DEFAULT '0',
  `nextserialno` bigint(20) NOT NULL DEFAULT '0',
  `netweight` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `lastcostupdate` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`stockid`),
  KEY `CategoryID` (`categoryid`),
  KEY `Description` (`description`),
  KEY `MBflag` (`mbflag`),
  KEY `StockID` (`stockid`,`categoryid`),
  KEY `Controlled` (`controlled`),
  KEY `DiscountCategory` (`discountcategory`),
  KEY `taxcatid` (`taxcatid`),
  CONSTRAINT `stockmaster_ibfk_1` FOREIGN KEY (`categoryid`) REFERENCES `stockcategory` (`categoryid`),
  CONSTRAINT `stockmaster_ibfk_2` FOREIGN KEY (`taxcatid`) REFERENCES `taxcategories` (`taxcatid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- Dumping structure for table weberpcn.stockmoves
DROP TABLE IF EXISTS `stockmoves`;
CREATE TABLE IF NOT EXISTS `stockmoves` (
  `stkmoveno` int(11) NOT NULL AUTO_INCREMENT,
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `type` smallint(6) NOT NULL DEFAULT '0',
  `transno` int(11) NOT NULL DEFAULT '0',
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `trandate` date NOT NULL DEFAULT '0000-00-00',
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `price` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `prd` smallint(6) NOT NULL DEFAULT '0',
  `reference` varchar(40) NOT NULL DEFAULT '',
  `qty` double NOT NULL DEFAULT '1',
  `discountpercent` double NOT NULL DEFAULT '0',
  `standardcost` double NOT NULL DEFAULT '0',
  `show_on_inv_crds` tinyint(4) NOT NULL DEFAULT '1',
  `newqoh` double NOT NULL DEFAULT '0',
  `hidemovt` tinyint(4) NOT NULL DEFAULT '0',
  `narrative` text,
  PRIMARY KEY (`stkmoveno`),
  KEY `DebtorNo` (`debtorno`),
  KEY `LocCode` (`loccode`),
  KEY `Prd` (`prd`),
  KEY `StockID_2` (`stockid`),
  KEY `TranDate` (`trandate`),
  KEY `TransNo` (`transno`),
  KEY `Type` (`type`),
  KEY `Show_On_Inv_Crds` (`show_on_inv_crds`),
  KEY `Hide` (`hidemovt`),
  KEY `reference` (`reference`),
  CONSTRAINT `stockmoves_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  CONSTRAINT `stockmoves_ibfk_2` FOREIGN KEY (`type`) REFERENCES `systypes` (`typeid`),
  CONSTRAINT `stockmoves_ibfk_3` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`),
  CONSTRAINT `stockmoves_ibfk_4` FOREIGN KEY (`prd`) REFERENCES `periods` (`periodno`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


-- Dumping structure for table weberpcn.stockmovestaxes
DROP TABLE IF EXISTS `stockmovestaxes`;
CREATE TABLE IF NOT EXISTS `stockmovestaxes` (
  `stkmoveno` int(11) NOT NULL DEFAULT '0',
  `taxauthid` tinyint(4) NOT NULL DEFAULT '0',
  `taxrate` double NOT NULL DEFAULT '0',
  `taxontax` tinyint(4) NOT NULL DEFAULT '0',
  `taxcalculationorder` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`stkmoveno`,`taxauthid`),
  KEY `taxauthid` (`taxauthid`),
  KEY `calculationorder` (`taxcalculationorder`),
  CONSTRAINT `stockmovestaxes_ibfk_1` FOREIGN KEY (`taxauthid`) REFERENCES `taxauthorities` (`taxid`),
  CONSTRAINT `stockmovestaxes_ibfk_2` FOREIGN KEY (`stkmoveno`) REFERENCES `stockmoves` (`stkmoveno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.stockmovestaxes: ~0 rows (approximately)
/*!40000 ALTER TABLE `stockmovestaxes` DISABLE KEYS */;
/*!40000 ALTER TABLE `stockmovestaxes` ENABLE KEYS */;


-- Dumping structure for table weberpcn.stockrequest
DROP TABLE IF EXISTS `stockrequest`;
CREATE TABLE IF NOT EXISTS `stockrequest` (
  `dispatchid` int(11) NOT NULL AUTO_INCREMENT,
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `departmentid` int(11) NOT NULL DEFAULT '0',
  `despatchdate` date NOT NULL DEFAULT '0000-00-00',
  `authorised` tinyint(4) NOT NULL DEFAULT '0',
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  `narrative` text NOT NULL,
  PRIMARY KEY (`dispatchid`),
  KEY `loccode` (`loccode`),
  KEY `departmentid` (`departmentid`),
  KEY `loccode_2` (`loccode`),
  KEY `departmentid_2` (`departmentid`),
  CONSTRAINT `stockrequest_ibfk_1` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`),
  CONSTRAINT `stockrequest_ibfk_2` FOREIGN KEY (`departmentid`) REFERENCES `departments` (`departmentid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


-- Dumping structure for table weberpcn.stockrequestitems
DROP TABLE IF EXISTS `stockrequestitems`;
CREATE TABLE IF NOT EXISTS `stockrequestitems` (
  `dispatchitemsid` int(11) NOT NULL DEFAULT '0',
  `dispatchid` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '0',
  `qtydelivered` double NOT NULL DEFAULT '0',
  `decimalplaces` int(11) NOT NULL DEFAULT '0',
  `uom` varchar(20) NOT NULL DEFAULT '',
  `completed` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`dispatchitemsid`,`dispatchid`),
  KEY `dispatchid` (`dispatchid`),
  KEY `stockid` (`stockid`),
  KEY `dispatchid_2` (`dispatchid`),
  KEY `stockid_2` (`stockid`),
  CONSTRAINT `stockrequestitems_ibfk_1` FOREIGN KEY (`dispatchid`) REFERENCES `stockrequest` (`dispatchid`),
  CONSTRAINT `stockrequestitems_ibfk_2` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Dumping structure for table weberpcn.stockserialitems
DROP TABLE IF EXISTS `stockserialitems`;
CREATE TABLE IF NOT EXISTS `stockserialitems` (
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `serialno` varchar(30) NOT NULL DEFAULT '',
  `expirationdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `quantity` double NOT NULL DEFAULT '0',
  `qualitytext` text NOT NULL,
  PRIMARY KEY (`stockid`,`serialno`,`loccode`),
  KEY `StockID` (`stockid`),
  KEY `LocCode` (`loccode`),
  KEY `serialno` (`serialno`),
  CONSTRAINT `stockserialitems_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  CONSTRAINT `stockserialitems_ibfk_2` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.stockserialitems: ~0 rows (approximately)
/*!40000 ALTER TABLE `stockserialitems` DISABLE KEYS */;
/*!40000 ALTER TABLE `stockserialitems` ENABLE KEYS */;


-- Dumping structure for table weberpcn.stockserialmoves
DROP TABLE IF EXISTS `stockserialmoves`;
CREATE TABLE IF NOT EXISTS `stockserialmoves` (
  `stkitmmoveno` int(11) NOT NULL AUTO_INCREMENT,
  `stockmoveno` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `serialno` varchar(30) NOT NULL DEFAULT '',
  `moveqty` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`stkitmmoveno`),
  KEY `StockMoveNo` (`stockmoveno`),
  KEY `StockID_SN` (`stockid`,`serialno`),
  KEY `serialno` (`serialno`),
  CONSTRAINT `stockserialmoves_ibfk_1` FOREIGN KEY (`stockmoveno`) REFERENCES `stockmoves` (`stkmoveno`),
  CONSTRAINT `stockserialmoves_ibfk_2` FOREIGN KEY (`stockid`, `serialno`) REFERENCES `stockserialitems` (`stockid`, `serialno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.stockserialmoves: ~0 rows (approximately)
/*!40000 ALTER TABLE `stockserialmoves` DISABLE KEYS */;
/*!40000 ALTER TABLE `stockserialmoves` ENABLE KEYS */;


-- Dumping structure for table weberpcn.suppallocs
DROP TABLE IF EXISTS `suppallocs`;
CREATE TABLE IF NOT EXISTS `suppallocs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amt` double NOT NULL DEFAULT '0',
  `datealloc` date NOT NULL DEFAULT '0000-00-00',
  `transid_allocfrom` int(11) NOT NULL DEFAULT '0',
  `transid_allocto` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `TransID_AllocFrom` (`transid_allocfrom`),
  KEY `TransID_AllocTo` (`transid_allocto`),
  KEY `DateAlloc` (`datealloc`),
  CONSTRAINT `suppallocs_ibfk_1` FOREIGN KEY (`transid_allocfrom`) REFERENCES `supptrans` (`id`),
  CONSTRAINT `suppallocs_ibfk_2` FOREIGN KEY (`transid_allocto`) REFERENCES `supptrans` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.suppallocs: ~0 rows (approximately)
/*!40000 ALTER TABLE `suppallocs` DISABLE KEYS */;
/*!40000 ALTER TABLE `suppallocs` ENABLE KEYS */;


-- Dumping structure for table weberpcn.suppliercontacts
DROP TABLE IF EXISTS `suppliercontacts`;
CREATE TABLE IF NOT EXISTS `suppliercontacts` (
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `contact` varchar(30) NOT NULL DEFAULT '',
  `position` varchar(30) NOT NULL DEFAULT '',
  `tel` varchar(30) NOT NULL DEFAULT '',
  `fax` varchar(30) NOT NULL DEFAULT '',
  `mobile` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(55) NOT NULL DEFAULT '',
  `ordercontact` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`supplierid`,`contact`),
  KEY `Contact` (`contact`),
  KEY `SupplierID` (`supplierid`),
  CONSTRAINT `suppliercontacts_ibfk_1` FOREIGN KEY (`supplierid`) REFERENCES `suppliers` (`supplierid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.suppliercontacts: ~0 rows (approximately)
/*!40000 ALTER TABLE `suppliercontacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `suppliercontacts` ENABLE KEYS */;


-- Dumping structure for table weberpcn.supplierdiscounts
DROP TABLE IF EXISTS `supplierdiscounts`;
CREATE TABLE IF NOT EXISTS `supplierdiscounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplierno` varchar(10) NOT NULL,
  `stockid` varchar(20) NOT NULL,
  `discountnarrative` varchar(20) NOT NULL,
  `discountpercent` double NOT NULL,
  `discountamount` double NOT NULL,
  `effectivefrom` date NOT NULL,
  `effectiveto` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `supplierno` (`supplierno`),
  KEY `effectivefrom` (`effectivefrom`),
  KEY `effectiveto` (`effectiveto`),
  KEY `stockid` (`stockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.supplierdiscounts: ~0 rows (approximately)
/*!40000 ALTER TABLE `supplierdiscounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `supplierdiscounts` ENABLE KEYS */;


-- Dumping structure for table weberpcn.suppliers
DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers` (
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `suppname` varchar(40) NOT NULL DEFAULT '',
  `address1` varchar(40) NOT NULL DEFAULT '',
  `address2` varchar(40) NOT NULL DEFAULT '',
  `address3` varchar(40) NOT NULL DEFAULT '',
  `address4` varchar(50) NOT NULL DEFAULT '',
  `address5` varchar(20) NOT NULL DEFAULT '',
  `address6` varchar(40) NOT NULL DEFAULT '',
  `supptype` tinyint(4) NOT NULL DEFAULT '1',
  `lat` float(10,6) NOT NULL DEFAULT '0.000000',
  `lng` float(10,6) NOT NULL DEFAULT '0.000000',
  `currcode` char(3) NOT NULL DEFAULT '',
  `suppliersince` date NOT NULL DEFAULT '0000-00-00',
  `paymentterms` char(2) NOT NULL DEFAULT '',
  `lastpaid` double NOT NULL DEFAULT '0',
  `lastpaiddate` datetime DEFAULT NULL,
  `bankact` varchar(30) NOT NULL DEFAULT '',
  `bankref` varchar(12) NOT NULL DEFAULT '',
  `bankpartics` varchar(12) NOT NULL DEFAULT '',
  `remittance` tinyint(4) NOT NULL DEFAULT '1',
  `taxgroupid` tinyint(4) NOT NULL DEFAULT '1',
  `factorcompanyid` int(11) NOT NULL DEFAULT '1',
  `taxref` varchar(20) NOT NULL DEFAULT '',
  `phn` varchar(50) NOT NULL DEFAULT '',
  `port` varchar(200) NOT NULL DEFAULT '',
  `email` varchar(55) DEFAULT NULL,
  `fax` varchar(25) DEFAULT NULL,
  `telephone` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`supplierid`),
  KEY `CurrCode` (`currcode`),
  KEY `PaymentTerms` (`paymentterms`),
  KEY `SuppName` (`suppname`),
  KEY `taxgroupid` (`taxgroupid`),
  CONSTRAINT `suppliers_ibfk_1` FOREIGN KEY (`currcode`) REFERENCES `currencies` (`currabrev`),
  CONSTRAINT `suppliers_ibfk_2` FOREIGN KEY (`paymentterms`) REFERENCES `paymentterms` (`termsindicator`),
  CONSTRAINT `suppliers_ibfk_3` FOREIGN KEY (`taxgroupid`) REFERENCES `taxgroups` (`taxgroupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.suppliers: ~0 rows (approximately)
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;


-- Dumping structure for table weberpcn.suppliertype
DROP TABLE IF EXISTS `suppliertype`;
CREATE TABLE IF NOT EXISTS `suppliertype` (
  `typeid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `typename` varchar(100) NOT NULL,
  PRIMARY KEY (`typeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.suppliertype: ~0 rows (approximately)
/*!40000 ALTER TABLE `suppliertype` DISABLE KEYS */;
/*!40000 ALTER TABLE `suppliertype` ENABLE KEYS */;


-- Dumping structure for table weberpcn.supptrans
DROP TABLE IF EXISTS `supptrans`;
CREATE TABLE IF NOT EXISTS `supptrans` (
  `transno` int(11) NOT NULL DEFAULT '0',
  `type` smallint(6) NOT NULL DEFAULT '0',
  `supplierno` varchar(10) NOT NULL DEFAULT '',
  `suppreference` varchar(20) NOT NULL DEFAULT '',
  `trandate` date NOT NULL DEFAULT '0000-00-00',
  `duedate` date NOT NULL DEFAULT '0000-00-00',
  `inputdate` datetime NOT NULL,
  `settled` tinyint(4) NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '1',
  `ovamount` double NOT NULL DEFAULT '0',
  `ovgst` double NOT NULL DEFAULT '0',
  `diffonexch` double NOT NULL DEFAULT '0',
  `alloc` double NOT NULL DEFAULT '0',
  `transtext` text,
  `hold` tinyint(4) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `TypeTransNo` (`transno`,`type`),
  KEY `DueDate` (`duedate`),
  KEY `Hold` (`hold`),
  KEY `SupplierNo` (`supplierno`),
  KEY `Settled` (`settled`),
  KEY `SupplierNo_2` (`supplierno`,`suppreference`),
  KEY `SuppReference` (`suppreference`),
  KEY `TranDate` (`trandate`),
  KEY `TransNo` (`transno`),
  KEY `Type` (`type`),
  CONSTRAINT `supptrans_ibfk_1` FOREIGN KEY (`type`) REFERENCES `systypes` (`typeid`),
  CONSTRAINT `supptrans_ibfk_2` FOREIGN KEY (`supplierno`) REFERENCES `suppliers` (`supplierid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.supptrans: ~0 rows (approximately)
/*!40000 ALTER TABLE `supptrans` DISABLE KEYS */;
/*!40000 ALTER TABLE `supptrans` ENABLE KEYS */;


-- Dumping structure for table weberpcn.supptranstaxes
DROP TABLE IF EXISTS `supptranstaxes`;
CREATE TABLE IF NOT EXISTS `supptranstaxes` (
  `supptransid` int(11) NOT NULL DEFAULT '0',
  `taxauthid` tinyint(4) NOT NULL DEFAULT '0',
  `taxamount` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`supptransid`,`taxauthid`),
  KEY `taxauthid` (`taxauthid`),
  CONSTRAINT `supptranstaxes_ibfk_1` FOREIGN KEY (`taxauthid`) REFERENCES `taxauthorities` (`taxid`),
  CONSTRAINT `supptranstaxes_ibfk_2` FOREIGN KEY (`supptransid`) REFERENCES `supptrans` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.supptranstaxes: ~0 rows (approximately)
/*!40000 ALTER TABLE `supptranstaxes` DISABLE KEYS */;
/*!40000 ALTER TABLE `supptranstaxes` ENABLE KEYS */;


-- Dumping structure for table weberpcn.systypes
DROP TABLE IF EXISTS `systypes`;
CREATE TABLE IF NOT EXISTS `systypes` (
  `typeid` smallint(6) NOT NULL DEFAULT '0',
  `typename` char(50) NOT NULL DEFAULT '',
  `typeno` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`typeid`),
  KEY `TypeNo` (`typeno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.systypes: ~35 rows (approximately)
/*!40000 ALTER TABLE `systypes` DISABLE KEYS */;
INSERT INTO `systypes` (`typeid`, `typename`, `typeno`) VALUES
	(0, '转账凭证', 0),
	(1, '总账付款', 0),
	(2, '总账收款', 0),
	(3, 'Standing Journal', 0),
	(10, '销售发票', 0),
	(11, '退货单', 0),
	(12, '收据', 0),
	(15, '其它应收', 0),
	(16, '调拨单', 0),
	(17, '库存调整单', 0),
	(18, '采购订单', 0),
	(19, '装箱单', 0),
	(20, '采购发票', 0),
	(21, '缴款单', 0),
	(22, '偿还债务', 0),
	(23, '应付账款', 0),
	(25, '收货暂诂', 0),
	(26, '生产入库单', 0),
	(28, '生产发料单', 0),
	(29, '工单差异', 0),
	(30, '销售订单', 0),
	(31, '运费结算单', 0),
	(32, '合同结算单', 0),
	(35, '成本更新', 0),
	(36, '汇兑差额', 0),
	(37, '招投标', 0),
	(38, '易耗品申请单', 0),
	(40, '工作单', 0),
	(41, '资产加入', 0),
	(42, '资产变更', 0),
	(43, '资产报废', 0),
	(44, '折旧', 0),
	(49, '资产导入', 0),
	(50, '期初结余', 0),
	(500, '客户编号', 0);
/*!40000 ALTER TABLE `systypes` ENABLE KEYS */;


-- Dumping structure for table weberpcn.tags
DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `tagref` tinyint(4) NOT NULL AUTO_INCREMENT,
  `tagdescription` varchar(50) NOT NULL,
  PRIMARY KEY (`tagref`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.tags: ~0 rows (approximately)
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;


-- Dumping structure for table weberpcn.taxauthorities
DROP TABLE IF EXISTS `taxauthorities`;
CREATE TABLE IF NOT EXISTS `taxauthorities` (
  `taxid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `description` varchar(20) NOT NULL DEFAULT '',
  `taxglcode` varchar(20) NOT NULL DEFAULT '0',
  `purchtaxglaccount` varchar(20) NOT NULL DEFAULT '0',
  `bank` varchar(50) NOT NULL DEFAULT '',
  `bankacctype` varchar(20) NOT NULL DEFAULT '',
  `bankacc` varchar(50) NOT NULL DEFAULT '',
  `bankswift` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`taxid`),
  KEY `TaxGLCode` (`taxglcode`),
  KEY `PurchTaxGLAccount` (`purchtaxglaccount`),
  CONSTRAINT `taxauthorities_ibfk_1` FOREIGN KEY (`taxglcode`) REFERENCES `chartmaster` (`accountcode`),
  CONSTRAINT `taxauthorities_ibfk_2` FOREIGN KEY (`purchtaxglaccount`) REFERENCES `chartmaster` (`accountcode`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.taxauthorities: ~2 rows (approximately)
/*!40000 ALTER TABLE `taxauthorities` DISABLE KEYS */;
INSERT INTO `taxauthorities` (`taxid`, `description`, `taxglcode`, `purchtaxglaccount`, `bank`, `bankacctype`, `bankacc`, `bankswift`) VALUES
	(14, '增值税', '2221', '2221', '', '', '', ''),
	(16, '普税%3', '2221', '2221', '', '', '', '');
/*!40000 ALTER TABLE `taxauthorities` ENABLE KEYS */;


-- Dumping structure for table weberpcn.taxauthrates
DROP TABLE IF EXISTS `taxauthrates`;
CREATE TABLE IF NOT EXISTS `taxauthrates` (
  `taxauthority` tinyint(4) NOT NULL DEFAULT '1',
  `dispatchtaxprovince` tinyint(4) NOT NULL DEFAULT '1',
  `taxcatid` tinyint(4) NOT NULL DEFAULT '0',
  `taxrate` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`taxauthority`,`dispatchtaxprovince`,`taxcatid`),
  KEY `TaxAuthority` (`taxauthority`),
  KEY `dispatchtaxprovince` (`dispatchtaxprovince`),
  KEY `taxcatid` (`taxcatid`),
  CONSTRAINT `taxauthrates_ibfk_1` FOREIGN KEY (`taxauthority`) REFERENCES `taxauthorities` (`taxid`),
  CONSTRAINT `taxauthrates_ibfk_2` FOREIGN KEY (`taxcatid`) REFERENCES `taxcategories` (`taxcatid`),
  CONSTRAINT `taxauthrates_ibfk_3` FOREIGN KEY (`dispatchtaxprovince`) REFERENCES `taxprovinces` (`taxprovinceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.taxauthrates: ~8 rows (approximately)
/*!40000 ALTER TABLE `taxauthrates` DISABLE KEYS */;
INSERT INTO `taxauthrates` (`taxauthority`, `dispatchtaxprovince`, `taxcatid`, `taxrate`) VALUES
	(14, 1, 1, 0),
	(14, 1, 2, 0.17),
	(14, 1, 4, 0),
	(14, 1, 6, 0),
	(16, 1, 1, 0.03),
	(16, 1, 2, 0),
	(16, 1, 4, 0),
	(16, 1, 6, 0);
/*!40000 ALTER TABLE `taxauthrates` ENABLE KEYS */;


-- Dumping structure for table weberpcn.taxcategories
DROP TABLE IF EXISTS `taxcategories`;
CREATE TABLE IF NOT EXISTS `taxcategories` (
  `taxcatid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `taxcatname` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`taxcatid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.taxcategories: ~4 rows (approximately)
/*!40000 ALTER TABLE `taxcategories` DISABLE KEYS */;
INSERT INTO `taxcategories` (`taxcatid`, `taxcatname`) VALUES
	(1, '普税'),
	(2, '增值税'),
	(4, '免税'),
	(6, 'Freight');
/*!40000 ALTER TABLE `taxcategories` ENABLE KEYS */;


-- Dumping structure for table weberpcn.taxgroups
DROP TABLE IF EXISTS `taxgroups`;
CREATE TABLE IF NOT EXISTS `taxgroups` (
  `taxgroupid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `taxgroupdescription` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`taxgroupid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.taxgroups: ~2 rows (approximately)
/*!40000 ALTER TABLE `taxgroups` DISABLE KEYS */;
INSERT INTO `taxgroups` (`taxgroupid`, `taxgroupdescription`) VALUES
	(1, '普通税种'),
	(2, '增值税种');
/*!40000 ALTER TABLE `taxgroups` ENABLE KEYS */;


-- Dumping structure for table weberpcn.taxgrouptaxes
DROP TABLE IF EXISTS `taxgrouptaxes`;
CREATE TABLE IF NOT EXISTS `taxgrouptaxes` (
  `taxgroupid` tinyint(4) NOT NULL DEFAULT '0',
  `taxauthid` tinyint(4) NOT NULL DEFAULT '0',
  `calculationorder` tinyint(4) NOT NULL DEFAULT '0',
  `taxontax` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`taxgroupid`,`taxauthid`),
  KEY `taxgroupid` (`taxgroupid`),
  KEY `taxauthid` (`taxauthid`),
  CONSTRAINT `taxgrouptaxes_ibfk_1` FOREIGN KEY (`taxgroupid`) REFERENCES `taxgroups` (`taxgroupid`),
  CONSTRAINT `taxgrouptaxes_ibfk_2` FOREIGN KEY (`taxauthid`) REFERENCES `taxauthorities` (`taxid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.taxgrouptaxes: ~3 rows (approximately)
/*!40000 ALTER TABLE `taxgrouptaxes` DISABLE KEYS */;
INSERT INTO `taxgrouptaxes` (`taxgroupid`, `taxauthid`, `calculationorder`, `taxontax`) VALUES
	(1, 14, 1, 0),
	(1, 16, 0, 0),
	(2, 14, 0, 0);
/*!40000 ALTER TABLE `taxgrouptaxes` ENABLE KEYS */;


-- Dumping structure for table weberpcn.taxprovinces
DROP TABLE IF EXISTS `taxprovinces`;
CREATE TABLE IF NOT EXISTS `taxprovinces` (
  `taxprovinceid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `taxprovincename` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`taxprovinceid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.taxprovinces: ~1 rows (approximately)
/*!40000 ALTER TABLE `taxprovinces` DISABLE KEYS */;
INSERT INTO `taxprovinces` (`taxprovinceid`, `taxprovincename`) VALUES
	(1, '中国大陆');
/*!40000 ALTER TABLE `taxprovinces` ENABLE KEYS */;


-- Dumping structure for table weberpcn.tenderitems
DROP TABLE IF EXISTS `tenderitems`;
CREATE TABLE IF NOT EXISTS `tenderitems` (
  `tenderid` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `quantity` varchar(40) NOT NULL DEFAULT '',
  `units` varchar(20) NOT NULL DEFAULT 'each',
  PRIMARY KEY (`tenderid`,`stockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.tenderitems: ~0 rows (approximately)
/*!40000 ALTER TABLE `tenderitems` DISABLE KEYS */;
/*!40000 ALTER TABLE `tenderitems` ENABLE KEYS */;


-- Dumping structure for table weberpcn.tenders
DROP TABLE IF EXISTS `tenders`;
CREATE TABLE IF NOT EXISTS `tenders` (
  `tenderid` int(11) NOT NULL DEFAULT '0',
  `location` varchar(5) NOT NULL DEFAULT '',
  `address1` varchar(40) NOT NULL DEFAULT '',
  `address2` varchar(40) NOT NULL DEFAULT '',
  `address3` varchar(40) NOT NULL DEFAULT '',
  `address4` varchar(40) NOT NULL DEFAULT '',
  `address5` varchar(20) NOT NULL DEFAULT '',
  `address6` varchar(15) NOT NULL DEFAULT '',
  `telephone` varchar(25) NOT NULL DEFAULT '',
  `closed` int(2) NOT NULL DEFAULT '0',
  `requiredbydate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`tenderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.tenders: ~0 rows (approximately)
/*!40000 ALTER TABLE `tenders` DISABLE KEYS */;
/*!40000 ALTER TABLE `tenders` ENABLE KEYS */;


-- Dumping structure for table weberpcn.tendersuppliers
DROP TABLE IF EXISTS `tendersuppliers`;
CREATE TABLE IF NOT EXISTS `tendersuppliers` (
  `tenderid` int(11) NOT NULL DEFAULT '0',
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `email` varchar(40) NOT NULL DEFAULT '',
  `responded` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tenderid`,`supplierid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.tendersuppliers: ~0 rows (approximately)
/*!40000 ALTER TABLE `tendersuppliers` DISABLE KEYS */;
/*!40000 ALTER TABLE `tendersuppliers` ENABLE KEYS */;


-- Dumping structure for table weberpcn.unitsofmeasure
DROP TABLE IF EXISTS `unitsofmeasure`;
CREATE TABLE IF NOT EXISTS `unitsofmeasure` (
  `unitid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `unitname` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`unitid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.unitsofmeasure: ~7 rows (approximately)
/*!40000 ALTER TABLE `unitsofmeasure` DISABLE KEYS */;
INSERT INTO `unitsofmeasure` (`unitid`, `unitname`) VALUES
	(1, '件'),
	(2, '米'),
	(3, '千克'),
	(4, '公升'),
	(5, '吨'),
	(6, '小时'),
	(7, '英尺');
/*!40000 ALTER TABLE `unitsofmeasure` ENABLE KEYS */;


-- Dumping structure for table weberpcn.woitems
DROP TABLE IF EXISTS `woitems`;
CREATE TABLE IF NOT EXISTS `woitems` (
  `wo` int(11) NOT NULL,
  `stockid` char(20) NOT NULL DEFAULT '',
  `qtyreqd` double NOT NULL DEFAULT '1',
  `qtyrecd` double NOT NULL DEFAULT '0',
  `stdcost` double NOT NULL,
  `nextlotsnref` varchar(20) DEFAULT '',
  PRIMARY KEY (`wo`,`stockid`),
  KEY `stockid` (`stockid`),
  CONSTRAINT `woitems_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  CONSTRAINT `woitems_ibfk_2` FOREIGN KEY (`wo`) REFERENCES `workorders` (`wo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.woitems: ~0 rows (approximately)
/*!40000 ALTER TABLE `woitems` DISABLE KEYS */;
/*!40000 ALTER TABLE `woitems` ENABLE KEYS */;


-- Dumping structure for table weberpcn.worequirements
DROP TABLE IF EXISTS `worequirements`;
CREATE TABLE IF NOT EXISTS `worequirements` (
  `wo` int(11) NOT NULL,
  `parentstockid` varchar(20) NOT NULL,
  `stockid` varchar(20) NOT NULL,
  `qtypu` double NOT NULL DEFAULT '1',
  `stdcost` double NOT NULL DEFAULT '0',
  `autoissue` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`wo`,`parentstockid`,`stockid`),
  KEY `stockid` (`stockid`),
  KEY `worequirements_ibfk_3` (`parentstockid`),
  CONSTRAINT `worequirements_ibfk_1` FOREIGN KEY (`wo`) REFERENCES `workorders` (`wo`),
  CONSTRAINT `worequirements_ibfk_2` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  CONSTRAINT `worequirements_ibfk_3` FOREIGN KEY (`wo`, `parentstockid`) REFERENCES `woitems` (`wo`, `stockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.worequirements: ~0 rows (approximately)
/*!40000 ALTER TABLE `worequirements` DISABLE KEYS */;
/*!40000 ALTER TABLE `worequirements` ENABLE KEYS */;


-- Dumping structure for table weberpcn.workcentres
DROP TABLE IF EXISTS `workcentres`;
CREATE TABLE IF NOT EXISTS `workcentres` (
  `code` char(5) NOT NULL DEFAULT '',
  `location` char(5) NOT NULL DEFAULT '',
  `description` char(20) NOT NULL DEFAULT '',
  `capacity` double NOT NULL DEFAULT '1',
  `overheadperhour` decimal(10,0) NOT NULL DEFAULT '0',
  `overheadrecoveryact` varchar(20) NOT NULL DEFAULT '0',
  `setuphrs` decimal(10,0) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`),
  KEY `Description` (`description`),
  KEY `Location` (`location`),
  CONSTRAINT `workcentres_ibfk_1` FOREIGN KEY (`location`) REFERENCES `locations` (`loccode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.workcentres: ~0 rows (approximately)
/*!40000 ALTER TABLE `workcentres` DISABLE KEYS */;
/*!40000 ALTER TABLE `workcentres` ENABLE KEYS */;


-- Dumping structure for table weberpcn.workorders
DROP TABLE IF EXISTS `workorders`;
CREATE TABLE IF NOT EXISTS `workorders` (
  `wo` int(11) NOT NULL,
  `loccode` char(5) NOT NULL DEFAULT '',
  `requiredby` date NOT NULL DEFAULT '0000-00-00',
  `startdate` date NOT NULL DEFAULT '0000-00-00',
  `costissued` double NOT NULL DEFAULT '0',
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`wo`),
  KEY `LocCode` (`loccode`),
  KEY `StartDate` (`startdate`),
  KEY `RequiredBy` (`requiredby`),
  CONSTRAINT `worksorders_ibfk_1` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.workorders: ~0 rows (approximately)
/*!40000 ALTER TABLE `workorders` DISABLE KEYS */;
/*!40000 ALTER TABLE `workorders` ENABLE KEYS */;


-- Dumping structure for table weberpcn.woserialnos
DROP TABLE IF EXISTS `woserialnos`;
CREATE TABLE IF NOT EXISTS `woserialnos` (
  `wo` int(11) NOT NULL,
  `stockid` varchar(20) NOT NULL,
  `serialno` varchar(30) NOT NULL,
  `quantity` double NOT NULL DEFAULT '1',
  `qualitytext` text NOT NULL,
  PRIMARY KEY (`wo`,`stockid`,`serialno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.woserialnos: ~0 rows (approximately)
/*!40000 ALTER TABLE `woserialnos` DISABLE KEYS */;
/*!40000 ALTER TABLE `woserialnos` ENABLE KEYS */;


-- Dumping structure for table weberpcn.www_users
DROP TABLE IF EXISTS `www_users`;
CREATE TABLE IF NOT EXISTS `www_users` (
  `userid` varchar(20) NOT NULL DEFAULT '',
  `password` text NOT NULL,
  `realname` varchar(35) NOT NULL DEFAULT '',
  `customerid` varchar(10) NOT NULL DEFAULT '',
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `salesman` char(3) NOT NULL,
  `phone` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(55) DEFAULT NULL,
  `defaultlocation` varchar(5) NOT NULL DEFAULT '',
  `fullaccess` int(11) NOT NULL DEFAULT '1',
  `cancreatetender` tinyint(1) NOT NULL DEFAULT '0',
  `lastvisitdate` datetime DEFAULT NULL,
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `pagesize` varchar(20) NOT NULL DEFAULT 'A4',
  `modulesallowed` varchar(40) NOT NULL DEFAULT '',
  `blocked` tinyint(4) NOT NULL DEFAULT '0',
  `displayrecordsmax` int(11) NOT NULL DEFAULT '0',
  `theme` varchar(30) NOT NULL DEFAULT 'fresh',
  `language` varchar(10) NOT NULL DEFAULT 'en_GB.utf8',
  `pdflanguage` tinyint(1) NOT NULL DEFAULT '0',
  `department` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`),
  KEY `CustomerID` (`customerid`),
  KEY `DefaultLocation` (`defaultlocation`),
  CONSTRAINT `www_users_ibfk_1` FOREIGN KEY (`defaultlocation`) REFERENCES `locations` (`loccode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table weberpcn.www_users: ~1 rows (approximately)
/*!40000 ALTER TABLE `www_users` DISABLE KEYS */;
INSERT INTO `www_users` (`userid`, `password`, `realname`, `customerid`, `supplierid`, `salesman`, `phone`, `email`, `defaultlocation`, `fullaccess`, `cancreatetender`, `lastvisitdate`, `branchcode`, `pagesize`, `modulesallowed`, `blocked`, `displayrecordsmax`, `theme`, `language`, `pdflanguage`, `department`) VALUES
	('admin', '$2y$10$e7xelMwFyXZd.Ap5ssqDhutlfNIY3yvckOYFb9EP0vSGG.HkuCVQm', '张三', '', '', '', '', 'admin@weberp.org', 'MEL', 8, 1, '2013-11-18 07:50:02', '', 'A4', '1,1,1,1,1,1,1,1,1,1,1,', 0, 50, 'fluid', 'zh_CN.utf8', 2, 0);
/*!40000 ALTER TABLE `www_users` ENABLE KEYS */;
SET FOREIGN_KEY_CHECKS=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
