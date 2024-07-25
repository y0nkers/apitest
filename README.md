# Setup
- Создание БД и таблицы с юзерами:
```sql
CREATE DATABASE IF NOT EXISTS `apitest` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `apitest`;

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```
- Подключение к БД: хост, бд и dsn в class/DbConnect.php:
```php
$host = "localhost";
$dbname = "apitest";
$charset = "utf8mb4";
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
```
- пользователь и пароль в api/core.php:
```php
$username = "root";
$password = "";
$db = new DbConnect($username, $password);
```

# Methods (class/UserController.php)
- createUser - Создание пользователя
- updateUser - Обновление информации пользователя
- deleteUser - Удаление пользователя
- authorizeUser - Авторизация пользователя
- getUser - Получить информацию о пользователе

# API Test (Postman)
## Создание пользователя
- Method: ```POST```
- URL: ```apitest/api/user```
- Body:
```json
{
    "name": "Иван Иванов",
    "email": "qwerty123@gmail.com",
    "password": "password123"
}
```

![createuser](https://github.com/user-attachments/assets/61949a84-6340-4b20-a36a-f00d7dd965f9)

## Обновление информации пользователя
- Method: ```PUT```
- URL: ```apitest/api/user/1```
- Body:
```json
{
    "name": "Пётр Петров",
    "email": "qwerty321@gmail.com",
    "password": "newpassword123"
}

```

![updateuser](https://github.com/user-attachments/assets/ef38b094-8208-48a3-8bf7-0e6661f85d13)

## Получить информацию о пользователе
- Method: ```GET```
- URL: ```apitest/api/user/1```

![getuser](https://github.com/user-attachments/assets/257aa94c-b51e-47a7-a9d9-267bd435909e)

## Авторизация пользователя
- Method: ```POST```
- URL: ```apitest/api/auth```
- Body:
```json
{
    "email": "qwerty321@gmail.com",
    "password": "newpassword123"
}

```

![auth](https://github.com/user-attachments/assets/f9b673b3-9dd0-4721-9cb2-8cd7e4170a39)

## Удаление пользователя
- METHOD: ```DELETE```
- URL: apitest/api/user/1

![deleteuser](https://github.com/user-attachments/assets/e0141fba-c6f8-4f95-ab4c-46468cd53605)
