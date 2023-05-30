<?php
require_once '../data/DAL.class.php';
require_once '../models/User.class.php';
require_once '../models/UserConnected.class.php';

class UserDAL extends DAL
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login(User $user): ?UserConnected
    {
        $userDB = $this->getUserByUsername($user->getUsername());


        if ($userDB === null) {
            throw new Exception("Aucun compte ne correspond à ce Nom.");
        }

        if (password_verify($user->getPassword(), $userDB->getPassword())) {
            return new UserConnected($userDB->getId(), $userDB->getUsername(), $userDB->getRole());
        }

        throw new Exception("Mot de passe incorrect.");

    }

    /**
     * @throws Exception
     */
    public function isUsernameUnique(string $username): bool
    {
        $sql = "SELECT COUNT(*) as count FROM users WHERE username = :username";
        $params = [':username' => $username];

        $result = $this->fetch($sql, $params);

        if ($result && $result['count'] == 0) {
            return true;
        }

        return false;
    }
    public function register(User $user): array
    {

        $sql = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
        $params = [
            ':username' => $user->getUsername(),
            ':password' => $user->getPassword(),
            ':role' => $user->getRole()
        ];

        $isExecuted = $this->executeQuery($sql, $params);
        if ($isExecuted) {
            return ['success' => true, 'message' => ''];
        } else {
            return ['success' => false, 'message' => 'There was an error in the registration process.'];
        }
    }

    public function userHasRole(User $user, string $role): bool
    {
        $userDB = $this->getUserByUsername($user->getRole());

        if ($userDB !== null) {
            return $userDB->getRole() === $role;
        }

        return false;
    }

    public function checkUserExists(string $username): bool
    {
        $userDB = $this->getUserByUsername($username);

        return $userDB !== null;
    }

    public function getAllUsers(): array
    {
        $sql = "SELECT * FROM users ORDER BY username";
        $results = $this->fetchAll($sql);
        $users = [];

        foreach ($results as $row) {
            $users[] = new User(
                $row['id'],
                $row['username'],
                $row['password'],
                $row['role']
            );
        }

        return $users;
    }

    /**
     * @throws Exception
     */
    public function getUserByUsername(string $username): ?User
    {
        try {
            $sql = "SELECT * FROM users WHERE username = :username";
            $params = [':username' => $username];
            $result = $this->fetch($sql, $params);

            if ($result) {
                return new User(
                    $result['id'],
                    $result['username'],
                    $result['password'],
                    $result['role']
                );
            }

            return null;
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de la récupération de l\'utilisateur par le nom d\'utilisateur.');
        }
    }

    public function countUsersByRole(string $role): int
    {
        $sql = "SELECT COUNT(*) as count FROM users WHERE role = :role";
        $params = [':role' => $role];

        $result = $this->fetch($sql, $params);
        return $result ? (int)$result['count'] : 0;
    }

    public function update(User $user): bool
    {
        $sql = "UPDATE users SET username = :username, password = :password, role = :role WHERE id = :id";
        $params = [
            ':id' => $user->getId(),
            ':username' => $user->getUsername(),
            ':password' => $user->getPassword(), // Make sure to hash the password before calling this method
            ':role' => $user->getRole(),
        ];

        return $this->executeQuery($sql, $params);
    }

    public function hasRendezvousInFutureById(int $userId): bool
    {
        $currentDate = new DateTime('now');
        $currentDateStr = $currentDate->format('Y-m-d');

        $sql = "SELECT COUNT(*) as count FROM rendezvous 
            WHERE user_id = :user_id AND date > :current_date";
        $params = [':user_id' => $userId, ':current_date' => $currentDateStr];

        $result = $this->fetch($sql, $params);
        return $result && $result['count'] > 0;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $params = [':id' => $id];

        return $this->executeQuery($sql, $params);
    }

    public function deleteAllRendezvous(int $userId): int
    {
        $sql = "DELETE FROM rendezvous WHERE user_id = :user_id";
        $params = [':user_id' => $userId];

        $stmt = $this->executePDO($sql, $params);
        return $stmt->rowCount();
    }


    public function executePDO(string $query, array $params = []): PDOStatement
    {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            // handle exception here, e.g., by logging it and re-throwing
            throw $e;
        }
    }


}

?>
