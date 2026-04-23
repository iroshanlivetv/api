<?php
header('Content-Type: application/json; charset=utf-8');

// 1. අලුත් URL එක ලබාගන්නා තැන
$token_generator_url = "https://api.viulk.xyz/api/api/live/2";

// 2. cURL භාවිතයෙන් දත්ත ලබාගැනීම
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $token_generator_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Redirect වෙනවා නම් ඒ පස්සේ යන්න
$fresh_mpd_url = curl_exec($ch);
curl_close($ch);

// Error handling
if(empty($fresh_mpd_url)) {
    $fresh_mpd_url = "Error: Could not fetch URL";
} else {
    $fresh_mpd_url = trim($fresh_mpd_url);
}

$license_url = "https://api.viulk.xyz/api/api/license";

// 3. JSON Response එක සකස් කිරීම
$response = [
    "status" => "ok",
    "data" => [
        "drm" => [
            "widevine" => true,
            "verimatrix" => false,
            "fairplay" => false
        ],
        "wv_license_proxy_url" => $license_url,
        "url" => $fresh_mpd_url,
        "limit_token" => "3,...",
        "isDeeplink" => null,
        "vod_type" => "INTERNAL",
        "cdn_used" => "broadpeakv1",
        "type" => "IPTV",
        "channel_uid" => "channelone"
    ]
];

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
