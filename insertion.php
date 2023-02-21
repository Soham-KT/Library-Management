<?php
    include "dbconnect.php";
    
    $a;$t;$e;$p;$pa;$c;$s;$b;
    if(datacheck())
    {
        $a=$_POST["author"];
        $t=$_POST["title"];
        $e=$_POST["edition"];
        $p=$_POST["publisher"];
        $pa=$_POST["pages"];
        $c=$_POST["cost"];
        $s=$_POST["supplier"];
        $b=$_POST["bill"];

        $sql="INSERT INTO books(Author,Title,Edition,Publisher,
        Total_Pages,Cost,Supplier,Bill_No) values('$a','$t','$e','$p',$pa,$c,'$s','$b');";
        $result=$conn->query($sql);
        if($result)
        {
            echo"Book Successfully Inserted!!!";
        }
        else
        {
            echo"$conn->error";
        }
    }
    function datacheck()
    {
        if(strlen($_POST["author"]) > 50)
        {
            echo"Author Name should not exceed 50 characters!!!";
            return false;
        }
        if(strlen($_POST["title"]) > 30)
        {
            echo"Title should not exceed 30 characters!!!";
            return false;
        }
        if(strlen($_POST["edition"]) > 15)
        {
            echo"Edition should not exceed 15 characters!!!";
            return false;
        }
        if(strlen($_POST["publisher"]) > 50)
        {
            echo"Publisher Name should not exceed 50 characters!!!";
            return false;
        }
        if(strlen(strval($_POST["pages"])) > 9)
        {
            echo"Total Pages should not exceed 9 characters!!!";
            return false;
        }
        if(strlen(strval($_POST["cost"])) > 9)
        {
            echo"Cost should not exceed 9 characters!!!";
            return false;
        }
        if(strlen($_POST["supplier"]) > 50)
        {
            echo"Supplier Name should not exceed 50 characters!!!";
            return false;
        }
        if(strlen($_POST["bill"]) > 20)
        {
            echo"Bill No. should not exceed 20 characters!!!";
            return false;
        }
        return true;
    }
?>