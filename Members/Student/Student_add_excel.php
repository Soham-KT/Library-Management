<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/LibraryManagement/vendor/autoload.php');
include "../../connection/dbconnect.php";
$sql_delete = "DELETE from `insert buffer`;";
$result=$conn->query($sql_delete);

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$targetPath = $_SERVER['DOCUMENT_ROOT'].'/LibraryManagement/Doc/student.xlsx';
$Reader = new Xlsx();
$spreadSheet = $Reader->load($targetPath);
$excelSheet = $spreadSheet->getActiveSheet();
$spreadSheetAry = $excelSheet->toArray();
$sheetCount = count($spreadSheetAry);


$sql_delete = "DELETE from `insert buffer`;";
$result=$conn->query($sql_delete);

function check($sArray,$sCount)
{
    include "../../connection/dbconnect.php";
    for($i=1;$i<$sCount;$i++)
    {
        $rollno=$sArray[$i][0];
        $rollno=strtoupper($rollno);
        $rollno=str_replace("-","",$rollno);
        $name=$sArray[$i][1];
        $course=$sArray[$i][2];
        $enroll=$sArray[$i][3];
        if(empty($rollno) || empty($name) || empty($course) || empty($enroll))
        {
            echo
            "
                <div id='dialog_exl_disp_student' style='color:red;' title='❌Not Allowed'>
                    <p><center>An Excel Field is Empty!!!</center></p>
                </div>
            ";
            return false;
        }
        $sql_check="SELECT Student_Rollno from student where Student_Rollno = '$rollno';";
        $result_check=$conn->query($sql_check);
        if($result_check)
        {
            if(mysqli_num_rows($result_check)>0)
            {
                echo
                "
                    <div id='dialog_exl_disp_student' style='color:red;' title='❌Error'>
                        <p>
                            <center>
                                '$rollno' is already present as a student. Please check the excel once!!!
                            </center>
                        </p>
                    </div>
                ";
                return false;
            }
        }
        else
        {
            echo 
            "
                <div id='dialog_exl_disp_student' style='color:red;' title='❌Error'>
                    <p>
                        <center>
                            $conn->error
                        </center>
                    </p>
                </div>
            ";
            return false;
        }
    }
    return true;
}

if(check($spreadSheetAry,$sheetCount))
{
    $flag=0;
    for($i=1;$i<$sheetCount;$i++)
    {
        $rollno=$spreadSheetAry[$i][0];
        $rollno=strtoupper($rollno);
        $rollno=str_replace("-","",$rollno);
        $name=$spreadSheetAry[$i][1];
        $course=$spreadSheetAry[$i][2];
        $enroll=$spreadSheetAry[$i][3];
        $sql="INSERT into `insert buffer`(id,val1,val2,val3,val4)
        values($i,'$rollno','$name','$course','$enroll');";
       
        $result=$conn->query($sql);
        if(!$result)
        {
            echo
            "
                <div id='dialog_exl_disp_student' style='color:red;' title='❌Error'>
                    <p><center>There was en error in the excel data format in the row of Roll No '$rollno'.
                    Rows after this record will not be inserted!!! Please reupload the excel.</center></p>
                </div>
            ";
            $flag=1;
            break;
        }  
    }
    if($flag==0)
    {
        //Zakie Works
        if($sheetCount>1)
        {
            echo "
            <style>
            .gola::-webkit-scrollbar {
                display: none; /* for Chrome, Safari, and Opera */
              }
            </style>   
            <center>     
            <div style='background-color: rgba(0, 0, 0, 0.2);border-radius:50%;backdrop-filter: blur(5px);width:700px;height:700px;'>
            
            <br/>
            <br/>
            <h1 style='font-weight:bold;color:white;position:relative;'>Confirm Submission?</h1><br/>
            
            <div class='gola' style='overflow-y:auto;height:50vh;'>
            <table style='position:relative;width:50vh;'>
            <tr>
            <th>Id</th>
            <th>Roll.</th>
            <th>Name</th>
            <th>Course</th>
            <th>Enrollment</th>
            </tr>
            <tbody>";

        // ksort($Book_Record);
        $ignore=0;
        foreach($spreadSheetAry as $bn=>$b)
        {
            if($ignore==0)
            {
                $ignore=$ignore+1;
            }
            else
            {
                echo"
                <tr>
                <td>".$bn."</td>
                <td>".$b[0]."</td>
                <td>".$b[1]."</td>
                <td>".$b[2]."</td>
                <td>".$b[3]."</td>
    
                <td></td>
                <td></td>
                </tr>
                ";
            }
        }
        echo"
            </tbody>
            </table>
            </div>
            </br>
            <div style='display:inline-flex;'>
                <form id='std_buffer' method='post' action=''>
                    <button class='btn' type='submit' id='upload-button' style='color:aliceblue;background-color:black;'> Confirm </button>
                </form> &nbsp;&nbsp;&nbsp;
                <form id='buff_back' method='post' action=''>
                    <button id='backissue' class='btn' style='font-weight: bold;background-color: #520702;color: aliceblue;'>Back</button>
                </form>
                </div>
            <div style='font-weight: bold;' id='response_student_excel'></div>
            </center>
            <script>
            $(document).ready(function()
            {
                
                $('#buff_back').click(function(e)
                {
                    $.ajax({

                        method: 'post',
                        url: './Members/Student/Student_empty_buffer.php',
                        data:'hi',
                        datatype:'text',
                        success: function(Result)
                        {
                            
                            
                        }
                    })
                })
                
                $('#std_buffer').click(function(e)
                {
                    
                    $.ajax({
                        
                        method: 'post',
                        url: './Members/Student/Student_insert_buffer.php',
                        data:'hi',
                        datatype:'text',
                        success: function(Result)
                        {
                            
                            $('#dialog_student_excel').dialog('destroy');
                            $('#response_student_excel').html(Result);
                            $('#dialog_student_excel').dialog();
                            
                        }
                    }) 
                })
            })
            
            </script>
            ";
    }
    else
    {
        echo"
        <div id='dialog_exl_disp_student' style='color:red;' title='❌Not Allowed'>
            <p><center>Excel File is Empty!!!</center></p>
        </div>
        ";
    }

    }
    if($flag == 1)
    {
        $sql_delete = "DELETE from `insert buffer`;";
        $result=$conn->query($sql_delete);
    }
}
?>