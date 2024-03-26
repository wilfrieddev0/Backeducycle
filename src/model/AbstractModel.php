<?php
namespace Model;
use Model\DB\DBConnexion;
use PDO;

abstract class AbstractModel{
    protected $con;
    protected string $table;
    protected array $result; //Stockera les resultats de toutes les requetes
    public function __construct($table){
        $this->table = $table;
        $this->result =[];
        $con = DBConnexion::getUniqueInstance();
        $this->con =  $con->getConnexion();
    }
    public function get(int $id){
        try{
            $sql ="SELECT * FROM $this->table WHERE id=$id";
            $stmt= $this->con->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->result =  $row;
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public abstract function create($data);
    public function update($data){
        try{
             $dataString = "";
            foreach ($data as $key => $value) {
                if ($key != "id") {
                    $dataString .= $key.'=\''.$value."', ";;
                }
            }
            $dataString = rtrim($dataString, ", ");
            $id =  $data["id"];
            $sql = "UPDATE $this->table SET $dataString WHERE id=$id";
            $stmt = $this->con->prepare($sql);
            return $stmt->execute();
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function delete($id){
        try{
            $sql = "DELETE FROM $this->table WHERE id=$id";
            $stmt = $this->con->prepare($sql);
            return $stmt->execute();
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
        
    }
    public function getResult() : array  {
        return $this->result;
    }
    public function __destruct(){}
}