<?php

declare(strict_types=1);
/**
 * T26 Genesis Boilerplate - WBCE CMS
 * @author      Thodde26 (Thorsten)
 * @link        https://www.thodde26.de
 * @Version     1.2.0
 * @file        save_tool.php
 * @license     http://www.gnu.org/licenses/gpl.html
 * GNU General Public License v3.0
 */

require_once('../../config.php');
require_once(WB_PATH . '/framework/class.admin.php');

$admin = new admin('admintools', 'admintools');

// 🔥 HIER IST DIE MAGIE: Der Ordnername wird automatisch ausgelesen!
$module_dir = basename(__DIR__);

// ── 1. SPRACH-FALLBACK LADEN (NEU FÜR I18N) ─────────────────────────────────
// Dynamischer Pfad zur Sprachdatei
$t26_boilerplate_path = WB_PATH . '/modules/' . $module_dir;
$lang_file = $t26_boilerplate_path . '/languages/' . LANGUAGE . '.php';
if (!file_exists($lang_file)) {
  $lang_file = $t26_boilerplate_path . '/languages/DE.php';
}
require_once($lang_file);

// ── 2. SICHERHEIT: FTAN CHECK (MIT SPRACHVARIABLE) ──────────────────────────
if (!$admin->checkFTAN()) {
  $admin->print_error($MOD_T26_GENESIS_BOILERPLATE['SECURITY_ERROR']);
  exit();
}

global $database;
// Dynamische Tabelle
$table_settings = TABLE_PREFIX . 'mod_' . $module_dir . '_settings';
$action         = $_POST['action'] ?? '';

// ============================================================================
// STATUS SPEICHERN (SOFT-KILL-SWITCH)
// ============================================================================
if ($action === 'save_status') {
  if (isset($_POST['t26_is_active']) && isset($_POST['t26_core_sync'])) {
    $is_active_val = ($_POST['t26_is_active'] == 1) ? 1 : 0;
    $core_sync_val = ($_POST['t26_core_sync'] == 1) ? 1 : 0;

    $database->query("UPDATE `$table_settings` SET `is_active` = $is_active_val, `core_sync` = $core_sync_val");
  }
  // Dynamischer Zurück-Link
  $admin->print_success($MOD_T26_GENESIS_BOILERPLATE['SAVE_SUCCESS'], ADMIN_URL . '/admintools/tool.php?tool=' . $module_dir);
}
// ============================================================================
// THEME SPEICHERN
// ============================================================================
elseif ($action === 'save_theme') {
  $allowed_themes = [
    't26_blue_light',
    't26_gold_light',
    't26_green_light',
    't26_lila_light',
    't26_orange_light',
    't26_red_light',
    't26_blue_dark',
    't26_gold_dark',
    't26_green_dark',
    't26_lila_dark',
    't26_orange_dark',
    't26_red_dark',
    'custom_light',
    'custom_dark'
  ];

  $selected_theme = $_POST['active_theme'] ?? 't26_blue_light';

  // Sicherheits-Prüfung: Ist das gesendete Theme erlaubt?
  if (!in_array($selected_theme, $allowed_themes)) {
    $selected_theme = 't26_blue_light'; // Fallback
  }

  $safe_theme = $database->escapeString($selected_theme);

  // In die Datenbank schreiben
  $database->query("UPDATE `$table_settings` SET `setting_value` = '$safe_theme' WHERE `setting_name` = 'active_theme'");

  // Dynamischer Zurück-Link
  $admin->print_success($MOD_T26_GENESIS_BOILERPLATE['SAVE_SUCCESS'], ADMIN_URL . '/admintools/tool.php?tool=' . $module_dir);
}

// ============================================================================
// B) CUSTOM FARBEN SPEICHERN
// ============================================================================
elseif ($action === 'save_colors') {
  $light_colors = $_POST['custom_light'] ?? [];
  $dark_colors  = $_POST['custom_dark'] ?? [];

  $light_json = $database->escapeString(json_encode($light_colors));
  $dark_json  = $database->escapeString(json_encode($dark_colors));

  $query_light = $database->query("SELECT `setting_id` FROM `$table_settings` WHERE `setting_name` = 'custom_light_colors'");
  if ($query_light && $query_light->numRows() > 0) {
    $database->query("UPDATE `$table_settings` SET `setting_value` = '$light_json' WHERE `setting_name` = 'custom_light_colors'");
  } else {
    $database->query("INSERT INTO `$table_settings` (`setting_name`, `setting_value`) VALUES ('custom_light_colors', '$light_json')");
  }

  $query_dark = $database->query("SELECT `setting_id` FROM `$table_settings` WHERE `setting_name` = 'custom_dark_colors'");
  if ($query_dark && $query_dark->numRows() > 0) {
    $database->query("UPDATE `$table_settings` SET `setting_value` = '$dark_json' WHERE `setting_name` = 'custom_dark_colors'");
  } else {
    $database->query("INSERT INTO `$table_settings` (`setting_name`, `setting_value`) VALUES ('custom_dark_colors', '$dark_json')");
  }
}

