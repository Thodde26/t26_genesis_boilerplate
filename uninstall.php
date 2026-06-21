<?php

declare(strict_types=1);
/**
 * T26 Genesis Boilerplate - WBCE CMS
 * @author      Thodde26 (Thorsten)
 * @link        https://www.thodde26.de
 * @Version     1.2.0
 * @file        uninstall.php
 * @license     http://www.gnu.org/licenses/gpl.html
 * GNU General Public License v3.0
 */

if (!defined('WB_PATH')) {
  die('Cannot access this file directly');
}

global $database;

// 🔥 HIER IST DIE MAGIE: Der Ordnername wird automatisch ausgelesen!
$module_dir = basename(__DIR__);

// ── 1. DATENBANK-TABELLE RESTLOS LÖSCHEN ────────────────────────────────────
// Dynamische Tabellen-Erkennung
$table_settings = TABLE_PREFIX . 'mod_' . $module_dir . '_settings';
$database->query("DROP TABLE IF EXISTS `$table_settings`");

// ── 2. DROPLETS LÖSCHEN ─────────────────────────────────────────────────────
$table_droplets = TABLE_PREFIX . 'mod_droplets';
$droplet_names = [$module_dir]; // Dynamischer Droplet-Name

$query_droplets_exist = $database->query("SHOW TABLES LIKE '$table_droplets'");
if ($query_droplets_exist && $query_droplets_exist->numRows() > 0) {
  foreach ($droplet_names as $name) {
    $name_esc = $database->escapeString($name);
    $database->query("DELETE FROM `$table_droplets` WHERE `name` = '$name_esc'");
  }
}

// ── 3. MEDIA- & CSS-VERZEICHNISSE AUFRÄUMEN (SICHERER MODUS) ────────────────
// Dynamische Ordner-Pfade
$media_dir = WB_PATH . '/media/' . $module_dir . '/logos';
$media_base_dir = WB_PATH . '/media/' . $module_dir;
$css_generated_dir = WB_PATH . '/modules/' . $module_dir . '/css/generated';

if (!function_exists('t26_safe_rmdir')) {
  function t26_safe_rmdir(string $dir, bool $deleteFiles = false): void
  {
    if (!is_dir($dir)) return;

    $files = array_diff(scandir($dir), ['.', '..']);

    if ($deleteFiles) {
      foreach ($files as $file) {
        $path = "$dir/$file";
        if (is_file($path)) unlink($path);
      }
      $files = [];
    }

    if (empty($files)) {
      rmdir($dir);
    }
  }
}

// 1. Media-Logos NUR löschen, wenn der Ordner leer ist (User-Daten schützen!)
t26_safe_rmdir($media_dir, false);
t26_safe_rmdir($media_base_dir, false);

// 2. Generiertes Custom-CSS mitsamt der Dateien restlos löschen
t26_safe_rmdir($css_generated_dir, true);
