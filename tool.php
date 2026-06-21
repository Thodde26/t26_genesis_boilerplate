<?php

declare(strict_types=1);
/**
 * T26 Genesis Boilerplate - WBCE CMS
 * @author      Thodde26 (Thorsten)
 * @link        https://www.thodde26.de
 * @Version     1.2.0
 * @file        tool.php
 * @license     http://www.gnu.org/licenses/gpl.html
 * GNU General Public License v3.0
 */

if (!defined('WB_PATH')) {
  die('Cannot access this file directly');
}

global $database, $admin, $TEXT;

// 🔥 HIER IST DIE MAGIE: Der Ordnername wird automatisch ausgelesen!
$module_dir = basename(__DIR__);

// ── 1. BASIS-PFADE (DRY-Prinzip) ────────────────────────────────────────────
$t26_boilerplate_url  = WB_URL . '/modules/' . $module_dir;
$t26_boilerplate_path = WB_PATH . '/modules/' . $module_dir;
$t26_media_url = WB_URL . '/media/' . $module_dir . '/logos';

// ── 2. SPRACH-FALLBACK LADEN ────────────────────────────────────────────────
$lang_file = $t26_boilerplate_path . '/languages/' . LANGUAGE . '.php';
if (!file_exists($lang_file)) {
  $lang_file = $t26_boilerplate_path . '/languages/DE.php';
}
require_once($lang_file);

// ── 3. CSS & JS FALLBACK ────────────────────────────────────────────────────
echo '<link rel="stylesheet" type="text/css" href="' . $t26_boilerplate_url . '/css/theme_presets.css">';
echo '<link rel="stylesheet" type="text/css" href="' . $t26_boilerplate_url . '/css/backend.css">';
echo '<script src="' . $t26_boilerplate_url . '/js/backend.js" defer></script>';

// ── 4. AKTUELLE EINSTELLUNGEN AUS DER DB LADEN ──────────────────────────────
$table_settings = TABLE_PREFIX . 'mod_' . $module_dir . '_settings'; // 🔥 Dynamische Tabelle

// Standardwerte definieren
$active_theme   = 't26_blue_light';
$custom_light_colors = '{}';
$custom_dark_colors  = '{}';
$custom_light_css    = '';
$custom_dark_css     = '';
$custom_logo             = '';
$custom_logo_dark        = '';
$custom_logo_mobile      = '';
$custom_logo_alt         = '';
$custom_favicon          = '';
$custom_apple_touch_icon = '';
$nav_menu_header        = '1';
$nav_menu_sidebar_left  = '99';
$nav_menu_sidebar_right = '2';
$nav_menu_footer        = '2';
$footer_copyright = '';

// 🔥 SCHRITT 1: Zuerst den Status holen, damit das Skript weiß, ob der Sync an ist!
$is_active = 1;
$core_sync = 1;
$query_status = $database->query("SELECT `is_active`, `core_sync` FROM `$table_settings` LIMIT 1");
if ($query_status && $query_status->numRows() > 0) {
  $status_data = $query_status->fetchRow();
  if (isset($status_data['is_active'])) $is_active = (int)$status_data['is_active'];
  if (isset($status_data['core_sync'])) $core_sync = (int)$status_data['core_sync'];
}

// Dynamische Klasse für das Ausgrauen
$disabled_class = ($is_active == 0) ? 't26-settings-disabled' : '';

