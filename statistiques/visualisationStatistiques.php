<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
try {
    $conn = new PDO('mysql:host=localhost;port=3306;dbname=projet_php', 'root', 'root');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

/*
   TODO: moyenne age des patients
        moyen age des patients par medecin
        nombre de rdv par medecin
*/

?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Statistiques</title>
    <link rel="stylesheet" href="../style.css">
    <style> 
        h1 {
            margin: 0;
        }

        .dashboard {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
<?php include '../nav.php'; ?>
<div class="col-12 justify-content-center">
    <div class="dashboard" style="width:39vw;">
        <h2>Statistiques</h2>
        <table>
            <tr>
                <th>Tranche d'age</th>
                <th>Nombre d'hommes</th>
                <th>Nombre de femmes</th>
            </tr>
            <?php
            $age = array("<25", "25-50", ">50");
            for ($i = 0; $i < 3; $i++) {
                switch ($i) {
                    case 0:
                        $query = "SELECT SUM(CASE WHEN civilite = 'M' THEN 1 ELSE 0 END) AS num_men, SUM(CASE WHEN civilite = 'Mme' THEN 1 ELSE 0 END) AS num_women FROM usager WHERE age BETWEEN 0 AND 25";
                        break;
                    case 1:
                        $query = "SELECT SUM(CASE WHEN civilite = 'M' THEN 1 ELSE 0 END) AS num_men, SUM(CASE WHEN civilite = 'Mme' THEN 1 ELSE 0 END) AS num_women FROM usager WHERE age BETWEEN 25 AND 50";
                        break;
                    case 2:
                        $query = "SELECT SUM(CASE WHEN civilite = 'M' THEN 1 ELSE 0 END) AS num_men, SUM(CASE WHEN civilite = 'Mme' THEN 1 ELSE 0 END) AS num_women FROM usager WHERE age > 50";
                        break;
                }
                $result = $conn->query($query);
                $row = $result->fetch();
                echo "<tr>";
                echo "<td>" . $age[$i] . "</td>";
                echo "<td>" . $row['num_men'] . "</td>";
                echo "<td>" . $row['num_women'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>

        <table>
            <tr>
                <th>Médecin</th>
                <th>Durée total des rendez-vous</th>
            </tr>
            <?php
            $query = "SELECT id_medecin, nom, prenom FROM medecin WHERE archive = 0 ORDER BY nom";
            $medecins = $conn->query($query);
            while ($medecin = $medecins->fetch()) {
                $query = "SELECT duree FROM rdv WHERE id_medecin = " . $medecin['id_medecin'];
                $result = $conn->query($query);
                $heures = 0;
                $minutes = 0;
                while ($row = $result->fetch()) {
                    $heures = $heures + intval(explode(":", $row['duree'])[0]);
                    $minutes = $minutes + intval(explode(":", $row['duree'])[1]);
                }
                $heures = $minutes>59?number_format($minutes / 60, 0, '.', '') + $heures:$heures;
                $minutes = $minutes % 60;

                echo "<tr>";
                echo "<td>" . $medecin['nom'] . " " . $medecin['prenom'] . "</td>";
                echo "<td>" . $heures . " H " . $minutes . " m</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>