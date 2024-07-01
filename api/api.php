<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include 'db.php';
include 'Peliculas.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handleGet($conn);
        break;
    case 'POST':
        handlePost();
        break;
    case 'PUT':
        handlePut();
        break;
    case 'DELETE':
        handleDelete($conn);
        break;
    default:
        echo json_encode(['message' => 'Metodo no permitido']);
        break;
}

//este metodo tiene que devolver todas o 1 pelicula
function handleGet($conn) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id > 0) {
        //devuelve la pelicula del id
        $stmt = $conn->prepare("SELECT * FROM peliculas WHERE id = ?");
        $stmt->execute([$id]);
        $pelicula = $stmt->fetch(PDO::FETCH_ASSOC);
        if($pelicula) {
            $peliculaObj = Peliculas::fromArray($pelicula);
            echo json_encode($peliculaObj->toArray());
        } else {
            http_response_code(404);
            echo json_encode(['message'=>'No se encontro Pelicula']);
        }
    } else {
        //devuelvo todas las peliculas
        $stmt = $conn->query("SELECT * FROM peliculas");
        $peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $peliculaObjs = array_map(fn($pelicula) => Peliculas::fromArray($pelicula)->toArray(), $peliculas);
        echo json_encode(['peliculas' => $peliculaObjs]);
    }
}

function handleDelete($conn) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id>0) {
        $smtp = $conn->prepare("DELETE FROM peliculas WHERE id = ?");
        $smtp->execute([$id]);
        if ($smtp->rowCount()>0) {
            echo json_encode(['message'=>'Pelicula eliminada']);
        } else {
            http_response_code(404);
            echo json_encode(['message'=>'No se encontro Pelicula']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['message'=>'Faltan datos']);
    }
}

?>