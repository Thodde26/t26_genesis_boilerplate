<?php

declare(strict_types=1);
/**
 * T26 Genesis Boilerplate - WBCE CMS
 * @author       Thodde26 (Thorsten)
 * @link         https://www.thodde26.de
 * @Version      1.2.0
 * @file         upgrade.php
 * @license      http://www.gnu.org/licenses/gpl.html
 * GNU General Public License v3.0
 */

if (!defined('WB_PATH')) {
  die('Cannot access this file directly');
}

global $database;
$msg = '';

// 🔥 HIER IST DIE MAGIE: Der Ordnername wird automatisch ausgelesen!
$module_dir = basename(__DIR__);

// Dynamische Tabelle
$table_settings = TABLE_PREFIX . 'mod_' . $module_dir . '_settings';

// ── 1. SICHERUNG: TABELLE PRÜFEN & GGF. NEU ANLEGEN ─────────────────────────
$query_check = $database->query("SHOW TABLES LIKE '$table_settings'");

if (!$query_check || $query_check->numRows() === 0) {
  require_once __DIR__ . '/install.php';
  $msg .= "<br>• Sicherung: DB-Tabelle fehlte und wurde per install.php komplett neu erstellt.";
} else {
  // ── 1.1 SICHERUNG: FEHLENDE SETTINGS NACHTRAGEN ───────────────────────────
  $defaultLightColors = json_encode(['primary_base' => '#1a73e8', 'primary_hover' => '#1557b0', 'bg_body' => '#f8f9fa', 'bg_surface' => '#ffffff', 'text_main' => '#334155', 'text_muted' => '#64748b', 'border_color' => '#cbd5e0', 'accent_color' => '#1a73e8']);
  $defaultDarkColors  = json_encode(['primary_base' => '#7A9BDB', 'primary_hover' => '#244684', 'bg_body' => '#121212', 'bg_surface' => '#1a1a1a', 'text_main' => '#e0e0e0', 'text_muted' => '#aaaaaa', 'border_color' => '#333333', 'accent_color' => '#7A9BDB']);

  $required_settings = [
    'active_theme'            => 't26_blue_light',
    'custom_light_colors'     => $defaultLightColors,
    'custom_dark_colors'      => $defaultDarkColors,
    'custom_light_css'        => '',
    'custom_dark_css'         => '',
    'custom_logo'             => '',
    'custom_logo_dark'        => '',
    'custom_logo_mobile'      => '',
    'custom_logo_alt'         => '',
    'custom_favicon'          => '',
    'custom_apple_touch_icon' => '',
    'nav_menu_header'         => '1',
    'nav_menu_sidebar_left'   => '99',
    'nav_menu_sidebar_right'  => '2',
    'nav_menu_footer'         => '2',
    'footer_copyright'        => '',
    'custom_repositories'     => '[]'
  ];

  foreach ($required_settings as $key => $default_val) {
    $key_esc = $database->escapeString($key);
    $val_esc = $database->escapeString((string)$default_val);
    $check_setting = $database->query("SELECT `setting_name` FROM `$table_settings` WHERE `setting_name` = '$key_esc'");

    if ($check_setting && $check_setting->numRows() === 0) {
      $database->query("INSERT INTO `$table_settings` (`setting_name`, `setting_value`) VALUES ('$key_esc', '$val_esc')");
      $msg .= "<br>• Update: Setting '$key_esc' wurde nachträglich integriert.";
    }
  }

  // ── 1.2 SICHERUNG: NEUE SPALTE 'is_active' (Soft-Kill-Switch v1.1) PRÜFEN ──
  $query_col = $database->query("SHOW COLUMNS FROM `$table_settings` LIKE 'is_active'");
  if ($query_col && $query_col->numRows() === 0) {
    $database->query("ALTER TABLE `$table_settings` ADD `is_active` INT(1) NOT NULL DEFAULT 1");
    $msg .= "<br>• Update: Spalte 'is_active' (Soft-Kill-Switch) wurde nachträglich integriert.";
  }
}

