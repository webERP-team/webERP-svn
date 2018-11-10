<?php
/* This table of contents allows the choice to display one section or select multiple sections to format for print.
     Selecting multiple sections is for printing
-->

<!-- The individual topics in the manual are in straight html files that are called along with the header and foot from here.
     No style, inline style or style sheet on purpose.
     In this way the help can be easily broken into sections for online context-sensitive help.
		 The only html used in them are:
		 <br />
		 <div>
		 <table>
		 <font>
		 <b>
		 <u>
		 <ul>
		 <ol>

		 Comments beginning with Help Begin and Help End denote the beginning and end of a section that goes into the online help.
		 What section is named after Help Begin: and there can be multiple sections separated with a comma.
-->';*/
// $PageSecurity=1;
$PathPrefix='../../';
//include($PathPrefix.'includes/session.php');

include('ManualHeader.html');
?>
	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'); ?>" method="POST">
<?php
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (((!isset($_POST['Submit'])) AND (!isset($_GET['ViewTopic']))) OR
     ((isset($_POST['Submit'])) AND (isset($_POST['SelectTableOfContents'])))) {
// if not submittws then coming into manual to look at TOC
// if SelectTableOfContents set then user wants it displayed
?>
<?php
  if (!isset($_POST['Submit'])) {
?>
          <input type="submit" name="Submit" value="显示选取">
					点击下面的链接查看。点击核取框，然后会显示要打印的格式
					<br /><br /><br />
<?php
  }
?>
    <table cellpadding="0" cellspacing="0">
      <tr>
        <td>
<?php
  if (!isset($_POST['Submit'])) {
?>
  	      <input type="checkbox" name="SelectTableOfContents">
<?php
  }
?>
          <font size="+3"><b>内容</b></font>
          <br /><br />
          <UL>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectIntroduction">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Introduction'; ?>">简介</A>
<?php
  } else {
?>
              <A href="#Introduction">简介</A>
<?php
	}
?>
              <UL>
                <LI>为什么要选另一款会计软件?</LI>
              </UL>
              <br />
            </LI>
						<LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectRequirements">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Requirements'; ?>">要求</A>
<?php
  } else {
?>
              <A href="#Requirements">要求</A>
<?php
	}
?>
              <UL>
                <LI>硬件要求</LI>
                <LI>软件要求</LI>
                <LI>将webERP与维基整合</LI>
              </UL>
              <br />
            </LI>
						<LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectGettingStarted">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=GettingStarted'; ?>">初识webERP</A>
<?php
  } else {
?>
              <A HREF="#GettingStarted">启航</A>
<?php
  }
?>
              <UL>
                <LI>前提条件</LI>
                <LI>复制PHP脚本</LI>
                <LI>创建数据库</LI>
                <LI>编辑config.php</LI>
                <LI>第一次登录</LI>
                <LI>皮肤和用户画面变更</LI>
                <LI>设置用户</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectSecuritySchema">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=SecuritySchema'; ?>">安全计划</A>
<?php
  } else {
?>
              <A HREF="#SecuritySchema">安全计划</A>
<?php
  }
?>
            </LI>
            <br /><br />
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectCreatingNewSystem">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=CreatingNewSystem'; ?>">创建一个新系统</A>
<?php
  } else {
?>
              <A HREF="#CreatingNewSystem">创建一个新系统</A>
<?php
  }
?>
              <UL>
                <LI>运行演示数据库</LI>
                <LI>设置系统</LI>
                <LI>设置库存商品</LI>
                <LI>输入库存余额</LI>
                <LI>库存总帐整合到总帐</LI>
                <LI>设置顾客</LI>
                <LI>输入顾客余额</LI>
                <LI>应收账款核对</LI>
                <LI>结尾</LI>
              </UL>
              <br />
						</LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectSystemConventions">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=SystemConventions'; ?>">系统惯例</A>
<?php
  } else {
?>
              <A HREF="#SystemConventions">系统惯例</A>
<?php
  }
?>
              <UL>
                <LI>菜单导航</LI>
                <LI>报告</LI>
              </UL>
              <br />
            </LI>
						<LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectInventory">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Inventory'; ?>">库存 (aka "存货")</A>
<?php
  } else {
?>
              <A HREF="#Inventory">库存 (aka "存货")</A>
<?php
  }
