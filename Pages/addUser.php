<?php
    session_start();
    if(!isset($_SESSION["user_id"])){
        header('location:./index.php');
    }
    $user_type = $_SESSION["user_type"];
    if($user_type=="sales" || $user_type=="account" || $user_type=="production"){
        header('location:../ErrorBoundary/403.php');
        return;
    }
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://use.fontawesome.com/c829a83b30.js"></script>
    <link rel="stylesheet" href="../assets/css/mainStyles.css">
    
    <title>Add User</title>

    <script type="text/javascript">
    function getXmlHttpRequestObject() {
        if (window.XMLHttpRequest) {
            return new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            return new ActiveXObject("Microsoft.XMLHTTP");
        } else {}
    }
    function goBack() {
        window.history.back();
    }
    function check_passwords(){
        const pwd = document.getElementById("txtPwd").value;
        const con_pwd = document.getElementById("txtConPwd").value;
        if(pwd!==con_pwd){
            document.getElementById("txtConPwd").value="";
            document.getElementById("txtConPwd").classList.add("is-invalid");
            document.getElementById("pwd_mismatch_error").innerHTML="Passwords do not match";
            document.getElementById("submitButton").disabled=true;
        }else{
            document.getElementById("pwd_mismatch_error").innerHTML="";
            document.getElementById("txtConPwd").classList.remove("is-invalid");
            document.getElementById("submitButton").disabled=false;
        }
    }
    function check_username() {
        const uname = document.getElementById("txtUsername").value;
        if(uname){
            var req = getXmlHttpRequestObject();
            if (req) {
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        if (req.status == 200) {
                            if (req.responseText == "ok") {
                                document.getElementById("txtUsername").classList.remove("is-invalid");
                                document.getElementById("txtUsername").classList.add("is-valid");
                                document.getElementById("username_success").innerHTML="Username is available";
                                document.getElementById("submitButton").disabled=false;
                            } else if (req.responseText == "duplicate") {
                                document.getElementById("txtUsername").classList.remove("is-valid");
                                document.getElementById("txtUsername").classList.add("is-invalid");
                                document.getElementById("duplicate_username_error").innerHTML="Username is taken.";
                                document.getElementById("submitButton").disabled=true;
                            }
                        }
                    }
                }
                req.open("POST", "../PHPScripts/check_username.php", true);
                req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                req.send("username=" + uname);
            }
        }else{
            document.getElementById("txtUsername").classList.remove("is-invalid");
            document.getElementById("txtUsername").classList.remove("is-valid");
            document.getElementById("duplicate_username_error").innerHTML="";
            document.getElementById("username_success").innerHTML="";
            document.getElementById("submitButton").disabled=true;
        }
    }
    </script>
</head>

<body>
<?php
    require('../Components/header.php');

    date_default_timezone_set("Asia/Colombo");
    $today_date = date("Y.m.d");
    include('../PHPScripts/db_connect.php');

    ?>
    <div class="container page-spacing">
        <form action="../PHPScripts/add_user_submit.php" method="post">
            <div class="row mb-3 mt-3">
                <div class="col-md-3">
                    <label for="txtUsername">Username</label>
                    <input type="text" class="form-control" id="txtUsername" name="txtUsername" required readonly onchange="check_username();" onfocus="this.removeAttribute('readonly');">
                    <div id="username_success" class="valid-feedback"></div>
                    <div id="duplicate_username_error" class="invalid-feedback"></div>
                </div>
                <div class="col-md-3">
                    <label for="txtFirstName">First Name</label>
                    <input type="text" class="form-control" id="txtFirstName" name="txtFirstName" required>
                </div>
                <div class="col-md-3">
                    <label for="txtLastName">Last Name</label>
                    <input type="text" class="form-control" id="txtLastName" name="txtLastName" required>
                </div>
            </div>

            <div class="row mb-3 mt-3">
                <div class="col-md-3">
                    <label for="selUserType">Department</label>
                    <select class="form-control" id="selUserType" name="selUserType" required>
                        <option value=''>--Select a Department--</option>
                        <option value='manager'>Manager</option>
                        <option value='sales'>Sales</option>
                        <option value='account'>Accounts</option>
                        <option value='production'>Production</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="txtEmail">Email</label>
                    <input type="email" class="form-control" id="txtEmail" name="txtEmail">
                </div>
            </div>

            <div class="row mb-3 mt-3">
                <div class="col-md-3">
                    <label for="txtPwd">Password</label>
                    <input type="password" class="form-control" id="txtPwd" name="txtPwd" required>
                </div>
                <div class="col-md-3">
                    <label for="txtConPwd">Confirm Password</label>
                    <input type="password" class="form-control" id="txtConPwd" name="txtConPwd" onchange="check_passwords();" required>
                    <div id="pwd_mismatch_error" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary" name="btnSubmit" id="submitButton">Submit</button>
                </div>
            </div>
        </form>
    </div>

</body>

</html>