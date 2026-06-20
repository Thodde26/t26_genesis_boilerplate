<?php

declare(strict_types=1);
/**
 * T26 Genesis Boilerplate - WBCE CMS
 * @author      Thodde26 (Thorsten)
 * @link        https://www.thodde26.de
 * @Version     1.1.0
 * @file        EN.php
 * @license     http://www.gnu.org/licenses/gpl.html
 * GNU General Public License v3.0
 */

if (!defined('WB_PATH')) {
  die('Cannot access this file directly');
}

$MOD_T26_GENESIS_BOILERPLATE = [
  'ADMIN_TITLE'        => 'T26 Genesis Boilerplate - Configuration',
  'TAB_WELCOME'        => 'Welcome',
  'TAB_DEMO'           => 'Demo',
  'TAB_THEMES'         => 'Theme Selection',
  'TAB_COLORS'         => 'Custom Colors',
  'TAB_GRAPHICS'       => 'Graphics',
  'TAB_EXTENSIONS'     => 'Extensions',
  'TAB_NAV'            => 'Navigation',
  'TAB_COPYRIGHT'      => 'Copyright',
  'TAB_ADVANCED'       => 'Advanced',
  'TAB_DROPLETS'       => 'Droplets',

  'WELCOME_HEAD'       => '🎉 Welcome to the T26 Admin Center!',
  'WELCOME_TEXT'       => 'Here you have full control over the design of your T26 Genesis Theme.',
  'WELCOME_BTN_APPSTORE' => 'App Store ➡️',

  'BOX_STATUS_TITLE'   => '📊 System Status',
  'BOX_STATUS_THEME'   => 'Active Theme:',
  'BOX_STATUS_OK'      => 'The module is successfully connected to the database and ready to use.',
  'BOX_STEPS_TITLE'    => '🚀 First Steps',
  'BOX_STEPS_TEXT'     => 'Select a predefined preset under <strong>Theme Selection</strong>.<br>Mix your own colors in the <strong>Custom Editor</strong>.<br>Replace your logo & images in the <strong>Graphics</strong> tab.',
  'BOX_HELP_TITLE'     => '💡 Help & Developer',
  'BOX_HELP_TEXT'      => 'Need help with the setup or want to leave feedback?',
  'BOX_HELP_BTN'       => 'Visit Thodde26.de',

  'THEME_OPT_LIGHT'    => 'Light Mode Presets',
  'THEME_OPT_DARK'     => 'Dark Mode Presets',
  'THEME_OPT_CUSTOM'   => 'Custom Color Schemes',
  'LABEL_ACTIVE_THEME' => 'Select Active Genesis Theme',

  'COLORS_TITLE_LIGHT' => '☀️ Light Mode',
  'COLORS_TITLE_DARK'  => '🌙 Dark Mode',
  'COLORS_BTN_RESET'   => '🔄 Load Default Values',

  'TAB_GRAPHICS_LOGOS'       => '🖼️ Logos',
  'TAB_GRAPHICS_MAIN_LOGO'   => 'Main Logo (Light Mode)',
  'TAB_GRAPHICS_MAIN_DESC'   => 'Format: PNG or SVG.',
  'TAB_GRAPHICS_NO_MAIN'     => 'No main logo uploaded.',
  'TAB_GRAPHICS_DARK_LOGO'   => 'Dark Mode Logo',
  'TAB_GRAPHICS_NO_DARK'     => 'No dark mode logo uploaded.',
  'TAB_GRAPHICS_MOBILE_LOGO' => 'Mobile Logo (Optional)',
  'TAB_GRAPHICS_NO_MOBILE'   => 'No mobile logo uploaded.',
  'TAB_GRAPHICS_DELETE'      => '🗑️ Delete this element completely',
  'TAB_GRAPHICS_ICONS'       => '📱 App & Browser Icons',
  'TAB_GRAPHICS_FAVICON'     => 'Favicon (Browser Tab)',
  'TAB_GRAPHICS_NO_FAVICON'  => 'No favicon uploaded.',
  'TAB_GRAPHICS_APPLE'       => 'Apple Touch Icon',
  'TAB_GRAPHICS_NO_APPLE'    => 'No Apple icon uploaded.',
  'GRAPHICS_ALT_PLACEHOLDER' => 'SEO Alt Text (e.g., Logo)',

  'TAB_HUB_TITLE'       => '🧩 T26 App Store & Hub',
  'TAB_HUB_ONLINE'      => '🟢 Hub Online',
  'TAB_HUB_OFFLINE'     => '🔴 Hub Offline',
  'TAB_HUB_WARNINGS'    => '⚠️ Hub Warnings:',
  'TAB_HUB_DESC'        => 'Here you can find all official T26 modules.',
  'TAB_HUB_REPOS_TITLE' => '🔗 Custom Repositories (Third-Party)',
  'TAB_HUB_REPOS_DESC'  => 'Paste JSON URLs here to display their T26 modules.',
  'TAB_HUB_REPOS_BTN'   => 'Save Sources & Scan 🔄',
  'TAB_HUB_UPDATE_AVAIL' => 'Update available!',
  'TAB_HUB_VERSION_TEXT' => 'T26 Genesis Core Version',
  'TAB_HUB_UPDATE_BTN'  => 'Update 🔄',
  'TAB_HUB_CURRENT'     => 'Current',
  'TAB_HUB_MANAGE'      => 'Manage ⚙️',
  'TAB_HUB_MASTER'      => 'Master System',
  'TAB_HUB_INSTALL'     => 'Install ⬇️',
  'TAB_HUB_CONN_ERROR'  => 'Connection to T26 Hub failed.',

  'NAV_HEADER'         => 'Header Menu (Top)',
  'NAV_SIDEBAR_LEFT'   => 'Left Sidebar (Default Menu)',
  'NAV_SIDEBAR_RIGHT'  => 'Right Sidebar (Default Menu)',
  'NAV_FOOTER'         => 'Footer Menu (Bottom)',
  'NAV_SAVE_BTN'       => 'Save Menu Assignment',
  'NAV_DEFAULT_1'      => '1: Default Navigation',
  'NAV_DEFAULT_2'      => '2: Footer Navigation',
  'NAV_DEFAULT_99'     => '99: No Navigation',
  'NAV_TEMPLATE_UNKNOWN' => 'Unknown',
  'NAV_INFO_TEXT'      => 'The menu names are loaded live from your active template:',
  'NAV_DROPLET_TIP'    => '<strong>Droplet Tip:</strong> Use <code>[[T26_SidebarMenu?side=left]]</code> or <code>[[T26_SidebarMenu?side=right]]</code>.',

  'COPYRIGHT_LABEL'        => 'Footer Copyright Text',
  'COPYRIGHT_PLACEHOLDER'  => 'e.g., © 2026 My Company.',
  'COPYRIGHT_DROPLET_INFO' => '<strong>Droplet Tip:</strong> Use <code>[[T26_Copyright]]</code>.',
  'COPYRIGHT_SAVE_BTN'     => 'Save Copyright',

  'TAB_ADV_TITLE'        => '🎨 Custom CSS',
  'TAB_ADV_DESC'         => 'Write custom CSS here. It will be appended automatically to the generated file.',
  'TAB_ADV_BTN_LOAD'     => '⚡ Load Default Variables',
  'TAB_ADV_SELECT_LIGHT' => '☀️ Light Mode CSS',
  'TAB_ADV_SELECT_DARK'  => '🌙 Dark Mode CSS',
  'TAB_ADV_SAVE_BTN'     => 'Save (Both Modes)',

  'TAB_DROPLETS_TITLE'      => '💡 T26 Core Droplets',
  'TAB_DROPLETS_DESC'       => 'Use these droplets anywhere in your WBCE modules or templates.',
  'DROPLET_CORE_DESC'       => 'Loads the CSS/JS assets of the framework (Must be placed in the &lt;head&gt; of the template).',
  'DROPLET_COPYRIGHT_DESC'  => 'Outputs the configured footer copyright text.',
  'DROPLET_MENU_LEFT_DESC'  => 'Loads the menu for the left sidebar.',
  'DROPLET_MENU_RIGHT_DESC' => 'Loads the menu for the right sidebar.',

  'SUPPORT_TITLE'      => 'Support the Developer',
  'SUPPORT_TEXT'       => 'Do you like this module? Buy the developer <strong>Thodde26</strong> a coffee on Ko-fi!',
  'SUPPORT_BTN'        => 'Support on Ko-fi',

  'BTN_BACK'           => '⬅ Back',
  'MODULE_NO_UI'       => 'This module does not offer its own management interface yet.',
  'SAVE_SUCCESS'       => 'Successfully saved.',
  'SAVE_ERROR'         => 'Error while saving.',
  'SECURITY_ERROR'     => 'Security error (FTAN).',

  'JS_CONFIRM_LIGHT'   => "Load default variables for Light Mode?\nWarning: Your code will be overwritten!",
  'JS_CONFIRM_DARK'    => "Load default variables for Dark Mode?\nWarning: Your code will be overwritten!"
];