?>
              <UL>
                <LI>概述</LI>
                <LI>库存系统特性</LI>
                <LI>库存种类</LI>
                <LI>增加库存商品</LI>
                <LI>商品代码</LI>
                <LI>部品描述</LI>
                <LI>种类</LI>
                <LI>计量单位</LI>
                <LI>经济订购数量</LI>
                <LI>包装体积</LI>
                <LI>包装重量</LI>
                <LI>计量单位</LI>
                <LI>当前或淘汰</LI>
                <LI>制造或者购买</LI>
                <LI>设置组装商品</LI>
                <LI>受控</LI>
                <LI>序列化</LI>
                <LI>条码</LI>
                <LI>折扣种类</LI>
                <LI>小数位数</LI>
                <LI>库存成本</LI>
                <LI>物料成本</LI>
                <LI>劳力成本</LI>
                <LI>制造费用</LI>
                <LI>标准成本考量</LI>
                <LI>实际成本</LI>
                <LI>变更劳力成本，物料成本或者制造费用</LI>
                <LI>选择库库存商品</LI>
                <LI>修改库存商品</LI>
                <LI>修改库存种类</LI>
                <LI>修改为制造或者购买标识</LI>
                <LI>库存种类</LI>
                <LI>库存种类代码</LI>
                <LI>库存种类描述</LI>
                <LI>资产负债表库存总帐账户</LI>
                <LI>库存调整总帐过账账户</LI>
                <LI>采购价格差异账户</LI>
                <LI>物料用量差异账户</LI>
                <LI>资源类型</LI>
                <LI>库存地点维护</LI>
                <LI>库存调整</LI>
                <LI>库存地点转移</LI>
                <LI>库存报告和查询</LI>
                <LI>库存状态查询</LI>
                <LI>库存变化查询</LI>
                <LI>库存用量查询</LI>
                <LI>库存价值报告</LI>
                <LI>库存计划报告</LI>
                <LI>库存盘点</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectAccountsReceivable">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=AccountsReceivable'; ?>">应收账款</A>
<?php
  } else {
?>
              <A HREF="#AccountsReceivable">应收账款</A>
<?php
  }
?>
              <UL>
                <LI>概述</LI>
                <LI>特性</LI>
                <LI>输入新顾客</LI>
                <LI>顾客代码</LI>
                <LI>顾客名称</LI>
                <LI>地址行 1, 2, 3 和 4</LI>
                <LI>货币</LI>
                <LI>发票折扣</LI>
                <LI>即付折扣</LI>
                <LI>顾客交易起始日</LI>
                <LI>付款条件</LI>
                <LI>信用状况或评级</LI>
                <LI>信用额度</LI>
                <LI>发票地址</LI>
                <LI>输入顾客分公司</LI>
                <LI>分公司名称</LI>
                <LI>分公司代码</LI>
                <LI>分公司联络人/电话/传真/地址</LI>
                <LI>销售人员</LI>
                <LI>提货仓库</LI>
                <LI>Forward Date From A Day In The Month</LI>
                <LI>付运天数</LI>
                <LI>电话/传真/电邮</LI>
                <LI>税收当局</LI>
                <LI>停止交易</LI>
                <LI>默认运输公司</LI>
                <LI>邮件地址1, 2, 3 和 4</LI>
                <LI>修改顾客细节</LI>
                <LI>承运人</LI>
              </UL>
              <br />
            </LI>
            <LI>

<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectAccountsPayable">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=AccountsPayable'; ?>">应付账款</A>
<?php
  } else {
?>
              <A HREF="#AccountsPayable">应付账款</A>
<?php
  }
?>
              <UL>
                <LI>概述</LI>
                <LI>特性</LI>
                <LI>输入新供应商</LI>
                <LI>供应商代码</LI>
                <LI>供应商名称</LI>
                <LI>地址行1，2，3和4</LI>
                <LI>供应商交易起始日</LI>
                <LI>付款条件</LI>
                <LI>Bank Particulars/Reference</LI>
                <LI>银行账户号码</LI>
                <LI>货币</LI>
		<LI>汇款通知</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectSalesPeople">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=SalesPeople'; ?>">销售人员</A>
<?php
  } else {
?>
              <A HREF="#SalesPeople">销售人员</A>
<?php
  }
?>
              <UL>
                <LI>销售人员记录</LI>
                <LI>销售人员代码</LI>
                <LI>销售人员名称，电话，和传真</LI>
                <LI>销售人员佣金率和折扣点</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectCurrencies">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Currencies'; ?>">货币</A>
