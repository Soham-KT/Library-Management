<?php
include "dbconnect.php";
if(empty($_POST["fac_name"]) || empty($_POST["fac_id"]) || empty(filter_input(INPUT_POST,"fac_type")))
{
    header("Location: /LibraryManagement/");
}
else
{
    $facName=$_POST["fac_name"];
    $facId=$_POST["fac_id"];
    $facType=filter_input(INPUT_POST,"fac_type");

    $sqlCheck1="SELECT Faculty_ID from faculty where Faculty_ID = '$facId';";
    $sqlCheck2="SELECT Member_ID from member where Member_ID = '$facId';";
    $resultCheck1=$conn->query($sqlCheck1);
    $resultCheck2=$conn->query($sqlCheck2);
    if($resultCheck1 && $resultCheck2)
    {
        if(mysqli_num_rows($resultCheck1)>0 || mysqli_num_rows($resultCheck2)>0)
        {
            echo"
            <div id='dialog_fac' style='color:red;' title='Not Allowed❌'>
                <p><center>Faculty $facId already present!!!
                <br>If you think this is an issue, Kindly add the Faculty Manually!!!</center></p>
            </div>";
        }
        else
        {
            $sql1="INSERT into faculty(Faculty_ID,Faculty_Name,Faculty_Type) values('$facId','$facName','$facType');";
            $sql2="INSERT into member(Member_ID,MemberType) values('$facId','Faculty');";
            $result1=$conn->query($sql1);
            $result2=$conn->query($sql2);
            if($result1 && $result2)
            {
                echo "
                <div id='dialog_fac' style='color:green;' title='Successful✅'>
                    <p><center>Faculty $facId added as a member successfully!!!</center></p>
                </div>";
            }
            else echo"
            <div id='dialog_fac' style='color:red;' title='Error❌'>
                <p><center>$conn->error</center></p>
            </div>";
        }
    }
    else echo"
            <div id='dialog_fac' style='color:red;' title='Error❌'>
                <p><center>$conn->error</center></p>
            </div>";

}
?>