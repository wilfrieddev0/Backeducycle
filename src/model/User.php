<?php
namespace Model;
use PDO;

class User extends AbstractModel{
    private $media;
    private $residence ;

    public function __construct(){
        Parent::__construct("ed_user");
        $this->media = new Media('ed_user');
        $this->residence = new Residence();
    }
    public function get($id,bool $restrict = false){
        try{
            //BON A SAVOIR : Mysql ne prend pas en compte les FULL JOIN par consequent on contourne en faisant une UNION d'une d'une LEFT JOIN  et d'une RIGHT JOIN
            $sql ="SELECT * FROM  ed_user WHERE id = $id";
            $stmt= $this->con->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row){
                $this->result =  $row;
                $this->result['medias'] = $this->media->get($id);
                $this->result['residence'] = $this->residence->get($id);
                unset($this->result['password']);
                // Je suppime de mdp
                if (!$restrict){
                    return $this->result;
                }else{ 
                    $result = [];
                    $result['id'] =  $row['id'];
                    $result['name'] = $row['firstName'].' '.$row['lastName'];
                    $result['dateCreation'] = $row['dateCreation'];
                    $result['email'] =  $row['email'];
                    $result['medias'] = $this->media->get($id);
                    $this->result['residence'] = $this->residence->get($id);
                    return $result;
                }
            }else $this->result = [];
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function getAll(){}
    public function login($credentials) : bool{
        try{
            $login  =  $credentials['login'];
            $password = $credentials['password'];
            $sql = "SELECT * FROM ed_user WHERE email=?";
            $query = $this->con->prepare($sql);
            $query->bindParam(1, $login, PDO::PARAM_STR);
            $query->execute();
            $row =  $query->fetch(PDO::FETCH_ASSOC);
            if ($row){
                if (password_verify($password.$_ENV['SALTING_KEY'],$row["password"])){
                    $this->result =  $row;
                    // Je suppime de mdp
                    unset($this->result["password"]);
                    if ($row['id']) {
                        $this->result['medias'] = $this->media->get($row['id']);
                        $this->result['residence'] = $this->residence->get($row['id']);
                    }
                    return true;
                }
            }
            return false;
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function update($data){
        try{
            $dataString = "";
            foreach ($data as $key => $value) {
                if ($key != "id") {
                    // Mis à jour de la residence
                    if ($key == 'residence'){
                        $value['id'] = $data['id'];
                        $this->residence->update($value);
                    }else if ($key == "password"){
                        //Si parmis les informations à mettre à jour il y'a le mot de passe on le hashe avant.
                        $value =password_hash($value.$_ENV['SALTING_KEY'], PASSWORD_DEFAULT);
                    }
                    $dataString .= $key.'=\''.$value."', ";;
                }
            }
            $dataString = rtrim($dataString, ", "); 
            $id =  $data["id"];
            $sql = "UPDATE ed_user SET $dataString WHERE id=$id";
            $stmt = $this->con->prepare($sql);
            return $stmt->execute();
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function create($data) : bool {
        try{
            $sql =  "INSERT INTO ed_user (role,firstName,lastName,email,phone,birthday,password) VALUES (:role,:firstName,:lastName,:email,:phone,:birthday,:password);";
            $stmt =  $this->con->prepare($sql);
            //On hashe le mot de passe avant de l'enregistrer avec une clé de sallage personnalisée
            $password =  $data["password"].$_ENV['SALTING_KEY'];
            $data["password"] =  password_hash($password,PASSWORD_DEFAULT); 
            $stmt->execute([
                "role"=> $data["role"],
                "firstName"=> $data["firstName"],
                "lastName"=> $data["lastName"],
                "email"=> $data["email"],
                "phone"=> $data["phone"],
                "birthday"=> $data["birthday"],
                "password"=> $data["password"],
            ]);
            $sql = "SELECT * FROM ed_user WHERE id=LAST_INSERT_ID()";
            $stmt =  $this->con->query($sql);
            $this->result = $stmt->fetch(PDO::FETCH_ASSOC);
            // On enregiste la residence 
            $data['residence']['idUser'] = $this->result['id'];
            $data['residence']['idItem'] = 0; //C'est parce que je veux pas mettre les tables d'association
            $this->residence->create($data['residence']);
            unset($this->result['password']);
            return  true;
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function __destruct(){}
}