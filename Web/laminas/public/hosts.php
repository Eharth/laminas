<?php
// Define application environment
switch($_SERVER['HTTP_HOST']){
  case "localhost":
  case "atol":
    define('BASE_URL', 'https://'.($_SERVER['HTTP_HOST']).'/laminas/');
    //define('APPLICATION_ENV', 'development');
    break; 
  default:
    define('BASE_URL', 'https://'.($_SERVER['HTTP_HOST']).'/laminas/');
    //define('APPLICATION_ENV', 'production');
  break;
}