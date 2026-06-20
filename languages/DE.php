<?php

declare(strict_types=1);
/**
 * T26 Genesis Boilerplate - WBCE CMS
 * @author      Thodde26 (Thorsten)
 * @link        https://www.thodde26.de
 * @Version     1.1.0
 * @file        DE.php
 * @license     http://www.gnu.org/licenses/gpl.html
 * GNU General Public License v3.0
 */

if (!defined('WB_PATH')) {
  die('Cannot access this file directly');
}

$MOD_T26_GENESIS_BOILERPLATE = [
  'ADMIN_TITLE'        => 'T26 Genesis Boilerplate - Konfiguration',
  'TAB_WELCOME'        => 'Willkommen',
  'TAB_DEMO'           => 'Demo',
  'TAB_THEMES'         => 'Theme-Auswahl',
  'TAB_COLORS'         => 'Custom-Farben',
  'TAB_GRAPHICS'       => 'Grafiken',
  'TAB_EXTENSIONS'     => 'Erweiterungen',
  'TAB_NAV'            => 'Navigation',
  'TAB_COPYRIGHT'      => 'Copyright',
  'TAB_ADVANCED'       => 'Erweitert',
  'TAB_DROPLETS'       => 'Droplets',

  'WELCOME_HEAD'       => '🎉 Willkommen im T26 Admin-Center!',
  'WELCOME_TEXT'       => 'Hier hast du die volle Kontrolle über das Design deines T26 Genesis Themes.',
  'WELCOME_BTN_APPSTORE' => 'App Store ➡️',

  'BOX_STATUS_TITLE'   => '📊 System-Status',
  'BOX_STATUS_THEME'   => 'Aktives Theme:',
  'BOX_STATUS_OK'      => 'Das Modul ist erfolgreich mit der Datenbank verbunden und einsatzbereit.',
  'BOX_STEPS_TITLE'    => '🚀 Erste Schritte',
  'BOX_STEPS_TEXT'     => 'Wähle unter <strong>Theme-Auswahl</strong> ein vorgefertigtes Preset.<br>Mische dir eigene Farben im <strong>Custom-Editor</strong> zusammen.<br>Tausche dein Logo & Bilder im Reiter <strong>Grafiken</strong> aus.',
  'BOX_HELP_TITLE'     => '💡 Hilfe & Entwickler',
  'BOX_HELP_TEXT'      => 'Du brauchst Unterstützung bei der Einrichtung oder möchtest Feedback dalassen?',
  'BOX_HELP_BTN'       => 'Besuche Thodde26.de',

  'THEME_OPT_LIGHT'    => 'Light Mode Presets',
  'THEME_OPT_DARK'     => 'Dark Mode Presets',
  'THEME_OPT_CUSTOM'   => 'Eigene Farbschemata',
  'LABEL_ACTIVE_THEME' => 'Aktives Genesis Theme auswählen',

  'COLORS_TITLE_LIGHT' => '☀️ Light Mode',
  'COLORS_TITLE_DARK'  => '🌙 Dark Mode',
  'COLORS_BTN_RESET'   => '🔄 Standard-Werte laden',

  'TAB_GRAPHICS_LOGOS'       => '🖼️ Logos',
  'TAB_GRAPHICS_MAIN_LOGO'   => 'Haupt-Logo (Light Mode)',
  'TAB_GRAPHICS_MAIN_DESC'   => 'Format: PNG oder SVG.',
  'TAB_GRAPHICS_NO_MAIN'     => 'Kein Haupt-Logo hochgeladen.',
  'TAB_GRAPHICS_DARK_LOGO'   => 'Dark Mode Logo',
  'TAB_GRAPHICS_NO_DARK'     => 'Kein Dark-Mode Logo hochgeladen.',
  'TAB_GRAPHICS_MOBILE_LOGO' => 'Mobile Logo (Optional)',
  'TAB_GRAPHICS_NO_MOBILE'   => 'Kein Mobile Logo hochgeladen.',
  'TAB_GRAPHICS_DELETE'      => '🗑️ Dieses Element restlos löschen',
  'TAB_GRAPHICS_ICONS'       => '📱 App- & Browser-Icons',
  'TAB_GRAPHICS_FAVICON'     => 'Favicon (Browser-Tab)',
  'TAB_GRAPHICS_NO_FAVICON'  => 'Kein Favicon hochgeladen.',
  'TAB_GRAPHICS_APPLE'       => 'Apple Touch Icon',
  'TAB_GRAPHICS_NO_APPLE'    => 'Kein Apple Icon hochgeladen.',
  'GRAPHICS_ALT_PLACEHOLDER' => 'SEO Alt-Text (z.B. Logo)',

  'TAB_HUB_TITLE'       => '🧩 T26 App Store & Hub',
  'TAB_HUB_ONLINE'      => '🟢 Hub Online',
  'TAB_HUB_OFFLINE'     => '🔴 Hub Offline',
  'TAB_HUB_WARNINGS'    => '⚠️ Hub-Warnungen:',
  'TAB_HUB_DESC'        => 'Hier findest du alle offiziellen T26-Module.',
  'TAB_HUB_REPOS_TITLE' => '🔗 Custom Repositories (Drittanbieter)',
  'TAB_HUB_REPOS_DESC'  => 'Füge hier JSON-URLs ein, um deren T26-Module anzuzeigen.',
  'TAB_HUB_REPOS_BTN'   => 'Quellen speichern & Scannen 🔄',
  'TAB_HUB_UPDATE_AVAIL' => 'Update verfügbar!',
  'TAB_HUB_VERSION_TEXT' => 'T26 Genesis Core Version',
  'TAB_HUB_UPDATE_BTN'  => 'Update 🔄',
  'TAB_HUB_CURRENT'     => 'Aktuell',
  'TAB_HUB_MANAGE'      => 'Verwalten ⚙️',
  'TAB_HUB_MASTER'      => 'Master System',
  'TAB_HUB_INSTALL'     => 'Installieren ⬇️',
  'TAB_HUB_CONN_ERROR'  => 'Verbindung zum T26 Hub fehlgeschlagen.',

  'NAV_HEADER'         => 'Header Menü (Oben)',
  'NAV_SIDEBAR_LEFT'   => 'Linke Sidebar (Standard-Menü)',
  'NAV_SIDEBAR_RIGHT'  => 'Rechte Sidebar (Standard-Menü)',
  'NAV_FOOTER'         => 'Footer Menü (Unten)',
  'NAV_SAVE_BTN'       => 'Menü-Zuordnung speichern',
  'NAV_DEFAULT_1'      => '1: Standard Navigation',
  'NAV_DEFAULT_2'      => '2: Footer Navigation',
  'NAV_DEFAULT_99'     => '99: Keine Navigation',
  'NAV_TEMPLATE_UNKNOWN' => 'Unbekannt',
  'NAV_INFO_TEXT'      => 'Die Menü-Namen werden live aus deinem aktiven Template geladen:',
  'NAV_DROPLET_TIP'    => '<strong>Droplet-Tipp:</strong> Nutze <code>[[T26_SidebarMenu?side=left]]</code> oder <code>[[T26_SidebarMenu?side=right]]</code>.',

  'COPYRIGHT_LABEL'        => 'Footer Copyright Text',
  'COPYRIGHT_PLACEHOLDER'  => 'z. B. © 2026 Meine Firma.',
  'COPYRIGHT_DROPLET_INFO' => '<strong>Droplet-Tipp:</strong> Nutze <code>[[T26_Copyright]]</code>.',
  'COPYRIGHT_SAVE_BTN'     => 'Copyright speichern',

  'TAB_ADV_TITLE'        => '🎨 Custom CSS',
  'TAB_ADV_DESC'         => 'Schreibe beliebiges CSS. Es wird automatisch an das Ende der Datei gehängt.',
  'TAB_ADV_BTN_LOAD'     => '⚡ Standard-Variablen laden',
  'TAB_ADV_SELECT_LIGHT' => '☀️ Light Mode CSS',
  'TAB_ADV_SELECT_DARK'  => '🌙 Dark Mode CSS',
  'TAB_ADV_SAVE_BTN'     => 'Speichern (Beide Modi)',

  'TAB_DROPLETS_TITLE'      => '💡 T26 Core-Droplets',
  'TAB_DROPLETS_DESC'       => 'Nutze diese Droplets überall in deinen WBCE-Modulen oder Templates.',
  'DROPLET_CORE_DESC'       => 'Lädt die CSS/JS Assets des Frameworks (Muss in den &lt;head&gt; des Templates).',
  'DROPLET_COPYRIGHT_DESC'  => 'Gibt den Footer-Copyright-Text aus.',
  'DROPLET_MENU_LEFT_DESC'  => 'Lädt das Menü für die linke Sidebar.',
  'DROPLET_MENU_RIGHT_DESC' => 'Lädt das Menü für die rechte Sidebar.',

  'SUPPORT_TITLE'      => 'Unterstütze den Entwickler',
  'SUPPORT_TEXT'       => 'Dir gefällt dieses Modul? Spendier dem Entwickler <strong>Thodde26</strong> einen Kaffee auf Ko-fi!',
  'SUPPORT_BTN'        => 'Support on Ko-fi',

  'BTN_BACK'           => '⬅ Zurück',
  'MODULE_NO_UI'       => 'Das Modul bietet noch keine eigene Verwaltungsoberfläche an.',
  'SAVE_SUCCESS'       => 'Erfolgreich gespeichert.',
  'SAVE_ERROR'         => 'Fehler beim Speichern.',
  'SECURITY_ERROR'     => 'Sicherheitsfehler (FTAN).',

  'JS_CONFIRM_LIGHT'   => "Standard-Variablen für den Light Mode laden?\nAchtung: Dein Code wird überschrieben!",
  'JS_CONFIRM_DARK'    => "Standard-Variablen für den Dark Mode laden?\nAchtung: Dein Code wird überschrieben!"
];
