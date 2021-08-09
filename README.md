Сервис синхронизуется с www.cbr.ru/scripts/XML_daily.asp и хранит историю изменений.


## Установка

В проекте присутствует seeder, для добавления тестового пользователя

 - `php artisan migrate:install --seed`
 - `php artisan passport:install`

---
## Комманды
 
 - `currency:sync` - Синхронизует данные о валюте.
 - `currency:update` - Обновление валюты вручную.

Обновление происходит автоматически каждые 6 часов.

---
## Авторизация

Для получения Bearer токена 

 [POST] `localhost/api/login` email, password

 [POST] `localhost/api/register` name, email, password

### Данные тестового пользователя

    email = testUser@mail.com 
    password = temp_password 

---
## API

 [GET] `localhost/api/currencies` - получить список курсов валют

 [GET] `localhost/api/currency/{CODE}/{date?}` - получить курс валюты по дате (dd.mm.YYYY)

    