<?php
/**
 * sync-from-github.php
 *
 * Server-side sync for internal.php — GitHub is the source of truth.
 *
 * Run this from a cPanel Cron Job every 15 minutes, e.g.:
 *
 *   php /home/edjnxkvg07x2/public_html/jerryscvv.vc/sync-from-github.php
 *
 * It downloads the canonical internal.php from GitHub, compares it with the
 * local copy, and overwrites the local copy if they differ.
 */

// Only allow command-line execution (blocks web visitors from triggering it).
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Forbidden');
}

$repoRawUrl = 'https://raw.githubusercontent.com/itssarthakarora-alt/project_jerry/main/internal.php';
$localFile  = __DIR__ . '/internal.php';

// Download the canonical copy from GitHub.
$remote = false;
if (function_exists('curl_init')) {
    $ch = curl_init($repoRawUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    $remote = curl_exec($ch);
    curl_close($ch);
}
if ($remote === false) {
    $remote = @file_get_contents($repoRawUrl);
}
if ($remote === false || $remote === '') {
    exit("ERROR: could not download {$repoRawUrl}\n");
}

// Compare and overwrite only if different.
if (!file_exists($localFile) || file_get_contents($localFile) !== $remote) {
    if (file_put_contents($localFile, $remote) === false) {
        exit("ERROR: could not write {$localFile}\n");
    }
    echo "SYNCED: internal.php updated from GitHub\n";
} else {
    echo "OK: internal.php already in sync\n";
}
