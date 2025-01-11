<?php

// Fungsi untuk melakukan simple GET request
function makeGetRequest($url, $headers = []) {
    $curl = curl_init();
    
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => $headers
    ]);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        return "Error: " . $err;
    }
    return $response;
}

// Fungsi untuk melakukan POST request dengan JSON data
function makePatchRequest($url, $data, $headers2 = []) {
    $curl = curl_init();
    
    // Tambahkan header content-type JSON jika belum ada
    $headers2[] = 'Content-Type: application/json';
    
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => $headers2
    ]);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        return "Error: " . $err;
    }
    return $response;
}

// Contoh penggunaan
$headers = [
     'upgrade-insecure-requests: 1',
      'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36',
       'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
       'dnt: 1',
       'x-requested-with: mark.via.gp',
       'sec-fetch-site: none',
       'sec-fetch-mode: navigate',
       'sec-fetch-user: ?1',
        'sec-fetch-dest: document',
        'referer: https://btcspinner.io/',
        'accept-encoding: gzip, deflate, br, zstd',
        'accept-language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
];
// Contoh penggunaan 2
$headers2 = [
     'upgrade-insecure-requests: 1',
      'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36',
       'accept: application/json, text/javascript, */*; q=0.01',
       'x-requested-with: mark.via.gp',
       'sec-ch-ua: "Android WebView";v="131", "Chromium";v="131", "Not_A ',
       'Brand";v="24"',
       'content-type: application/x-www-form-urlencoded; charset=UTF-8',
       'sec-ch-ua-mobile: ?1',
       'origin: https://btcspinner.io',
       'sec-fetch-site: same-origin',
       'sec-fetch-mode: cors',
       'sec-fetch-dest: empty',
       'referer: https://btcspinner.io/spinner',
       'accept-encoding: gzip, deflate, br, zstd',
       'accept-language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7'
];
// Contoh GET request
$getResponse = makeGetRequest(
    'https://btcspinner.io/spinner',
    $headers
);
echo "GET Response: " . $getResponse . "\n";

// Contoh POST request
$postData = [
    '_token' => 'cHUwzzXkYR1Dsl7WX2CrVPRstpvUj0uQXpTEHLAw',
    'token' => 'user_f1c95c9afd4aeffea4be05608042b8ab',
    'coins' => '9.074291481960144'
];

$postResponse = makePatchRequest(
    'https://btcspinner.io/spinner',
    $postData,
    $headers2
);
echo "POST Response: " . $postResponse . "\n";
?>
