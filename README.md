# Thermik-Sprint 3D

Kleiner Streckenflug-Simulator für den Browser, in 3D (three.js). Fliege 50 km über
das Münsterland nach **EDXE Rheine-Eschendorf**, Anflug über **EDWN Nordhorn-Lingen**
(bei km 29 vor dem Ziel). Die Landschaft — Wälder, Flüsse (Ems!), Ortschaften —
stammt aus OpenStreetMap (`map-data.js`, einmalig per Overpass gebaut), inklusive
echter Gebäudegrundrisse, Hauptstraßen und der Windräder des Emslands. Das
Segelflugzeug ist einer Glasflügel Standard Libelle nachempfunden.

Eine einzige Datei, kein Build. three.js wird als ES-Modul vom CDN geladen —
`index.html` braucht also einmalig eine Internetverbindung.

## Bedienung

- **Touch:** Himmel gedrückt halten zum Kreisen, mit „Langsamer" und „Schneller" die Fahrt ändern.
- **Tastatur:** `↑` / `↓` für die Fahrt, Leertaste halten zum Kreisen, Enter für einen neuen Flug.

## Spielprinzip

- Start auf 1200 m, bei 1700 m kratzt man knapp unter den Wolken, Ziel nach 50 km.
- Die Restanzeige und die Schilder zählen rückwärts bis EDXE.
- Beim Loslassen der Kreisen-Taste wird die Kurve zu Ende geflogen, bis die Nase
  wieder auf Kurs zeigt.
- Schneller fliegen kostet Höhe (Gleitzahl 1:38 bei ~110 km/h, bei 160 nur noch 1:26).
- Um jeden Bart liegt ein Ring aus Sinken. Nicht jeder Bart lohnt sich.
- Hinter EDWN Nordhorn-Lingen steht keine Thermik mehr: 29 km blauer Endanflug,
  der je nach Fahrt gut 800–1000 m Höhe braucht. Der Endanflugrechner zeigt,
  ob die Höhe bis ins Ziel reicht.
- Die Zeit läuft 20-fach beschleunigt.

## Level

Fünf feste Level. Innerhalb eines Levels hat **jeder Bart dieselbe Stärke**, und die
Bärte stehen bei jedem Start an denselben Stellen. Mit dem Level sinken Starthöhe
und Wolkenbasis — ein zäher Tag hängt tiefer:

| Level | Steigen | Start | Basis | Charakter |
|---|---|---|---|---|
| L1 Hammertag | 4,5 m/s | 1200 m | 1700 m | trägt im Geradeausflug durch — nichts tun |
| L2 Guter Tag | 3,3 m/s | 1000 m | 1700 m | fast durchgleitbar, 1–2 Bärte nötig |
| L3 Streckentag | 2,5 m/s | 850 m | 1600 m | Höhe einteilen |
| L4 Zäher Tag | 1,8 m/s | 650 m | 1450 m | jeder Bart muss sitzen |
| L5 ULTRA | 1,15 m/s | 420 m | 1150 m | fast jeden Bart voll auskurbeln — gerade so schaffbar |

Die Balance ist gegen eine Nachbildung der Flugphysik simuliert: L1 erreicht das
Ziel ohne jede Eingabe, L5 kommt mit allen Bärten mit ~240 m Resthöhe an.

## Highscores

Arcade-Bestenliste wie in den 80ern: drei Initialen, Top 10, pro Level eine
eigene Liste. Punkte = Schnittgeschwindigkeit × 1000 + Resthöhe im Ziel; eine
Außenlandung bringt 250 Punkte pro geflogenem Kilometer.

Liegt `scores.php` mit auf einem PHP-fähigen Server, ist die Liste **gemeinsam für
alle Spieler** (Ablage in `scores.json` neben dem Skript, mit Plausibilitätsprüfung der gemeldeten
Punkte gegen die Polare, Außenlandungen gegen die Maximaldistanz). Ohne PHP-Backend fällt das Spiel automatisch auf eine lokale
Liste im `localStorage` zurück. Die Punkte werden im Browser gemessen und sind nicht
manipulationssicher.
