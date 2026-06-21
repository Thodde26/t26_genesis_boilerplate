/**
 * T26 Genesis Boilerplate - WBCE CMS
 * @author      Thodde26 (Thorsten)
 * @Version     1.1.0
 * @link        https://www.thodde26.de
 * @file        backend.js
 * @license     http://www.gnu.org/licenses/gpl.html
 * GNU General Public License v3.0
 */

document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const wrapper = document.getElementById('t26-live-wrapper');
    const themeSelect = document.getElementById('active_theme');

    // ── 1. TAB-NAVIGATION ───────────────────────────────────────────────────
    const tabBtns = document.querySelectorAll('.t26-tab-btn');
    const tabContents = document.querySelectorAll('.t26-tab-content');

    if(tabBtns.length > 0 && tabContents.length > 0) {
        tabBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                btn.classList.add('active');

                const targetId = btn.getAttribute('data-target');
                const targetContent = document.getElementById(targetId);
                if(targetContent) {
                    targetContent.classList.add('active');
                }
            });
        });
    }

    // ── 2. THEME-DROPDOWN LIVE-VORSCHAU (CHAMÄLEON-MODUS) ───────────────────
    if (themeSelect && wrapper) {
        themeSelect.addEventListener('change', function() {
            wrapper.setAttribute('data-t26-theme', this.value);

            // 🔥 FIX: Aktualisiert die versteckten Felder in ALLEN Formularen zeitgleich!
            const hiddenFields = document.querySelectorAll('.t26-hidden-theme-field, #hidden_active_theme_colors');
            hiddenFields.forEach(field => {
                if(field) field.value = this.value;
            });

            if(this.value !== 'custom_light' && this.value !== 'custom_dark') {
                wrapper.style = '';
            } else {
                // Bei Custom: Lade SOFORT die Farben aus den Pickern für die Live-Vorschau!
                const mode = this.value === 'custom_dark' ? 'dark' : 'light';
                const pickers = document.querySelectorAll(`input[type="color"][id^="${mode}_"]`);

                pickers.forEach(picker => {
                    let varName = '--t26-' + picker.id.replace(`${mode}_`, '').replace(/_/g, '-');
                    wrapper.style.setProperty(varName, picker.value);
                });

                // Tab "Farben" blinkt kurz auf
                const colorTabBtn = document.querySelector('.t26-tab-btn[data-target="tab-colors"]');
                if (colorTabBtn) {
                    colorTabBtn.style.transition = 'background-color 0.3s ease';
                    colorTabBtn.style.backgroundColor = 'var(--t26-primary-base, #0b5ed7)';
                    colorTabBtn.style.color = '#fff';
                    setTimeout(() => {
                        colorTabBtn.style.backgroundColor = '';
                        colorTabBtn.style.color = '';
                    }, 800);
                }
            }
        });
    }

    // ── 3. LIVE-VORSCHAU FÜR CUSTOM COLORS ──────────────────────────────────
    const colorPickers = document.querySelectorAll('input[type="color"][id^="light_"], input[type="color"][id^="dark_"]');

    if (colorPickers.length > 0 && wrapper) {
        colorPickers.forEach(picker => {
            picker.addEventListener('input', function() {
                if (themeSelect) {
                    const isDarkCustom = themeSelect.value === 'custom_dark';
                    const isLightCustom = themeSelect.value === 'custom_light';

                    // Verhindere, dass das Drehen am Dark-Picker das aktive Light-Theme live beeinflusst!
                    if (isLightCustom && this.id.startsWith('light_')) {
                        let varName = '--t26-' + this.id.replace('light_', '').replace(/_/g, '-');
                        wrapper.style.setProperty(varName, this.value);
                    } else if (isDarkCustom && this.id.startsWith('dark_')) {
                        let varName = '--t26-' + this.id.replace('dark_', '').replace(/_/g, '-');
                        wrapper.style.setProperty(varName, this.value);
                    }
                }
            });
        });
    }

    // ── 4. RESET-KNOPF (STANDARD-FARBEN LADEN) ──────────────────────────────
    const resetBtn = document.getElementById('t26-reset-colors-btn');

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if(!confirm('Möchtest du wirklich alle Farben auf das T26 Standard-Blau zurücksetzen?')) {
                return;
            }

            // Die perfekten T26 Standard-Blau Werte
            const defaultColors = {
                'light_primary_base': '#1a73e8',
                'light_primary_hover': '#1557b0',
                'light_bg_body': '#f8f9fa',
                'light_bg_surface': '#ffffff',
                'light_text_main': '#334155',
                'light_text_muted': '#64748b',
                'light_border_color': '#cbd5e0',
                'light_accent_color': '#1a73e8',

                'dark_primary_base': '#7A9BDB',
                'dark_primary_hover': '#244684',
                'dark_bg_body': '#121212',
                'dark_bg_surface': '#1a1a1a',
                'dark_text_main': '#e0e0e0',
                'dark_text_muted': '#aaaaaa',
                'dark_border_color': '#333333',
                'dark_accent_color': '#7A9BDB'
            };

            // Felder füllen und Live-Vorschau triggern
            for (const [id, hexValue] of Object.entries(defaultColors)) {
                const inputField = document.getElementById(id);
                if (inputField) {
                    inputField.value = hexValue;
                    inputField.dispatchEvent(new Event('input'));
                }
            }
        });
    }

    // ── 5. CODEMIRROR LOGIK (CSS Editor Erweiterung) ────────────────────────
    const lightTextarea = document.getElementById('t26_light_textarea');
    const darkTextarea = document.getElementById('t26_dark_textarea');
    const modeSelector = document.getElementById('t26-css-mode-selector');
    const loadDefaultsBtn = document.getElementById('t26-load-defaults');

    let cmLight, cmDark;

    if (lightTextarea && darkTextarea && typeof CodeMirror !== 'undefined') {
        cmLight = CodeMirror.fromTextArea(lightTextarea, { mode: "css", lineNumbers: true, theme: "default" });
        cmDark = CodeMirror.fromTextArea(darkTextarea, { mode: "css", lineNumbers: true, theme: "monokai" });

        setTimeout(() => { if (cmDark) cmDark.refresh(); }, 100);

        if (modeSelector) {
            modeSelector.addEventListener('change', function(e) {
                if (e.target.value === 'light') {
                    document.getElementById('t26-css-editor-light').style.display = 'block';
                    document.getElementById('t26-css-editor-dark').style.display = 'none';
                    cmLight.refresh();
                } else {
                    document.getElementById('t26-css-editor-light').style.display = 'none';
                    document.getElementById('t26-css-editor-dark').style.display = 'block';
                    cmDark.refresh();
                }
            });
        }

        if(loadDefaultsBtn) {
            loadDefaultsBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const currentMode = modeSelector.value;
                const confirmMsg = currentMode === 'light'
                    ? "Standard-Variablen für den Light Mode laden?\nAchtung: Dein aktueller Code im Editor wird überschrieben!"
                    : "Standard-Variablen für den Dark Mode laden?\nAchtung: Dein aktueller Code im Editor wird überschrieben!";

                if (confirm(confirmMsg)) {
                    if (currentMode === 'light') {
                        const defaultLightCSS = `/* Standard T26 Light Mode Variablen */\n:root {\n  --t26-primary-base: #1a73e8;\n  --t26-primary-hover: #1557b0;\n  --t26-bg-body: #f8f9fa;\n  --t26-bg-surface: #ffffff;\n  --t26-text-main: #334155;\n  --t26-text-muted: #64748b;\n  --t26-border-color: #cbd5e0;\n  --t26-input-bg: #ffffff;\n  --t26-input-color: #334155;\n  --t26-input-border: #cbd5e0;\n}\n\n/* Dein eigenes Light-Mode CSS hier: */\n`;
                        cmLight.setValue(defaultLightCSS);
                    } else {
                        const defaultDarkCSS = `/* Standard T26 Dark Mode Variablen */\n:root {\n  --t26-primary-base: #7A9BDB;\n  --t26-primary-hover: #244684;\n  --t26-bg-body: #121212;\n  --t26-bg-surface: #1a1a1a;\n  --t26-text-main: #e0e0e0;\n  --t26-text-muted: #aaaaaa;\n  --t26-border-color: #333333;\n  --t26-input-bg: #222222;\n  --t26-input-color: #e0e0e0;\n  --t26-input-border: #444444;\n}\n\n/* Dein eigenes Dark-Mode CSS hier: */\n`;
                        cmDark.setValue(defaultDarkCSS);
                    }
                }
            });
        }
    }

    // ── 6. MASTER-SWITCH LOGIK (Ausgrauen bei Inaktivität) ──────────────────
    const masterSwitch = document.getElementById('t26_is_active');
    const disableableAreas = document.querySelectorAll('.t26-disableable-area');

    if (masterSwitch && disableableAreas.length > 0) {
        masterSwitch.addEventListener('change', (e) => {
            if (e.target.value === "0") { // 0 = Pausiert -> Ausgrauen AN
                disableableAreas.forEach(area => area.classList.add('t26-settings-disabled'));
            } else { // 1 = Aktiv -> Ausgrauen AUS
                disableableAreas.forEach(area => area.classList.remove('t26-settings-disabled'));
            }
        });
    }
});
