<!-- This table of contents allows the choice to display one section or select multiple sections to format for print.
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
-->

<?php
//check for XSS
if(strpos($_SERVER['REQUEST_URI'],'/%22%3E%3C')) {
	//if so chop off the XSS code and just return the appropriate URL
	header('Location: ' . 'http' . (($_SERVER['SERVER_PORT'] == 443) ? 's' : '')  . '://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],'/%22%3E%3C')));
}

include('ManualHeader.html');

?>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="post">
<?php
if (((!isset($_POST['Submit'])) AND (!isset($_GET['ViewTopic']))) OR
     ((isset($_POST['Submit'])) AND (isset($_POST['SelectTableOfContents'])))) {
// if not submittws then coming into manual to look at TOC
// if SelectTableOfContents set then user wants it displayed
?>
<?php
  if (!isset($_POST['Submit'])) {
?>
          <input type="submit" name="Submit" value="Markierte anzeigen" /><br/>
					Klicken Sie auf einen Titel, um den Anschnitt anzuzeigen.  Markieren Sie Auswahlkästchen und drücken Sie dann auf "Markierte anzeigen", wenn Sie eine druckfähige Ausgabe erzeugen möchten.
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
  	      <input type="checkbox" name="SelectTableOfContents" />
<?php
  }
?>
          <font size="+3"><b>Inhaltsverzeichnis</b></font>
          <br /><br />
          <ul>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectIntroduction" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Introduction'; ?>">Einleitung</a>
<?php
  } else {
?>
              <a href="#Introduction">Einleitung</a>
<?php
	}
?>
              <ul>
                <li>Warum noch ein Buchhaltungsprogramm?</li>
              </ul>
              <br />
            </li>
						<li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectRequirements" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Requirements'; ?>">Anforderungen</a>
<?php
  } else {
?>
              <a href="#Requirements">Anforderungen</a>
<?php
	}
?>
              <ul>
                <li>Hardware-Anforderungen</li>
                <li>Software-Anforderungen</li>
              </ul>
              <br />
            </li>
						<li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectGettingStarted" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=GettingStarted'; ?>">Inbetriebnahme</a>
<?php
  } else {
?>
              <a href="#GettingStarted">Inbetriebnahme</a>
<?php
  }
?>
              <ul>
                <li>Voraussetzungen</li>
                <li>Die PHP-Scripte kopieren</li>
                <li>Die Datenbank anlegen</li>
                <li>Die Datei config.php bearbeiten</li>
                <li>Erstmalige Anmeldung</li>
                <li>Layouts und GUI-Anpassungen</li>
                <li>Benutzer einrichten</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectSecuritySchema" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=SecuritySchema'; ?>">Sicherheitskonzept</a>
<?php
  } else {
?>
              <a href="#SecuritySchema">Sicherheitskonzept</a>
<?php
  }
?>
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectCreatingNewSystem" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=CreatingNewSystem'; ?>">Ein neues System einrichten</a>
<?php
  } else {
?>
              <a href="#CreatingNewSystem">Ein neues System einrichten</a>
<?php
  }
?>
              <ul>
                <li>Das Demosystem erproben</li>
                <li>Einen Mandanten einrichten</li>
                <li>Materialien einrichten</li>
                <li>Materialbestände einpflegen</li>
                <li>Problematik der Integration der Bestandsführung mit dem Hauptbuch</li>
                <li>Kundenstammdaten erfassen</li>
                <li>Kundensalden aufnehmen</li>
                <li>Das Sammelkonto für Debitorenforderungen</li>
                <li>Zum Schluss</li>
              </ul>
              <br />
						</li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectSystemConventions" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=SystemConventions'; ?>">System-Gepflogenheiten </a>
<?php
  } else {
?>
              <a href="#SystemConventions">System-Gepflogenheiten </a>
<?php
  }
?>
              <ul>
                <li>Navigation im Menü</li>
                <li>Berichtswesen</li>
              </ul>
              <br />
            </li>
						<li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectInventory" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Inventory'; ?>">Bestandsführung</a>
<?php
  } else {
?>
              <a href="#Inventory">Bestandsführung</a>
<?php
  }
