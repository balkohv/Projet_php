<?php
 error_reporting(E_ALL); 
 ini_set("display_errors", 1);
 try{
     $conn = new PDO('mysql:host=localhost;port=3306;dbname=projet_php','root','root');
 }
 catch (Exception $e)
 {
    die('Erreur : ' . $e->getMessage());
 }
 if(!isset($_GET['date'])){
    $Sdate = new DateTime();
 }else{
    $Sdate = new DateTime($_GET['date']);
 }


?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>visualiser les medecins</title>
        <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        <header>
            <h1>Projet 1</h1>
        </header>
        <?php include '../nav.html'; ?>
        <div class="col-12 justify-content-center">
            <div class="dashboard" style="width:70vw;flex-direction:column;">
                <h2>Visualiation consultation</h2>
                <form action="visualisationConsultations.php" method="GET" class="form">
                    <div class="col-4">
                        <div class="col-2">
                            <input type="date" name="date" id="date" value="<?php echo $Sdate->format('Y-m-d'); ?>">
                        </div>
                        <div class="col-2">
                            <select name="id_medecin" id="id_medecin" >
                                <option value="0">Tous</option>
                                <?php
                                    $medecins = $conn->query('SELECT id_medecin, nom, prenom  FROM medecin WHERE archive = 0 ORDER BY nom');
                                    while ($medecin = $medecins->fetch()){
                                        $selected="";
                                        if ($medecin['id_medecin'] == $_GET['id_medecin']){
                                            $selected="selected";
                                        }
                                        echo '<option value="' . $medecin['id_medecin'] . '" '.$selected.'>' . $medecin['nom'] . ' ' . $medecin['prenom'] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-2">
                            <input type="submit" class="submit" style="margin:0!important;"value="Rechercher">
                        </div>
                    </div>
                </form>
                <table cellspacing="0" class="tableCons">
                    <thead>
                        <tr>
                            <th>Medecin</th>
                            <th>8h</th>
                            <th>9h</th>
                            <th>10h</th>
                            <th>11h</th>
                            <th>12h</th>
                            <th>13h</th>
                            <th>14h</th>
                            <th>15h</th>
                            <th>16h</th>
                            <th>17h</th>
                            <th>18h</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(isset($_GET['id_medecin']) and $_GET['id_medecin'] != 0){
                                $req1 = $conn->prepare('SELECT * FROM rdv where date_rdv> :daterdv and date_rdv < :daterdv2 and id_medecin = :id_medecin');
                                $req1->execute(
                                    array(
                                        'daterdv' => $Sdate->format('Y-m-d'),
                                        'daterdv2' => $Sdate->modify('+1 day')->format('Y-m-d'),
                                        'id_medecin' => $_GET['id_medecin'],
                                    )
                                );
                            }else{
                                
                                $req1 = $conn->prepare('SELECT * FROM rdv where date_rdv> :daterdv and date_rdv < :daterdv2');
                                $req1->execute(
                                    array(
                                        'daterdv' => $Sdate->format('Y-m-d'),
                                        'daterdv2' => $Sdate->modify('+1 day')->format('Y-m-d'),
                                    )
                                );
                            }
                            while($rdv = $req1->fetch()){
                                //-----------------------------------------calcul date------------------------------------//
                                $date = new DateTime($rdv['date_rdv']);
                                $now = $date;
                                $heuredebut =$date->format('H');
                                $minuteDebut =$date->format('i');
                                $now->modify('+' . explode(":",$rdv['duree'])[0] . ' hours');
                                $now->modify('+' . explode(":",$rdv['duree'])[1] . ' minutes');
                                $minutefin = $now->format('i');
                                $heurefin = $now->format('H');
                                $duree = $rdv['duree'];
                                //-----------------------------------------recuperation medecin------------------------------------//
                                $reqSelMedecin = $conn->prepare('SELECT * FROM medecin where id_medecin = :id');
                                $reqSelMedecin->execute(array(
                                    'id' => $rdv['id_medecin'],
                                ));
                                $medecin = $reqSelMedecin->fetch();
                                //-----------------------------------------ecriture ligne------------------------------------//
                                echo '<tr id="'.$rdv['date_rdv'].'+'.$rdv['id_medecin'].'" class="row consultation" title="De '.$heuredebut.'h'.$minuteDebut.'m a '.$heurefin.'h'.$minutefin.'m">';
                                echo '<td>'.$medecin['civilite'].' '.$medecin['nom'].', '.$medecin['prenom'].'</td>';
                                for ($i = 8;$i <= 18 ; $i++) {// ecriture du "trait" sur la ligne 
                                    if($heuredebut < $i and $heurefin > $i){
                                        echo '<td class="tdrdv" style="border-left: none!important;"><div class="RdvEnCours"></div></td>';
                                    }elseif($heuredebut == $i and $heurefin != $heuredebut){
                                        echo '<td class="tdrdv" ><div class="RdvEnCours" style="width:'.(1-$minuteDebut/60)*100 .'%;float: inline-end;"></div></td>';
                                    }elseif($heurefin == $i and $heurefin != $heuredebut){
                                        echo '<td class="tdrdv" style="border-left: none!important;" ><div class="RdvEnCours" style="width:'.$minutefin/60*100 .'%;"></div></td>';
                                    }elseif ($heuredebut==$heurefin and $heuredebut == $i) {
                                        echo '<td class="tdrdv" ><div class="RdvEnCours" style="border-radius:5px;margin-inline-start:'.($minuteDebut)/60*100 .'%;margin-inline-end:'.(1-$minutefin/60)*100 .'%;"></div></td>';
                                    }else{
                                        echo '<td></td>';
                                    }
                                }
                                echo '</tr>';
                                //-----------------------------------------fin ecriture ligne------------------------------------//
                            }
                        ?>
                    </tbody>
            </div>
        </div>
    </body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $('.row').on('click',function(){
            modifier($(this).attr('id'));

        });
        

        function modifier(id){
            window.location.href = "modificationConsultation.php?id="+id.split("+")[1]+"&daterdv="+id.split("+")[0]+"";
        }
    });
</script>