// ── 1.3 SICHERUNG: NEUE SPALTE 'core_sync' PRÜFEN ──
$query_sync = $database->query("SHOW COLUMNS FROM `$table_settings` LIKE 'core_sync'");
if ($query_sync && $query_sync->numRows() === 0) {
  $database->query("ALTER TABLE `$table_settings` ADD `core_sync` INT(1) NOT NULL DEFAULT 1");
  $msg .= "<br>• Update: Spalte 'core_sync' (Abhängigkeit) wurde integriert.";
}

// ── 2. DROPLETS AKTUALISIEREN / NACHTRAGEN ──────────────────────────────────
$table_droplets = TABLE_PREFIX . 'mod_droplets';

// 🔥 HIER IST DIE GANZE MAGIE: Türsteher + Test-Text vereint für die upgrade.php
$droplet_code = "global \$database;
\$table = TABLE_PREFIX . 'mod_" . $module_dir . "_settings';
\$query = \$database->query(\"SELECT `is_active` FROM `\$table` LIMIT 1\");

if(\$query && \$query->numRows() > 0) {
    \$settings = \$query->fetchRow();
    // Soft-Kill-Switch: Wenn auf 0, gibt das Droplet einfach GAR NICHTS aus!
    if(\$settings['is_active'] == 0) {
        return '';
    }
}

// Wenn aktiv, lade die Assets und hänge den Test-Text an
require_once(WB_PATH . '/modules/" . $module_dir . "/include.php');
\$assets = t26_get_frontend_assets();
\$mein_text = '<p>Das T26 Boilerplate Droplet funktioniert!</p>';
return \$assets . \$mein_text;";

$t26_droplets = [
  [
    'name' => $module_dir,
    'code' => $droplet_code,
    'desc' => 'Lädt die CSS/JS Assets des Frameworks (Muss in den <head> des Templates).'
  ]
];

$query_droplets_exist = $database->query("SHOW TABLES LIKE '$table_droplets'");
if ($query_droplets_exist && $query_droplets_exist->numRows() > 0) {
  foreach ($t26_droplets as $drop) {
    $name = $database->escapeString($drop['name']);
    $code = $database->escapeString($drop['code']);
    $desc = $database->escapeString($drop['desc']);

    $check = $database->query("SELECT * FROM `$table_droplets` WHERE `name` = '$name'");
    if ($check && $check->numRows() > 0) {
      // Wenn das Droplet existiert, bügeln wir das Update drüber (Heilungs-Routine)
      $database->query("UPDATE `$table_droplets` SET `code` = '$code', `description` = '$desc', `modified_when` = " . time() . " WHERE `name` = '$name'");
      $msg .= "<br>• Update: Droplet '$name' aktualisiert.";
    } else {
      // Falls es gelöscht wurde, installieren wir es neu
      $database->query("INSERT INTO `$table_droplets` (`name`, `code`, `description`, `modified_when`, `modified_by`, `active`, `admin_edit`, `admin_view`, `show_wysiwyg`, `comments`) VALUES ('$name', '$code', '$desc', " . time() . ", 1, 1, 1, 1, 1, '')");
      $msg .= "<br>• Update: Droplet '$name' neu installiert.";
    }
  }
}

// ── 3. BENÖTIGTE VERZEICHNISSE PRÜFEN/ANLEGEN ───────────────────────────────
$dirs_to_check = [
  WB_PATH . '/media/' . $module_dir . '/logos',
  WB_PATH . '/modules/' . $module_dir . '/css/generated'
];

foreach ($dirs_to_check as $dir) {
  if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
  }
}

// ── 4. RÜCKMELDUNG AN DAS SYSTEM ────────────────────────────────────────────
if (empty($msg)) {
  $msg = " System ist auf dem neuesten Stand.";
}
echo '<div style="margin:1em 0; padding:1em; border:1px solid #148011; background:#e4fbe3;">';
echo '<strong>Modul \'' . htmlspecialchars($module_dir) . '\' erfolgreich aktualisiert!</strong>' . $msg;
echo '</div>';
