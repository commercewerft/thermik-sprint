# Thermik-Sprint 3D

Kleiner Streckenflug-Simulator für den Browser, jetzt in 3D (three.js). Fliege 50 km
so schnell wie möglich: Kreise in Thermikbärten, gleite zwischen den Low-Poly-Wolken
und komm mit Endanflughöhe ins Ziel.

Eine einzige Datei, kein Build. three.js wird als ES-Modul vom CDN geladen —
`index.html` braucht also einmalig eine Internetverbindung.

## Bedienung

- **Touch:** Himmel gedrückt halten zum Kreisen, mit „Langsamer" und „Schneller" die Fahrt ändern.
- **Tastatur:** `↑` / `↓` für die Fahrt, Leertaste halten zum Kreisen, Enter für einen neuen Flug.

## Spielprinzip

- Start auf 1200 m, Wolkenbasis bei 1700 m, Ziel nach 50 km.
- Schneller fliegen kostet Höhe (Polare grob eines Doppelsitzers, Gleitzahl ca. 43).
- Um jeden Bart liegt ein Ring aus Sinken. Nicht jeder Bart lohnt sich.
- Der Endanflugrechner zeigt, ob die Höhe bis ins Ziel reicht.
- Die Zeit läuft 20-fach beschleunigt.

## Wetter

Drei feste Wettermodelle, jeweils mit identischen Bärten bei jedem Start:

- **Wenig Thermik** — wenige, schwache Bärte mit großen Lücken. Geduldsspiel.
- **Mittlere Thermik** — ein normaler Streckentag.
- **Starke Thermik** — kräftige Bärte dicht an dicht. Vollgas.

## Highscores

Arcade-Bestenliste wie in den 80ern: drei Initialen, Top 10, pro Wettermodell eine
eigene Liste. Punkte = Schnittgeschwindigkeit × 1000 + Resthöhe im Ziel; eine
Außenlandung bringt 250 Punkte pro geflogenem Kilometer.

Liegt `scores.php` mit auf einem PHP-fähigen Server, ist die Liste **gemeinsam für
alle Spieler** (Ablage in `scores.json` neben dem Skript, mit Plausibilitätsprüfung
der gemeldeten Punkte). Ohne PHP-Backend fällt das Spiel automatisch auf eine lokale
Liste im `localStorage` zurück. Die Punkte werden im Browser gemessen und sind nicht
manipulationssicher.