?>
              <ul>
                <li>Übersicht</li>
                <li>Merkmale der Bestandsführung</li>
                <li>Neue Materialien anlegen</li>
                <li>Materialnummer</li>
                <li>Materialbezeichnung</li>
                <li>Warengruppe</li>
                <li>Optimale Bestellmenge</li>
                <li>Volumen pro Verpackungseinheit</li>
                <li>Gewicht pro Verpackungseinheit</li>
                <li>Maßeinheiten</li>
                <li>CZeitgemäß oder veraltet </li>
                <li>Materialart</li>
                <li>Sammelmaterialien einrichten</li>
                <li>Chargen-, Seriennummern oder Lose überwachen</li>
                <li>mit Seriennummer</li>
                <li>Barcode</li>
                <li>Rabattgruppe</li>
                <li>Dezimalstellen</li>
                <li>Bestandsbewertung</li>
                <li>Materialkosten</li>
                <li>Arbeitskosten</li>
                <li>Gemeinkosten</li>
                <li>Überlegungen zu den Standardkosten</li>
                <li>Istkosten</li>
                <li>Änderungen an den Arbeitskosten, Materialkosten oder Gemeinkosten</li>
                <li>Materialsuche</li>
                <li>Materialstamm ändern</li>
                <li>Änderung der Warengruppe</li>
                <li>Änderung der Materialart </li>
                <li>Warengruppen</li>
                <li>Warengruppen-Schlüssel</li>
                <li>Warengruppen-Beschreibung</li>
                <li>Konto Bestand</li>
                <li>Konto Bestandskorrekturen</li>
                <li>Konto Einkaufsabweichungen</li>
                <li>Konto Fertigungsabweichungen</li>
                <li>Ressourcentyp</li>
                <li>Betriebsstätten (Lagerorte) pflegen</li>
                <li>Bestandskorrekturen</li>
                <li>Umlagerungen</li>
                <li>Bestandsauswertungen und -berichte</li>
                <li>Auswertung Bestandsstatus</li>
                <li>Auswertung Warenbewegungen</li>
                <li>Auswertung Bestandsverwendung</li>
                <li>Bericht Bestandsbewertung</li>
                <li>Bericht Bestandsplanung</li>
                <li>Inventur</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectAccountsReceivable" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=AccountsReceivable'; ?>">Debitorenbuchhaltung</a>
<?php
  } else {
?>
              <a href="#AccountsReceivable">Debitorenbuchhaltung</a>
<?php
  }
?>
              <ul>
                <li>Übersicht</li>
                <li>Merkmale der Debitorenbuchhaltung</li>
                <li>Neue Kunden anlegen</li>
                <li>Kundennummer</li>
                <li>Kundenname</li>
                <li>Adresszeilen 1, 2, 3, 4, 5 und 6</li>
                <li>Währung</li>
                <li>Rabattsatz</li>
                <li>Skontoprozentsatz</li>
                <li>Kunde seit</li>
                <li>Zahlungsbedingungen</li>
                <li>Kreditstatus</li>
                <li>Kreditlimit</li>
                <li>Rechnung senden an</li>
                <li>Kundenniederlassungen anlegen</li>
                <li>Name der Niederlassung</li>
                <li>Nummer der Niederlassung</li>
                <li>Kontakt / Telefon / Fax / Adresse</li>
                <li>Verkäufer</li>
                <li>bezieht Waren vom Lager</li>
                <li>Vordatieren nach dem (Tag im Monat)</li>
                <li>Lieferfrist Tage</li>
                <li>Telefon/Fax/Email</li>
                <li>Steuergruppe</li>
                <li>Aufträge für diese Niederlassung</li>
                <li>Übliche Versandart</li>
                <li>Postanschrift Zeilen 1, 2, 3 und 4</li>
                <li>Änderungen an den Kundendaten</li>
                <li>Versandarten</li>
              </ul>
              <br />
            </li>
            <li>

<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectAccountsPayable" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=AccountsPayable'; ?>">Kreditorenbuchhaltung</a>
<?php
  } else {
?>
              <a href="#AccountsPayable">Kreditorenbuchhaltung</a>
<?php
  }
