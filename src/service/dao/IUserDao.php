<?php

interface IUserDao
{
  public function getUserByEmailAndPassword(UserModel $user);

  public function insertUser(UserModel $user);

  public function selectUser(String $email);
}