<?php
  } else {
?>
              <A HREF="#Currencies">货币</A>
<?php
  }
?>
              <UL>
                <LI>货币缩写</LI>
                <LI>货币名称</LI>
                <LI>货币国家</LI>
                <LI>货币百分单位名称</LI>
                <LI>汇率</LI>
              </UL>
              <br />
            </LI>
            <LI>

<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectSalesTypes">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=SalesTypes'; ?>">销售类型 / 价格表</A>
<?php
  } else {
?>
              <A HREF="#SalesTypes">销售类型/价格表</A>
<?php
  }
?>
              <UL>
                <LI>销售类型/ 价格表</LI>
                <LI>销售类型代码</LI>
                <LI>销售类型描述</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectPaymentTerms">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=PaymentTerms'; ?>">付款条件</A>
<?php
  } else {
?>
              <A HREF="#PaymentTerms">付款条件</A>
<?php
  }
?>
              <UL>
                <LI>付款条件</LI>
                <LI>付款条件代码</LI>
                <LI>付款条件描述</LI>
                <LI>到期天数/下个月某天到期</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectCreditStatus">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=CreditStatus'; ?>">信用状况</A>
<?php
  } else {
?>
              <A HREF="#CreditStatus">信用状况</A>
<?php
  }
?>
              <UL>
                <LI>信用状况评级</LI>
                <LI>信用状况代码</LI>
                <LI>状况描述</LI>
                <LI>禁止开发票</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectTax">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Tax'; ?>">税收</A>
<?php
  } else {
?>
              <A HREF="#Tax">税收</A>
<?php
  }
?>
              <UL>
                <LI>税收计算</LI>
                <LI>概述</LI>
                <LI>设置税收</LI>
                <LI>一个税务机关内的销售例子--2个税收水平:</LI>
                <LI>一个税务机关内销售的例子--3个税收水平:</LI>
                <LI>两个税务机关内销售的例子--3个税收水平:</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectPrices">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Prices'; ?>">价格和折扣</A>
<?php
  } else {
?>
              <A HREF="#Prices">价格和折扣</A>
<?php
  }
?>
              <UL>
                <LI>价格和折扣</LI>
                <LI>定价概述</LI>
                <LI>维护价格</LI>
                <LI>折扣矩阵</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectARTransactions">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=ARTransactions'; ?>">应收账款交易</A>
<?php
  } else {
?>
              <A HREF="#ARTransactions">应收账款交易</A>
<?php
  }
?>
              <UL>
                <LI>为订单开发票</LI>
                <LI>选择要开发票的订单</LI>
                <LI>从选择的订单生成发票</LI>
                <LI>红字发票</LI>
                <LI>收款输入</LI>
                <LI>收款 - 顾客</LI>
                <LI>收款 - 日期</LI>
                <LI>收款 - 货币和汇率</LI>
                <LI>收款 - 付款方式</LI>
                <LI>收款 - 金额</LI>
                <LI>收款 - 折扣</LI>
                <LI>收款 - 分配至发票</LI>
                <LI>汇率差异</LI>
                <LI>收款处理</LI>
                <LI>存款列表</LI>
                <LI>分配红字款项给顾客</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectARInquiries">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=ARInquiries'; ?>">应收账款查询</A>
<?php
  } else {
?>
              <A HREF="#ARInquiries">应收账款查询</A>
<?php
  }
?>
              <UL>
                <LI>顾客查询</LI>
                <LI>顾客账户查询</LI>
                <LI>交易细节查询</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectARReports">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=ARReports'; ?>">应收账款报告</A>
<?php
  } else {
?>
              <A HREF="#ARReports">应收账款报告</A>
<?php
  }
?>
              <UL>
                <LI>顾客 - 报告</LI>
                <LI>过期顾客账户表</LI>
                <LI>顾客对账单</LI>
                <LI>顾客交易列表选项</LI>
                <LI>打印发票或红字发票</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectSalesAnalysis">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=SalesAnalysis'; ?>">销售分析</A>
<?php
  } else {
?>
              <A HREF="#SalesAnalysis">销售分析</A>
<?php
  }
?>
              <UL>
                <LI>销售分析</LI>
                <LI>销售分析报告表头</LI>
                <LI>销售分析报告栏</LI>
                <LI>自动销售报告</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectSalesOrders">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=SalesOrders'; ?>">销售订单</A>
<?php
  } else {
?>
              <A HREF="#SalesOrders">销售订单</A>
<?php
  }
