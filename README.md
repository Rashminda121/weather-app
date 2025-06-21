# 🌦️ Weather Dashboard

A PHP-based web application that provides real-time weather information with a sleek, responsive interface.

---

## 📑 Table of Contents

- [Overview](#overview)  
- [Features](#features)  
- [Requirements](#requirements)  
- [Installation](#installation)  
- [Configuration](#configuration)  
- [Usage](#usage)  
- [API Documentation](#api-documentation)  
- [Customization](#customization)  
- [Troubleshooting](#troubleshooting)  

---

## 🌍 Overview

The Weather Dashboard is a PHP-based web application that provides real-time weather information for locations worldwide. It features a clean, responsive interface with both light and dark mode options, and offers comprehensive weather data including current conditions, forecasts, air quality, and more.

---

## ✨ Features

### 🔹 Current Weather Data:
- Temperature (actual and feels-like)
- Humidity with comfort level indicators
- Wind speed and direction
- UV index with risk assessment
- Precipitation levels
- Visibility
- Pressure

### 🔹 Extended Forecast:
- 3-day forecast with high/low temperatures
- Hourly forecast for next 6 hours
- Chance of rain indicators
- Moon phase information

### 🔹 Special Features:
- Sunrise/sunset times with daylight duration
- Air quality index with pollutant levels
- Dynamic day/night mode based on local time
- Manual light/dark theme toggle

### 🔹 User Experience:
- Responsive design for all screen sizes
- Loading indicators during API requests
- Error handling for invalid locations
- Search functionality for any city worldwide
- Auto-refresh every 15 minutes

---

## 🧰 Requirements

### 🔸 Server Requirements:
- PHP 7.4 or higher
- cURL extension enabled
- Sessions enabled

### 🔸 API Requirements:
- [WeatherAPI.com](https://www.weatherapi.com/) account (free tier available)
- Valid API key from WeatherAPI.com

### 🔸 Browser Support:
- Modern browsers (Chrome, Firefox, Safari, Edge)
- Mobile browsers (iOS, Android)

---

## 📦 Installation

1. **Download the application:**

git clone https://github.com/Rashminda121/weather-app.git
cd weather-app

### 🔧 Set up the environment

1. **Create a `.env` file** in the root directory and add your WeatherAPI key:

    env
    WEATHER_API=your_api_key_here

2. **Upload to your server:**

    Upload all files to your web server's public directory.

3. **Verify permissions:**

    Ensure the server has write permissions for session storage.

## ⚙️ Configuration

### 🌐 Environment Variables

| Variable      | Description              | Example       |
|---------------|--------------------------|---------------|
| `WEATHER_API` | Your WeatherAPI.com key  | `abc123def456`|

### 🔧 Default Settings

| Setting              | File        | Description                                     |
|----------------------|-------------|-------------------------------------------------|
| Default City         | `index.php` | Line 11 - City shown on first load (`Colombo`)  |
| Auto-refresh Interval| `index.php` | Near end - Time in ms (`900000 = 15 mins`)      |

---

## 🚀 Usage

1. **Open** `index.php` in your web browser.

2. **Search for a location:**
   - Use the search bar to type any city name.
   - Press **Enter** or click the search icon.

3. **Toggle theme mode:**
   - Click the theme toggle in the top-right corner.
   - The system will remember your preference.

4. **View weather details:**
   - Current conditions appear at the top.
   - Scroll down to see forecasts and additional widgets.
  
## 📡 API Documentation

### Endpoint Used:

GET /v1/forecast.json?key={key}&q={location}&days=3&aqi=yes&alerts=no

### Response Includes:

- Location data (name, region, country, localtime)
- Current conditions (temperature, humidity, wind, etc.)
- 3-day forecast (hourly breakdown)
- Air quality index and pollutant levels

---

## 🎨 Customization

### 🖌️ UI Customization

Edit `style.css` to change:

- Color schemes (CSS variables)
- Layout and dimensions
- Fonts and typography
- Widget designs

### 🛠️ Functional Customization

Edit `functions.php` to:

- Add new metrics
- Adjust API parsing
- Modify default logic

### 🌐 Language Localization

- Create a file like `lang/en.php`
- Replace hardcoded strings with language variables
- Include the language file in `index.php`

## 🧪 Troubleshooting

### Common Issues

| Issue               | Solution                                  |
|---------------------|-------------------------------------------|
| No weather data     | Check API key and cURL extension          |
| Location not found  | Use specific names, e.g., `London,UK`     |
| Theme not persisting| Ensure sessions are enabled, check JS errors |
| Mobile layout issues| Ensure proper viewport meta tag            |

### Error Messages

| Error                       | Suggestion                             |
|-----------------------------|---------------------------------------|
| Failed to fetch weather data | Check API key and internet connection |
| Please enter a city name     | Input is required                      |
| Location not found           | Try a different location or add country |

---

Thank you for using the Weather Dashboard!  
For questions, issues, or contributions, please feel free to open an issue or submit a pull request.

Stay safe and enjoy accurate weather updates! ☀️🌧️🌙



