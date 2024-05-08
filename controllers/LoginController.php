<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {

  public static function login(Router $router){

    $alertas = [];

    if($_SERVER['REQUEST_METHOD']==='POST'){
      $auth = new Usuario($_POST);
      $alertas = $auth->validarLogin();

      if(empty($alertas)){
        // Verificar que el usuario existe
        $usuario = Usuario::where('email', $auth->email);
        if($usuario && $usuario->confirmado){
          // Comprobar password
          $resultado = password_verify($auth->password, $usuario->password);
          if($resultado) {
            // Iniciar la sesión
            session_start();
            $_SESSION['id'] = $usuario->id;
            $_SESSION['nombre'] = $usuario->nombre;
            $_SESSION['apellido'] = $usuario->apellido;
            $_SESSION['email'] = $usuario->email;
            $_SESSION['login'] = true;
            //Redireccionar
            header('Location: /dashboard');
          } else {
            Usuario::setAlerta('error', "El Password es incorrecto");
          }
        } else {
          Usuario::setAlerta('error', 'El Usuario no existe o no está confirmado');  
        }
      }
    }

    $alertas = Usuario::getAlertas();

    // Render a la vista
    $router->render('auth/login', [
      'titulo' => "Iniciar Sesión",
      'alertas' => $alertas
    ]);
  }

  public static function logout(){
    
    session_start();
    $_SESSION = [];

    header('Location: /');

  }

  public static function crear(Router $router){

    $usuario = new Usuario;
    $alertas = [];

    if($_SERVER['REQUEST_METHOD']==='POST'){
      $usuario->sincronizar($_POST);
      $password2 = $_POST['password2'];
      $alertas = $usuario->validarNuevaCuenta($password2);

      if(empty($alertas)){
        // Buscar email en base de datos para saber si un usuario existe
        $existeUsuario = Usuario::where('email', $usuario->email);
        if($existeUsuario){
          Usuario::setAlerta('error', 'El Usuario ya está registrado');
          $alertas = Usuario::getAlertas();
        } else {
          // Hashear el password
          $usuario->hashPassword();
          // Eliminar propiedad de un objeto
          // unset($usuario->nombrePropiedad)

          // Generar el token
          $usuario->crearToken();

          // Guardar usuario
          $resultado = $usuario->guardar();

          // Crear instancia de email para enviar el correo de confirmación
          $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
          $email->enviarConfirmacion();

          // Enviar al usuario a la página de confirmación de creación de cuenta
          if($resultado) {
            header('Location: /mensaje');
          }

        }
      }
    }

    
    // Render a la vista
    $router->render('auth/crear', [
      'titulo' => "Crea tu Cuenta",
      'usuario' => $usuario,
      'alertas' => $alertas
    ]);
  }

  public static function olvide(Router $router){

    $alertas = [];
    if($_SERVER['REQUEST_METHOD']==='POST'){
      
      $usuario = new Usuario($_POST);
      $alertas = $usuario->validarEmail();

      if(empty($alertas)) {
        $usuario  = Usuario::where('email', $usuario->email);

        if($usuario && $usuario->confirmado){
          //Generar un nuevo token
          $usuario->crearToken();
          // Actualizar el usuario
          $usuario->guardar();
          // Imprimir la alerta
          Usuario::setAlerta('exito', "Hemos enviado las instrucciones a tu email");
          // Enviar instrucciones
          $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
          $email->enviarInstuccionesCambioPassword();
          // header("Location: /");
        } else {
          Usuario::setAlerta('error', "El Usuario no existe o no está confirmado");
          
        }
      }

    }

    $alertas = Usuario::getAlertas();

    $router->render('auth/olvide', [
      'titulo' => "Olvidé my password",
      'alertas'=>$alertas
    ]);
  }

  public static function restablecer(Router $router){

    $mostrarCampos = true;

    $token = s($_GET['token']);
    if(!$token) header("Location: /");

    // Identificar el usuario con este token
    $usuario = Usuario::where('token', $token);
    if(empty($usuario)) {
      Usuario::setAlerta('error', "Token No Válido");
      $mostrarCampos = false;
    }

    if($_SERVER['REQUEST_METHOD']==='POST'){

      $usuario->sincronizar($_POST);

      // Validar el password
      $alertas = $usuario->validarCambioPassword($_POST['password2']);

      if(empty($alertas)){
        // Hashear password
        $usuario->hashPassword();
        // Eliminar token
        $usuario->token = '';
        // Actualizar datos del usuario
        $resultado = $usuario->guardar();
        // Redireccionar
        if($resultado){
          header('Location: /');
        }
      }
    }

    $alertas = Usuario::getAlertas();

    $router->render('auth/restablecer', [
      'titulo' => "Restablece tu Password",
      'alertas' => $alertas,
      'mostrarCampos' => $mostrarCampos
    ]);
  }

  public static function mensaje(Router $router){
    
    $router->render('auth/mensaje', [
      'titulo' => "Cuenta creada exitosamente"
    ]);
  }

  public static function confirmar(Router $router){

    $token = s($_GET['token']);
    if(!$token) header("Location: /");

    $usuario = Usuario::where('token', $token);

    if(empty($usuario)) {
      Usuario::setAlerta('error', "Token No Válido");
    } else {
      // Confirmar la cuenta
      $usuario->confirmado = 1;
      $usuario->token = '';
      $usuario->guardar();

    }
      

    $alertas = Usuario::getAlertas();

    $router->render('auth/confirmar', [
      'titulo' => "Confirma tu cuenta UpTask",
      'alertas' => $alertas
    ]);
  }
}