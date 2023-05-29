document.getElementById("h").addEventListener('click',()=>
{
    var container=document.getElementById("contain");
    container.innerHTML="";
});

document.getElementById("i").addEventListener('click',()=>
{
    var container=document.getElementById("contain");
    container.innerHTML='<div style="font-weight:bold;width:50vw;height:50vh;position:absolute;top:50%;left:50%;translate: -50% -35%;border-radius: 5px;background-color: #5cdb95;color:#05386b;border-color: #05386b;border-width: 5px;border-style: solid;"><div style="position: absolute;top:50%;left:50%;translate: -50% -50%;"><form id="issuebook" method="post" action=""><center><h1>Book Issue Form</h1><label>Book Number:</label><input type="text" name="bookno" class="form-control" style="width:100%;" placeholder="Scan the Barcode or Enter Book No."/><br><label>Member ID:</label><input type="text" name="memberid" class="form-control" style="width:100%;" placeholder="Scan the Barcode or Enter Member ID"/><br><label>Member Type:</label><br><label class="form-check-label">Student:</label>&nbsp;&nbsp;<input type="radio" name="membertype" checked class="form-check-input" value="Student"/><label class="form-check-label">Faculty:</label>&nbsp;&nbsp;<input type="radio" name="membertype" class="form-check-input" value="Faculty"/><br><br><input type="submit" class="btn" style="color:aliceblue;background-color: #05386b;font-weight: bold;" value="Issue"/><button type="reset" class="btn btn-danger" style="font-weight: bold;">Clear</button><br><br><div style="color:red;font-weight: bold;" id="response"></div></center></form></div></div>';
    $(document).ready(function()
    {
        $("#issuebook").submit(function(e)
        {
            e.preventDefault();
            $.ajax(
            {
                method: "post",
                url: "Issue.php",
                data: $(this).serialize(),
                datatype: "text",
                success: function(Result)
                {
                    $("#response").html(Result);
                }
            });
        });
    });
});

document.getElementById("r").addEventListener('click',()=>
{
    var container=document.getElementById("contain");
    container.innerHTML="";
    $("#contain").load("./Return.html");
});

document.getElementById("ins").addEventListener('click',()=>
{
    var container=document.getElementById("contain");
    container.innerHTML="";
    $("#contain").load("./Insert.html");
});

document.getElementById("d").addEventListener('click',()=>
{
    var container=document.getElementById("contain");
    container.innerHTML="";
    $("#contain").load("./Delete.html");
});

$(document).ready(function()
{
        
    $("#returnform").submit(function(e)
    {
        e.preventDefault();
        $.ajax(
        {
            method : "post",
            url: "Return.php",
            data: $(this).serialize(),
            datatype: "text",
            success: function(Result)
            {
                $("#response2").html(Result);
            }
        });
    });
    $("#delbook").submit(function(e)
    {
        e.preventDefault();
        $.ajax(
        {
            method:"post",
            url: "Delete.php",
            data: $(this).serialize(),
            datatype: "text",
            success: function(Result)
            {
                $("#response4").html(Result);
            }
        });
    });
});