// ============================================================================
// C) ERWEITERTES CUSTOM CSS (LIGHT & DARK) SPEICHERN
// ============================================================================
elseif ($action === 'save_advanced') {
  $custom_light_css = $database->escapeString($_POST['custom_light_css'] ?? '');
  $query_light_css = $database->query("SELECT `setting_id` FROM `$table_settings` WHERE `setting_name` = 'custom_light_css'");
  if ($query_light_css && $query_light_css->numRows() > 0) {
    $database->query("UPDATE `$table_settings` SET `setting_value` = '$custom_light_css' WHERE `setting_name` = 'custom_light_css'");
  } else {
    $database->query("INSERT INTO `$table_settings` (`setting_name`, `setting_value`) VALUES ('custom_light_css', '$custom_light_css')");
  }

  $custom_dark_css = $database->escapeString($_POST['custom_dark_css'] ?? '');
  $query_dark_css = $database->query("SELECT `setting_id` FROM `$table_settings` WHERE `setting_name` = 'custom_dark_css'");
  if ($query_dark_css && $query_dark_css->numRows() > 0) {
    $database->query("UPDATE `$table_settings` SET `setting_value` = '$custom_dark_css' WHERE `setting_name` = 'custom_dark_css'");
  } else {
    $database->query("INSERT INTO `$table_settings` (`setting_name`, `setting_value`) VALUES ('custom_dark_css', '$custom_dark_css')");
  }
}