// 🔥 SCHRITT 2: Lokale Datenbank-Werte laden
$query_settings = $database->query("SELECT `setting_name`, `setting_value` FROM `$table_settings`");
if ($query_settings && $query_settings->numRows() > 0) {
  while ($row = $query_settings->fetchRow()) {
    $key = $row['setting_name'];
    $val = $row['setting_value'];
    if ($key === 'active_theme') $active_theme = $val;
    elseif ($key === 'custom_light_colors') $custom_light_colors = $val;
    elseif ($key === 'custom_dark_colors') $custom_dark_colors = $val;
    elseif ($key === 'custom_light_css') $custom_light_css = $val;
    elseif ($key === 'custom_dark_css') $custom_dark_css = $val;
    elseif ($key === 'custom_logo') $custom_logo = $val;
    elseif ($key === 'custom_logo_dark') $custom_logo_dark = $val;
    elseif ($key === 'custom_logo_mobile') $custom_logo_mobile = $val;
    elseif ($key === 'custom_logo_alt') $custom_logo_alt = $val;
    elseif ($key === 'custom_favicon') $custom_favicon = $val;
    elseif ($key === 'custom_apple_touch_icon') $custom_apple_touch_icon = $val;
    elseif ($key === 'nav_menu_header') $nav_menu_header = $val;
    elseif ($key === 'nav_menu_sidebar_left') $nav_menu_sidebar_left = $val;
    elseif ($key === 'nav_menu_sidebar_right') $nav_menu_sidebar_right = $val;
    elseif ($key === 'nav_menu_footer') $nav_menu_footer = $val;
    elseif ($key === 'footer_copyright') $footer_copyright = $val;
  }
}

// 🔥 SCHRITT 3: CORE-SYNC ÜBERSCHREIBEN (Hier darf nichts geändert werden!)
if ($core_sync === 1) {
  $table_core = TABLE_PREFIX . 'mod_t26_genesis_core_settings'; // Bleibt starr auf Core gerichtet
  // Prüfen, ob die Core überhaupt installiert ist
  $check_core = $database->query("SHOW TABLES LIKE '$table_core'");
  if ($check_core && $check_core->numRows() > 0) {
    // Theme und Custom-CSS aus der Core holen
    $query_core_data = $database->query("SELECT `setting_name`, `setting_value` FROM `$table_core` WHERE `setting_name` IN ('active_theme', 'custom_light_css', 'custom_dark_css')");
    if ($query_core_data && $query_core_data->numRows() > 0) {
      while ($c_row = $query_core_data->fetchRow()) {
        if ($c_row['setting_name'] === 'active_theme') $active_theme = $c_row['setting_value'];
        elseif ($c_row['setting_name'] === 'custom_light_css') $custom_light_css = $c_row['setting_value'];
        elseif ($c_row['setting_name'] === 'custom_dark_css') $custom_dark_css = $c_row['setting_value'];
      }
    }
  }
}

// Farben parsen
$lightColorsArr = json_decode($custom_light_colors, true) ?? [];
$darkColorsArr  = json_decode($custom_dark_colors, true) ?? [];

$color_keys = [
  'primary_base'  => 'Primary Base',
  'primary_hover' => 'Primary Hover',
  'bg_body'       => 'Background Body',
  'bg_surface'    => 'Background Surface',
  'text_main'     => 'Text Main',
  'text_muted'    => 'Text Muted',
  'border_color'  => 'Border Color',
  'accent_color'  => 'Accent Color'
];

// ── 5. T26 GENESIS HUB & CUSTOM REPOS (UPDATE-SCANNER) ──────────────────────
$custom_repositories = '';
$query_repos = $database->query("SELECT `setting_value` FROM `$table_settings` WHERE `setting_name` = 'custom_repositories'");
if ($query_repos && $query_repos->numRows() > 0) {
  $custom_repositories = $query_repos->fetchRow()['setting_value'];
}
$custom_repos_arr = json_decode($custom_repositories, true) ?? [];

$t26_hub_urls = ['https://raw.githubusercontent.com/Thodde26/t26-genesis-hub/refs/heads/main/t26_modules.json'];
$t26_hub_urls = array_merge($t26_hub_urls, $custom_repos_arr);

$t26_hub_data = [];
$t26_hub_errors = [];
$t26_core_update_available = false;

$ctx = stream_context_create(['http' => ['timeout' => 3]]);

