<?php

    header ("Access-Control-Allow-Origin:*");
                
    $conn = new mysqli("localhost", "thyler", "k", "reg_c3lf");
    if($conn->connect_error){
        die("Connection Failed!".$conn->connect_error);
    }

    $action = '';

    if(isset($_GET['action'])){
        $action = htmlspecialchars($_GET['action']);
    }

    if($action == 'regUser'){
        $matricule = $_POST['matricule'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $parcours = $_POST['parcours'];

        $matricule = mysqli_real_escape_string($conn, $matricule);
        $nom = mysqli_real_escape_string($conn, $nom);
        $prenom = mysqli_real_escape_string($conn, $prenom);
        $parcours = mysqli_real_escape_string($conn, $parcours);

        $sql = "INSERT INTO User (matricule, nom, prenom, parcours) VALUES ('$matricule', '$nom', '$prenom', '$parcours')";

        if ($conn->query($sql) === TRUE) {
            $result = array(
                "status" => "success",
                "message" => "Registration Complete!",
                "matricule" => $matricule,
                "parcours" => $parcours
            );
            
        } else {
            $result = array(
            "status" => "error",
            "message" => "Registration failed: " . $conn->error
        );
        }

    }

    $conn->close();
    echo json_encode($result);

?>