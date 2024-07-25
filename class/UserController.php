<?php
require_once 'DbConnect.php';

class UserController {
    private $db;

    public function __construct(DbConnect $db) {
        $this->db = $db;
    }

    // Проверка на существование пользователя с указанным id
    private function userExists($id) {
        $sql = 'SELECT COUNT(*) FROM users WHERE id = :id';
        $stmt = $this->db->getPDO()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchColumn() > 0;
    }

    // Создание пользователя
    public function createUser($data) {
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            return ['status' => 400, 'message' => 'Некорректные данные'];
        }
        
        $sql = 'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)';
        $stmt = $this->db->getPDO()->prepare($sql);
        try {
            $stmt->execute([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_BCRYPT)
            ]);
            return ['status' => 201, 'id' => $this->db->getPDO()->lastInsertId()];
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { 
                return ['status' => 400, 'message' => 'Пользователь с такой почтой уже существует!'];
            }
            return ['status' => 500, 'message' => 'Внутренняя ошибка сервера'];
        }
    }

    // Обновление информации пользователя
    public function updateUser($id, $data) {
        if (!$this->userExists($id)) {
            return ['status' => 404, 'message' => 'Пользователь не найден'];
        }
        
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            return ['status' => 400, 'message' => 'Некорректные данные'];
        }

        $sql = 'UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id';
        $stmt = $this->db->getPDO()->prepare($sql);
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'id' => $id
        ]);
        return ['status' => 200, 'message' => 'Информация успешно обновлена!'];
    }

    // Удаление пользователя
    public function deleteUser($id) {
        if (!$this->userExists($id)) {
            return ['status' => 404, 'message' => 'Пользователь не найден'];
        }

        $sql = 'DELETE FROM users WHERE id = :id';
        $stmt = $this->db->getPDO()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return ['status' => 200, 'message' => 'Пользователь успешно удалён!'];
    }

    // Авторизация пользователя
    public function authorizeUser($data) {
        if (empty($data['email']) || empty($data['password'])) {
            return ['status' => 400, 'message' => 'Некорректные данные'];
        }

        $sql = 'SELECT * FROM users WHERE email = :email';
        $stmt = $this->db->getPDO()->prepare($sql);
        $stmt->execute(['email' => $data['email']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($data['password'], $user['password'])) {
            return ['status' => 200, 'message' => 'Авторизация успешно пройдена!'];
        } else {
            return ['status' => 401, 'message' => 'Не удалось авторизоваться'];
        }
    }

    // Получить информацию о пользователе
    public function getUser($id) {
        if (!$this->userExists($id)) {
            return ['status' => 404, 'message' => 'Пользователь не найден'];
        }

        $sql = 'SELECT id, name, email, created_at FROM users WHERE id = :id';
        $stmt = $this->db->getPDO()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return ['status' => 200, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)];
    }
}
?>
