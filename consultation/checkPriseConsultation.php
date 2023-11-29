<?php //FIXME: verifier pourquoi le check ne marche pas a l'insertion
      //TODO: bloquer les champs time 
 error_reporting(E_ALL); 
 ini_set("display_errors", 1);
 try{
     $conn = new PDO('mysql:host=localhost;port=3306;dbname=projet_php','root','root');
 }
 catch (Exception $e)
 {
    die('Erreur : ' . $e->getMessage());
 }
    if($_POST['type']=='insert'){
        $req1 = $conn->prepare('SELECT * FROM rdv WHERE id_medecin = :id_medecin');
        $req1->execute(array(
            'id_medecin' => $_POST['id_medecin'],
        ));
        var_dump($req1->fetchAll());
    }else{
        $req1 = $conn->prepare('SELECT * FROM rdv WHERE id_medecin = :id_medecin AND date_rdv != :date_rdv');
        $req1->execute(array(
            'id_medecin' => $_POST['id_medecin'],
            'date_rdv' => $_POST['date_rdv_og'],
        ));
    }
    var_dump($_POST);
    $res="0";
    $rdvs = $req1->fetchAll();
    $rdv_date=new DateTime($_POST['date_rdv']);
    $rdv_date=$rdv_date->format('Y-m-d H:i:s');
    $rdv_date_d=new DateTime($rdv_date);
    $date_rdv_1_fin=$rdv_date_d;
    $date_rdv_1_fin->modify('+' . explode(":",$_POST['duree'])[0] . ' hours');
    $date_rdv_1_fin->modify('+' . explode(":",$_POST['duree'])[1] . ' minutes');
    if(($date_rdv_1_fin->format('H')>=18 and $date_rdv_1_fin->format('i')!="00")or $date_rdv_1_fin->format('H')<8){
        $res = "2";
    }
    $date_rdv_1_fin=$date_rdv_1_fin->format('Y-m-d H:i:s');
    foreach($rdvs as $rdv){
        $date_rdv_2 = new DateTime($rdv['date_rdv']);
        $date_rdv_2_fin = new DateTime($rdv['date_rdv']);
        $date_rdv_2_fin->modify('+' . explode(":",$rdv['duree'])[0] . ' hours');
        $date_rdv_2_fin->modify('+' . explode(":",$rdv['duree'])[1] . ' minutes');
        $date_rdv_2_fin=$date_rdv_2_fin->format('Y-m-d H:i:s');
        
        if(($rdv['date_rdv']<$rdv_date and $rdv_date<$date_rdv_2_fin) or ($rdv['date_rdv']<$date_rdv_1_fin and $date_rdv_1_fin<$date_rdv_2_fin) or ($rdv['date_rdv']>$date_rdv and $date_rdv_1_fin>$date_rdv_2_fin)){
            $res = "1";
        }
    }
    echo $res;
    ?>