<?php
@session_start();
if(empty($_SESSION["username"]) || $_SESSION["File"] != "Index.php")
{
    header("Location: /LibraryManagement/");
}
else
{
    $_SESSION["TEMP"] = "reload"; //for reload
    ?>
                    <!--navbar-->
                    <div style="background-color: black;font-size: large;font-weight: bold;">
                        <nav class="navbar navbar-expand-lg navbar-expand-md navbar-expand-sm navbar-expand-xl navbar-expand">
                            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                                <ul class="navbar-nav nav-fill w-100">
                                    <!-- <li class="nav-item">
                                        <a class="nav-link hovered" id="s">Search</a>
                                    </li> -->
                                    <li class="nav-item dropdown">
                                        <a id="books" class="nav-link dropdown-toggle hovered" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Books
                                        </a>
                                        <div id="booksdiv" class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item dropdown_hover" id="s">Search</a>
                                        <a class="dropdown-item dropdown_hover" id="b">Book Filter</a>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a id="reports" class="nav-link dropdown-toggle hovered" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Reports
                                        </a>
                                        <div id="reportsdiv" class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item dropdown_hover" id="au">Audit</a>
                                        <a class="dropdown-item dropdown_hover" id="m">Dues/NoDues</a>
                                        <a class="dropdown-item dropdown_hover" id="std">Student Records</a>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a id="transactions" class="nav-link dropdown-toggle hovered" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Transactions
                                        </a>
                                        <div id="transactionsdiv" class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item dropdown_hover" id="i">Issue</a>
                                        <a class="dropdown-item dropdown_hover" id="r">Return</a>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a id="book" class="nav-link dropdown-toggle hovered" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Book Manipulation
                                        </a>
                                        <div id="bookdiv" class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item dropdown_hover" id="ins">Insert</a>
                                        <a class="dropdown-item dropdown_hover" id="d">Delete</a>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a id="membership" class="nav-link dropdown-toggle hovered" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Membership
                                        </a>
                                        <div id="membershipdiv" class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item dropdown_hover" id="me">Add Student Member</a>
                                        <a class="dropdown-item dropdown_hover" id="me_fac">Add Faculty Member</a>
                                        <a class="dropdown-item dropdown_hover" id="de">Delete Student Member</a>
                                        <a class="dropdown-item dropdown_hover" id="de_fac">Delete Faculty Member</a>
                                        
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle hovered" href="#" id="admin_panel"  id="navbarDropdown"role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Administrator
                                        </a>
                                        <div id="admin_paneldiv" class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item dropdown_hover" id="admin_add">Add Admin</a>
                                            <a class="dropdown-item dropdown_hover" id="admin_delete">Delete Admin</a>
                                            <a class="dropdown-item dropdown_hover" id="admin_disp">Show Admin</a>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link hovered" id="tools">Support Tools</a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                    <div id="container"></div>
                    <script src="./jquery-ui-1.13.2.custom/jquery-ui.js"></script>
                    <script src="./jquery-ui-1.13.2.custom/jquery-ui.css"></script>
                    <script src="Main.js"></script>
                    <style>
                        .ui-autocomplete {
                            position: absolute;
                            top: 100%;
                            left: 0; 
                            z-index: 1000;
                            float: left;
                            display: none;
                            min-width: 160px;   
                            padding: 4px 0;
                            margin: 0 0 10px 25px;
                            list-style: none;
                            background-color: black;
                            border-color: #ccc;
                            border-color: rgba(0, 0, 0, 0.2);
                            border-style: solid;
                            color: white;
                            border-width: 1px;
                            -webkit-border-radius: 5px;
                            -moz-border-radius: 5px;
                            border-radius: 5px;
                            -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
                            -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
                            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
                            -webkit-background-clip: padding-box;
                            -moz-background-clip: padding;
                            background-clip: padding-box;
                            max-height: 200px;
                            overflow-y: auto;
                            /* prevent horizontal scrollbar */
                            overflow-x: hidden;
                        }

                        * html .ui-autocomplete {
                            height: 200px;
                        }

                        .ui-menu-item > a.ui-corner-all {
                            display: block;
                            padding: 3px 15px;
                            clear: both;
                            font-weight: normal;
                            line-height: 18px;
                            color: #555555;
                            white-space: nowrap;
                            text-decoration: none;
                        }

                        .ui-state-hover, .ui-state-active {
                            color: #ffffff;
                            text-decoration: none;
                            background-color: #0088cc;
                            border-radius: 0px;
                            -webkit-border-radius: 0px;
                            -moz-border-radius: 0px;
                            background-image: none;
                        }
                    </style>
    <?php
}
?>