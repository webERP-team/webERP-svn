<?php
//	Z_poAdmin.php
//	Allows for a gettext language po file to be administered
/*	Steve Kitchen
	Up front menu for language file maintenance */

//$PageSecurity = 15;

include ('includes/session.php');
$ViewTopic = 'SpecialUtilities';
$BookMark = 'Z_poAdmin';
$Title = _('Maintain Language Files');
include('includes/header.php');
echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme,
	'/images/maintenance.png" title="', // Icon image.
	$Title, '" /> ', // Icon title.
	$Title, '</p>';// Page title.

/* Check if we have gettext - we're useless without it... */

if (!function_exists('gettext')){
	prnMsg (_('gettext is not installed on this system') . '. ' . _('You cannot use the language files without it'),'error');
	exit;
}

if (!is_writable('./locale/' . $_SESSION['Language'])) {
	prnMsg(_('You do not have write access to the required files please contact your system administrator'),'error');
} else {
	echo '<p><a href="' . $RootPath . '/Z_poRebuildDefault.php">' .   _('Rebuild the System Default Language File') . '</a>';
	echo '<p><a href="' . $RootPath . '/Z_poAddLanguage.php">' . _('Add a New Language to the System')  . '</a>';
	echo '<p><a href="' . $RootPath . '/Z_poEditLangHeader.php">' .  _('Edit a Language File Header') . '</a>';
	echo '<p><a href="' . $RootPath . '/Z_poEditLangModule.php">' .  _('Edit a Language File Module') . '</a>';
	echo '<p><a href="' . $RootPath . '/Z_poEditLangRemaining.php">' .  _('Edit Remaining Strings For This Language') . '</a>';
	echo '<p><a href="' . $RootPath . '/locale/'.$_SESSION['Language'].'/LC_MESSAGES/messages.po">' .  _('Download messages.po file') . '</a>';
	echo '<p><a href="' . $RootPath . '/locale/'.$_SESSION['Language'].'/LC_MESSAGES/messages.mo">' .  _('Download messages.mo file') . '</a>';
}

include('includes/footer.php');
?>