foreach ($t26_hub_urls as $url) {
  $clean_url = trim($url);
  if (empty($clean_url)) continue;

  $hub_json = @file_get_contents($clean_url, false, $ctx);
  if ($hub_json === false) {
    $t26_hub_errors[] = "Verbindung fehlgeschlagen: <strong>" . htmlspecialchars($clean_url) . "</strong>";
    continue;
  }

  $parsed_hub = json_decode($hub_json, true);
  if (json_last_error() !== JSON_ERROR_NONE) {
    $t26_hub_errors[] = "Ungültiges JSON-Format in: <strong>" . htmlspecialchars($clean_url) . "</strong>";
    continue;
  }

  if (!isset($parsed_hub['modules']) || !is_array($parsed_hub['modules'])) {
    $t26_hub_errors[] = "Fehlender 'modules' Knoten in: <strong>" . htmlspecialchars($clean_url) . "</strong>";
    continue;
  }

  $t26_hub_data = array_merge($t26_hub_data, $parsed_hub['modules']);
}

// 🔥 Dynamische Abfrage der aktuellen Modul-Version
$query_core_ver = $database->query("SELECT `version` FROM `" . TABLE_PREFIX . "addons` WHERE `directory` = '$module_dir'");
$current_core_version = ($query_core_ver && $query_core_ver->numRows() > 0) ? $query_core_ver->fetchRow()['version'] : '1.0.0';

// 🔥 Dynamische Abfrage beim Hub
if (isset($t26_hub_data[$module_dir])) {
  $hub_core_version = $t26_hub_data[$module_dir]['version'];
  if (version_compare($hub_core_version, $current_core_version, '>')) {
    $t26_core_update_available = $hub_core_version;
  }
}

// ============================================================================
// 🎯 TAKEOVER LOGIK
// ============================================================================
$manage_module = $_GET['manage_module'] ?? '';

// 🔥 Dynamischer Check, damit sich das Modul nicht selbst übernimmt
if (!empty($manage_module) && strpos($manage_module, 't26_') === 0 && $manage_module !== $module_dir) {
  $submodule_path = WB_PATH . '/modules/' . basename($manage_module) . '/tool.php';

  echo '<div class="t26-admin-wrapper">';
  echo '<header class="t26-admin-header" style="display:flex; justify-content:space-between; align-items:center;">';
  echo '  <div class="t26-header-title">⚙️ Verwaltung: ' . htmlspecialchars($manage_module) . '</div>';
  echo '  <a href="' . ADMIN_URL . '/admintools/tool.php?tool=' . $module_dir . '" class="t26-btn" style="background:var(--t26-bg-lighter); color:var(--t26-text-main); border:1px solid var(--t26-border-color);">' . $MOD_T26_GENESIS_BOILERPLATE['BTN_BACK'] . '</a>';
  echo '</header>';
  echo '<main class="t26-main-card" style="padding: 30px;">';

  if (file_exists($submodule_path)) {
    require($submodule_path);
  } else {
    echo '<div class="t26-alert t26-alert-warning">' . $MOD_T26_GENESIS_BOILERPLATE['MODULE_NO_UI'] . ' <strong>' . htmlspecialchars($manage_module) . '</strong></div>';
  }

  echo '</main></div>';
  return;
}
// ============================================================================
?>