// ============================================================================
// E) MEDIEN & GRAFIKEN (LOGO / FAVICON) SPEICHERN
// ============================================================================
elseif ($action === 'save_media') {
  // Dynamischer Media-Pfad
  $media_dir = WB_PATH . '/media/' . $module_dir . '/logos/';
  if (!is_dir($media_dir)) {
    mkdir($media_dir, 0777, true);
  }

  // Alt-Text speichern
  $logo_alt_esc = $database->escapeString(strip_tags($_POST['custom_logo_alt'] ?? ''));
  $query_alt = $database->query("SELECT `setting_id` FROM `$table_settings` WHERE `setting_name` = 'custom_logo_alt'");
  if ($query_alt && $query_alt->numRows() > 0) {
    $database->query("UPDATE `$table_settings` SET `setting_value` = '$logo_alt_esc' WHERE `setting_name` = 'custom_logo_alt'");
  } else {
    $database->query("INSERT INTO `$table_settings` (`setting_name`, `setting_value`) VALUES ('custom_logo_alt', '$logo_alt_esc')");
  }

  // 🔥 NEU: DATEIEN LÖSCHEN (Wenn die Checkbox gesetzt wurde)
  $delete_requests = [
    'delete_custom_logo' => 'custom_logo',
    'delete_custom_logo_dark' => 'custom_logo_dark',
    'delete_custom_logo_mobile' => 'custom_logo_mobile',
    'delete_custom_favicon' => 'custom_favicon',
    'delete_custom_apple_touch_icon' => 'custom_apple_touch_icon'
  ];

  foreach ($delete_requests as $post_key => $db_key) {
    if (isset($_POST[$post_key]) && $_POST[$post_key] === '1') {
      // 1. Finde den alten Dateinamen aus der DB heraus
      $query_old_file = $database->query("SELECT `setting_value` FROM `$table_settings` WHERE `setting_name` = '$db_key'");
      if ($query_old_file && $query_old_file->numRows() > 0) {
        $old_filename = $query_old_file->fetchRow()['setting_value'];

        // 🔥 SICHERHEIT 101%: Path-Traversal-Schutz mit basename()
        $safe_old_filename = basename($old_filename);

        // 2. Lösche die physische Datei vom Server
        if (!empty($safe_old_filename) && file_exists($media_dir . $safe_old_filename)) {
          unlink($media_dir . $safe_old_filename);
        }
        // 3. Leere den Eintrag in der Datenbank
        $database->query("UPDATE `$table_settings` SET `setting_value` = '' WHERE `setting_name` = '$db_key'");
      }
    }
  }

  function t26_secure_upload($file_array, $allowed_exts, $upload_dir)
  {
    if (isset($file_array) && $file_array['error'] === UPLOAD_ERR_OK) {
      $tmp_name = $file_array['tmp_name'];
      $name = basename($file_array['name']);
      $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

      if (in_array($ext, $allowed_exts)) {

        // 🔥 NEU: SVG XSS-Sicherheits-Check
        if ($ext === 'svg') {
          $svg_content = file_get_contents($tmp_name);
          // Sucht nach <script, javascript: oder Event-Handlern wie onload=, onerror=
          if (preg_match('/<script|javascript:|on[a-z]+\s*=/i', $svg_content)) {
            // Gefährliches SVG erkannt -> Upload sofort abbrechen!
            return false;
          }
        }

        // Dateinamen bereinigen
        $clean_name = preg_replace('/[^a-zA-Z0-9_-]/', '', pathinfo($name, PATHINFO_FILENAME));
        $safe_name = $clean_name . '_' . time() . '.' . $ext;

        if (move_uploaded_file($tmp_name, $upload_dir . $safe_name)) {
          return $safe_name;
        }
      }
    }
    return false;
  }


  $uploads = [
    'custom_logo_upload'             => ['db_key' => 'custom_logo',             'exts' => ['png', 'jpg', 'jpeg', 'svg', 'webp']],
    'custom_logo_dark_upload'        => ['db_key' => 'custom_logo_dark',        'exts' => ['png', 'svg', 'webp']],
    'custom_logo_mobile_upload'      => ['db_key' => 'custom_logo_mobile',      'exts' => ['png', 'svg', 'webp']],
    'custom_favicon_upload'          => ['db_key' => 'custom_favicon',          'exts' => ['png', 'svg', 'ico']],
    'custom_apple_touch_icon_upload' => ['db_key' => 'custom_apple_touch_icon', 'exts' => ['png']]
  ];

  foreach ($uploads as $file_input => $config) {
    if (!empty($_FILES[$file_input]['name'])) {
      $filename = t26_secure_upload($_FILES[$file_input], $config['exts'], $media_dir);
      if ($filename) {
        $filename_esc = $database->escapeString($filename);
        $db_key = $config['db_key'];
        $query_file = $database->query("SELECT `setting_id` FROM `$table_settings` WHERE `setting_name` = '$db_key'");
        if ($query_file && $query_file->numRows() > 0) {
          $database->query("UPDATE `$table_settings` SET `setting_value` = '$filename_esc' WHERE `setting_name` = '$db_key'");
        } else {
          $database->query("INSERT INTO `$table_settings` (`setting_name`, `setting_value`) VALUES ('$db_key', '$filename_esc')");
        }
      }
    }
  }
}

// ============================================================================
// F) NAVIGATION & MENÜ-ZUORDNUNG SPEICHERN
// ============================================================================
elseif ($action === 'save_nav') {
  $nav_fields = [
    'nav_menu_header',
    'nav_menu_sidebar_left',
    'nav_menu_sidebar_right',
    'nav_menu_footer'
  ];

  foreach ($nav_fields as $field) {
    if (isset($_POST[$field])) {
      // 🔥 SICHERHEIT 101%: strip_tags() verhindert Cross-Site-Scripting (XSS)
      $safe_value = $database->escapeString(strip_tags($_POST[$field]));
      $query_check = $database->query("SELECT `setting_id` FROM `$table_settings` WHERE `setting_name` = '$field'");

      if ($query_check && $query_check->numRows() > 0) {
        $database->query("UPDATE `$table_settings` SET `setting_value` = '$safe_value' WHERE `setting_name` = '$field'");
      } else {
        $database->query("INSERT INTO `$table_settings` (`setting_name`, `setting_value`) VALUES ('$field', '$safe_value')");
      }
    }
  }
}

// ============================================================================
// I) CUSTOM REPOSITORIES SPEICHERN
// ============================================================================
elseif ($action === 'save_repos') {
  if (isset($_POST['custom_repositories_text'])) {
    // Textarea am Zeilenumbruch in ein Array spalten
    $lines = explode("\n", $_POST['custom_repositories_text']);
    $valid_urls = [];

    foreach ($lines as $line) {
      $url = trim(strip_tags($line)); // Bereinigen
      // Nur echte URLs zulassen (verhindert Schadcode/Müll)
      if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
        $valid_urls[] = $url;
      }
    }

    $safe_json = $database->escapeString(json_encode($valid_urls));

    $query_check = $database->query("SELECT `setting_id` FROM `$table_settings` WHERE `setting_name` = 'custom_repositories'");
    if ($query_check && $query_check->numRows() > 0) {
      $database->query("UPDATE `$table_settings` SET `setting_value` = '$safe_json' WHERE `setting_name` = 'custom_repositories'");
    } else {
      $database->query("INSERT INTO `$table_settings` (`setting_name`, `setting_value`) VALUES ('custom_repositories', '$safe_json')");
    }
  }
}

