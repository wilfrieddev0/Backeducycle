<?php
namespace Controller;
use Controller\AbstractController;
use Model\Comment;
class CommentController extends AbstractController {
    private $comment;
    public function handleRequest(){
        $this->comment = new Comment();
        switch ($this->method){
            case "GET":
                if ($this->ressource =="comment"){
                    if ($this->id != 0){
                        $this->comment->get($this->id);
                    }else{
                        // $this->comment->getAll();
                    }
                    $this->result =  $this->comment->getResult();
                }
                break;

            case "POST":
                switch ($this->ressource){
                    case "comment":
                        $this->result = $this->comment->create($this->body) ? [ "statut"=> 1,"message"=> "Succeed"] : [ "statut"=> 0,"message"=> "Failed"];
                        break;
                    case "updateComment":
                        if ($this->comment->update($this->body)){
                            $this->result = [ "statut"=> 1,"message"=> "Succeed"];
                        }else{
                            $this->result = [ "statut"=> 0,"message"=> "Failed"];
                        };
                        break;
                }
                break;
            case "DELETE":
                if ($this->ressource == "comment"){
                    if ($this->comment->delete($this->id)){
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