<?php
namespace Model;
use PDO;

class Residence extends AbstractModel {
    private string $table_ass;
    
    public function __construct() {
        parent::__construct("ed_residence");
    }
    public function get($id, $foreignKey='idUser'){
        try{
            $sql ="SELECT * FROM $this->table WHERE $foreignKey=$id";
            $stmt= $this->con->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->result =  $row ? $row :  [];
            return $this->result;
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function create($data){
        $sql =  "INSERT INTO $this->table (idUser,idItem,name,url,website) VALUES (:idUser,:idItem,:name,:url,:website)";
        $stmt = $this->con->prepare($sql);
        if ($stmt->execute([
            "idUser"=> $data["idUser"],
            "idItem"=> $data["idItem"],
            "name"=> $data["name"],
            "url"=> $data["url"],
            "website"=> $data["website"]
        ])){
            $sql = "SELECT * FROM $this->table WHERE id=LAST_INSERT_ID()";
            $stmt =  $this->con->query($sql);
            $this->result =  $stmt->fetch(PDO::FETCH_ASSOC);
            return  true;
        }else return false ; 
    }
}