?>
              <ul>
                <li>Übersicht</li>
                <li>Merkmale der Kreditorenbuchhaltung</li>
                <li>Lieferanten (Kreditoren) anlegen</li>
                <li>Lieferantennummer</li>
                <li>Lieferantenname</li>
                <li>Adresszeilen 1, 2, 3 und 4</li>
                <li>Lieferant seit </li>
                <li>Zahlungsbedingung</li>
                <li>Bankangaben, Bankreferenz</li>
                <li>Bankkontonummer</li>
                <li>Lieferantenwährung</li>
		<li>Zahlungsmitteilung</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectSalesPeople" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=SalesPeople'; ?>">Verkäufer</a>
<?php
  } else {
?>
              <a href="#SalesPeople">Verkäufer</a>
<?php
  }
?>
              <ul>
                <li>Verkäuferstammdaten</li>
                <li>Verkäufer-Schlüssel</li>
                <li>Kommunikationsdaten</li>
                <li>Provisionssätze und Grenzbetrag</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectSalesTypes" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=SalesTypes'; ?>">Umsatzarten/Preislisten</a>
<?php
  } else {
?>
              <a href="#SalesTypes">Umsatzarten/Preislisten</a>
<?php
  }
?>
              <ul>
                <li>Umsatzart-ID</li>
                <li>Umsatzart-Bezeichnung</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectPaymentTerms" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=PaymentTerms'; ?>">Zahlungsbedingungen</a>
<?php
  } else {
?>
              <a href="#PaymentTerms">Zahlungsbedingungen</a>
<?php
  }
?>
              <ul>
                <li>ZB-Schlüssel</li>
                <li>Beschreibung der Zahlungsbedingung</li>
                <li>Fällig nach Anzahl Tage / Tage oder Tag im Folgemonat</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectCreditStatus" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=CreditStatus'; ?>">Kreditstatus</a>
<?php
  } else {
?>
              <a href="#CreditStatus">Kreditstatus</a>
<?php
  }
?>
              <ul>
                <li>Credit Status Ratings</li>
                <li>Status-Schlüssel</li>
                <li>Beschreibung</li>
                <li>Fakturasperre</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectTax" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Tax'; ?>">Steuern</a>
<?php
  } else {
?>
              <a href="#Tax">Steuern</a>
<?php
  }
?>
              <ul>
                <li>Steuerberechnungen</li>
                <li>Überblick</li>
                <li>Steuern einrichten</li>
                <li>Beispiel 1: Eine Verkaufssteuer innerhalb eines Steuerstandortes - Zwei Steuerkategorien</li>
                <li>Beispiel 2: Verkauf innerhalb eines Steuerstandortes - drei Steuersätze</li>
                <li>Beispiel 3: Verkauf zwischen zwei Steuerstandorten - drei Steuersätze</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectPrices" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Prices'; ?>">Preise und Rabatte</a>
<?php
  } else {
?>
              <a href="#Prices">Preise und Rabatte</a>
<?php
  }
?>
              <ul>
                <li>Preise und Rabatte</li>
                <li>Übersicht</li>
                <li>Preise pflegen</li>
                <li>Rabattstaffel</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectARTransactions" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=ARTransactions'; ?>">Debitorenbuchungen</a>
<?php
  } else {
?>
              <a href="#ARTransactions">Debitorenbuchungen</a>
<?php
  }
?>
              <ul>
                <li>Fakturieren eines Kundenauftrages</li>
                <li>Auftrag zum Fakturieren auswählen</li>
                <li>Erstellen der Faktura zum Kundenauftrag</li>
                <li>Gutschriften</li>
                <li>Erfassung von Zahlungseingängen</li>
                <li>Zahlungseingang - Debitor<ImportBankTrans.php/li>
                <li>Zahlungseingang - Datum</li>
                <li>Zahlungseingang - Währung und Umrechnungskurs</li>
                <li>Zahlungseingang - Zahlweg</li>
                <li>Zahlungseingang - Betrag</li>
                <li>Zahlungseingang - Skonto</li>
                <li>Zahlungseingang - Ausgleichen mit der Rechnung</li>
                <li>Kursdifferenzen</li>
                <li>Zahlungseingänge verbuchen</li>
                <li>Liste der Zahlungseingänge</li>
                <li>Habenbeträge dem Debitorenkonto zuordnen</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectARInquiries" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=ARInquiries'; ?>">Debitorenauswertungen</a>
