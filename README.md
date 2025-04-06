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

GET "http://localhost:8000/api/weather/Moscow"

 - Accept-Language - заголовок для выбора языка ответа (поддерживаются en, ru)

### Пример ответа:

```
{
    "city": "Moscow",
    "temperature": -1,
    "condition": "snow",
    "wind": {
        "speed": 7.03,
        "direction": {
            "degrees": 36,
            "text": "ne",
            "cardinal": "northeast"
        }
    },
    "pressure": 755,
    "humidity": 58,
    "rain_probability": 10,
    "icon": "13d",
    "language": "en"
}
```



