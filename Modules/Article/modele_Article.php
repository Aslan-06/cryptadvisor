<?php
require_once "./connexion.php";

class ModeleArticle extends Connexion {

    public function getListeArticles($page){
        if($page < 1)
            $page = 1;
        $listeDebut = ($page-1)*10;
        $req = self::$bdd->prepare("SELECT idArticle, titre, contenuArticle, nbVues, likes, dateCreaArticle FROM Article LIMIT 10 OFFSET ?");
        $req->bindParam(1, $listeDebut, PDO::PARAM_INT);
        $req->execute();
        $listeArticles = $req->fetchAll(PDO::FETCH_ASSOC);
        if(isset($_SESSION['pseudo'])){
            $req = self::$bdd->prepare("SELECT idUtilisateur FROM Utilisateur WHERE pseudo = ?");
            $req->bindParam(1, $_SESSION['pseudo'], PDO::PARAM_STR);
            $req->execute();
            $idUtilisateur = $req->fetch(PDO::FETCH_ASSOC);

            $req = self::$bdd->prepare("SELECT idRole FROM Utilisateur WHERE idUtilisateur = ?");
            $req->bindParam(1, $idUtilisateur['idUtilisateur'], PDO::PARAM_STR);
            $req->execute();
            $role = $req->fetch(PDO::FETCH_ASSOC)['idRole'];

            $listeArticles['role'] = $role;
        }
        $listeArticles['page']=$page;
        return $listeArticles;
    }

    public function getArticle($id){
        $req = self::$bdd->prepare("UPDATE Article SET nbVues = nbVues+1 where idArticle = ?");
        $req->bindParam(1, $id, PDO::PARAM_INT);
        $req->execute();
        $req = self::$bdd->prepare("SELECT titre, contenuArticle FROM Article where idArticle = ?");
        $req->bindParam(1, $id, PDO::PARAM_INT);
        $req->execute();
        $article = $req->fetch(PDO::FETCH_ASSOC);
        return $article;
    }
}