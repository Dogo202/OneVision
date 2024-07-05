# OneVision Laravel Project

## Описание

Этот проект представляет собой API для CRUD операций с постами блога, использующий Laravel Sail и PostgreSQL. Он также интегрирует Laravel Passport для аутентификации.

PHP:8.0
Laravel: 9.19
Laravel/passport: 12.2
Laravel/sail: 1.0

## Требования

- Docker
- Docker Compose
- Laravel Sail

## Установка и настройка

### 1. Клонирование репозитория

Клонируйте репозиторий на ваш локальный компьютер:

Bash/Ubuntu/cmd

git clone https://github.com/Dogo202/onevision.git
cd onevision

### 2. Установка зависимостей
Используйте Composer для установки зависимостей:

composer install
(убедитесь что у вас установлены необходимые расширения PHP) 

### 3. Запуск Docker контейнеров
Используйте Laravel Sail для запуска Docker контейнеров:

./vendor/bin/sail up -d

### 4. Настройка окружения
Скопируйте файл .env.example в .env и настройте его:

APP_NAME=Laravel

APP_ENV=local

APP_KEY=base64:r2MSeRzfUzXm2kKRCxZlgbUDKtLt9jwTGHLHfgtT6j8=

APP_DEBUG=true

APP_URL=http://localhost

LOG_CHANNEL=stack

LOG_DEPRECATIONS_CHANNEL=null

LOG_LEVEL=debug


APP_PORT=81

FORWARD_DB_PORT=5433


DB_CONNECTION=pgsql

DB_HOST=pgsql

DB_PORT=5432

DB_DATABASE=laravel

DB_USERNAME=sail

DB_PASSWORD=password


остальные настройки можно оставить по умолчанию

### 5. Выполнение миграций и установка Passport
Выполните миграции и установите Laravel Passport:

./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan passport:install --force

### 6. Проверка установки
Убедитесь, что проект работает, открыв в браузере http://localhost:81

# API Документация
Используйте Postman или другой инструмент для тестирования API запросов. Убедитесь, что вы используете правильный токен аутентификации в заголовке Authorization.

## Аутентификация
Для выполнения CRUD операций необходимо пройти аутентификацию.

### 1.Регистрация пользователя:
URL: http://localhost/api/register
Метод: POST
Тело запроса (JSON):
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}


### 2.Аутентификация пользователя:
URL: http://localhost/api/login
Метод: POST
Тело запроса (JSON):
json
Копировать код
{
    "email": "john@example.com",
    "password": "password"
}
Запомните токен доступа (access_token) в ответе на запрос, который будет использоваться для аутентификации в последующих запросах.


##CRUD Операции

### 1.Создание поста
Метод: POST
URL: http://localhost/api/posts
Тело запроса:
{
    "title": "New Post",
    "body": "This is a new post"
}
Заголовок: Authorization: Bearer YOUR_ACCESS_TOKEN

### 2.Получение списка постов
Метод: GET
URL: http://localhost/api/posts
Заголовок: Authorization: Bearer YOUR_ACCESS_TOKEN

### 3.Получение определенного поста
Метод: GET
URL: http://localhost/api/posts/{id}
Заголовок: Authorization: Bearer YOUR_ACCESS_TOKEN

### 4.Обновление поста
Метод: PUT
URL: http://localhost/api/posts/{id}
Тело запроса:
{
    "title": "Updated Title",
    "body": "This is the updated body"
}
Заголовок: Authorization: Bearer YOUR_ACCESS_TOKEN

### 5.Удаление поста
Метод: DELETE
URL: http://localhost/api/posts/{id}
Заголовок: Authorization: Bearer YOUR_ACCESS_TOKEN


### Завершение работы
Для остановки  контейнеров Docker используйте:

./vendor/bin/sail down



Устранение неполадок
Убедитесь, что все зависимости установлены правильно.
Убедитесь, что Docker и Docker Compose работают корректно.
Проверьте, что порты, указанные в docker-compose.yml, не заняты другими процессами.
