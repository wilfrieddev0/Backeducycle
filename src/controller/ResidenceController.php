<?php
namespace Controller;
use Controller\AbstractController;
use Model\Residence;
class ResidenceController extends AbstractController{
    private $address;
    public function handleRequest (){
        $this->address = new Residence();
        switch ($this->method){
            case "GET":
                if ($this->ressource =="address"){
                    if ($this->id != 0){
                        $this->address->get($this->id);
                    }else{
                        // $this->address->getAll();
                    }
                    $this->result =  $this->address->getResult();
                }
                break;

            case "POST":
                switch ($this->ressource){
                    case "updateAddress":
                        if ($this->address->update($this->body)){
                            $this->result = [ "statut"=> 1,"message"=> "Succeed"];
                        }else{
                            $this->result = [ "statut"=> 0,"message"=> "Failed"];
                        };
                        break;
                }
                break;
            case "DELETE":
                if ($this->ressource == "address"){
                    if ($this->address->delete($this->id)){
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