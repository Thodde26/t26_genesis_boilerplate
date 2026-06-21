# 📦 T26 Genesis Boilerplate - WBCE CMS

[![Version](https://img.shields.io/badge/Version-1.2.0-blue.svg)](#)
[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-green.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![Platform](https://img.shields.io/badge/Platform-WBCE%201.6+-orange.svg)](https://wbce.org)

Die **T26 Genesis Boilerplate** ist deine saubere, sofort einsatzbereite Entwickler-Schablone für das **WBCE CMS**.
Sie wurde aus dem T26 Genesis Core extrahiert und dient als leichtgewichtiges Fundament, um blitzschnell eigene, voll funktionsfähige Admin-Tools und Submodule im T26-Ökosystem zu entwickeln – ganz ohne lästigen Code-Ballast.

---

## 🚀 Die Kern-Features im Überblick

| Feature | Beschreibung |
| :--- | :--- |
| **Clean Code Basis** | Eine aufgeräumte, dynamische Modulstruktur, bei der sich Ordner- und Tabellennamen automatisch anpassen. Perfekt, um sofort mit der eigenen Logik zu starten. |
| **Nahtlose Core-Integration** | Fügt sich optisch und technisch nahtlos in den T26 Genesis Master-Hub ein. |
| **Hybrid-CSS Engine** | Wähle aus vorgefertigten T26-Presets oder definiere eigene Color-Schemata. Diese werden in der DB gespeichert UND als performante, statische `.css` Datei generiert. |
| **Erweiterter CSS-Editor** | Integrierter **CodeMirror-Editor** im Backend inkl. Zeilennummern und Syntax-Highlighting für Custom-CSS (getrennt nach Light/Dark Mode). |
| **Höchste Sicherheit** | Vollständiger FTAN-Schutz (CSRF), Strict Types, Vanilla JS und konsequentes Escaping out of the box. Backend-UI ist barrierefrei nach WCAG 2.1 AA. |

---

## 🛠️ Installation & Aktivierung

1. Lade dir die aktuelle `.zip`-Datei herunter.
2. Logge dich in dein WBCE-Backend ein.
3. Navigiere zu **Erweiterungen → Module → Modul installieren**.
4. Wähle die `.zip`-Datei aus und klicke auf **Installieren**.
*(Die `install.php` legt vollautomatisch alle benötigten Datenbanktabellen an).*

---

## 📁 Dateistruktur

```text
/modules/t26_genesis_boilerplate/
├── css/
│   ├── backend.css        # Styles ausschließlich für das WBCE-Backend
│   ├── frontend.css       # Allgemeine Styles für die öffentliche Seite
│   └── generated/         # Automatisch generierte CSS-Dateien (Custom Light/Dark)
├── js/
│   ├── backend.js         # Interaktive Backend-Logik (Live-Vorschau, Tabs, Editor)
│   └── frontend.js        # Skripte für die öffentliche Seite
├── languages/             # Sprachdateien (DE.php, EN.php) für i18n
├── info.php               # Modul-Registrierung & Basis-Setup
├── install.php            # Auto-Installation für Datenbanktabellen
├── uninstall.php          # Sauberes Clean-up (Sicherheitslöschung)
├── upgrade.php            # Idempotente Update-Routinen
├── tool.php               # Das Backend-UI deiner Erweiterung
└── save_tool.php          # Die Backend-Schleuse (Speicherung, CSS-Kompilierung)
📝 Lizenz & Support
Autor: Thorsten (Thodde26)

Website: www.thodde26.de

Lizenz: GNU General Public License v3.0 (GPLv3)

Support: Dir gefällt diese Arbeit und sie spart dir Zeit? Ich freue mich über einen virtuellen Kaffee auf Ko-fi! ☕
