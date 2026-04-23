<?php
header('Content-Type: application/json; charset=utf-8');

// 1. අලුත් URL එක generate කරන/ලබාදෙන ඔයාගේ අනිත් API එක හෝ Script එකේ ලින්ක් එක
// (උදාහරණයක් විදියට, Dialog එකෙන් token එක අරන් දෙන වෙනම script එකක් ඔයාට තියෙනවා නම් ඒක මෙතන දෙන්න)
$token_generator_url = "https://api.viulk.xyz/api/api/live/2";

// 2. cURL පාවිච්චි කරලා අලුත් URL එක fetch කරගැනීම
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $token_generator_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// ඔයාගේ අනිත් API එකට header යවන්න ඕන නම් පහත line එක පාවිච්චි කරන්න
// curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer YOUR_SECRET']); 
$fresh_mpd_url = curl_exec($ch);
curl_close($ch);

// අලුත් URL එක ආවෙ නැත්නම් පෙන්නන්න default එකක් දාමු (Error handling)
if(empty($fresh_mpd_url) || $fresh_mpd_url === false) {
    $fresh_mpd_url = "Error: Could not generate new MPD URL";
} else {
    // අනවශ්‍ය spaces තියෙනවා නම් අයින් කරන්න
    $fresh_mpd_url = trim($fresh_mpd_url);
}

$license_url = "https://api.viulk.xyz/api/api/license";

// 3. අලුතින් ගත්ත $fresh_mpd_url එක JSON එකට ඇතුලත් කිරීම
$response = [
    "status" => "ok",
    "data" => [
        "drm" => [
            "widevine" => true,
            "verimatrix" => false,
            "fairplay" => false
        ],
        "wv_license_proxy_url" => $license_url,
        "url" => $fresh_mpd_url, // <--- Auto update වෙන අලුත් ලින්ක් එක මෙතනට එනවා
        "limit_token" => "3,...",
        "isDeeplink" => null,
        "vod_type" => "INTERNAL",
        "cdn_used" => "broadpeakv1",
        "type" => "IPTV",
        "channel_uid" => "channelone"
    ]
];

// Output එක JSON විදියට ලබාදීම
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
