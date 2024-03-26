<?php
namespace Controller;
use Model\User;
use Model\Donation;
use Controller\AbstractController;

class DonationController extends AbstractController {
    private $donation;
    private $user;
    public function __construct() {
        parent::__construct();
        $this->donation = new Donation();
        $this->user = new User();
    }
    public function create(){}
    public function handleRequest (){
        switch($this->method) {
            case "GET":
                $this->donation->getAll($this->id);
                $results  =  $this->donation->getResult();
                for($i = 0; $i<count($results); $i++ ) {
                    $sender =  Array();
                    $sender = $this->user->get($results[$i]['idHunter'],true);
                    $results[$i]['sender'] = $sender;
                }
                $this->result =  $results;
                break;
            case "DELETE":
                $this->result =  $this->donation->delete($this->id) ? [ "statut"=> 1,"message"=> "Succeed"] :[ "statut"=> 0,"message"=> "Failed"];
                break;
        }
        http_response_code(200);
        echo json_encode($this->result);
        exit;
    }
}