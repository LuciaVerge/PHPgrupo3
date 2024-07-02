<?php

include 'db.php';
include 'Peliculas.php';

$method=$_SERVER['REQUEST_METHOD'];


switch($method)
{
    case 'GET':
        handleGet($conn);
        break;
    case 'POST':
        handlePost($conn);
        break;
    case 'PUT':
        handlePut($conn);
        break;
    case 'DELETE':
        handleDelete($conn);
        break;
    default:
        echo json_encode(['message'=>'Metodo no permitido']);
        break;
}



//Este metodo tiene que devolver todas o una pelicula
function handleGet($conn)
{
    $id=isset($_GET['id']) ? intval($_GET['id']) : 0;  //preguntamos si la variable existe, si existe la convierte en entero y si no existe la convierte en 0

    if($id>0) //si existe el id 
    {
        //entonces devuelvo una pelicula segun el id proporcionado
        $smtp=$conn->prepare("SELECT * FROM  peliculas WHERE id = ?"); //consulta precompilada
        $smtp->execute([$id]);
        $pelicula=$smtp->fetch(PDO::FETCH_ASSOC);

        if($pelicula)
        {
            $peliculaObj=Peliculas::fromArray($pelicula);
            echo json_encode($peliculaObj->toArray());
        }
        else
        {
            http_response_code(404);
            echo json_encode(['message'=>'No se encontro pelicula']);
        }
    }
    else
    {
        //devuelvo todas las peliculas
        $smtp=$conn->query("SELECT * FROM  peliculas"); 
        $peliculas=$smtp->fetchAll(PDO::FETCH_ASSOC);
        $peliculaObjs=array_map(fn($pelicula)=>Peliculas::fromArray($pelicula)->toArray(),$peliculas);
        echo json_encode(['peliculas'=>$peliculaObjs]);
    }
}






//Metodo para ingresar peliculas
function handlePost($conn)
{
    if ($conn === null) 
    {
        echo json_encode(['message' => 'Error en la conexión a la base de datos']);
        return;
    }

    $data= json_decode(file_get_contents('php://input'),true);
    $requiredFields=['titulo','fecha_lanzamiento','genero'];  

    foreach($requiredFields as $field)  //quiero hacer una validacion desde el backend
    {
        if(!isset($data[$field])){
            echo json_encode(['message'=>'Datos incompletos']);
            return;
        }
    }

    $pelicula=Peliculas::fromArray($data);

    try
    {
        $smtp=$conn->prepare("INSERT INTO peliculas (titulo,fecha_lanzamiento,genero,duracion,director,reparto,sinopsis) VALUES (?,?,?,?,?,?,?)");

        $smtp->execute([
            $pelicula->titulo,
            $pelicula->fecha_lanzamiento,
            $pelicula->genero,
            $pelicula->duracion,
            $pelicula->director,
            $pelicula->reparto,
            $pelicula->sinopsis
        ]);

        echo json_encode(['message'=>'Pelicula ingresada correctamente']);
    }
    catch(PDOException $e)
    {
        echo json_encode(['message'=>'Error al ingresar pelicula', 'error'=> $e->getMessage()]);
    }

}




//Metodo para actualizar peliculas
function handlePut($conn)
{
    $id=isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id>0)
    {
        $data= json_decode(file_get_contents('php://input'),true);
        $pelicula=Peliculas::fromArray($data);  //convierto el dato json decodificado en un objeto tipo pelicula
        $pelicula->id=$id;

        $field=[];
        $params=[];

        if($pelicula->titulo!==null)
        {
            $field[]='titulo=?';
            $params[]=$pelicula->titulo;
        }

        if($pelicula->genero!==null)
        {
            $field[]='genero=?';
            $params[]=$pelicula->genero;
        }

        if($pelicula->fecha_lanzamiento!==null)
        {
            $field[]='fecha_lanzamiento=?';
            $params[]=$pelicula->fecha_lanzamiento;
        }

        if($pelicula->duracion!==null)
        {
            $field[]='duracion=?';
            $params[]=$pelicula->duracion;
        }

        if($pelicula->director!==null)
        {
            $field[]='director=?';
            $params[]=$pelicula->director;
        }

        if($pelicula->reparto!==null)
        {
            $field[]='reparto=?';
            $params[]=$pelicula->reparto;
        }

        if($pelicula->sinopsis!==null)
        {
            $field[]='sinopsis=?';
            $params[]=$pelicula->sinopsis;
        }

        if(!empty($field))
        {
            $params[]=$id;
            $smtp=$conn->prepare("UPDATE peliculas SET ".implode(',',$field)."where id=?");
            $smtp->execute($params);
            echo json_encode(['message'=>'La pelicula se actualizo con exito']);

        }
        else{
            echo json_encode(['message'=>'No hay campos para actualizar']);
        }


    }
    else
    {
        echo json_encode(['message'=>'ID no encontrado']);
    }
}





//Metodo para eliminar peliculas
function handleDelete($conn)
{
    $id=isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if($id>0)
    {
        $smtp=$conn->prepare("DELETE * FROM  peliculas WHERE id = ?"); //consulta precompilada
        $smtp->execute([$id]);
        echo json_encode(['message'=>'Pelicula eliminada con exito']);
    }else
    {
        echo json_encode(['message'=>'Id no encontrado']);
    }


}


?>