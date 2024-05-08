<?php 

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController {

    public static function index(Router $router) {

        session_start();
        isAuth();

        $id_usuario = $_SESSION['id'];

        $proyectos = Proyecto::belongsTo('propietarioId', $id_usuario);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router) {
        session_start();

        isAuth();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto($_POST);

            // Validación 
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)) {
                // Generar URL única
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                // Almacenar creador del proyecto
                $proyecto->propietarioId = $_SESSION['id'];
                
                // Guardar Proyecto
                $proyecto->guardar();

                // Redireccionar
                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }

        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function perfil(Router $router) {
        session_start();

        isAuth();

        $usuario = Usuario::find($_SESSION['id']);
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPerfil();

            if(empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);
                if($existeUsuario && $existeUsuario->id !== $usuario->id){
                    Usuario::setAlerta('error', "Ya existe un usuario registrado con este email.");
                    $alertas = $usuario->getAlertas(); 
                } else {
                    // Guardar usuario
                    $usuario->guardar();
                    $_SESSION['nombre'] = $usuario->nombre;
                    $_SESSION['apellido'] = $usuario->apellido;
                    $_SESSION['email'] = $usuario->email;

                    Usuario::setAlerta('exito', "Cambios guardados correctamente");
                    $alertas = $usuario->getAlertas(); 
                }          
            }
        }
        
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router) {

        session_start();

        isAuth();

        $token = $_GET['id'];
        if(!$token) header('Location: /dashboard');
        
        // Revisar que la persona que visita el proyecto, es quien lo creó
        $proyecto = Proyecto::where('url', $token);
        $id_usuario = $_SESSION['id'];

        if($proyecto->propietarioId !== $id_usuario){
            header('Location: /dashboard');
        }
        
        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function cambiar_password(Router $router){

        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);
            $alertas = $usuario->validarActualizarPassword($_POST['password-actual'], $_POST['password-nuevo']);    

            if(empty($alertas)){
                $usuario->password = '';
                $usuario->password = $_POST['password-nuevo'];
                $usuario->hashPassword();
                $resultado = $usuario->guardar();

                if($resultado){
                    Usuario::setAlerta('exito', 'Cambio de Password exitoso');
                    $alertas = $usuario->getAlertas();
                }
            }
        }
        $router->render('dashboard/cambiar-password', [
            'titulo' => "Cambiar Password",
            'alertas' => $alertas
        ]);
    }
}