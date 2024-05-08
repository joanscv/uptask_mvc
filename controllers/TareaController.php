<?php
namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController {
    public static function index(){
        $urlProyecto = $_GET['id'];

        if(!$urlProyecto){
            header('Location: /dashboard');
        }

        session_start();

        $proyecto = Proyecto::where('url', $urlProyecto);

        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
            header('Location: /404');
        }

        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);
        echo json_encode(['tareas'=>$tareas]);
    }

    public static function crear(){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            session_start();

            $urlProyecto = $_POST['proyectoId'];

            $proyecto = Proyecto::where('url', $urlProyecto);


            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => "Hubo un error al agregar la tarea"
                ];
                echo json_encode($respuesta);     
                return;
            } 

            // Todo OK
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            if($resultado['resultado']){
                $respuesta = [
                    'tipo' => 'exito',
                    'mensaje' => "Tarea Creada Correctamente",
                    'id' => $resultado['id'],
                    'proyectoId' => $proyecto->id
                ];
            } else {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => "Hubo un error al agregar la tarea"
                ];
            }
            echo json_encode($respuesta);
        }

    }

    public static function actualizar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);
            session_start();
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => "Hubo un error al actualizar la tarea"
                ];
                echo json_encode($respuesta);     
                return;
            } 

            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id; 
            $resultado = $tarea->guardar();
            if($resultado){
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $tarea->id,
                    'proyectoId' => $proyecto->id,
                    'mensaje' => "Actualizado correctamente"
                ];
                echo json_encode(['respuesta' => $respuesta]);
            }
    
        }
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);
            session_start();
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => "Hubo un error al eliminar la tarea"
                ];
                echo json_encode($respuesta);     
                return;
            } 

            $tarea = Tarea::where('id', $_POST['id']);
            $resultado = $tarea->eliminar();
            $resultado = [
                'resultado' => $resultado,
                'mensaje' => "Eliminado correctamente",
                'tipo' => 'exito'
            ];
            echo json_encode(['resultado' => $resultado]);

        }
    }
}