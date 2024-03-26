<?php
namespace Model;
use PDO;
class Comment extends AbstractModel {
    private string $table_ass;
    public function __construct(){
        $this->table_ass =  'ed_user';
        parent::__construct("ed_comment");
    }
    
    public function get($id){
        try{
            $x =  strlen($this->table_ass) - 3;
            $field =  substr($this->table_ass,-$x);
            $sql ="SELECT * FROM $this->table JOIN $this->table_ass ON $this->table.id$field = $this->table_ass.id$field WHERE $this->table.id$field=$id";
            $stmt= $this->con->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->result =  $row;
        }catch(\Exception $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function create($data){
        try{            
            $sql =  "INSERT INTO $this->table (note, message, idItem, idTarget, idHunter) VALUES (:note, :message, :idItem, :idTarget, :idHunter)";
            $stmt = $this->con->prepare($sql);
            if ($stmt->execute($data)){
                $sql = "SELECT * FROM $this->table WHERE id=LAST_INSERT_ID()";
                $stmt =  $this->con->query($sql);
                $this->result =  $stmt->fetch(PDO::FETCH_ASSOC);
                return  true;
            }else return false ;    
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
}