?>
              <UL>
                <LI>销售订单</LI>
                <LI>销售订单功能</LI>
                <LI>输入销售订单</LI>
                <LI>销售订单 - 选择顾客和分公司</LI>
                <LI>选择订单行商品</LI>
                <LI>交付细节</LI>
                <LI>修改订单</LI>
				<LI>报价单</LI>
				<LI>周期订单</LI>
				<LI>柜台销售 - 直接输入销售</LI>
				<LI>根据产品组或者顾客组（矩阵）管理折扣</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="PurchaseOrdering">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=PurchaseOrdering'; ?>">采购订单</A>
<?php
  } else {
?>
              <A HREF="#Shipments">采购订单</A>
<?php
  }
?>
              <UL>
                <LI>概述</LI>
                <LI>采购订单</LI>
                <LI>增加新采购订单</LI>
                <LI>采购订单授权 </LI>
                <LI>采购订单收货</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectShipments">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Shipments'; ?>">运输</A>
<?php
  } else {
?>
              <A HREF="#Shipments">运输</A>
<?php
  }
?>
              <UL>
                <LI>运输</LI>
                <LI>运输总帐过账</LI>
                <LI>创建运输</LI>
                <LI>运输成本</LI>
                <LI>关闭运输</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectContractCosting">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Contracts'; ?>">合同成本</A>
<?php
  } else {
?>
              <A HREF="#Contracts">合同成本</A>
<?php
  }
?>
              <UL>
                <LI>合同成本概述</LI>
                <LI>创建新合同</LI>
                <LI>选择合同</LI>
                <LI>合同付费</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectManufacturing">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Manufacturing'; ?>">制造</A>
<?php
  } else {
?>
              <A HREF="#Manufacturing">制造</A>
<?php
  }
?>
              <UL>
                <LI>制造概述</LI>
                <LI>总帐意义</LI>
                <LI>工单输入</LI>
                <LI>工单收货</LI>
                <LI>工单发料</LI>
                <LI>关闭工单</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectMRP">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=MRP'; ?>">
              物料需求计划</A>
<?php
  } else {
?>
              <A HREF="#MRP">物料需求计划</A>
<?php
  }
?>
              <UL>
                <LI>MRP 概述</LI>
                <LI>基础数据需求</LI>
                <LI>生产日历</LI>
                <LI>主（生产）计划</LI>
                <LI>运行MRP计算</LI>
                <LI>工作原理</LI>
                <LI>MRP 报告</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectGeneralLedger">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=GeneralLedger'; ?>">总帐</A>
<?php
  } else {
?>
              <A HREF="#GeneralLedger">总帐</A>
<?php
  }
?>
              <UL>
                <LI>总帐概述</LI>
                <LI>科目组</LI>
                <LI>银行账户</LI>
                <LI>银行账户付款</LI>
                <LI>总帐整合设置</LI>
                <LI>销售分录</LI>
                <LI>库存分录</LI>
                <LI>EDI</LI>
                <LI>EDI设置</LI>
                <LI>发送 EDI 发票</LI>
              </UL>
              <br />
            </LI>
            <LI>
 <?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectFixedAssets">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=FixedAssets'; ?>">固定资产</A>
<?php
  } else {
?>
              <A HREF="#Fixed Assets">固定资产</A>
<?php
  }
?>
              <UL>
                <LI>固定资产概述</LI>
                <LI>创建固定资产</LI>
                <LI>选择固定资产</LI>
                <LI>运行折旧</LI>
                <LI>固定资产计划</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectReportBuilder">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=ReportBuilder'; ?>">自定义SQL报告工具</A>
<?php
  } else {
?>
              <A HREF="#ReportBuilder">报告工具</A>
<?php
  }
?>
              <UL>
                <LI>报告工具介绍</LI>
                <LI>报告管理</LI>
                <LI>导入导出报告</LI>
                <LI>报告的编辑 复制 重命名</LI>
                <LI>创建一个新报告 - 识别</LI>
                <LI>创建新报告 - 页面设置</LI>
                <LI>创建新报告 - 指定数据库表和链接</LI>
                <LI>创建新报告 - 指定查询字段</LI>
                <LI>创建新报告 - 输入和安排条件</LI>
                <LI>查看报告</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="PettyCash">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=PettyCash'; ?>">小额现金管理系统</A>
<?php
  } else {
?>
              <A HREF="#PettyCash">小额现金管理系统</A>
<?php
  }
