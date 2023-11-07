<?php
@session_start();
include "auth.php";
include "dbconnect.php";
$BookIndex =0;
$BookSlots =[]; // Available slots in between
$MaxBookIndex =0;
$error = "";
$Data_Status = false;
if(verification() && $_POST["Access"] == "Book_add_excel-Confirmation"){
    if($Data_Status && strlen($error) == 0 && count($Book_Record)>0){
        
    }
    else{
        // return error and reload
    }
    
}
elseif(!verification() || $_POST["Access"] != "Main-Book_add_excel" )
{
    header("Location: /LibraryManagement/");
}

require_once ('vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;


function Book_check($b,$count){
    include "dbconnect.php";
    global $error;
    for($i = 0; $i < $count; $i++)
    {
        
        $sql = "SELECT Book_No from books WHERE Book_No = '$b';";
        $res = $conn->query($sql);
        if(mysqli_num_rows($res) != 0){
            $error = $error."unable to insert book's at $b already present!!!";
            return false;
        }
        $b++;
    }
    return true;
}

function set_BookIndex($count){
    include "dbconnect.php";
    global $BookSlots;
    global $BookIndex;
    global $MaxBookIndex;
    if($count == 1 && count($BookSlots)>=1){
        $BookIndex = $BookSlots[0]; 
        return;
    }
    if(count($BookSlots)>= $count){
        $a = $BookSlots;
        array_push($a, 0);
        $res = [];
        $stage = [];
        
        foreach($a as $i) {
            if(count($stage) > 0 && $i != $stage[count($stage)-1]+1) {
                if(count($stage) > 1) {
                    $res[] = $stage;
                }
                $stage = [];
            }
            $stage[] = $i;
        }
        foreach($res as $slot){
            if(count($slot)>= $count){
                $BookIndex = $slot[0];
                return;
            }
        }
        
        $BookIndex = $MaxBookIndex +1;
        $MaxBookIndex+=$count;
        return;
    }
    else{
        
        $BookIndex = $MaxBookIndex +1;
        $MaxBookIndex+=$count;
        return;
    }
}

function Book_num($book_seq){
    include "dbconnect.php";
    global $BookSlots;
    global $MaxBookIndex;
    $sql = "Select Book_No from books Order By Book_No ASC;";
    $res = $conn->query($sql);
    while($row =$res->fetch_assoc()){
        array_push( $book_seq, $row["Book_No"]);
    }
    $MaxBookIndex = max($book_seq);
    for($i = 1; $i < $MaxBookIndex; $i++){
        if(!in_array($i,$book_seq)) array_push($BookSlots,$i);
    }
    sort($BookSlots);
}

function ErrorCheck($a1,$a2,$a3,$title,$ed,$pub,$cl,$tpag){
    global $error;
    if($a1 == null && $a2 == null && $a3 == null){
        $error = $error."Author not found";
    }
    if($title == null){
        $error = $error."Title not found";
    }
    if($ed == null){
        $error = $error."edition not found";
    }
    if($pub == null){
        $error = $error."publisher not found";
    }
    if($cl == null){
        $error = $error."Cl No of book not found";
    }
    if($tpag == null){
        $error = $error."Total No of Book pages missing";
    }
    return $error;
}

$targetPath = "Book_insert.xlsx";
$Reader = new Xlsx();

$spreadSheet = $Reader->load($targetPath);
$excelSheet = $spreadSheet->getActiveSheet();
$spreadSheetAry = $excelSheet->toArray();
$sheetCount = count($spreadSheetAry);
$bno =0;
$error;
$author1;
$author2;
$author3;
$edition;
$publisher;
$cl_no;
$total_pages;
$cost;
$supplier;
$remark;
$billno;
$bookcount;
$Book_Record =[];

$bookserial =[];
for($i = 1;$i<$sheetCount; $i++){
    $b = 0;
    $count = 0;
    if(!empty($spreadSheetAry[$i][0])){
        if(!empty($spreadSheetAry[$i][13])) $count=$spreadSheetAry[$i][13];
        else $count=1;
        $b = $spreadSheetAry[$i][0];
        for($j= 0;$j<$count;$j++){
            array_push($bookserial,$b+$j);
        }
    }
}

$temp_array = array_unique($bookserial);
if(sizeof($temp_array) == sizeof($bookserial))
{
    Book_num($bookserial);
    
    for($i = 1;$i<$sheetCount; $i++){
        
        $bno = 0;
        if(!empty($spreadSheetAry[$i][13])) $bookcount=$spreadSheetAry[$i][13];
        else $bookcount=1; 
    
        if(!empty($spreadSheetAry[$i][0])){
            $bno=$spreadSheetAry[$i][0];// cases missing for book no
            if(!Book_check($bno,$bookcount)){
                echo $error;
                break;
            }
        }
        else
        {
            set_BookIndex($bookcount);
            $bno =$BookIndex;
        }
        if(!empty($spreadSheetAry[$i][1])) $title=$spreadSheetAry[$i][1];
        
        if(!empty($spreadSheetAry[$i][2])) $author1=$spreadSheetAry[$i][2];
        else
        {
            $author1=null;
        }
        if(!empty($spreadSheetAry[$i][3])) $author2=$spreadSheetAry[$i][3];
        else
        {
            $author2=null;
        }
        if(!empty($spreadSheetAry[$i][4])) $author3=$spreadSheetAry[$i][4];
        else
        {
            $author3=null;
        }
        if(!empty($spreadSheetAry[$i][4]) && empty($spreadSheetAry[$i][3]))
        {
            $author2=$spreadSheetAry[$i][4];
            $author3=null;
        }
        if(!empty($spreadSheetAry[$i][3]) && empty($spreadSheetAry[$i][2]))
        {
            $author1=$spreadSheetAry[$i][3];
            $author2=$spreadSheetAry[$i][4];
            $author3=null;
        }
        if(!empty($spreadSheetAry[$i][5]))$edition=$spreadSheetAry[$i][5];
        else $edition=null;
        if(!empty($spreadSheetAry[$i][6]))$publisher=$spreadSheetAry[$i][6];
        else $publisher=null;
        if(!empty($spreadSheetAry[$i][7]))$cl_no=$spreadSheetAry[$i][7];
        else $cl_no=null;
        if(!empty($spreadSheetAry[$i][8]))$total_pages=$spreadSheetAry[$i][8];
        else $total_pages=null;
        if(!empty($spreadSheetAry[$i][9])) $cost=$spreadSheetAry[$i][9];
        else $cost=null;
        if(!empty($spreadSheetAry[$i][10])) $supplier=$spreadSheetAry[$i][10];
        else $supplier=null;
        if(!empty($spreadSheetAry[$i][11]))$remark=$spreadSheetAry[$i][11];
        else $remark=null;
        if(!empty($spreadSheetAry[$i][12])) $billno=$spreadSheetAry[$i][12];
        else $billno=null;
        
        $error = ErrorCheck($author1,$author2,$author3,$title,$edition,$publisher,$cl_no,$total_pages);
        if(strlen($error) > 0){
            echo $error."At Index: ".$i+1;
            break;
        }
        if(!array_key_exists($bno,$Book_Record))$Book_Record[$bno]= array($author1,$author2,$author3,$title,$edition,$publisher,$cl_no,$total_pages,$cost,$supplier,$remark,$billno,$bookcount);
        
    }
    if(count($Book_Record)> 0){
        $Data_Status = true;
        echo "
                    <div style='width:100%;overflow:auto;height:650px;postion:relative;transform:translate(0%,-35%);'><table>
                    <tr>
                    <th>Book No.</th>
                    <th>Author's</th>
                    <th>Title</th>
                    <th>Edition</th>
                    <th>Publisher</th>
                    <th>No of Copies</th>
                    </tr>
                    <tbody>";
    
                ksort($Book_Record);
                foreach($Book_Record as $bn=>$b)
                {
                    echo"
                    <tr>
                    <td>".$bn."</td>
                    <td>".$b[0]." ".$b[1]." ".$b[2]." "."</td>
                    <td>".$b[3]."</td>
                    <td>".$b[4]."</td>
                    <td>".$b[5]."</td>
                    <td>".$b[12]."</td>
                    <td></td>
                    <td></td>
                    </tr>
                    ";
                    $sql = "INSERT INTO `insert buffer`(val1,val2,val3,val4,val5,val6,val7,val8,val9,val10,val11,val12,val13,val14) VALUES('$b[0]','$b[1]','$b[2]','$b[3]','$b[4]','$b[5]',$b[6],$b[7],$b[8],'$b[9]','$b[10]','$b[11]',$b[12],$bn)";
                    $res = $conn->query($sql);
                    if(!$res){
                        echo $conn->error;
                    }
                }
    
                echo"
                    </tbody></table>
                  
                    <script>
                    $(document).ready(function()
                    {
                        document.getElementById('exl_srch').style.transform='translate(-120%,-50%)';
                        $('#excelConfirm').click(function(e)
                        {
                            $.ajax(
                            {
                                method: 'post',
                                url: 'Book_add_excel.php',
                                data: {
                                    
                                }
                                datatype: 'text',
                                success: function(Result)
                                {
                                    
                                    $( '#dialog_exl_disp' ).dialog( 'destroy');
                                    $('#response_exl_records').html(Result);
                                    $('#dialog_exl_disp').dialog();  
                                }
                            });
                        }); 
                    });
                    </script>
                    </div>
                    ";
    }
    else{
        echo "No data found";
    }


}
else{
    echo "duplicate records";
}


?>