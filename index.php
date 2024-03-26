<?php
require_once realpath(__DIR__ . '/vendor/autoload.php');
use Dotenv\Dotenv; 

// en mode test
if (getenv('APP_ENV') !== 'production'){
    $dotenv =  Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}
// Les en-têtes CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers:*");

//On renvoie les authorisations CORS au navigateur qui emet les requetes 
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

use Model\JWT\JWT;
use Controller\ItemController;
use Controller\UserController;  
use Controller\MediaController;
use Controller\ResidenceController;
use Controller\CommentController;
use Controller\DonationController;

// http_response_code(204);

// //On vérifie si on reçoit un token
$ressource ="";
if (isset($_SERVER['PATH_INFO'])){
    $uri = explode("/",$_SERVER['PATH_INFO']);
    $ressource  = $uri[1];
}
//Token d'autorisation est indispensable pour toute les requêtes sauf celle d'authentification et de recuperation
if ($ressource === 'signup' or $ressource === 'login' or $_SERVER['REQUEST_METHOD'] === "GET" or isset($_REQUEST)) {
}else{
    $token = "";
    if(isset($_SERVER['Authorization'])){
        $token = trim($_SERVER['Authorization']);
    }elseif(isset($_SERVER['HTTP_AUTHORIZATION'])){
        $token = trim($_SERVER['HTTP_AUTHORIZATION']);
    }elseif(function_exists('apache_request_headers')){
        $requestHeaders = apache_request_headers();
        if(isset($requestHeaders['Authorization'])){
            $token = trim($requestHeaders['Authorization']);
        }
    }
    // // On vérifie si la chaine commence par "Bearer" et si vide cela supposerait l'url origin a des déjà les authorisations
        if(!isset($token) || !preg_match('/Bearer\s(\S+)/', $token, $matches)){
            http_response_code(400);
            echo json_encode(['statut' => 2, 'message' => 'Token introuvable']);
            exit;
        }
        // // On extrait le token
        $token = str_replace('Bearer ', '', $token);
        $jwt = new JWT();
        // On vérifie la validité
        if(!$jwt->isValid($token)){
            http_response_code(400);
            echo json_encode(['statut' => 2,'message' => 'Token invalide']);
            exit;
        }

        // On vérifie la signature
        if(!$jwt->check($token, $_ENV['SECRET'])){
        }

        // On vérifie l'expiration
        if($jwt->isExpired($token)){
            http_response_code(403);
            echo json_encode(['statut' => 2,'message' => 'Le token a expiré']);
            exit;
        }
}
// On dispatche les methodes aux controllers
switch ($ressource){
    case "" : 
        http_response_code(200);
        echo "Bienvenu sur l'api REST d'educycle";
        exit;
    case "signup":
    case "login":
    case "logout":
    case "accountVerification":
    case "user":
        $controller =  new UserController();
        break;
    case "comment":
        $controller = new CommentController();
        break;
    case "recover":
    case 'files':
    case "item":
    case "items":
        $controller = new ItemController();
        break;
    case "donation":
        $controller = new DonationController();
        break;
    case "residence":
        $controller = new ResidenceController();
        break;
    case "mediaUpdate":
    case "media" :
        $controller = new MediaController();
        break;
    default : 
        http_response_code(404);
        echo json_encode("message  :  Ressource not found");
        exit;
}
// Je performe la methode handleRequest de la classe AbstractController
$controller->handleRequest();
// echo json_encode($jwt->getPayload($token));