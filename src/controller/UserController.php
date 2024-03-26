<?php
namespace Controller;
use Model\User;

class UserController extends AbstractController{
    private $user;
    private $headers = [
        'typ' => 'JWT',
        'alg' => 'HS256'
    ];
    public function handleRequest(){
        $this->user = new User();
        switch ($this->method){
            case "GET":
                if ($this->ressource =="user"){
                    if ($this->id != 0){
                        $this->user->get($this->id);
                    }else{
                        $this->user->getAll();
                    }
                    $this->result =  $this->user->getResult();
                } else if ($this->ressource == 'accountVerification'){
                    // La verificaiton de mail
                    $this->result = [];
                    $this->body['id'] =  $this->id;
                    $this->body['emailVerified'] =  1;
                    if ($this->user->update($this->body)){
                        $this->result = [ "statut"=> 1,"message"=> "Succeed"];
                    }else{
                        $this->result = [ "statut"=> 0,"message"=> "Failed"];
                    };
                    break;
                }
                break;
            case "POST":
                switch ($this->ressource){
                    // Le jwt d'autorisation sera crée à chaque inscription
                    case "signup":
                        if ($this->body!=null){
                            if ($this->user->create($this->body)){
                                // On declare le header du token conformemenet à la doc
                                // Ici le payload C'est quelque info sur l'utillisteur
                                $data = $this->user->getResult();
                                $data['token'] = $this->jwt->generate($this->headers, $this->user->getResult(),$_ENV['SECRET']); 
                                $this->result =  ["statut" => 1,"message" =>  "Signup succeed","data" => $data];
                            }else{
                                $this->result =  [ "statut" => 0,"message" =>  "Signup failed"];
                            }
                        }else{
                            $this->result = ['statut' => 2, 'message' => "Le formualire n'a pas pu chargé côté serveur"];
                        }
                        break;
                    case "login" : 
                        if ($this->user->login($this->body)){
                            $data = $this->user->getResult();
                            $data['token'] = $this->jwt->generate($this->headers, $this->user->getResult(),$_ENV['SECRET']); 
                            $this->result =  [ "statut" => 1,"message" =>  "login succeed","data" => $data];
                        }else{
                            $this->result =  [ "statut" => 0,"message" =>  "login failed. Login or password wrong"];    
                        }
                        break; 
                }
                break;
            case "PATCH" :
                $this->result =$this->user->update($this->body) ? [ "statut"=> 1,"message"=> "Succeed"] : [ "statut"=> 0,"message"=> "Failed"];
                break;
            case "DELETE":
                $this->result =$this->user->delete($this->id) ? [ "statut"=> 1,"message"=> "Succeed"] : [ "statut"=> 0,"message"=> "Failed"];
                break;
            }
            http_response_code(200);
            echo json_encode($this->result);
            exit;
    }
}