<?php
  } else {
?>
              <a href="#ARInquiries">Debitorenauswertungen</a>
<?php
  }
?>
              <ul>
                <li>Kundenauswertungen</li>
                <li>Auswertung des Debitorenkontos</li>
                <li>Beleganzeige</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectARReports" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=ARReports'; ?>">Debitorenberichte</a>
<?php
  } else {
?>
              <a href="#ARReports">Debitorenberichte</a>
<?php
  }
?>
              <ul>
                <li>Customers - Reporting</li>
                <li>Gerasterte Debitorensalden</li>
                <li>Kontoauszüge</li>
                <li>Auswertung der Kundenvorgänge</li>
                <li>Rechnungen und Gutschriften drucken</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectSalesAnalysis" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=SalesAnalysis'; ?>">Umsatzauswertungen</a>
<?php
  } else {
?>
              <a href="#SalesAnalysis">Umsatzauswertungen</a>
<?php
  }
?>
              <ul>
                <li>Umsatzauswertungen</li>
                <li>Kopf des Ergebnisberichtes</li>
                <li>Spalten der Ergebnisberichte</li>
                <li>Automatisierung der Ergebnisberichte</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectSalesOrders" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=SalesOrders'; ?>">Kundenaufträge</a>
<?php
  } else {
?>
              <a href="#SalesOrders">Kundenaufträge</a>
<?php
  }
?>
              <ul>
                <li>Kundenaufträge</li>
                <li>Funktionalität</li>
                <li>Kundenaufträge erfassen</li>
                <li>Auswahl des Kunden und der Niederlassung</li>
                <li>Auswahl der Kundenauftragspositionen</li>
                <li>Lieferangaben</li>
                <li>Kundenauftrag ändern</li>
		<li>Angebote</li>
		<li>Daueraufträge</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectShipments" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Shipments'; ?>">Transportkosten</a>
<?php
  } else {
?>
              <a href="#Shipments">Transportkosten</a>
<?php
  }
?>
              <ul>
                <li>Transportkosten</li>
                <li>Buchung der Transportkosten im Hauptbuch</li>
                <li>Transport anlegen</li>
                <li>Transportkalkulation</li>
                <li>Abrechnung eines Transportes</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectManufacturing" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Manufacturing'; ?>">Fertigung</a>
<?php
  } else {
?>
              <a href="#Manufacturing">Fertigung</a>
<?php
  }
?>
              <ul>
                <li>Fertigung Überblick</li>
                <li>Hauptbuch-Integration</li>
                <li>Fertigungsauftrag anlegen</li>
                <li>Ablieferungen zum Fertigungsauftrag</li>
                <li>Entnahmen zum Fertigungsauftrag</li>
                <li>Fertigungsaufträge abschließen</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectGeneralLedger" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=GeneralLedger'; ?>">Hauptbuchhaltung</a>
<?php
  } else {
?>
              <a href="#GeneralLedger">Hauptbuchhaltung</a>
<?php
  }
?>
              <ul>
                <li>Überblick</li>
                <li>Kontengruppen</li>
                <li>Bankkonten</li>
                <li>Zahlungsausgänge</li>
                <li>Einrichten der Hauptbuchintegration</li>
                <li>Umsatzpositionen</li>
                <li>Bestandsbuchungen</li>
                <li>EDI</li>
                <li>EDI einrichten</li>
                <li>Versenden von EDI-Rechnungen</li>
              </ul>
              <br />
            </li>
            <li>
<?php
if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectReportBuilder" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=ReportBuilder'; ?>">Report Builder/Form Builder</a>
<?php
  } else {
?>
              <a href="#ReportBuilder">Report Builder/Form Builder</a>
<?php
  }
