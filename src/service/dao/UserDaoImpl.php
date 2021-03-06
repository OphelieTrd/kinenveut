<?php

class UserDaoImpl implements IUserDao
{
  public function insertUser(UserModel $user): ?int
  {
    $request = 'INSERT INTO User(firstName, lastName, email, password, birthDate) VALUES (?, ?, ?, ?, ?)';

    try {
      $birthDate = ($user->getBirthDate() != null) ? ($user->getBirthDate())->format('Y-m-d') : null;
      $query = db()->prepare($request);
      $query->execute([utf8_decode($user->getFirstName()), utf8_decode($user->getLastName()), utf8_decode($user->getEmail()), $user->getPassword(), $birthDate]);
    } catch (PDOException $Exception) {
      throw new BDDException($Exception->getMessage(), $Exception->getCode());
    }

    return db()->lastInsertId();
  }

  public function updateUserIsAuthorised(UserModel $user): bool
  {
    $success = false;
    $request = 'UPDATE User SET isAuthorised = :isAuthorised WHERE id = :id';

    try {
      $query = db()->prepare($request);
      $success = $query->execute(['id' => $user->getId(), 'isAuthorised' => $user->getIsAuthorised()]);
    } catch (PDOException $Exception) {
      throw new BDDException($Exception->getMessage(), $Exception->getCode());
    }

    return $success;
  }

  public function updateUser(UserModel $user): bool
  {
    $success = null;
    $request = 'UPDATE User SET firstName = ?, lastName = ?, email = ? WHERE id = ?';

    try {
      $query = db()->prepare($request);
      $success = $query->execute([utf8_decode($user->getFirstName()), utf8_decode($user->getLastName()), utf8_decode($user->getEmail()), $user->getId()]);
    } catch (PDOException $Exception) {
      throw new BDDException($Exception->getMessage(), $Exception->getCode());
    }

    return $success;
  }

  public function deleteUser(int $userId): bool
  {
    $success = false;
    $request = 'DELETE FROM User WHERE id=?';

    try {
      $query = db()->prepare($request);
      $success = $query->execute([$userId]);
    } catch (PDOException $Exception) {
      throw new BDDException($Exception->getMessage(), $Exception->getCode());
    }

    return $success;
  }

  public function selectUserByUserId(int $userId): ?UserModel
  {
    $userSelected = null;
    $request = 'SELECT id, firstName, lastName, email, birthDate, isAuthorised, isAdmin FROM User WHERE id=?';

    try {
      $query = db()->prepare($request);
      $query->execute([$userId]);
      $userSelected = $query->fetch();
    } catch (PDOException $Exception) {
      throw new BDDException($Exception->getMessage(), $Exception->getCode());
    }

    $user = null;
    if ($userSelected) {
      $user = new UserModel();
      $user
                ->setId($userSelected['id'])
                ->setFirstName(protectStringToDisplay($userSelected['firstName']))
                ->setLastName(protectStringToDisplay($userSelected['lastName']))
                ->setEmail(protectStringToDisplay($userSelected['email']))
                ->setBirthDate(new DateTime($userSelected['birthDate']))
                ->setIsAuthorised($userSelected['isAuthorised'])
                ->setIsAdmin($userSelected['isAdmin']);
    }

    return $user;
  }

  public function selectUserByEmailAndPassword(string $email, string $password): ?UserModel
  {
    $firstUser = null;
    $request = 'SELECT id, firstName, lastName, email, birthDate, isAuthorised, isAdmin, password FROM User WHERE email=?';

    try {
      $query = db()->prepare($request);
      $query->execute([$email]);
      $firstUser = $query->fetch();
    } catch (PDOException $Exception) {
      throw new BDDException($Exception->getMessage(), $Exception->getCode());
    }

    $user = null;
    if ($firstUser && is_array($firstUser) && password_verify($password, $firstUser['password'])) {
      $user = new UserModel();
      $user
                ->setId($firstUser['id'])
                ->setFirstName(protectStringToDisplay($firstUser['firstName']))
                ->setLastName(protectStringToDisplay($firstUser['lastName']))
                ->setEmail(protectStringToDisplay($firstUser['email']))
                ->setBirthDate(new DateTime($firstUser['birthDate']))
                ->setIsAuthorised($firstUser['isAuthorised'])
                ->setIsAdmin($firstUser['isAdmin']);
    }

    return $user;
  }

  public function selectUserByEmail(string $email): ?UserModel
  {
    $firstUser = null;
    $request = 'SELECT * FROM User WHERE email=?';

    try {
      $query = db()->prepare($request);
      $query->execute([$email]);
      $firstUser = $query->fetch();
    } catch (PDOException $Exception) {
      throw new BDDException($Exception->getMessage(), $Exception->getCode());
    }

    $user = null;
    if ($firstUser) {
      $user = new UserModel();
      $user
                ->setId($firstUser['id'])
                ->setFirstName(protectStringToDisplay($firstUser['firstName']))
                ->setLastName(protectStringToDisplay($firstUser['lastName']))
                ->setEmail(protectStringToDisplay($firstUser['email']))
                ->setBirthDate(new DateTime($firstUser['birthDate']))
                ->setIsAuthorised($firstUser['isAuthorised'])
                ->setIsAdmin($firstUser['isAdmin']);
    }

    return $user;
  }

  public function selectUsersByState(int $state): array
  {
    $usersList = null;
    $request = 'SELECT id, firstName, lastName, email, birthDate, isAuthorised, isAdmin
                    FROM User
                    WHERE isAuthorised = :state
                    ORDER BY User.id DESC';

    try {
      $query = db()->prepare($request);
      $query->execute(['state' => $state]);
      $usersList = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {
      throw new BDDException($Exception->getMessage(), $Exception->getCode());
    }

    $users = [];
    foreach ($usersList as $oneUser) {
      $user = new UserModel();
      $user
                ->setId($oneUser['id'])
                ->setFirstName(protectStringToDisplay($oneUser['firstName']))
                ->setLastName(protectStringToDisplay($oneUser['lastName']))
                ->setEmail(protectStringToDisplay($oneUser['email']))
                ->setBirthDate(new DateTime($oneUser['birthDate']))
                ->setIsAuthorised($oneUser['isAuthorised'])
                ->setIsAdmin($oneUser['isAdmin']);

      array_push($users, $user);
    }

    return $users;
  }

  public function selectAllUserExceptState0(): array
  {
    $usersList = null;
    $request = 'SELECT id, firstName, lastName, email, birthDate, isAuthorised, isAdmin
                    FROM User
                    WHERE isAuthorised != :state
                    ORDER BY User.isAuthorised ASC  ';

    try {
      $query = db()->prepare($request);
      $query->execute(['state' => 0]);
      $usersList = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {
      throw new BDDException($Exception->getMessage(), $Exception->getCode());
    }

    $users = [];
    foreach ($usersList as $oneUser) {
      $user = new UserModel();
      $user
                ->setId($oneUser['id'])
                ->setFirstName(protectStringToDisplay($oneUser['firstName']))
                ->setLastName(protectStringToDisplay($oneUser['lastName']))
                ->setEmail(protectStringToDisplay($oneUser['email']))
                ->setBirthDate(new DateTime($oneUser['birthDate']))
                ->setIsAuthorised($oneUser['isAuthorised'])
                ->setIsAdmin($oneUser['isAdmin']);

      array_push($users, $user);
    }

    return $users;
  }
}
