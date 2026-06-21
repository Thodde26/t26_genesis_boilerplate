<?php

declare(strict_types=1);
/**
 * T26 Genesis Boilerplate - WBCE CMS
 * @author      Thodde26 (Thorsten)
 * @link        https://www.thodde26.de
 * @file        install.php
 * @license     http://www.gnu.org/licenses/gpl.html
 * GNU General Public License v3.0
 */

if (!defined('WB_PATH')) {
  die('Cannot access this file directly');
}

global $database;

// 🔥 HIER IST DIE MAGIE: Der Ordnername wird automatisch ausgelesen!
$module_dir = basename(__DIR__);

// ── 1. DATENBANK-TABELLEN ANLEGEN ───────────────────────────────────────────
// Dynamischer Tabellenname, z.B. "mod_t26_toolbar_settings"
$table_settings = TABLE_PREFIX . 'mod_' . $module_dir . '_settings';

$database->query("DROP TABLE IF EXISTS `$table_settings`");
$database->query("CREATE TABLE `$table_settings` (
    `setting_id` INT(11) NOT NULL AUTO_INCREMENT,
    `setting_name` VARCHAR(255) NOT NULL,
    `setting_value` TEXT NOT NULL,
    `is_active` INT(1) NOT NULL DEFAULT 1,
    `core_sync` INT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Standard-JSON-Skelette für das Custom Theme ("T26 Blue" als Startwert)
$defaultLightColors = json_encode([
  'primary_base'  => '#1a73e8',
  'primary_hover' => '#1557b0',
  'bg_body'       => '#f8f9fa',
  'bg_surface'    => '#ffffff',
  'text_main'     => '#334155',
  'text_muted'    => '#64748b',
  'border_color'  => '#cbd5e0',
  'accent_color'  => '#1a73e8'
]);

$defaultDarkColors = json_encode([
  'primary_base'  => '#7A9BDB',
  'primary_hover' => '#244684',
  'bg_body'       => '#121212',
  'bg_surface'    => '#1a1a1a',
  'text_main'     => '#e0e0e0',
  'text_muted'    => '#aaaaaa',
  'border_color'  => '#333333',
  'accent_color'  => '#7A9BDB'
]);

// ── 2. STANDARDWERTE EINFÜGEN ───────────────────────────────────────────────
$defaults = [
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

foreach ($defaults as $key => $val) {
  $k_esc = $database->escapeString($key);
  $v_esc = $database->escapeString($val);
  $database->query("INSERT INTO `$table_settings` (`setting_name`, `setting_value`) VALUES ('$k_esc', '$v_esc')");
}

// ── 3. DROPLETS INSTALLIEREN ────────────────────────────────────────────────
$table_droplets = TABLE_PREFIX . 'mod_droplets';

// 🔥 Der komplette Code für das Droplet (sauber escaped als String)
$droplet_code = "global \$database;
\$table = TABLE_PREFIX . 'mod_" . $module_dir . "_settings'; // Dynamischer Tabellenname
\$query = \$database->query(\"SELECT `is_active` FROM `\$table` LIMIT 1\");

if(\$query && \$query->numRows() > 0) {
    \$settings = \$query->fetchRow();
    // Soft-Kill-Switch: Wenn auf 0, gibt das Droplet einfach GAR NICHTS aus!
    if(\$settings['is_active'] == 0) {
        return '';
    }
}

// Wenn aktiv, lade die eigentliche Funktion und füge den Text hinzu
require_once(WB_PATH . '/modules/" . $module_dir . "/include.php');
\$assets = t26_get_frontend_assets();
\$mein_text = '<p>Das T26 Boilerplate Droplet funktioniert!</p>';
return \$assets . \$mein_text;";

$t26_droplets = [
  [
    'name' => $module_dir, // Dynamischer Droplet-Name
    'code' => $droplet_code, // Hier übergeben wir unseren sauberen Block!
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
    if ($check && $check->numRows() === 0) {
      $database->query("INSERT INTO `$table_droplets` (`name`, `code`, `description`, `modified_when`, `modified_by`, `active`, `admin_edit`, `admin_view`, `show_wysiwyg`, `comments`) VALUES ('$name', '$code', '$desc', " . time() . ", 1, 1, 1, 1, 1, '')");
    }
  }
}

// ── 4. BENÖTIGTE VERZEICHNISSE ANLEGEN ──────────────────────────────────────
// Dynamische Ordner-Pfade
$media_dir = WB_PATH . '/media/' . $module_dir . '/logos';
if (!is_dir($media_dir)) {
  mkdir($media_dir, 0777, true);
}
$css_dir = WB_PATH . '/modules/' . $module_dir . '/css/generated';
if (!is_dir($css_dir)) {
  mkdir($css_dir, 0777, true);
}