<?php
$custom_inline_css = '';
if ($active_theme === 'custom_light' || $active_theme === 'custom_dark') {
  $custom_inline_css .= '#t26-live-wrapper {';

  // 🔥 NEU: Wenn Core-Sync aktiv ist, holen wir uns das Custom-Farbarray live aus der Core!
  if (isset($core_sync) && $core_sync === 1) {
    $table_core = TABLE_PREFIX . 'mod_t26_genesis_core_settings'; // Bleibt starr auf Core
    $core_colors_name = ($active_theme === 'custom_dark') ? 'custom_dark_colors' : 'custom_light_colors';
    $query_core_colors = $database->query("SELECT `setting_value` FROM `$table_core` WHERE `setting_name` = '$core_colors_name'");
    if ($query_core_colors && $query_core_colors->numRows() > 0) {
      $core_colors_json = $query_core_colors->fetchRow()['setting_value'];
      $active_colors_arr = json_decode($core_colors_json, true) ?? [];
    } else {
      $active_colors_arr = ($active_theme === 'custom_dark') ? $darkColorsArr : $lightColorsArr;
    }
  } else {
    $active_colors_arr = ($active_theme === 'custom_dark') ? $darkColorsArr : $lightColorsArr;
  }

  foreach ($active_colors_arr as $k => $v) {
    // 🔥 SICHERHEIT 101%: Auch die Keys aus dem JSON escapen!
    $css_var = '--t26-' . htmlspecialchars(str_replace('_', '-', (string)$k));
    $custom_inline_css .= $css_var . ': ' . htmlspecialchars((string)$v) . '; ';
  }
  $custom_inline_css .= '}';
}
?>
<?php if (!empty($custom_inline_css)) echo '<style>' . $custom_inline_css . '</style>'; ?>

