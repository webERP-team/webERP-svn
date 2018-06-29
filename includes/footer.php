<?php
echo '<div id="MessageContainerFoot">';

if (isset($Messages) and count($Messages) > 0) {
	foreach ($Messages as $Message) {
		$Prefix = $Message[2];
		switch ($Message[1]) {
			case 'error':
				$Class = 'error';
				$Prefix = $Prefix ? $Prefix : _('ERROR') . ' ' . _('Report');
				if (isset($_SESSION['LogSeverity']) and $_SESSION['LogSeverity'] > 3) {
					fwrite($LogFile, date('Y-m-d h-m-s') . ',' . $Type . ',' . $_SESSION['UserID'] . ',' . trim($Msg, ',') . "\n");
				}
				echo '<div class="Message ' . $Class . ' noPrint">
				<span class="MessageCloseButton">&times;</span>
				<b>' . $Prefix . '</b> : ' . $Message[0] . '</div>';
				break;
			case 'warn':
			case 'warning':
				$Class = 'warn';
				$Prefix = $Prefix ? $Prefix : _('WARNING') . ' ' . _('Report');
				if (isset($_SESSION['LogSeverity']) and $_SESSION['LogSeverity'] > 3) {
					fwrite($LogFile, date('Y-m-d h-m-s') . ',' . $Type . ',' . $_SESSION['UserID'] . ',' . trim($Msg, ',') . "\n");
				}
				echo '<div class="Message ' . $Class . ' noPrint">
				<span class="MessageCloseButton">&times;</span>
				<b>' . $Prefix . '</b> : ' . $Message[0] . '</div>';
				break;
			case 'success':
				$Class = 'success';
				$Prefix = $Prefix ? $Prefix : _('SUCCESS') . ' ' . _('Report');
				if (isset($_SESSION['LogSeverity']) and $_SESSION['LogSeverity'] > 3) {
					fwrite($LogFile, date('Y-m-d h-m-s') . ',' . $Type . ',' . $_SESSION['UserID'] . ',' . trim($Msg, ',') . "\n");
				}
				echo '<div class="Message ' . $Class . ' noPrint">
				<span class="MessageCloseButton">&times;</span>
				<b>' . $Prefix . '</b> : ' . $Message[0] . '</div>';
				break;
			case 'info':
			default:
				$Prefix = $Prefix ? $Prefix : _('INFORMATION') . ' ' . _('Message');
				$Class = 'info';
				if (isset($_SESSION['LogSeverity']) and $_SESSION['LogSeverity'] > 2) {
					fwrite($LogFile, date('Y-m-d h-m-s') . ',' . $Type . ',' . $_SESSION['UserID'] . ',' . trim($Msg, ',') . "\n");
				}
				echo '<div class="Message ' . $Class . ' noPrint">
				<span class="MessageCloseButton">&times;</span>
				<b>' . $Prefix . '</b> : ' . $Message[0] . '</div>';
		}
	}
}
echo '</div>'; // eof MessageContainer div
echo '</div>'; // BodyWrapDiv
echo '</div>'; // BodyDiv

echo '<div id="FooterDiv">';
echo '<div id="FooterWrapDiv">';

echo '<div id="FooterLogoDiv">';
	echo '<img src="'. $RootPath . '/' . $_SESSION['LogoFile'] . '" width="120" alt="webERP" title="webERP ' . _('Copyright') . ' &copy; weberp.org - ' . date('Y') . '" />';
echo '</div>';

echo '<div id="FooterTimeDiv">';
	echo DisplayDateTime();
echo '</div>';

echo '<div id="FooterVersionDiv">';
	echo 'webERP ' . _('version') . ' ' . $_SESSION['VersionNumber'] . ' ' . _('Copyright') . ' &copy; 2004 - ' . Date('Y'). ' <a target="_blank" href="http://www.weberp.org/weberp/doc/Manual/ManualContributors.html">weberp.org</a>';
echo '</div>';

echo '</div>'; // FooterWrapDiv
echo '</div>'; // FooterDiv
$BaseName = basename($_SERVER['PHP_SELF']);
$ScriptName = $BaseName.'?'.$_SERVER['QUERY_STRING'];
if ( $BaseName != 'index.php' AND !isset($_SESSION['Favourites'][$ScriptName])) {
	$ShowAdd = '<input type="submit" value="' . _('Add To Menu') . '" name="AddToMenu" />';
} else {
	$ShowAdd = '';
}
if (isset($_SESSION['Favourites'][$ScriptName])) {
	$ShowDel = '<input type="submit" value="' . _('Remove From Menu') . '" name="DelFromMenu" />';
} else {
	$ShowDel = '';
}

echo '<div>
	<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
	<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
	<input type="hidden" name="ScriptName" value="' . htmlspecialchars($ScriptName,ENT_QUOTES,'UTF-8') . '" />
	<input type="hidden" name="Title" value="' . $Title . '" />
	' . $ShowAdd . $ShowDel . '
	</form>
	</div>
';
echo '</div>'; // Canvas
echo '</body>
	</html>';
?>
