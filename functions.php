<?php
function getWeatherData($api_key, $city) {
    if (empty($city)) {
        return ['error' => ['message' => 'Please enter a city name']];
    }

    // Get current weather and forecast (3 days)
    $url = "http://api.weatherapi.com/v1/forecast.json?key={$api_key}&q={$city}&days=3&aqi=yes&alerts=no";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);
    
    if ($http_code !== 200) {
        $error_data = json_decode($response, true);
        return ['error' => [
            'message' => $error_data['error']['message'] ?? 'Failed to fetch weather data'
        ]];
    }
    
    return json_decode($response, true);
}

function getSunInfo($api_key, $city) {
    $url = "http://api.weatherapi.com/v1/forecast.json?key={$api_key}&q={$city}&days=1&aqi=no&alerts=no";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    
    $response = curl_exec($ch);
    $data = json_decode($response, true);
    
    curl_close($ch);
    
    if (isset($data['forecast']['forecastday'][0]['astro'])) {
        $astro = $data['forecast']['forecastday'][0]['astro'];
        
        // Calculate daylight duration
        $sunrise = DateTime::createFromFormat('h:i A', $astro['sunrise']);
        $sunset = DateTime::createFromFormat('h:i A', $astro['sunset']);
        $daylight = $sunset->diff($sunrise)->format('%h hr %i min');
        
        return [
            'sunrise' => $astro['sunrise'],
            'sunset' => $astro['sunset'],
            'daylight' => $daylight
        ];
    }
    
    return [
        'sunrise' => 'N/A',
        'sunset' => 'N/A',
        'daylight' => 'N/A'
    ];
}

function getHumidityDescription($humidity) {
    if ($humidity < 30) return 'Dry';
    if ($humidity < 60) return 'Comfortable';
    if ($humidity < 80) return 'Moderate';
    return 'High';
}

function getUVIndexDescription($uv) {
    if ($uv <= 2) return 'Low';
    if ($uv <= 5) return 'Moderate';
    if ($uv <= 7) return 'High';
    if ($uv <= 10) return 'Very High';
    return 'Extreme';
}

function getAQIDescription($index) {
    $levels = [
        1 => 'Good',
        2 => 'Moderate',
        3 => 'Unhealthy for Sensitive Groups',
        4 => 'Unhealthy',
        5 => 'Very Unhealthy',
        6 => 'Hazardous'
    ];
    return $levels[$index] ?? 'N/A';
}

function getAQIColors($aqiIndex) {
    $bgColors = [
        1 => '#66f066', // Light Green
        2 => '#ffff99', // Light Yellow
        3 => '#ffb366', // Light Orange
        4 => '#ff6666', // Light Red
        5 => '#c080d0', // Light Purple
        6 => '#b35c6e'  // Light Maroon
    ];

    $textColors = [
        1 => '#000000',
        2 => '#000000',
        3 => '#000000',
        4 => '#ffffff',
        5 => '#ffffff',
        6 => '#ffffff'
    ];

    return [
        'bg' => $bgColors[$aqiIndex] ?? '#ffffff',
        'text' => $textColors[$aqiIndex] ?? '#000000'
    ];
}
?>