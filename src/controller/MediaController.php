<?php 
namespace Controller;
use Controller\AbstractController;
use Model\Media;

class MediaController extends AbstractController{
    private $media;
    public function handleRequest (){
        $this->media = new Media('ed_user');
        switch ($this->method){
            // Que pour test
            case "GET":
                if ($this->ressource =="media"){
                    if ($this->id != 0){
                        $this->media->get($this->id);
                    }else{
                        $this->media->getAll($this->id);
                    }
                    $this->result = ['statut' => 1,'data' => $this->media->getResult()];
                }
                break;
            case "POST":
                if ($this->body==null){
                    $this->result = [ "statut"=> 2,"message"=> "Le formulaire n'a pas pu charger côté serveur!"];
                    break;
                }
                switch ($this->ressource){
                    case 'media' :  
                        $this->result = $this->media->moveMedia("",$this->body['idUser'],$this->body['name']) ? [ "statut"=> 1,"message"=> "Succeed",'data' => $this->media->getResult()] : [ "statut"=> 0,"message"=> "Failed"];
                        break;
                    case "updateMedia":
                        if ($this->media->update($this->body)){
                            $this->result = [ "statut"=> 1,"message"=> "Succeed"];
                        }else{
                            $this->result = [ "statut"=> 0,"message"=> "Failed"];
                        };
                        break;
                }
                break;
            case "PATCH":
                $this->result = $this->media->update($this->body) ? [ "statut"=> 1,"message"=> "Succeed", 'data' => $this->media->getResult()] : [ "statut"=> 0,"message"=> "Failed"];
                break;
            case "DELETE":
                if ($this->ressource == "media"){
                    if ($this->media->delete($this->id)){
                        $this->result = [ "statut"=> 1,"message"=> "Succeed"];
                    }else{
                        $this->result = [ "statut"=> 0,"message"=> "Failed"];
                    };
                }
            }
            http_response_code(200);
            echo json_encode($this->result);
            exit;
    }

}