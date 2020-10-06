<?php

function my_autoloader($name)
{
  $dir = '';
  if (stripos($name, 'Model') !== false) {
    $dir = 'model';
  } elseif (stripos($name, 'Controller') !== false) {
    $dir = 'controller';
  } elseif (stripos($name, 'Bo') !== false) {
    $dir = 'service/bo';
  } elseif (stripos($name, 'Dao') !== false) {
    $dir = 'service/dao';
  } elseif (stripos($name, 'Exception') !== false) {
    $dir = 'exception';
  }
  include_once $dir . '/' . $name . '.php';
}

spl_autoload_register('my_autoloader');

function protectStringToDisplay($str)
{
  $str = trim($str);
  $str = utf8_encode($str);

  return htmlentities($str, ENT_QUOTES, 'UTF-8');
}
