<?php

namespace Model;


class Usuario extends ActiveRecord {

  protected static $tabla = 'usuarios';
  protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'token', 'confirmado'];

  public $id;
  public $nombre;
  public $apellido;
  public $email;
  public $password;
  public $token;
  public $confirmado;

  public function __construct($args=[])
  {
    $this->id = $args['id'] ?? NULL;
    $this->nombre = $args['nombre'] ?? '';
    $this->apellido = $args['apellido'] ?? '';
    $this->email = $args['email'] ?? '';
    $this->password = $args['password'] ?? '';
    $this->token = $args['token'] ?? '';
    $this->confirmado = $args['confirmado'] ?? 0;
  }

  // Validar login
  public function validarLogin() {
    if(!$this->email){
      self::$alertas['error'][] = "El Email del usuario es obligatorio";
    }

    if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      self::$alertas['error'][] = "El Email no es válido";
    }

    if(!$this->password){
      self::$alertas['error'][] = "El Password es obligatorio";
    }
    
    return self::$alertas;
  }

  // Validación para cuentas nuevas
  public function validarNuevaCuenta(string $password2): array {

    if(!$this->nombre){
      self::$alertas['error'][] = "El Nombre del usuario es obligatorio";
    }

    if(!$this->email){
      self::$alertas['error'][] = "El Email del usuario es obligatorio";
    }

    if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      self::$alertas['error'][] = "El Email no es válido";
    }

    if(!$this->password){
      self::$alertas['error'][] = "El Password es obligatorio";
    }

    if(strlen($this->password) < 6){
      self::$alertas['error'][] = "El Password debe contener al menos 6 caracteres";
    }

    if(strcmp($this->password, $password2) !== 0){
      self::$alertas['error'][] = "Los Passwords no coinciden";
    }

    return self::$alertas;
  }

  public function validarCambioPassword($password2){

    if(!$this->password){
      self::$alertas['error'][] = "El Password es obligatorio";
    }

    if(strlen($this->password) < 6){
      self::$alertas['error'][] = "El Password debe contener al menos 6 caracteres";
    }

    if(strcmp($this->password, $password2) !== 0){
      self::$alertas['error'][] = "Los Passwords no coinciden";
    }

    return self::$alertas;
  }

  public function validarPerfil() {
    if(!$this->nombre){
      self::$alertas['error'][] = "El Nombre del usuario es obligatorio";
    }

    if(!$this->email){
      self::$alertas['error'][] = "El Email del usuario es obligatorio";
    }

    return self::$alertas;
  }

  // Hashea el password
  public function hashPassword() {
    $this->password = password_hash($this->password, PASSWORD_BCRYPT);
  }


  // Generar un token
  public function crearToken() {
    $this->token = uniqid(); //md5
  }

  // Validar email
  public function validarEmail() {

    if(!$this->email) {
      self::$alertas['error'][] = "El Email del Usuario es obligatorio";
    }

    if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      self::$alertas['error'][] = "El Email no es válido";
    }

    return self::$alertas;
  }

  // Validar actualización de password
  public function validarActualizarPassword($currentPass, $newPass){

    if(!password_verify($currentPass, $this->password)){
      self::$alertas['error'][] = "El password actual ingresado no es correcto";
    }

    if(strlen($newPass) < 6){
      self::$alertas['error'][] = "El Nuevo Password debe contener al menos 6 caracteres";
    }

    if(password_verify($newPass, $this->password)){
      self::$alertas['error'][] = "El Nuevo Password no puede ser igual al anterior";
    }

    return self::$alertas;
  }
}