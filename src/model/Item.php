<?php
namespace Model;
use PDO;
use PDOException;
final class Item extends AbstractModel {
    private $media;
    private $user;
    private $residence ;
    public function __construct(){
        parent::__construct("ed_item");
        $this->media = new Media('ed_item');
        $this->residence = new Residence();
        $this->user =  new User();
    }
    public function fullSearch($keyWord){
        try{
            $sql =" SELECT 
                ed_item.id,
                ed_donation.id AS idDonation,
                ed_item.idUser,
                ed_item.name,
                ed_item.category,
                ed_item.description,
                ed_item.worth,
                ed_item.state,
                ed_item.period,
                ed_item.available,
                ed_item.publishedDate,
                ed_item.statut
            FROM 
                $this->table
            LEFT JOIN 
                ed_donation ON $this->table.id = ed_donation.idItem
            WHERE 
                ed_item.statut = 'normal'
                AND MATCH (ed_item.name, ed_item.category, ed_item.description) AGAINST ($keyWord)
            UNION ALL
            SELECT 
                ed_item.id,
                ed_donation.id AS idDonation,
                ed_item.idUser,
                ed_item.name,
                ed_item.category,
                ed_item.description,
                ed_item.worth,
                ed_item.state,
                ed_item.period,
                ed_item.available,
                ed_item.publishedDate,
                ed_item.statut
            FROM 
                $this->table
            RIGHT JOIN 
                ed_donation ON ed_item.id = ed_donation.idItem
            WHERE 
                ed_item.statut = 'normal'
            AND MATCH (ed_item.name,ed_item.description,ed_item.category) AGAINST ($keyWord);"; 

            $stmt= $this->con->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            for ($i = 0; $i < count($rows); $i++){
                $rows[$i]['medias'] =  $this->media->getAll($rows[$i]['id']);
                $publisher =  $this->user->get($rows[$i]['idUser'],true);
                $rows[$i]['publisher'] = $publisher;
                    $rows[$i]['residence'] = $this->residence->get($rows[$i]['id'],'idItem');
            }
            $this->result =  $rows;
            return $this->result;
        }catch(PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function getRecover(int $id, $who) {
        try{
            $sql ="SELECT ed_item.id,ed_donation.id as idDonation,ed_item.idUser,ed_item.name,ed_item.category,ed_item.description,ed_item.worth,ed_item.state,ed_item.period,ed_item.available,ed_item.publishedDate,ed_item.statut FROM $this->table JOIN ed_donation ON (ed_item.id =  ed_donation.idItem) WHERE ed_donation.id$who=$id";
            $stmt= $this->con->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            for ($i = 0; $i < count($rows); $i++){
                $rows[$i]['medias'] =  $this->media->getAll($rows[$i]['id']);
                $publisher =  $this->user->get($rows[$i]['idUser'],true);
                $rows[$i]['publisher'] = $publisher;
                    $rows[$i]['residence'] = $this->residence->get($rows[$i]['id'],'idItem');
            }
            $this->result =  $rows;
            return $this->result;
        }catch(PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function get($id){ 
        // On fait une UNION car les full JOIN ne sont pas possible sous Mysql
        try{
            $sql ="SELECT ed_item.id,ed_donation.id as idDonation,ed_item.idUser,ed_item.name,ed_item.category,ed_item.description,ed_item.worth,ed_item.state,ed_item.period,ed_item.available,ed_item.publishedDate,ed_item.statut
            FROM $this->table
            LEFT JOIN ed_donation ON $this->table.id = ed_donation.idItem
            WHERE $this->table.id =$id;
            UNION ALL
            SELECT ed_item.id,ed_donation.id as idDonation,ed_item.idUser,ed_item.name,ed_item.category,ed_item.description,ed_item.worth,ed_item.state,ed_item.period,ed_item.available,ed_item.publishedDate,ed_item.statut
            FROM $this->table
            RIGHT JOIN ed_donation ON $this->table.id = ed_donation.idItem
            WHERE $this->table.id =$id;";
            $stmt= $this->con->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt = null;
            $medias  =  $this->media->getAll($row['id']);
            $row['medias'] = $medias;
            $publisher =  $this->user->get($row['idUser'],true);
            $row['publisher'] = $publisher;
            $row['residence'] = $this->residence->get($row['id'],'idItem');
            $this->result =  $row;
        }catch(PDOException $e){
            echo json_encode(['statut' => 2,'message'=>  'Oui nous sommes...'.$e->getMessage()]);
            exit;
        }
    }
    public function getAll($idUser = 0){
        try{
            // On verifie ceci car quand les utlisateurs suppriment leur comptes, les annonces qu'ils ont publiées sont concervés
            $sql = ($idUser==0 || $idUser ==null)? "SELECT * FROM $this->table WHERE idUser IS NOT NULL" : "SELECT * FROM $this->table WHERE idUser=$idUser";
            $stmt= $this->con->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            for ($i = 0; $i < count($rows); $i++){
                $rows[$i]['medias'] =  $this->media->getAll($rows[$i]['id']);
                $publisher =  $this->user->get($rows[$i]['idUser'],true);
                $rows[$i]['publisher'] = $publisher;
                 $rows[$i]['residence'] = $this->residence->get($rows[$i]['id'],'idItem');
            }
            $this->result =  $rows;
        }catch(PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function create($data){
        // Je insère l'annonce en base et je déplace les images vers le repertoire des images de serveurs
        try{
            $sql =  "INSERT INTO $this->table (idUser,name,worth,state,description,available,period,category) VALUES (:idUser,:name,:worth,:state,:description,:available,:period,:category)";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam("idUser", $data["idUser"]);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':worth', $data['worth']);
            $stmt->bindParam(':state', $data['state']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':period', $data['period']);
            $stmt->bindParam(':category', $data['category']);
            $stmt->bindParam(':available', $data['available']);
            if ($stmt->execute()){
                $sql = "SELECT * FROM ed_item WHERE id=LAST_INSERT_ID()";
                $stmt =  $this->con->query($sql);
                $this->result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$this->media->moveMedia($this->result['id'],"","")){
                    echo json_encode(['statut' => 2,'message'=> "Les images n'ont pas pu être uploader sur serveur!"]);
                    exit;
                }
                  // On enregiste la residence 
                $data['residence'] =  json_decode($data['residence'], true);
                $data['residence']['idItem'] = $this->result['id'];
                $data['residence']['idUser'] = 0; //C'est parce que je veux pas mettre les tables d'associations
                $this->residence->create($data['residence']);
                return  true;
            }
        }catch(PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
}