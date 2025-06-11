<?php

        $con = new mysqli("localhost","root","","db");
        if ($con->connect_error) {
          die("connection failed". $con->connect_error);
        }
        $sql = "SELECT * FROM `house` WHERE `status` = 'waiting'";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->num_rows;

        $sql1 = "SELECT * FROM `house` WHERE `status` = 'approved'";
        $stmt1 = $con->prepare($sql1);
        $stmt1->execute();
        $result1 = $stmt1->get_result();
        $row1 = $result1->num_rows;

        $sql3 = "SELECT * FROM `order`";
        $stmt3 = $con->prepare($sql3);
        $stmt3->execute();
        $result3 = $stmt3->get_result();
        $row3 = $result3->num_rows;

        $sql4 = "SELECT * FROM `comment`";
        $stmt4 = $con->prepare($sql4);
        $stmt4->execute();
        $result4 = $stmt4->get_result();
        $row4 = $result4->num_rows;

        $sql5 = "SELECT * FROM `history`";
        $stmt5 = $con->prepare($sql5);
        $stmt5->execute();
        $result5 = $stmt5->get_result();
        $row5 = $result5->num_rows;
?>  