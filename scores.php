<?php
/* Thermik-Sprint 3D: gemeinsame Arcade-Highscores.
   GET  ?wx=0..2                          -> {"scores":[{ini,sc,t},...]} (Top 10)
   POST {"wx":0..2,"ini":"ABC","sc":int,"t":int} -> {"scores":[...],"rank":int|null}
   Ablage: scores.json neben diesem Skript, pro Wettermodell Top 50, flock-gesichert. */

declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

const FILE_PATH = __DIR__ . '/scores.json';
const KEEP = 50;
const SHOW = 10;

function fail(int $code, string $msg): void {
    http_response_code($code);
    echo json_encode(['error' => $msg]);
    exit;
}

function top(array $list): array {
    return array_map(
        fn($e) => ['ini' => $e['ini'], 'sc' => $e['sc'], 't' => $e['t']],
        array_slice($list, 0, SHOW)
    );
}

$fh = fopen(FILE_PATH, 'c+');
if ($fh === false) fail(500, 'storage unavailable');
flock($fh, LOCK_EX);
$raw = stream_get_contents($fh);
$data = json_decode($raw ?: '{}', true);
if (!is_array($data)) $data = [];
for ($i = 0; $i <= 2; $i++) {
    if (!isset($data[$i]) || !is_array($data[$i])) $data[$i] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = json_decode(file_get_contents('php://input') ?: '', true);
    if (!is_array($body)) fail(400, 'bad json');

    $wx = $body['wx'] ?? null;
    if (!is_int($wx) || $wx < 0 || $wx > 2) fail(400, 'bad wx');

    $ini = mb_strtoupper(trim((string)($body['ini'] ?? '')));
    $ini = preg_replace('/[^A-Z0-9ÄÖÜ]/u', '', $ini);
    if ($ini === '' || mb_strlen($ini) > 3) fail(400, 'bad ini');
    $ini = str_pad($ini, 3, ' ');

    $sc = $body['sc'] ?? null;
    $t  = $body['t'] ?? 0;
    if (!is_int($sc) || $sc < 1 || $sc > 500000) fail(400, 'bad score');
    if (!is_int($t) || $t < 0 || $t > 86400) fail(400, 'bad time');
    /* ein Zielflug (t > 0) kann physikalisch nicht schneller sein, als die Polare hergibt */
    /* Punkte = Schnitt(km/h)*1000 + Resthöhe; Schnitt = 50 km / (t/3600 h) => max. 180000000/t + 1700 */
    if ($t > 0 && ($t < 800 || $sc > intdiv(180000000, $t) + 1700)) fail(400, 'implausible');

    $list = $data[$wx];
    $list[] = ['ini' => $ini, 'sc' => $sc, 't' => $t, 'ts' => time()];
    usort($list, fn($a, $b) => $b['sc'] <=> $a['sc']);
    $list = array_slice($list, 0, KEEP);
    $data[$wx] = $list;

    rewind($fh);
    ftruncate($fh, 0);
    fwrite($fh, json_encode($data, JSON_UNESCAPED_UNICODE));
    fflush($fh);
    flock($fh, LOCK_UN);
    fclose($fh);

    $rank = null;
    foreach (top($list) as $i => $e) {
        if ($rank === null && $e['ini'] === $ini && $e['sc'] === $sc) $rank = $i;
    }
    echo json_encode(['scores' => top($list), 'rank' => $rank], JSON_UNESCAPED_UNICODE);
    exit;
}

$wx = isset($_GET['wx']) ? (int)$_GET['wx'] : -1;
flock($fh, LOCK_UN);
fclose($fh);
if ($wx < 0 || $wx > 2) fail(400, 'bad wx');
echo json_encode(['scores' => top($data[$wx])], JSON_UNESCAPED_UNICODE);
