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

            $sql = "INSERT INTO User (matricule, nom, prenom, parcours,avatar) VALUES ('$matricule', '$nom', '$prenom', '$parcours','in')";

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

        $chk = "select matricule from User where matricule='$matricule' and parcours='$parcours' and avatar='in'";
        if($conn->query($chk)->num_rows>0){
            $result = array(
                'status' => 'warning',
                'message' => 'User already verified'
            );
        }else{
            $sql = "select matricule, nom, prenom, parcours from User where matricule = '$matricule' and parcours = '$parcours'";

            if( $conn->query($sql) -> num_rows>0){
                $row = $conn->query($sql)->fetch_assoc();
                if($row['prenom'] == 'null'){
                    $row['prenom'] = "";
                }
                $result = array(
                    'status' => 'success',
                    'matricule' => $row['matricule'],
                    'nom' => $row['nom'],
                    'prenom' => $row['prenom'],
                    'parcours' => $row['parcours'],
                    'message' => 'Verified'
                );
                $conn->query("update User set avatar='in' where matricule='$matricule' and parcours='$parcours'");
            }else{
                $result = array(
                    'status' => 'error',
                    'message' => 'User not registered'
                );
            }
    
        }

        
    }

    if($action == 'notIn'){
        $res = $conn->query("update User set avatar='not in'");
        if($res){
            $result = array(
                'status' => 's',
                'message' => 'Presence reset succeeded'
            );
        }else{
            $result = array(
                'status' => 'error',
                'message' => 'Presence reset failed'
            );
        }
    }

    if($action == 'getTot'){
        $res = $conn->query("select count(*) as tot from User");
        if($res->num_rows>0){
            $r = $res->fetch_assoc();
            $result = array(
                'status' => 's',
                'tot' => $r['tot']
            );
        }else{
            $result = array(
                'status' => 'error',
                'tot' => 0
            );
        }
    }

    if($action == 'getScan'){
        $res = $conn->query("select count(*) as tot from User where avatar='in'");
        if($res->num_rows>0){
            $r = $res->fetch_assoc();
            $result = array(
                'status' => 's',
                'pres' => $r['tot']
            );
        }else{
            $result = array(
                'status' => 'error',
                'tot' => 0
            );
        }
    }

    $conn->close();
    echo json_encode($result);

?>