?>
              <UL>
                <LI>概述</LI>
                <LI>设置基本参数</LI>

              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectMultilanguage">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Multilanguage'; ?>">多语言</A>
<?php
  } else {
?>
              <A HREF="#Multilanguage">多语言</A>
<?php
  }
?>
              <UL>
                <LI>多语言简介</LI>
                <LI>重建系统默认语言文件</LI>
                <LI>为系统增加新语言</LI>
                <LI>编辑语言文件头</LI>
                <LI>编辑语言文件模块</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectSpecialUtilities">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=SpecialUtilities'; ?>">特殊工具</A>
<?php
  } else {
?>
              <A HREF="#SpecialUtilities">特殊工具</A>
<?php
  }
?>
              <UL>
                <LI>以新标准成本进行销售分析</LI>
                <LI>改变顾客代码</LI>
                <LI>改变库存代码</LI>
                <LI>创建库存地点</LI>
                <LI>重新过账指定账期总帐</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectNewScripts">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=NewScripts'; ?>">研发 - 基础</A>
<?php
  } else {
?>
              <A HREF="#NewScripts">研发 - 基础</A>
<?php
  }
?>
              <UL>
                <LI>路径结构</LI>
                <LI>session.php</LI>
                <LI>header.php</LI>
                <LI>footer.php</LI>
                <LI>config.php</LI>
                <LI>PDFStarter.php</LI>
                <LI>数据库抽象层 - ConnectDB.inc</LI>
                <LI>DateFunctions.inc</LI>
                <LI>SQL_CommonFuctions.inc</LI>
              </UL>
              <br />
            </LI>
            <LI>





<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectAPI">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=API'; ?>">研发 - API</A>
<?php
  } else {
?>
              <A HREF="#API">研发 - API</A>
<?php
  }
?>
              <br />
              <br />
            </LI>
            <LI>






<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectStructure">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Structure'; ?>">研发 - 结构</A>
<?php
  } else {
?>
              <A HREF="#Structure">研发 - 结构</A>
<?php
  }
?>
              <UL>
                <LI>销售订单</LI>
                <LI>定价</LI>
                <LI>付运成本</LI>
                <LI>查找销售订单</LI>
                <LI>开发票</LI>
                <LI>应收账户/顾客账户</LI>
                <LI>应收账户收款</LI>
                <LI>应收账户分配</LI>
                <LI>销售分析</LI>
                <LI>采购订单</LI>
                <LI>库存</LI>
                <LI>库存查询</LI>
                <LI>应付账户</LI>
                <LI>供应商付款</LI>
              </UL>
              <br />
            </LI>
            <LI>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectContributors">
              <A HREF="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Contributors'; ?>">贡献者 -致谢</A>
<?php
  } else {
?>
              <A HREF="#Contributors">贡献者 - 致谢</A>
<?php
  }
?>
            </LI>
          </UL>
        </td>
      </tr>
    </table>

<?php
}
?>
  </form>
<?php

if (!isset($_GET['ViewTopic'])) {
	$_GET['ViewTopic'] = '';
}

if ($_GET['ViewTopic'] == 'Introduction' OR isset($_POST['SelectIntroduction'])) {
  include('ManualIntroduction.html');
}

if ($_GET['ViewTopic'] == 'Requirements' OR isset($_POST['SelectRequirements'])) {
  include('ManualRequirements.html');
}

if ($_GET['ViewTopic'] == 'GettingStarted' OR isset($_POST['SelectGettingStarted'])) {
  include('ManualGettingStarted.html');
}

if ($_GET['ViewTopic'] == 'SecuritySchema' OR isset($_POST['SelectSecuritySchema'])) {
  include('ManualSecuritySchema.html');
}

if ($_GET['ViewTopic'] == 'CreatingNewSystem' OR isset($_POST['SelectCreatingNewSystem'])) {
  include('ManualCreatingNewSystem.html');
}

if ($_GET['ViewTopic'] == 'SystemConventions' OR isset($_POST['SelectSystemConventions'])) {
  include('ManualSystemConventions.html');
}

if ($_GET['ViewTopic'] == 'Inventory' OR isset($_POST['SelectInventory'])) {
  include('ManualInventory.html');
}

if ($_GET['ViewTopic'] == 'AccountsReceivable' OR isset($_POST['SelectAccountsReceivable'])) {
  include('ManualAccountsReceivable.html');
}

