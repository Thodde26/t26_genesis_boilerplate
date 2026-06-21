<?php

declare(strict_types=1);
/**
 * T26 Genesis Boilerplate - WBCE CMS
 * @author       Thodde26 (Thorsten)
 * @link         https://www.thodde26.de
 * @Version      1.1.0
 * @file         include.php
 * @license      http://www.gnu.org/licenses/gpl.html
 * GNU General Public License v3.0
 */

if (!defined('WB_PATH')) {
  die('Cannot access this file directly');
}

// ── 0. SMART STATUS CHECK ─────────────────────────────────────────────
if (!function_exists('t26_boilerplate_get_boilerplate_status')) {
  /**
   * Holt die globalen Schalter (Aktivierungsstatus & Core-Sync) des Moduls.
   */
  function t26_boilerplate_get_boilerplate_status(): array
  {
    global $database;
    // 🔥 DYNAMIK: Modul-Ordner und Tabellenname automatisch ermitteln
    $module_dir = basename(__DIR__);
    $table = TABLE_PREFIX . 'mod_' . $module_dir . '_settings';

    $status = ['is_active' => 1, 'core_sync' => 1];

    $query_check = $database->query("SHOW TABLES LIKE '$table'");
    if ($query_check && $query_check->numRows() > 0) {
      $query_status = $database->query("SELECT `is_active`, `core_sync` FROM `$table` LIMIT 1");
      if ($query_status && $query_status->numRows() > 0) {
        $res = $query_status->fetchRow();
        $status['is_active'] = isset($res['is_active']) ? (int)$res['is_active'] : 1;
        $status['core_sync'] = isset($res['core_sync']) ? (int)$res['core_sync'] : 1;
      }
    }
    return $status;
  }
}

// ── 1. THEME AUSLESEN ───────────────────────────────────────────────────────
if (!function_exists('t26_boilerplate_get_active_theme')) {
  /**
   * Liest das aktuell aktive T26 Genesis Theme aus.
   * Berücksichtigt die Vererbung (core_sync) aus dem Core-Framework.
   */
  function t26_boilerplate_get_active_theme(): string
  {
    global $database;
    // 🔥 FIX: Korrekter Funktionsaufruf
    $status = t26_boilerplate_get_boilerplate_status();

    // WENN CORE-SYNC AKTIV: Theme direkt aus der Core-Tabelle erben!
    if ($status['core_sync'] === 1) {
      $table_core = TABLE_PREFIX . 'mod_t26_genesis_core_settings';
      $query_core = $database->query("SHOW TABLES LIKE '$table_core'");
      if ($query_core && $query_core->numRows() > 0) {
        $query_theme = $database->query("SELECT `setting_value` FROM `$table_core` WHERE `setting_name` = 'active_theme'");
        if ($query_theme && $query_theme->numRows() > 0) {
          return (string)$query_theme->fetchRow()['setting_value'];
        }
      }
    }

    // Ansonsten (wenn autark): Eigenes zugewiesenes Theme auslesen
    $module_dir = basename(__DIR__);
    $table_settings = TABLE_PREFIX . 'mod_' . $module_dir . '_settings';
    $query = $database->query("SELECT `setting_value` FROM `$table_settings` WHERE `setting_name` = 'active_theme'");

    if ($query && $query->numRows() > 0) {
      return (string)$query->fetchRow()['setting_value'];
    }

    return 't26_blue_light'; // Bulletproof Fallback
  }
}

// ── 2. SMARTE ASSET-LADELOGIK (FRONTEND) ────────────────────────────────────
if (!function_exists('t26_boilerplate_get_frontend_assets')) {
  /**
   * Generiert die <link> und <script> Tags für das Frontend.
   */
  function t26_boilerplate_get_frontend_assets(): string
  {
    // 🔥 FIX: Korrekter Funktionsaufruf
    $status = t26_boilerplate_get_boilerplate_status();

    // Soft-Kill-Switch: Wenn das Modul pausiert ist, absolut gar nichts ins Frontend laden!
    if ($status['is_active'] === 0) {
      return '';
    }

    $active_theme = t26_boilerplate_get_active_theme();
    $module_dir = basename(__DIR__);

    // WENN CORE-SYNC AKTIV: Pfad zum Core-Verzeichnis nutzen (für Custom-CSS)
    if ($status['core_sync'] === 1) {
      $t26_base_url = WB_URL . '/modules/t26_genesis_core';
    } else {
      $t26_base_url = WB_URL . '/modules/' . $module_dir;
    }

    $output  = "\n\n";

    // A) CSS intelligent laden
    if (strpos($active_theme, 'custom_') === 0) {
      $generated_file = $t26_base_url . '/css/generated/' . $active_theme . '.css';
      $output .= '<link rel="stylesheet" type="text/css" href="' . $generated_file . '">' . "\n";
    } else {
      $output .= '<link rel="stylesheet" type="text/css" href="' . WB_URL . '/modules/' . $module_dir . '/css/theme_presets.css">' . "\n";
    }

    // B) Anti-FOUC Script
    $output .= '<script>document.documentElement.setAttribute("data-t26-theme", "' . htmlspecialchars($active_theme) . '");</script>' . "\n";
    $output .= "\n";

    return $output;
  }
}

/**
 * T26 Genesis Boilerplate API
 */
if (!function_exists('t26_genesis_boilerplate_api')) {
  function t26_genesis_boilerplate_api($request = '')
  {
    global $database;
    $module_dir = basename(__DIR__);
    $table = TABLE_PREFIX . 'mod_' . $module_dir . '_settings';

    $query = $database->query("SHOW TABLES LIKE '$table'");
    if ($query->numRows() == 0) {
      return false;
    }

    $settings = $database->query("SELECT * FROM `$table` LIMIT 1");
    if ($settings->numRows() > 0) {
      $data = $settings->fetchRow();

      if (isset($data['is_active']) && $data['is_active'] == 0) {
        return false;
      }

      if ($request === 'status') {
        return true;
      }
      if ($request === 'get_settings') {
        return $data;
      }
    }

    return false;
  }
}
