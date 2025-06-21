<?php
require_once 'functions.php';

require_once 'load_env.php';
loadEnv(__DIR__ . '/.env');

$api_key = $_ENV['WEATHER_API'];
$default_city = 'Colombo';
$city = isset($_GET['city']) ? $_GET['city'] : $default_city;

// Fetch weather data
$weather_data = getWeatherData($api_key, $city);
$is_day = isset($weather_data['current']['is_day']) ? $weather_data['current']['is_day'] : 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container <?php echo $is_day ? 'day' : 'night'; ?>">
        <h1 class="app-title">Weather Forecast</h1>
        
        <div class="search-container">
            <form action="" method="GET">
                <input type="text" name="city" placeholder="Search for a city..." value="<?php echo htmlspecialchars($city); ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        
        <?php if (isset($weather_data['error'])): ?>
            <div class="error-message">
                <p><?php echo $weather_data['error']['message']; ?></p>
            </div>
        <?php elseif ($weather_data): ?>
            <div class="weather-card">
                <div class="location">
                    <h2><?php echo $weather_data['location']['name']; ?>, <?php echo $weather_data['location']['country']; ?></h2>
                    <p class="date"><?php echo date('l, F j, Y', strtotime($weather_data['location']['localtime'])); ?></p>
                </div>
                
                <div class="current-weather">
                    <div class="temperature">
                        <img src="<?php echo $weather_data['current']['condition']['icon']; ?>" alt="<?php echo $weather_data['current']['condition']['text']; ?>">
                        <span><?php echo round($weather_data['current']['temp_c']); ?>°C</span>
                    </div>
                    <div class="condition">
                        <p><?php echo $weather_data['current']['condition']['text']; ?></p>
                        <p>Feels like <?php echo round($weather_data['current']['feelslike_c']); ?>°C</p>
                    </div>
                </div>
                
                <div class="weather-details">
                    <div class="detail">
                        <i class="fas fa-tint"></i>
                        <div>
                            <span>Humidity</span>
                            <span><?php echo $weather_data['current']['humidity']; ?>%</span>
                        </div>
                    </div>
                    <div class="detail">
                        <i class="fas fa-wind"></i>
                        <div>
                            <span>Wind</span>
                            <span><?php echo round($weather_data['current']['wind_kph']); ?> km/h</span>
                        </div>
                    </div>
                    <div class="detail">
                        <i class="fas fa-sun"></i>
                        <div>
                            <span>UV Index</span>
                            <span><?php echo $weather_data['current']['uv']; ?></span>
                        </div>
                    </div>
                    <div class="detail">
                        <i class="fas fa-cloud"></i>
                        <div>
                            <span>Cloud Cover</span>
                            <span><?php echo $weather_data['current']['cloud']; ?>%</span>
                        </div>
                    </div>
                    <div class="detail">
                        <i class="fas fa-eye"></i>
                        <div>
                            <span>Visibility</span>
                            <span><?php echo round($weather_data['current']['vis_km']); ?> km</span>
                        </div>
                    </div>
                    <div class="detail">
                        <i class="fas fa-temperature-high"></i>
                        <div>
                            <span>Pressure</span>
                            <span><?php echo $weather_data['current']['pressure_mb']; ?> mb</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="loading">
                <div class="spinner"></div>
                <p>Loading weather data...</p>
            </div>
        <?php endif; ?>
        
        <footer>
            <p>Weather data provided by <a href="https://www.weatherapi.com/" target="_blank">WeatherAPI.com</a></p>
        </footer>
    </div>
</body>
</html>