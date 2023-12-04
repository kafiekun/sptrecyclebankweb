<?php 
    session_start();
    include('connect.php');

    $errors = array();
    if (isset($_POST['login_user'])) {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $proximity_id = $_POST['rfid'];
        if (empty($proximity_id)) {
            array_push($errors, "โปรดกรอกข้อมูลให้ครบถ้วน!");
        }

 

        if (count($errors) == 0) {
            $stmt = $pdo->prepare("SELECT * FROM proximity_card WHERE proximity_id = :proximity_id");
            $stmt->execute(array(':proximity_id' => $proximity_id));    
            $count = $stmt->rowCount();
            $row   = $stmt->fetch(PDO::FETCH_ASSOC);

            $id = $row['id'];
            $sql2 = "SELECT * FROM st_master where id = ?";
            $q2 = $pdo->prepare($sql2);
            $q2->execute(array($id));
            
            $data = $q2->fetch(PDO::FETCH_ASSOC);
            date_default_timezone_get("Asia/Bangkok");
            $mydate = date("Y/m/d");
            $day_report = date("d");
            $month_report = date("m");
            $year_report = date("Y") + 543;
            $time_report = date("H:i:s");
        
            $full_date = $day_report . '/' . $month_report . '/' . $year_report;
            
            $detail = "แยกขยะได้ถูกต้อง";
            $t_year = "2566";
            $code = "60024";
            $date_trans = $full_date;
            $other = "-";
            $kanal = "0";
            $teacher = "45013";
            $tam = "2";
            $id_ST = $id;
            $clsrm = $data['clsrm'];
          
            $sqlpoint = "INSERT INTO trans_Virtue_BANK (detail, t_year, code, date_trans, other, kanal, teacher, tam, id_ST, clsrm) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $q = $pdo->prepare($sqlpoint);

            if ($count == 1 && !empty($row)) {
                $_SESSION['rfid'] = $id;
                $_SESSION['success'] = "เข้าสู่ระบบสำเร็จ!";
                $q->execute(array($detail, $t_year, $code, $date_trans, $other, $kanal, $teacher, $tam, $id_ST, $clsrm));
                header("location: user_data");
            } else {
                array_push($errors, "โปรดกรอกข้อมูลให้ถูกต้อง!");
                $_SESSION['error'] = "โปรดกรอกข้อมูลให้ถูกต้อง!";
                header("location: login");
            }
        } else {
            array_push($errors, "โปรดกรอกข้อมูลให้ครบถ้วน!");
            $_SESSION['error'] = "โปรดกรอกข้อมูลให้ครบถ้วน!";
            header("location: login");
        } 
        Database::disconnect();
    }
?>
