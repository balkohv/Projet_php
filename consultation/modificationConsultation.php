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
if(!isset($_GET['id']) and !isset($_POST['id'])){
    header("location: ../index.php");
}else{
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $date_rdv = $_GET['daterdv'];
    }elseif(isset($_POST['id'])){
        $id = $_POST['id'];
        $date_rdv = $_POST['date_rdv'];
    }
    $req1 = $conn->prepare('SELECT * FROM rdv WHERE date_rdv= :daterdv AND id_medecin = :id');
    $req1->execute(array(
        'daterdv' => $date_rdv,
        'id' => $id,
    ));
    $rdvs = $req1->fetch();
    if(!isset($rdvs['id_medecin'])){
       header("location: ../index.php"); 
    }
    if(isset($_POST['id_medecin'])){
        $date_rdv = new DateTime($_POST['date_rdv']);
        $duree = $_POST['duree'];
        $patient = $_POST['id_patient'];
        $medecin=$_POST['id_medecin'];
        $id_medecin=$_POST['id_medecin'];
        $req1 = $conn->prepare('UPDATE rdv set date_rdv = :date_rdv, duree = :duree, id_medecin = :id_medecin, id_usager = :id_usager WHERE date_rdv= :daterdv1 AND id_medecin = :id1');

            if (!$req1){
                die('Erreur : ' . $req1.errorInfo());
            }

            $req1->execute(array(
                'date_rdv' => $date_rdv->format('Y-m-d H:i:s'),
                'duree' => $duree,
                'id_medecin' => $medecin,
                'id_usager' => $patient,
                'daterdv1' => $rdvs['date_rdv'],
                'id1' => $rdvs['id_medecin'],
            ));
        if($req1){
          header("location: ../index.php");
        }else{
            echo 'query error: '. mysqli_error($conn);
        }
    }

}

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>modifier un usager</title>
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
                            echo '<p>La durée fournie n est pas correcte car il y a plus de 60 minutes ou 23H.</p>';
                        }
                    }else{
                        echo '<p>La consultation que vous avez créé chevauche une autre consultation de ce médecin ou arrivera a son terme en dehors de horraires du cabinet.</p>';
                    }
                ?>
            </div>
        </div>
        <header>
            <h1>Projet 1</h1>
        </header>
        <?php include '../nav.html'; ?>
        <div class="col-12 justify-content-center">
        <div class="dashboard" style="width:39vw;">
                <h2>Modifier une consultation</h2>
                <form action="ModificationConsultation.php" method="POST" class="form col-12">
                    <div class="col-4">
                        <div class="col-2">
                            <label for="nom">Date de la consultaion</label>
                            <input type="datetime-local" name="date_rdv" id="date_rdv" value="<?echo $rdvs['date_rdv']?>"required readonly>
                            <label for="id_patient">Patient</label>
                            <select name="id_patient" id="id_patient" required>
                                <?php
                                    $selected = '';
                                    $patients = $conn->query('SELECT id_usager, nom, prenom FROM usager ORDER BY nom');
                                    while ($patient = $patients->fetch()){
                                        if($patient['id_usager'] == $rdvs['id_usager']){
                                            $selected = 'selected';
                                        }
                                        echo '<option value="' . $patient['id_usager'] . '"'.$selected.'>' . $patient['nom'] . ' ' . $patient['prenom'] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-2">
                            <label for="duree">Durée (Heure:minute)</label>
                            <input type="text" name="duree" id="duree" placeholder="00:00" required>
                            <label for="id_medecin">Medecin</label>
                            <select name="id_medecin" id="id_medecin" required>
                                <?php
                                    $medecins = $conn->query('SELECT id_medecin, nom, prenom FROM medecin ORDER BY nom');
                                    $selected = '';
                                    while ($medecin = $medecins->fetch()){
                                        if($medecin['id_medecin'] == $rdvs['id_medecin']){
                                            $selected = 'selected';
                                        }
                                        echo '<option value="' . $medecin['id_medecin'] . '" '.$selected.'>' . $medecin['nom'] . ' ' . $medecin['prenom'] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="id" value="<?echo $rdvs['id_medecin']?>">
                    <input type="submit" value="Envoyer" class="col-2 submit">
                    <td><a class="delete col-2" href="suppressionConsultation.php?id=<?echo $rdvs['id_medecin']?>&date_rdv=<?echo $rdvs['date_rdv']?>">Supprimer</a></td>
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
            defaultDate: "<?echo $rdvs['duree']?>",
            time_24hr: true
        });

        $(".flatpickr-minute").attr("disabled", true);
        $(".flatpickr-hour").attr("disabled", true);

        $('#duree').mask('00:00');

        function openModal() {
            document.getElementById("errorModal").style.display = "block";
        }

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

        /* const inputElement = document.getElementById('date_rdv');
        setMinutesStep(inputElement, 15); */

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
                    type:"update",
                    date_rdv_og: '<?echo $rdvs['date_rdv']?>',
                },
                success: function(response){
                    if(response == 1){
                        datepicker.setDate("<?echo $rdvs['date_rdv']?>");
                        timepicker.setDate("00:30");
                        openModal();
                    }else if(response == 2){
                        datepicker.setDate("<?echo $rdvs['date_rdv']?>");
                        timepicker.setDate("00:30");
                        openModal();
                    }
                }
            }); 
        }

        function numRoundMultiple(x, y) {
            return Math.round(x / y) * y;
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