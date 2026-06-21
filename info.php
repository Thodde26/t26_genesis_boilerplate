<?php

declare(strict_types=1);
/**
 * T26 Genesis Boilerplate - WBCE CMS
 * @author      Thodde26 (Thorsten)
 * @link        https://www.thodde26.de
 * @file        info.php
 * @license     http://www.gnu.org/licenses/gpl.html
 * GNU General Public License v3.0
 */

if (!defined('WB_PATH')) {
  die('Cannot access this file directly');
}

$module_directory   = 't26_genesis_boilerplate';
$module_name        = 'T26 Genesis Boilerplate';
$module_function    = 'tool'; // Admin-Tool & Frontend-Funktionen
$module_version     = '1.2.0';
$module_platform    = '1.6.x';
$module_author      = 'Thodde26 (Thorsten)';
$module_license     = 'GNU General Public License v3.0';
$module_description = 'Zentrales Admin-Tool und Framework-Motor für das T26 Genesis System (Theme-Steuerung, Droplets & Core-Logik).';
$module_guid        = 'e94b12a8-1c44-48f1-a1b2-3c4d5e6f7a8b';

// CSS & JS Ressourcen laden
$module_css_backend  = 'css/backend.css';
$module_css_frontend = 'css/frontend.css';
$module_js_backend   = 'js/backend.js';
$module_js_frontend  = 'js/frontend.js';

// 🌍 Menü-Definitionen für das WBCE Backend
$menu[1] = 'Main-Menu (Header)';
$menu[2] = 'Footer-Menü (Unten)';
$menu[3] = 'Linke Sidebar';
$menu[4] = 'Rechte Sidebar';
