<?php 
namespace Controller;
use Controller\AbstractController;
use Model\Donation;
use Model\Item;

class ItemController extends AbstractController{
    private $item;
    private $donation;
    public function handleRequest(){
        $this->item = new Item();
        $this->donation = new Donation();
        switch ($this->method){
            case "GET":
                switch ($this->ressource){
                    case "item" : 
                        if ($this->id != 0 && $this->id !=""){
                            $this->item->get($this->id);
                        }else{
                            $this->item->getAll();
                        }
                        $this->result =  $this->item->getResult(); 
                        break;
                    case "items":
                        if($this->id!="" || $this->id!=0){
                            $this->item->getAll($this->id);  
                            $this->result = $this->item->getResult();
                        }
                        break;
                    case 'recover' :
                        if ($this->id!="" || $this->id!=0){
                            $this->result = $this->item->getRecover($this->id,'Target');
                        }
                        break;
                    case 'files' :
                        if ($this->id!="" || $this->id!=0){
                            $this->result = $this->item->getRecover($this->id,'Hunter');
                        }
                        break;
                    case 'fullSearch':  
                        if ($this->id!=""){
                            $this->result =  $this->item->fullSearch($this->id);
                        }
                        break;
                }
                break;
            case "POST":
                switch ($this->ressource){
                    case "item":
                        if ($this->body!=null && isset($_FILES['files'])){
                            $this->result =  $this->item->create($this->body) ? [ "statut"=> 1,"message"=> "Succeed"] : [ "statut"=> 0,"message"=> "Failed"];
                        }else{
                            $this->result = ['statut' => 2, 'message' => "Le formualire n'a pas pu être correctement chargé côté serveur"];
                        }
                        break;
                    case "recover":
                        $this->donation->create($this->body);
                        $fields = Array();
                        $fields["id"] = $this->body['idItem'];
                        $fields["statut"] = "En attente de validation";
                        $this->result = $this->item->update($fields) ? [ "statut"=> 1,"message"=> "Succeed"] :[ "statut"=> 0,"message"=> "Failed"];
                }
                break;
            case "PATCH" :
                switch ($this->ressource){
                    case "item":
                        if ($this->body!= null){
                            $this->result = $this->item->update($this->body) ? [ "statut"=> 1,"message"=> "Succeed"] :[ "statut"=> 0,"message"=> "Failed"];
                        }else{
                            $this->result = ['statut' => 2, 'message' => "Le formualire n'a pas pu chargé côté serveur"];
                        }
                        break;  
                }
                break;
            case "DELETE":
                if ($this->ressource == "item"){
                    if ($this->item->delete($this->id)){
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