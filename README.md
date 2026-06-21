# 📦 T26 Genesis Boilerplate - WBCE CMS

[![Version](https://img.shields.io/badge/Version-1.2.0-blue.svg)](#)
[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-green.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![Platform](https://img.shields.io/badge/Platform-WBCE%201.6+-orange.svg)](https://wbce.org)

Die **T26 Genesis Boilerplate** ist deine saubere, 100 % dynamische Entwickler-Schablone (Kopiervorlage) für das **WBCE CMS**.
Sie dient als leichtgewichtiges Fundament, um blitzschnell eigene, voll funktionsfähige Admin-Tools und Submodule im T26-Ökosystem zu entwickeln – ohne lästigen Code-Ballast und mit automatischer Pfad-Erkennung.

---

## 🚀 Die Kern-Features im Überblick

| Feature | Beschreibung |
| :--- | :--- |
| **Chamäleon-Architektur** | 100 % dynamisch: Kopiere den Ordner, benenne ihn um und das Modul passt seine Datenbanktabellen, Pfade und Droplets vollautomatisch an (`basename(__DIR__)`). |
| **Core-Sync & Autarkie** | Integrierter Soft-Kill-Switch. Wähle im Backend, ob dein neues Modul das Design vom *T26 Genesis Core* erben soll (Sync) oder als komplett eigenständiges Tool läuft. |
| **Hybrid-CSS Engine** | Wähle aus vorgefertigten T26-Presets oder erstelle eigene Color-Schemata. Diese werden als performante, statische `.css` Datei in deinem Modulordner generiert. |
| **Erweiterter CSS-Editor** | Integrierter **CodeMirror-Editor** im Backend inkl. Zeilennummern und Syntax-Highlighting für Custom-CSS (getrennt nach Light/Dark Mode). |
| **Höchste Sicherheit** | Vollständiger FTAN-Schutz (CSRF), Strict Types, Vanilla JS und konsequentes Escaping out of the box. Das Backend-UI ist barrierefrei nach WCAG 2.1 AA. |

---

## 🛠️ Modul klonen & umbenennen (Dein Workflow)

Da dies eine Boilerplate ist, installierst du sie meistens nicht direkt, sondern nutzt sie als Vorlage für neue Projekte:

1. Lade dir die aktuelle `.zip`-Datei herunter und entpacke sie lokal.
2. Benenne den Hauptordner `t26_genesis_boilerplate` in dein neues Projekt um (z. B. in `t26_meine_erweiterung`).
3. Öffne die `info.php` und passe `$module_directory` (muss exakt wie der Ordner heißen) sowie den `$module_name` an.
4. Nutze in deinem Code-Editor "Suchen und Ersetzen" und ersetze das Wort `_BOILERPLATE` durch deinen neuen Namen (für die Sprachvariablen).
5. Zippen, im WBCE-Backend installieren – fertig! Das Modul installiert sich nun dynamisch unter seinem neuen Namen.

---

## 📁 Dateistruktur

```text
/modules/t26_genesis_boilerplate/
├── css/
│   ├── backend.css        # Styles ausschließlich für das WBCE-Backend
│   ├── frontend.css       # Allgemeine Styles für die öffentliche Seite
│   └── generated/         # Automatisch generierte CSS-Dateien (Light/Dark)
├── js/
│   ├── backend.js         # Interaktive Backend-Logik (Live-Vorschau, Tabs, Editor)
│   └── frontend.js        # Skripte für die öffentliche Seite
├── languages/             # Sprachdateien (DE.php, EN.php) für i18n
├── info.php               # Statische Modul-Registrierung
├── install.php            # Dynamische Auto-Installation für Datenbanktabellen & Droplets
├── uninstall.php          # Dynamischer "Staubsauger" (Rückstandsfreie Löschung)
├── upgrade.php            # Dynamische Update-Routinen
├── tool.php               # Das Backend-UI deiner Erweiterung
└── save_tool.php          # Die Backend-Schleuse (Speicherung, CSS-Kompilierung)
📝 Lizenz & Support
Autor: Thorsten (Thodde26)

Website: www.thodde26.de

Lizenz: GNU General Public License v3.0 (GPLv3)

Support: Dir gefällt diese Arbeit und sie spart dir Zeit? Ich freue mich über einen virtuellen Kaffee auf Ko-fi! ☕
