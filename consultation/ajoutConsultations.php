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
$date = new DateTime();
$minutes = $date->format('i');
$roundedMinutes = ceil($minutes / 15) * 15;
$date->setTime($date->format('H'), $roundedMinutes);
$date=$date->format('Y-m-d H:i:s');  

if(isset($_POST['date_rdv'])){
    $date_rdv = new DateTime($_POST['date_rdv']);
    $date_rdv=$date_rdv->format('Y-m-d H:i:s');
    //---------------------------check de duree-------------------------------------//
    if(explode(":",$_POST['duree'])[0]>23 or explode(":",$_POST['duree'])[1]>59){
        header("location: ajoutConsultations.php?error=2");
        exit();
    }
    //---------------------------check chevauchement de rdv-------------------------//
    $req1 = $conn->prepare('SELECT * FROM rdv WHERE id_medecin = :id_medecin');
    $req1->execute(array(
        'id_medecin' => $_POST['id_medecin'],
    ));
    $rdvs = $req1->fetchAll();
    foreach($rdvs as $rdv){
        if(($rdv['date_rdv']<$rdv_date and $rdv_date<$date_rdv_2_fin) or ($rdv['date_rdv']<$date_rdv_1_fin and $date_rdv_1_fin<$date_rdv_2_fin)){
            header("location: ajoutConsultations.php?error=1");
            exit();
        }
    }

    $duree = $_POST['duree'];
    $patient = $_POST['id_patient'];
    $medecin=$_POST['id_medecin'];
    $req1 = $conn->prepare('INSERT INTO rdv(date_rdv, duree, id_medecin, id_usager) VALUES(:date_rdv,:duree,:id_medecin,:id_usager)');

        if (!$req1){
            die('Erreur : ' . $req1.errorInfo());
        }
    try{
        $req1->execute(array(
            'date_rdv' => $date_rdv,
            'duree' => $duree,
            'id_medecin' => $medecin,
            'id_usager' => $patient,
        ));
    }catch(Exception $e){
        header("location: ajoutConsultations.php?error=1");
        exit();
    }
    if($req1){
        header("location: ../index.php");
    }else{
        echo 'query error: '. mysqli_error($conn);
    }
}
$now = new DateTime('now');

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Ajouter une consultation</title>
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    </head>
    <body>
        <div id="errorModal" class="modal">
            <div class="modal-content">
                <span class="close" id="close">&times;</span>
                <h2>Une erreur s'est produite !</h2>
                <?php 
                    if(isset($_GET['error'])){
                        if($_GET['error']==2){
                            echo '<p>La durée fournie est invalide car le cabinet sera fermé.</p>';
                        }
                    }else{
                        echo '<p>La consultation que vous avez créé chevauche une autre consultation de ce médecin ou arrivera a son terme en dehors de horraires du cabinet.</p>';
                    }
                ?>
            </div>
        </div>
        <?php include '../nav.php'; ?>
        <div class="col-12 justify-content-center">
            <div class="dashboard" style="width:39vw;">
                <h2>Ajouter une consultation</h2>
                <form action="AjoutConsultations.php" method="POST" class="form col-12">
                    <div class="col-4">
                        <div class="col-2">
                            <label for="nom">Date de la consultaion</label>
                            <input type="datetime-local" name="date_rdv" id="date_rdv"  value="<?= $date?>" required>
                            <label for="id_patient">Patient</label>
                            <select name="id_patient" id="id_patient" required>
                                <?php
                                    $patients = $conn->query('SELECT id_usager, nom, prenom FROM usager ORDER BY nom');
                                    while ($patient = $patients->fetch()){
                                        echo '<option value="' . $patient['id_usager'] . '">' . $patient['nom'] . ' ' . $patient['prenom'] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-2">
                            <label for="duree">Durée (Heure:minute)</label>
                            <input type="text" name="duree" id="duree" placeholder="00:00"  value="00:30"required>
                            <label for="id_medecin">Medecin</label>
                            <select name="id_medecin" id="id_medecin" required>
                                <?php
                                    $medecins = $conn->query('SELECT id_medecin, nom, prenom FROM medecin ORDER BY nom');
                                    while ($medecin = $medecins->fetch()){
                                        echo '<option value="' . $medecin['id_medecin'] . '">' . $medecin['nom'] . ' ' . $medecin['prenom'] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <input type="submit" value="Envoyer" class="col-2 submit">
                </form>
            </div>
        </div>
    </body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="..\jQuery-Mask-Plugin-master\src\jquery.mask.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script type="text/javascript">
    $(document).ready(function(){
        <?php
            if(isset($_GET['error'])){
                echo 'openModal();';
            }
        ?>

        var datepicker = flatpickr("#date_rdv", {
            enableTime: true,
            noCalendar: false,
            dateFormat: "Y-m-d H:i",
            minuteIncrement: 15,
            allowInput: false,
            minTime: "08:00",
            maxTime: "17:45",
            time_24hr: true
        });

        var timepicker = flatpickr("#duree", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            minuteIncrement: 15,
            allowInput: false,
            minTime: "00:15",
            maxTime: "06:00",
            defaultDate: "00:30",
            time_24hr: true
        });
        $('#duree').mask('00:00');

        function openModal() {
            document.getElementById("errorModal").style.display = "block";
        }
/* 
        function setMinutesStep(inputElement, step) {
            const dateField = inputElement;
            dateField.addEventListener('input', function () {
                let selectedDate = new Date(dateField.value);
                let minutes = selectedDate.getMinutes();
                let nearestStep = Math.round(minutes / step) * step;
                selectedDate.setMinutes(nearestStep);
                dateField.value = selectedDate.toISOString().slice(0, 16);
            });
        }

        const inputElement = document.getElementById('date_rdv');
        setMinutesStep(inputElement, 15);
 */
        $('#date_rdv').change(function(){
            check();
        });

        $('#id_medecin').change(function(){
            check();
        });

        $('#duree').change(function(){
            check();
        });

        
        function check(){
            var date_rdv = $('#date_rdv').val();
            var id_medecin = $('#id_medecin').val();
            $.ajax({
                url: 'checkPriseConsultation.php',
                type: 'POST',
                data: {
                    date_rdv: date_rdv,
                    id_medecin: id_medecin,
                    duree: $('#duree').val(),
                    type:"insert",
                    date_rdv_og: null,
                },
                success: function(response){
                    if(response == 1){
                        datepicker.setDate("<?= $date?>");
                        timepicker.setDate("00:30");
                        openModal();
                    }else if(response == 2){
                        datepicker.setDate("<?= $date?>");
                        timepicker.setDate("00:30");
                        openModal();
                    }
                }
            }); 
        }

        // Fonction pour fermer la modal
        $('#close').click(function() {
            closeModal();
        });
        function closeModal() {
            document.getElementById("errorModal").style.display = "none";
        }

        // Fermer la modal si l'utilisateur clique en dehors de la zone de contenu
        window.onclick = function(event) {
            var modal = document.getElementById("errorModal");
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }
    });
</script>