if ($_GET['ViewTopic'] == 'AccountsPayable' OR isset($_POST['SelectAccountsPayable'])) {
  include('ManualAccountsPayable.html');
}

if ($_GET['ViewTopic'] == 'SalesPeople' OR isset($_POST['SelectSalesPeople'])) {
  include('ManualSalesPeople.html');
}
if ($_GET['ViewTopic'] == 'Currencies' OR isset($_POST['Currencies'])) {
  include('ManualCurrencies.html');
}
if ($_GET['ViewTopic'] == 'SalesTypes' OR isset($_POST['SelectSalesTypes'])) {
  include('ManualSalesTypes.html');
}

if ($_GET['ViewTopic'] == 'PaymentTerms' OR isset($_POST['SelectPaymentTerms'])) {
  include('ManualPaymentTerms.html');
}

if ($_GET['ViewTopic'] == 'CreditStatus' OR isset($_POST['SelectCreditStatus'])) {
  include('ManualCreditStatus.html');
}

if ($_GET['ViewTopic'] == 'Tax' OR isset($_POST['SelectTax'])) {
  include('ManualTax.html');
}

if ($_GET['ViewTopic'] == 'Prices' OR isset($_POST['SelectPrices'])) {
  include('ManualPrices.html');
}

if ($_GET['ViewTopic'] == 'ARTransactions' OR isset($_POST['SelectARTransactions'])) {
  include('ManualARTransactions.html');
}

if ($_GET['ViewTopic'] == 'ARInquiries' OR isset($_POST['SelectARInquiries'])) {
  include('ManualARInquiries.html');
}

if ($_GET['ViewTopic'] == 'ARReports' OR isset($_POST['SelectARReports'])) {
  include('ManualARReports.html');
}

if ($_GET['ViewTopic'] == 'SalesAnalysis' OR isset($_POST['SelectSalesAnalysis'])) {
  include('ManualSalesAnalysis.html');
}

if ($_GET['ViewTopic'] == 'SalesOrders' OR isset($_POST['SelectSalesOrders'])) {
  include('ManualSalesOrders.html');
}

if ($_GET['ViewTopic'] == 'PurchaseOrdering' OR isset($_POST['PurchaseOrdering'])) {
  include('ManualPurchaseOrdering.html');
}
if ($_GET['ViewTopic'] == 'Shipments' OR isset($_POST['SelectShipments'])) {
  include('ManualShipments.html');
}
if ($_GET['ViewTopic'] == 'Contracts' OR isset($_POST['SelectContractCosting'])) {
  include('ManualContracts.html');
}
if ($_GET['ViewTopic'] == 'GeneralLedger' OR isset($_POST['SelectGeneralLedger'])) {
  include('ManualGeneralLedger.html');
}
if ($_GET['ViewTopic'] == 'FixedAssets' OR isset($_POST['SelectFixedAssets'])) {
  include('ManualFixedAssets.html');
}
if ($_GET['ViewTopic'] == 'Manufacturing' OR isset($_POST['SelectManufacturing'])) {
  include('ManualManufacturing.html');
}
if ($_GET['ViewTopic'] == 'MRP' OR isset($_POST['SelectMRP'])) {
  include('ManualMRP.html');
}
if ($_GET['ViewTopic'] == 'ReportBuilder' OR isset($_POST['SelectReportBuilder'])) {
  include('ManualReportBuilder.html');
}
if ($_GET['ViewTopic'] == 'PettyCash' OR isset($_POST['PettyCash'])) {
  include('ManualPettyCash.html');
}
if ($_GET['ViewTopic'] == 'Multilanguage' OR isset($_POST['SelectMultilanguage'])) {
  include('ManualMultilanguage.html');
}

if ($_GET['ViewTopic'] == 'SpecialUtilities' OR isset($_POST['SelectSpecialUtilities'])) {
  include('ManualSpecialUtilities.html');
}

if ($_GET['ViewTopic'] == 'NewScripts' OR isset($_POST['SelectNewScripts'])) {
  include('ManualNewScripts.html');
}

if ($_GET['ViewTopic'] == 'API' OR isset($_POST['SelectAPI'])) {
  include('ManualAPIFunctions.php');
}

if ($_GET['ViewTopic'] == 'Structure' OR isset($_POST['SelectStructure'])) {
  include('ManualDevelopmentStructure.html');
}

if ($_GET['ViewTopic'] == 'Contributors' OR isset($_POST['SelectContributors'])) {
  include('ManualContributors.html');
}

include('ManualFooter.html');