?>
              <ul>
                <li>Einführung</li>
                <li>Reports Administration</li>
                <li>Importing and Exporting Reports</li>
                <li>Editing Copying Renaming Reports</li>
                <li>Creating A New Report - Identification</li>
                <li>Creating A New Report - Page Setup</li>
                <li>Creating A New Report - Specifying Database Tables and Links</li>
                <li>Creating A New Report - Specifying fields to Retrieve</li>
                <li>Creating A New Report - Entering and Arranging Criteria</li>
                <li>Viewing Reports</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectMultilanguage" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Multilanguage'; ?>">Mehrsprachigkeit</a>
<?php
  } else {
?>
              <a href="#Multilanguage">Mehrsprachigkeit</a>
<?php
  }
?>
              <ul>
                <li>Mehrsprachigkeit</li>
                <li>Die System-Sprachdatei neu erstellen</li>
                <li>Eine neue Sprache zum System hinzufügen</li>
                <li>Sprachdatei-Kopf bearbeiten</li>
                <li>Sprachdatei-Module bearbeiten</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectSpecialUtilities" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=SpecialUtilities'; ?>">Servicewerkzeuge</a>
<?php
  } else {
?>
              <a href="#SpecialUtilities">Servicewerkzeuge</a>
<?php
  }
?>
              <ul>
                <li>Ergebnisrechnungssätze zu Standardkosten neu bewerten</li>
                <li>Eine Kundennummer ändern</li>
                <li>Eine Materialnummer ändern</li>
                <li>Bestandsdatensätze erzeugen</li>
                <li>Hauptbuchsalden nachbuchen</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectNewScripts" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=NewScripts'; ?>">Entwicklung - Grundlagen</a>
<?php
  } else {
?>
              <a href="#NewScripts">Entwicklung - Grundlagen</a>
<?php
  }
?>
              <ul>
                <li>Verzeichnisstruktur</li>
                <li>session.php</li>
                <li>header.php</li>
                <li>footer.php</li>
                <li>config.php</li>
                <li>PDFStarter.php</li>
                <li>Datenbank-Abstraktionsschicht - ConnectDB.inc</li>
                <li>DateFunctions.inc</li>
                <li>SQL_CommonFuctions.inc</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectStructure" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Structure'; ?>">Entwicklung - Struktur</a>
<?php
  } else {
?>
              <a href="#Structure">Entwicklung - Struktur</a>
<?php
  }
?>
              <ul>
                <li>Kundenaufträge</li>
                <li>Preisfestlegung</li>
                <li>Lieferangaben und Versandkosten</li>
                <li>Kundenaufträge suchen</li>
                <li>Faktura</li>
                <li>Forderungen / Debitorenkonten</li>
                <li>Debitoren-Zahlungseingänge</li>
                <li>Debitoren-Ausgleich</li>
                <li>Umsatzauswertungen</li>
                <li>Bestellungen</li>
                <li>Bestand</li>
                <li>Bestandsauswertungen</li>
                <li>Kreditoren</li>
                <li>Kreditorenzahlungen</li>
              </ul>
              <br />
            </li>
            <li>
<?php
  if (!isset($_POST['Submit'])) {
?>
              <input type="checkbox" name="SelectContributors" />
              <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?ViewTopic=Contributors'; ?>">Mitwirkende - Anerkennungen</a>
<?php
  } else {
?>
              <a href="#Contributors">Mitwirkende - Anerkennungen</a>
<?php
  }
?>
            </li>
          </ul>
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

if ($_GET['ViewTopic'] == 'Shipments' OR isset($_POST['SelectShipments'])) {
  include('ManualShipments.html');
}

if ($_GET['ViewTopic'] == 'Manufacturing' OR isset($_POST['SelectManufacturing'])) {
  include('ManualManufacturing.html');
}

if ($_GET['ViewTopic'] == 'GeneralLedger' OR isset($_POST['SelectGeneralLedger'])) {
  include('ManualGeneralLedger.html');
}

if ($_GET['ViewTopic'] == 'ReportBuilder' OR isset($_POST['SelectReportBuilder'])) {
  include('ManualReportBuilder.html');
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

if ($_GET['ViewTopic'] == 'Structure' OR isset($_POST['SelectStructure'])) {
  include('ManualDevelopmentStructure.html');
}

if ($_GET['ViewTopic'] == 'Contributors' OR isset($_POST['SelectContributors'])) {
  include('ManualContributors.html');
}

include('ManualFooter.html');