// ============================================================================
// H) FOOTER COPYRIGHT SPEICHERN
// ============================================================================
elseif ($action === 'save_copyright') {
  if (isset($_POST['footer_copyright'])) {
    // Entfernt gefährliche HTML-Tags, erlaubt aber normalen Text
    $safe_copyright = $database->escapeString(strip_tags($_POST['footer_copyright']));

    $query_check = $database->query("SELECT `setting_id` FROM `$table_settings` WHERE `setting_name` = 'footer_copyright'");

    if ($query_check && $query_check->numRows() > 0) {
      $database->query("UPDATE `$table_settings` SET `setting_value` = '$safe_copyright' WHERE `setting_name` = 'footer_copyright'");
    } else {
      $database->query("INSERT INTO `$table_settings` (`setting_name`, `setting_value`) VALUES ('footer_copyright', '$safe_copyright')");
    }
  }
}
// ============================================================================
// D) ZENTRALER CSS-GENERATOR (Wird bei Farben & Advanced aufgerufen)
// ============================================================================
if ($action === 'save_colors' || $action === 'save_advanced') {

  $db_light_colors = [];
  $db_dark_colors = [];
  $db_light_css = '';
  $db_dark_css = '';

  $query_settings = $database->query("SELECT `setting_name`, `setting_value` FROM `$table_settings`");
  if ($query_settings && $query_settings->numRows() > 0) {
    while ($row = $query_settings->fetchRow()) {
      if ($row['setting_name'] === 'custom_light_colors') {
        $db_light_colors = json_decode($row['setting_value'], true) ?? [];
      } elseif ($row['setting_name'] === 'custom_dark_colors') {
        $db_dark_colors = json_decode($row['setting_value'], true) ?? [];
      } elseif ($row['setting_name'] === 'custom_light_css') {
        $db_light_css = $row['setting_value'];
      } elseif ($row['setting_name'] === 'custom_dark_css') {
        $db_dark_css = $row['setting_value'];
      }
    }
  }

  // 🔥 SICHERHEIT 101%: JETZT filtern, nachdem wir die Daten aus der DB haben!
  $db_light_css = str_ireplace(['<?php', '<?', '?>', '<script', '</script>'], '', $db_light_css);
  $db_dark_css  = str_ireplace(['<?php', '<?', '?>', '<script', '</script>'], '', $db_dark_css);

  // Generierung Custom Light Mode
  $css_light_content  = "/* T26 GENESIS Module - GENERATED CUSTOM LIGHT MODE */\n[data-t26-theme=\"custom_light\"] {\n";
  foreach ($db_light_colors as $key => $hex) {
    $css_light_content .= "  --t26-" . str_replace('_', '-', $key) . ": " . htmlspecialchars($hex) . ";\n";
  }
  $css_light_content .= "}\n\n/* --- Custom Light User CSS --- */\n" . $db_light_css;

  // Generierung Custom Dark Mode
  $css_dark_content  = "/* T26 GENESIS Module - GENERATED CUSTOM DARK MODE */\n[data-t26-theme=\"custom_dark\"] {\n";
  foreach ($db_dark_colors as $key => $hex) {
    $css_dark_content .= "  --t26-" . str_replace('_', '-', $key) . ": " . htmlspecialchars($hex) . ";\n";
  }
  $css_dark_content .= "}\n\n/* --- Custom Dark User CSS --- */\n" . $db_dark_css;

  // Dynamischer CSS-Ordner
  $css_dir = WB_PATH . '/modules/' . $module_dir . '/css/generated';
  if (!is_dir($css_dir)) {
    mkdir($css_dir, 0777, true);
  }
  file_put_contents($css_dir . '/custom_light.css', $css_light_content);
  file_put_contents($css_dir . '/custom_dark.css', $css_dark_content);
}
// ── 3. ZURÜCKLEITEN MIT ERFOLGSMELDUNG (MIT SPRACHVARIABLE) ─────────────────
// Dynamischer Zurück-Link
$admin->print_success($MOD_T26_GENESIS_BOILERPLATE['SAVE_SUCCESS'], ADMIN_URL . '/admintools/tool.php?tool=' . $module_dir);

$admin->print_footer();
