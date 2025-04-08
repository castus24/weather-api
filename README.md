# Weather API Service

## Описание

Сервис для получения текущих погодных данных через OpenWeatherMap API. Возвращает информацию о температуре, ветре, давлении и других метеорологических показателях для запрашиваемого города.

### Установка

1. Клонируйте репозиторий:

   ```
   git clone https://github.com/castus24/weather-api

2. Установите зависимости с помощью Composer:

   ```bash
   composer install

3. Скопируйте файл .env.example в .env и настройте параметры подключения к базе данных:
   В .env установите настройки Mail для получения по почте пароля пользователя.
   Работать он будет используя очереди. Установите QUEUE_CONNECTION=database.

   ```
   .env.example .env
   ```

4. Сгенерируйте ключ приложения:

   ```bash
    php artisan key:generate
   ```

5. Получите API ключ на OpenWeatherMap и добавьте в .env:

- OPENWEATHERMAP_API_KEY=your_api_key_here
- OPENWEATHERMAP_API_PATH=https://api.openweathermap.org/data/2.5/weather

6. Запустите cервер artisan и npm, а также воркер для очередей:

```
php artisan serve
```

### Пример запроса на localhost:


POST "http://localhost:8000/api/weather"

 - body:
```
{
    "lon": -0.13,
    "lat": 51.51,
    "units": "metric"
}

or

{
    "city": "London",
    "units": "metric"
}
```

 - Accept-Language - заголовок для выбора языка ответа (поддерживаются en, ru)

### Пример ответа:

```
{
    "city": "London",
    "coordinates": {
        "lat": 51.5107,
        "lon": -0.1293
    },
    "temperature": {
        "value": 11.3,
        "in": "celsius"
    },
    "unit": "metric",
    "condition": "clear sky",
    "wind": {
        "speed": 4.12,
        "direction": {
            "degrees": 90,
            "text": "e",
            "cardinal": "east"
        }
    },
    "pressure": 770,
    "humidity": 52,
    "icon": "01n",
    "language": "en"
}
```



