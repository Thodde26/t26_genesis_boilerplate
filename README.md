# 📦 T26 Genesis Boilerplate - WBCE CMS

[![Version](https://img.shields.io/badge/Version-1.1.0-blue.svg)](#)
[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-green.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![Platform](https://img.shields.io/badge/Platform-WBCE%201.6+-orange.svg)](https://wbce.org)

Das **T26 Genesis Boilerplate - WBCE CMS
** ist das zentrale "Betriebssystem" und Steuerungsmodul (Admin-Tool) für das **WBCE CMS**.
Es wurde speziell konzipiert, um das gesamte T26 Genesis Ökosystem zentral zu verwalten, globale Systemeinstellungen bereitzustellen und als intelligenter Hub für alle zukünftigen T26-Submodule (Boilerplates & Tools) zu agieren.

---

## 🚀 Die Kern-Features im Überblick

| Feature | Beschreibung |
| :--- | :--- |
| **App Store & Update-Hub** | Nativer Package-Manager: Das Core-Modul verbindet sich mit dem offiziellen GitHub-Repository. Module können mit einem Klick aus dem Backend installiert und aktualisiert werden. |
| **Custom Repositories** | Voll dezentral: Andere Entwickler können ihre eigenen `modules.json` URLs hinterlegen, um eigene T26-Erweiterungen direkt in deinem App Store anzubieten (ähnlich wie bei Linux `apt` oder Node `npm`). |
| **Master-Takeover (Hub)** | Das Core-Modul scannt installierte T26-Submodule (z. B. `t26_tool_boilerplate`) und lädt deren Einstellungsseiten direkt in die eigene Oberfläche. Du musst das Master-Dashboard nie verlassen. |
| **Hybrid-CSS Engine** | Wähle aus vorgefertigten Presets oder erstelle eigene Color-Schemata im Backend. Diese werden in der DB gespeichert UND als performante, statische `.css` Datei für das Frontend kompiliert. |
| **Erweiterter CSS-Editor** | Integrierter **CodeMirror-Editor** im Backend inkl. Zeilennummern und Syntax-Highlighting für Custom-CSS (getrennt nach Light/Dark Mode) – inkl. Template-Generierung auf Knopfdruck. |
| **Medien & Branding** | Sicherer Upload von Haupt-Logo, Dark Mode Logo, Mobile Logo, Favicon und Apple Touch Icon in einen dedizierten, abgeriegelten Media-Ordner. |
| **Dynamisches Routing** | Komfortable Zuweisung von WBCE-Standard-Menüs zu den jeweiligen Layout-Bereichen (Header, Footer, Sidebars) via intelligenter T26-Droplets. |
| **Höchste Sicherheit** | Vollständiger FTAN-Schutz (CSRF), Strict Types, Vanilla JS, kugelsichere Upload-Schleusen und konsequentes Escaping. Backend-UI ist barrierefrei nach WCAG 2.1 AA. |

---

## 🛠️ Installation & Aktivierung

1. Lade dir die aktuelle `.zip`-Datei herunter.
2. Logge dich in dein WBCE-Backend ein.
3. Navigiere zu **Erweiterungen → Module → Modul installieren**.
4. Wähle die `.zip`-Datei aus und klicke auf **Installieren**.
*(Die `install.php` legt vollautomatisch alle benötigten Datenbanken, Core-Droplets und sicheren Medien-Ordner an).*

---

## 📁 Dateistruktur

```text
/media/t26_genesis_core/logos/   # Sicherer Upload-Ordner für Branding-Dateien
/modules/t26_genesis_core/
├── css/
│   ├── backend.css        # Styles ausschließlich für das WBCE-Backend
│   ├── frontend.css       # Allgemeine Styles für die öffentliche Seite
│   └── generated/         # Automatisch generierte CSS-Dateien (Custom Light/Dark)
├── js/
│   ├── backend.js         # Interaktive Backend-Logik (Live-Vorschau, Tabs, Editor)
│   └── frontend.js        # Skripte für die öffentliche Seite
├── languages/             # Sprachdateien (DE.php, EN.php) für i18n
├── info.php               # Modul-Registrierung
├── install.php            # DB-Setup, Auto-Ordner & Droplet-Installation
├── uninstall.php          # Sauberes Clean-up (Sicherheitslöschung)
├── upgrade.php            # Idempotente Update-Routinen & Selbstheilung
├── tool.php               # Das Master-Dashboard & App Store UI
└── save_tool.php          # Die Backend-Schleuse (Speicherung, CSS-Kompilierung)
📝 Lizenz & Support
Autor: Thorsten (Thodde26)

Website: www.thodde26.de

Lizenz: GNU General Public License v3.0 (GPLv3)

Support: Dir gefällt diese Arbeit und sie spart dir Zeit? Ich freue mich über einen virtuellen Kaffee auf Ko-fi! ☕
