<?php
namespace Model;
use PDO;
final class Media extends AbstractModel {
    private string $table_ass;
    private $field_ass;
    
    public function __construct(string $table_ass) {
        $this->table_ass = $table_ass;
        parent::__construct("ed_media");
        $x =  strlen($this->table_ass) - 3;
        $field =  substr($this->table_ass,-$x);
        $this->field_ass =  ucfirst($field);
    }
    public function get($id){
        try{
            $sql ="SELECT ed_media.id,ed_media.idItem,ed_media.category,ed_media.description,ed_media.location FROM ed_media JOIN $this->table_ass ON $this->table.id$this->field_ass = $this->table_ass.id WHERE $this->table.id$this->field_ass=$id ORDER BY id DESC ";
            $stmt= $this->con->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $row['location'] = $_ENV['URL_APP'].$row['location'];
            return $row;
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        } 
    }
    public function getAll(int $id){
        try{
            $sql ="SELECT * FROM $this->table WHERE id$this->field_ass=$id";
            $stmt= $this->con->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows)> 0){
                for($i=0;$i<count($rows);$i++) {
                    $rows[$i]['location'] = $_ENV['URL_APP'].$rows[$i]['location'];
                }
            }
            return  $rows;
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function moveMedia($idItem =null, $idUser =null, $nameInput = "files",$idMedia=0){
        try{
            $uploads_dir = $_ENV['CHEMIN_IMAGES'];
            if (isset($_FILES["files"])){
                foreach ($_FILES["files"]["error"] as $key => $error) {
                    if ($error == UPLOAD_ERR_OK) {
                        $tmp_name = $_FILES["files"]["tmp_name"][$key];
                        $name = basename($_FILES["files"]["name"][$key]);
                        move_uploaded_file($tmp_name, "$uploads_dir/$name");
                        if ($idMedia>0){
                            $sql = "UPDATE $this->table SET location = '$uploads_dir/$name' WHERE id=$idMedia";
                            $stmt = $this->con->prepare($sql);
                            $stmt->execute();
                        }else{
                            $data = Array();
                            $data['idUser'] =  $idUser;
                            $data['idItem'] = $idItem;
                            $data['name'] =  $name;
                            $data['category'] =  $nameInput;
                            $data['location'] = "$uploads_dir/$name";
                            $this->create($data);
                        }
                    } else {
                        return false;
                    }
                }return true;
            }else if (isset($_FILES[$nameInput])){
                $file = $_FILES[$nameInput];
                if ($file['error'] == UPLOAD_ERR_OK) {
                    $tmp_name = $file["tmp_name"];
                    $name = basename($file["name"]);
                    move_uploaded_file($tmp_name, "$uploads_dir/$name");
                    if ($idMedia>0){
                        $sql = "UPDATE $this->table SET location = '$uploads_dir/$name' WHERE id=$idMedia";
                        $stmt = $this->con->prepare($sql);
                        $stmt->execute();
                        $this->result['newURL'] = $_ENV['URL_APP'].$uploads_dir."/".$name;
                    }else{
                        $data = Array();
                        $data['idUser'] =  $idUser;
                        $data['idItem'] = $idItem;
                        $data['name'] =  $name;
                        $data['category'] =  $nameInput;
                        $data['location'] = "$uploads_dir/$name";
                        return $this->create($data);
                    }
                }else{
                    return false;
                }
            }
        }catch(\Exception $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function create($data){
        try{
            $sql =  "INSERT INTO $this->table (idUser,idItem,name,category,location) VALUES (:idUser,:idItem,:name,:category,:location)";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([
                "idUser"=> $data['idUser'] =="" ? null : $data['idUser'],
                "idItem"=> $data['idItem'] =="" ? null : $data['idItem'],
                "name"=> $data["name"],
                "category"=> $data["category"], 
                "location"=> $data["location"]
            ]);
            $sql = "SELECT * FROM $this->table WHERE id=LAST_INSERT_ID()";
            $stmt =  $this->con->query($sql);
            $this->result =  $stmt->fetch(PDO::FETCH_ASSOC);
            return  true;
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function update($data){
        try{
            // Avant de mettre a jour un media on supprime celui qui existe dabord
            $id =  $data['id'];
            $sql = "SELECT * FROM $this->table WHERE id=$id";
            $stmt = $this->con->query($sql);
            $stmt =  $stmt->fetch(PDO::FETCH_ASSOC);
            if (file_exists($stmt['location'])){
                if(unlink($stmt['location'])){
                    $this->moveMedia("","",$data['name'], $id);
                }         
            }else{
                $this->moveMedia("","",$data['name'], $id);
            }
            return true;
       }catch(\PDOException $e){
           echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
           exit;
       }
    }
    public function delete($id){
        try{
            $sql = "SELECT * FROM ed_media WHERE id=$id";
            $stmt = $this->con->query($sql);
            $stmt =  $stmt->fetch(PDO::FETCH_ASSOC);
            if(unlink($stmt['location'])){
                $sql = "DELETE FROM $this->table WHERE id=$id";
                $stmt = $this->con->prepare($sql);
                $stmt->execute();
            }
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
}