<div class="t26-admin-wrapper" id="t26-live-wrapper" data-t26-theme="<?php echo htmlspecialchars($active_theme); ?>">

  <header class="t26-admin-header">
    <div class="t26-header-title"><?php echo $MOD_T26_GENESIS_BOILERPLATE['ADMIN_TITLE']; ?></div>
  </header>

  <nav class="t26-nav-tabs">
    <button class="t26-tab-btn active" data-target="tab-willkommen"><?php echo $MOD_T26_GENESIS_BOILERPLATE['TAB_WELCOME']; ?></button>
    <button class="t26-tab-btn" data-target="tab-demo"><?php echo $MOD_T26_GENESIS_BOILERPLATE['TAB_DEMO']; ?></button>
    <button class="t26-tab-btn" data-target="tab-theme"><?php echo $MOD_T26_GENESIS_BOILERPLATE['TAB_THEMES']; ?></button>
    <button class="t26-tab-btn" data-target="tab-colors"><?php echo $MOD_T26_GENESIS_BOILERPLATE['TAB_COLORS']; ?></button>
    <button class="t26-tab-btn" data-target="tab-erweitert"><?php echo $MOD_T26_GENESIS_BOILERPLATE['TAB_ADVANCED']; ?></button>
    <button class="t26-tab-btn" data-target="tab-droplets"><?php echo $MOD_T26_GENESIS_BOILERPLATE['TAB_DROPLETS']; ?></button>
  </nav>

  <main class="t26-main-card">

    <div class="t26-tab-content active" id="tab-willkommen">
      <div class="t26-welcome-banner">
        <?php echo $MOD_T26_GENESIS_BOILERPLATE['WELCOME_HEAD']; ?>
      </div>

      <?php if ($t26_core_update_available): ?>
        <div class="t26-alert t26-alert-warning" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
          <div style="display:flex; align-items:center; gap:10px;">
            <span style="font-size:24px;">🚀</span>
            <div>
              <strong style="font-size:15px;"><?php echo $MOD_T26_GENESIS_BOILERPLATE['TAB_HUB_UPDATE_AVAIL']; ?></strong><br>
              <span style="font-size:13px;">Version <strong><?php echo htmlspecialchars($t26_core_update_available); ?></strong> ist im Hub verfügbar.</span>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <p class="t26-welcome-text"><?php echo $MOD_T26_GENESIS_BOILERPLATE['WELCOME_TEXT']; ?></p>
      <div class="t26-grid-container">
        <div class="t26-grid-box">
          <h3><?php echo $MOD_T26_GENESIS_BOILERPLATE['BOX_STATUS_TITLE']; ?></h3>
          <p><strong><?php echo $MOD_T26_GENESIS_BOILERPLATE['BOX_STATUS_THEME']; ?></strong><br>
            <span style="color:var(--t26-primary-base); font-size:18px; font-weight:bold;"><?php echo htmlspecialchars($active_theme); ?></span>
          </p>
          <p style="font-size:13px; color:var(--t26-text-muted); margin-top:15px;"><?php echo $MOD_T26_GENESIS_BOILERPLATE['BOX_STATUS_OK']; ?></p>
        </div>
        <div class="t26-grid-box">
          <h3><?php echo $MOD_T26_GENESIS_BOILERPLATE['BOX_STEPS_TITLE']; ?></h3>
          <p style="font-size:14px; line-height:1.6;"><?php echo $MOD_T26_GENESIS_BOILERPLATE['BOX_STEPS_TEXT']; ?></p>
        </div>
        <div class="t26-grid-box highlight">
          <h3><?php echo $MOD_T26_GENESIS_BOILERPLATE['BOX_HELP_TITLE']; ?></h3>
          <p style="font-size:14px; margin-bottom:20px;"><?php echo $MOD_T26_GENESIS_BOILERPLATE['BOX_HELP_TEXT']; ?></p>
          <a href="https://www.thodde26.de" target="_blank" rel="noopener noreferrer" class="t26-btn t26-btn--primary">
            <?php echo $MOD_T26_GENESIS_BOILERPLATE['BOX_HELP_BTN']; ?>
          </a>
        </div>
      </div>
    </div>

    <div class="t26-tab-content" id="tab-demo">

      <form action="<?php echo $t26_boilerplate_url; ?>/save_tool.php" method="post">
        <?php echo $admin->getFTAN(); ?>
        <input type="hidden" name="action" value="save_status">

        <div class="t26-grid-box" style="margin-bottom: 30px;">
          <h3 style="margin-top:0;">🔌 Modul-Status (Soft-Kill-Switch)</h3>
          <p style="color: var(--t26-text-muted); font-size: 14px; margin-bottom: 20px;">
            Hier kannst du das Modul komplett deaktivieren. Es sendet dann keine API-Daten mehr an andere T26-Module. Deine gespeicherten Einstellungen bleiben aber vollständig erhalten!
          </p>

          <div class="t26-form-group">
            <label for="t26_is_active">Modul aktivieren:</label>
            <select name="t26_is_active" id="t26_is_active" class="t26-select" style="max-width: 300px;">
              <option value="1" <?php echo ($is_active == 1) ? 'selected' : ''; ?>>🟢 Ja, Modul ist aktiv</option>
              <option value="0" <?php echo ($is_active == 0) ? 'selected' : ''; ?>>🔴 Nein, Modul pausieren</option>
            </select>
          </div>

          <div class="t26-form-group" style="margin-top: 15px;">
            <label for="t26_core_sync">Abhängigkeit von T26 Core:</label>
            <select name="t26_core_sync" id="t26_core_sync" class="t26-select" style="max-width: 300px;">
              <option value="1" <?php echo ($core_sync == 1) ? 'selected' : ''; ?>>🔗 Ja, Core-Einstellungen erben</option>
              <option value="0" <?php echo ($core_sync == 0) ? 'selected' : ''; ?>>🛡️ Nein, völlig autark laufen</option>
            </select>
          </div>
          <div class="t26-form-actions" style="margin-top: 15px;">
            <button type="submit" class="t26-btn t26-btn--primary"><?php echo $TEXT['SAVE']; ?></button>
          </div>
        </div>
      </form>

      <div class="t26-grid-box t26-disableable-area <?php echo $disabled_class; ?>" style="text-align: center; padding: 40px 20px;">
        <span style="font-size: 40px; display: block; margin-bottom: 15px;">🚧</span>
        <h3 style="margin-top: 0;">Hier entsteht dein neues Modul</h3>
        <p style="color: var(--t26-text-muted); font-size: 14px; max-width: 500px; margin: 0 auto 20px auto;">
          Dies ist der Platzhalter für deine eigenen HTML-Formulare, Tabellen oder Einstellungen.
          Kopiere diese Boilerplate, benenne sie um und fülle diesen Tab mit Leben!
        </p>
        <button class="t26-btn t26-btn--primary" onclick="alert('Ich bin ein Test-Button!'); return false;">Beispiel Button</button>
      </div>
    </div>

    <div class="t26-tab-content" id="tab-theme">
      <form action="<?php echo $t26_boilerplate_url; ?>/save_tool.php" method="post" id="t26-theme-form" class="t26-disableable-area <?php echo $disabled_class; ?>">
        <?php echo $admin->getFTAN(); ?>
        <input type="hidden" name="action" value="save_theme">
        <div class="t26-form-group">
          <label for="active_theme"><?php echo $MOD_T26_GENESIS_BOILERPLATE['LABEL_ACTIVE_THEME']; ?></label>
          <select id="active_theme" name="active_theme" class="t26-select">
            <optgroup label="<?php echo $MOD_T26_GENESIS_BOILERPLATE['THEME_OPT_LIGHT']; ?>">
              <option value="t26_blue_light" <?php if ($active_theme === 't26_blue_light') echo 'selected'; ?>>Light: Blue</option>
              <option value="t26_gold_light" <?php if ($active_theme === 't26_gold_light') echo 'selected'; ?>>Light: Elegant Gold</option>
              <option value="t26_green_light" <?php if ($active_theme === 't26_green_light') echo 'selected'; ?>>Light: Cyber Mint</option>
              <option value="t26_lila_light" <?php if ($active_theme === 't26_lila_light') echo 'selected'; ?>>Light: Soft Purple</option>
              <option value="t26_orange_light" <?php if ($active_theme === 't26_orange_light') echo 'selected'; ?>>Light: Orange</option>
              <option value="t26_red_light" <?php if ($active_theme === 't26_red_light') echo 'selected'; ?>>Light: Red</option>
            </optgroup>
            <optgroup label="<?php echo $MOD_T26_GENESIS_BOILERPLATE['THEME_OPT_DARK']; ?>">
              <option value="t26_blue_dark" <?php if ($active_theme === 't26_blue_dark') echo 'selected'; ?>>Dark: Blue</option>
              <option value="t26_gold_dark" <?php if ($active_theme === 't26_gold_dark') echo 'selected'; ?>>Dark: Premium Gold</option>
              <option value="t26_green_dark" <?php if ($active_theme === 't26_green_dark') echo 'selected'; ?>>Dark: Toxic Green</option>
              <option value="t26_lila_dark" <?php if ($active_theme === 't26_lila_dark') echo 'selected'; ?>>Dark: Neon Lila</option>
              <option value="t26_orange_dark" <?php if ($active_theme === 't26_orange_dark') echo 'selected'; ?>>Dark: Orange</option>
              <option value="t26_red_dark" <?php if ($active_theme === 't26_red_dark') echo 'selected'; ?>>Dark: Red</option>
            </optgroup>
            <optgroup label="<?php echo $MOD_T26_GENESIS_BOILERPLATE['THEME_OPT_CUSTOM']; ?>">
              <option value="custom_light" <?php if ($active_theme === 'custom_light') echo 'selected'; ?>>Custom: Light</option>
              <option value="custom_dark" <?php if ($active_theme === 'custom_dark') echo 'selected'; ?>>Custom: Dark</option>
            </optgroup>
          </select>
        </div>
        <div class="t26-form-actions">
          <button type="submit" class="t26-btn t26-btn--primary"><?php echo $TEXT['SAVE']; ?></button>
        </div>
      </form>
    </div>

    <div class="t26-tab-content" id="tab-colors">
      <form action="<?php echo $t26_boilerplate_url; ?>/save_tool.php" method="post" id="t26-colors-form" class="t26-disableable-area <?php echo $disabled_class; ?>">
        <?php echo $admin->getFTAN(); ?>
        <input type="hidden" name="action" value="save_colors">
        <input type="hidden" name="active_theme" class="t26-hidden-theme-field" value="<?php echo htmlspecialchars($active_theme); ?>">

        <div class="t26-grid-container" style="display:flex; gap:30px;">
          <div class="t26-grid-box" style="flex:1;">
            <h3 style="border-bottom:2px solid var(--t26-border-color); padding-bottom:10px; margin-bottom:20px;"><?php echo $MOD_T26_GENESIS_BOILERPLATE['COLORS_TITLE_LIGHT']; ?></h3>
            <?php foreach ($color_keys as $key => $label): ?>
              <div class="t26-form-group" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                <label for="light_<?php echo $key; ?>" style="margin:0; font-size:14px; font-weight:500;"><?php echo $label; ?></label>
                <input type="color" id="light_<?php echo $key; ?>" name="custom_light[<?php echo $key; ?>]" value="<?php echo htmlspecialchars($lightColorsArr[$key] ?? '#000000'); ?>" class="t26-input" style="width:50px; height:35px; padding:0; cursor:pointer;">
              </div>
            <?php endforeach; ?>
          </div>
          <div class="t26-grid-box" style="flex:1;">
            <h3 style="border-bottom:2px solid var(--t26-border-color); padding-bottom:10px; margin-bottom:20px;"><?php echo $MOD_T26_GENESIS_BOILERPLATE['COLORS_TITLE_DARK']; ?></h3>
            <?php foreach ($color_keys as $key => $label): ?>
              <div class="t26-form-group" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                <label for="dark_<?php echo $key; ?>" style="margin:0; font-size:14px; font-weight:500;"><?php echo $label; ?></label>
                <input type="color" id="dark_<?php echo $key; ?>" name="custom_dark[<?php echo $key; ?>]" value="<?php echo htmlspecialchars($darkColorsArr[$key] ?? '#000000'); ?>" class="t26-input" style="width:50px; height:35px; padding:0; cursor:pointer;">
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="t26-form-actions" style="margin-top:20px; display:flex; gap:15px; align-items:center;">
          <button type="submit" class="t26-btn t26-btn--primary"><?php echo $TEXT['SAVE']; ?></button>
          <button type="button" id="t26-reset-colors-btn" class="t26-btn" style="background:var(--t26-bg-lighter); color:var(--t26-text-main); border:1px solid var(--t26-border-color);">
            <?php echo $MOD_T26_GENESIS_BOILERPLATE['COLORS_BTN_RESET']; ?>
          </button>
        </div>
      </form>
    </div>

    <div class="t26-tab-content" id="tab-erweitert">
      <form action="<?php echo $t26_boilerplate_url; ?>/save_tool.php" method="post" id="t26-advanced-form" class="t26-disableable-area <?php echo $disabled_class; ?>">
        <?php echo $admin->getFTAN(); ?>
        <input type="hidden" name="action" value="save_advanced">
        <input type="hidden" name="active_theme" class="t26-hidden-theme-field" value="<?php echo htmlspecialchars($active_theme); ?>">

        <div class="t26-grid-container" style="display:block;">
          <div class="t26-grid-box" style="width:100%;">
            <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:2px solid var(--t26-border-color); padding-bottom:15px; margin-bottom:20px; flex-wrap:wrap; gap:15px;">
              <h3 style="margin:0; font-size:20px; color:var(--t26-text-main);"><?php echo $MOD_T26_GENESIS_BOILERPLATE['TAB_ADV_TITLE']; ?></h3>
              <div style="display:flex; gap:15px; align-items:center;">
                <button type="button" id="t26-load-defaults" class="t26-btn" style="height:50px; background:var(--t26-bg-lighter); color:var(--t26-text-main); font-size:14px; font-weight:bold; border:1px solid var(--t26-border-color);" data-confirm-light="<?php echo htmlspecialchars($MOD_T26_GENESIS_BOILERPLATE['JS_CONFIRM_LIGHT']); ?>" data-confirm-dark="<?php echo htmlspecialchars($MOD_T26_GENESIS_BOILERPLATE['JS_CONFIRM_DARK']); ?>">
                  <?php echo $MOD_T26_GENESIS_BOILERPLATE['TAB_ADV_BTN_LOAD']; ?>
                </button>
                <select id="t26-css-mode-selector" class="t26-select" style="height:50px; font-weight:bold; font-size:14px; max-width:200px;">
                  <option value="light"><?php echo $MOD_T26_GENESIS_BOILERPLATE['TAB_ADV_SELECT_LIGHT']; ?></option>
                  <option value="dark"><?php echo $MOD_T26_GENESIS_BOILERPLATE['TAB_ADV_SELECT_DARK']; ?></option>
                </select>
              </div>
            </div>

            <p style="font-size:13px; color:var(--t26-text-muted); margin-bottom:15px;">
              <?php echo $MOD_T26_GENESIS_BOILERPLATE['TAB_ADV_DESC']; ?>
            </p>

            <div id="t26-css-editor-light">
              <textarea id="t26_light_textarea" name="custom_light_css" rows="15" class="t26-input" style="width:100%; font-family:monospace; line-height:1.5;"><?php echo htmlspecialchars($custom_light_css); ?></textarea>
            </div>

            <div id="t26-css-editor-dark" style="display:none;">
              <textarea id="t26_dark_textarea" name="custom_dark_css" rows="15" class="t26-input" style="width:100%; font-family:monospace; line-height:1.5;"><?php echo htmlspecialchars($custom_dark_css); ?></textarea>
            </div>

            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/theme/monokai.min.css">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/css/css.min.js"></script>
          </div>
        </div>

        <div class="t26-form-actions" style="margin-top:20px;">
          <button type="submit" class="t26-btn t26-btn--primary"><?php echo $MOD_T26_GENESIS_BOILERPLATE['TAB_ADV_SAVE_BTN']; ?></button>
        </div>
      </form>
    </div>

    <div class="t26-tab-content" id="tab-droplets">
      <div class="t26-grid-box t26-disableable-area <?php echo $disabled_class; ?>">
        <h3 style="margin-top:0; border-bottom:2px solid var(--t26-border-color); padding-bottom:10px; margin-bottom:20px; font-size:20px;"><?php echo $MOD_T26_GENESIS_BOILERPLATE['TAB_DROPLETS_TITLE']; ?></h3>
        <p style="font-size:14px; color:var(--t26-text-muted); margin-bottom:20px;"><?php echo $MOD_T26_GENESIS_BOILERPLATE['TAB_DROPLETS_DESC']; ?></p>

        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap:20px;">
          <div style="background:var(--t26-bg-lighter); border:1px solid var(--t26-border-color); padding:15px; border-radius:4px;">
            <code style="background:var(--t26-bg-surface); color:var(--t26-primary-base); padding:4px 8px; border-radius:4px; font-weight:bold; font-size:14px; display:inline-block; margin-bottom:10px;">[[<?php echo htmlspecialchars($module_dir); ?>]]</code>
            <p style="font-size:13px; color:var(--t26-text-muted); margin:0;"><?php echo $MOD_T26_GENESIS_BOILERPLATE['DROPLET_CORE_DESC']; ?></p>
          </div>
        </div>
      </div>
    </div>

  </main>

  <div class="t26-support-banner">
    <div style="display:flex; align-items:center;">
      <span class="t26-icon-coffee">☕</span>
      <div class="t26-support-content">
        <h4><?php echo $MOD_T26_GENESIS_BOILERPLATE['SUPPORT_TITLE']; ?></h4>
        <p><?php echo $MOD_T26_GENESIS_BOILERPLATE['SUPPORT_TEXT']; ?></p>
      </div>
    </div>
    <a href="https://ko-fi.com/thodde26" target="_blank" rel="noopener noreferrer" class="t26-btn t26-btn--danger">
      <?php echo $MOD_T26_GENESIS_BOILERPLATE['SUPPORT_BTN']; ?>
    </a>
  </div>

</div>
