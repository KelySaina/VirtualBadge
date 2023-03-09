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

        $req = $conn->query("select matricule from User where matricule = '$matricule'");

        if($req->num_rows>0){
            $result = array(
                "status" => "error",
                "message" => "Registration failed: User already registered with matricule $matricule"
            );
        }else{

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

        

    }

    if($action == 'verifUser'){
        $matricule = $_POST['mat'];
        $parcours = $_POST['par'];

        $matricule = mysqli_real_escape_string($conn, $matricule);
        $parcours = mysqli_real_escape_string($conn, $parcours);

        $sql = "select matricule, nom, prenom, parcours from User where matricule = '$matricule' and parcours = '$parcours'";

        if( $conn->query($sql) -> num_rows>0){
            $row = $conn->query($sql)->fetch_assoc();
            $result = array(
                'status' => 'success',
                'matricule' => $row['matricule'],
                'nom' => $row['nom'],
                'prenom' => $row['prenom'],
                'parcours' => $row['parcours'],
                'message' => 'Verified'
            );
        }else{
            $result = array(
                'status' => 'error',
                'message' => 'User not registered'
            );
        }

    }

    $conn->close();
    echo json_encode($result);

?>