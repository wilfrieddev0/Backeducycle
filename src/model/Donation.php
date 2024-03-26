<?php
namespace Model;
use PDOException;
use PDO;
final class Donation extends AbstractModel {
    public function __construct(){
        parent::__construct("ed_donation");
    }
    public function create($data){
        try{
            $sql =  "INSERT INTO ed_donation (message, idHunter, idTarget, idItem) VALUES (:message, :idHunter, :idTarget, :idItem)";
            $stmt = $this->con->prepare($sql);
            if ($stmt->execute($data)){
                $sql = "SELECT * FROM $this->table WHERE id=LAST_INSERT_ID()";
                $stmt =  $this->con->query($sql);
                $this->result =  $stmt->fetch(PDO::FETCH_ASSOC);
                return  true;
            }else return false ; 
        }catch(PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public  function getAll(int $id) {
        try{
            $sql =  "SELECT * FROM ed_donation WHERE idTarget=$id";
            $stmt = $this->con->prepare($sql);
            if ($stmt->execute()){
                $this->result = $stmt->fetchAll(PDO::FETCH_ASSOC);;
                return  true;
            }else return false ; 
        }catch(PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
}