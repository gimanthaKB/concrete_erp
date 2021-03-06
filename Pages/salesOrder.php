<?php
    session_start();
    if(!isset($_SESSION["user_id"])){
        header('location:./index.php');
    }
    $user_type = $_SESSION["user_type"];
    if($user_type=="account" || $user_type=="production"){
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

    <!-- Jquery, autocomplete -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.min.js"
        integrity="sha512-TToQDr91fBeG4RE5RjMl/tqNAo35hSRR4cbIFasiV2AAMQ6yKXXYhdSdEpUcRE6bqsTiB+FPLPls4ZAFMoK5WA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://use.fontawesome.com/c829a83b30.js"></script>
    <link rel="stylesheet" href="../assets/css/mainStyles.css">
    
    <title>Concrete Order</title>

    <script type="text/javascript">
    function goBack() {
        window.history.back();
    }
    function get_customer() {
        jQuery.noConflict()(function($) {
            $("#txtCustomerName").autocomplete({
                source: "../PHPScripts/get_customer_details.php",
                minLength: 2,
                select: function(event, ui) {
                    $('#customer_id').val(ui.item.cus_id);
                    $('#txtCustomerTele').val(ui.item.phone);
                    $('#txtSiteAddress').val(ui.item.address);
                    $('#txtCustomerTele').attr('readonly', true);
                    $('#txtSiteAddress').attr('readonly', true);
                }
            });
        });
    }
    </script>
</head>

<body>
<?php
    include('../PHPScripts/db_connect.php');
    require('../Components/header.php');

    date_default_timezone_set("Asia/Colombo");
    $today_date = date("Y.m.d");

    $select_max_job_no = mysqli_query($con, "SELECT MAX(job_no) FROM concrete_order");
	$result_max_job_no = mysqli_fetch_array($select_max_job_no);
	if ($result_max_job_no[0] == '') {
		$job_no = '1001';
	} else {
		$job_no = $result_max_job_no[0]+1;
    }
    ?>
    <div class="container page-spacing">
        <form action="../PHPScripts/sales_order_submit.php" method="post">
            <div class="row mb-3 mt-3">
                <div class="col-md-3">
                    <label for="txtDate">Date</label>
                    <input type="text" class="form-control" id="txtDate" value="<?php echo $today_date; ?>" readonly>
                </div>
                <div class="col-md-3">
                    <label for="txtSalesCode">Sales Code</label>
                    <input type="text" class="form-control" id="txtSalesCode" name="txtSalesCode">
                </div>
                <div class="col-md-3">
                    <label for="txtJobNo">Job No.</label>
                    <input type="text" class="form-control" id="txtJobNo" value="<?php echo $job_no; ?>" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="txtCustomerName">Customer Name</label>
                    <input type="text" class="form-control" id="txtCustomerName" name="txtCustomerName"
                        onkeypress="get_customer();" required>
                    <input type="hidden" class="form-control" id="customer_id" name="customer_id">
                </div>
                <div class="col-md-3">
                    <label for="txtCustomerTele">Tele.</label>
                    <input type="text" class="form-control" id="txtCustomerTele" name="txtCustomerTele">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="txtSiteAddress">Site Address</label>
                    <textarea class="form-control" id="txtSiteAddress" name="txtSiteAddress"></textarea>
                </div>
            </div>
            <label><u>Concrete Requirement</u></label>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="txtReqDate">Date</label>
                    <input type="date" class="form-control" id="txtReqDate" name="txtReqDate"
                        min="<?php echo date("Y-m-d"); ?>">
                </div>
                <div class="col-md-3">
                    <label for="txtReqTime">Time</label>
                    <input type="time" class="form-control" id="txtReqTime" name="txtReqTime">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="txtGrade">Grade (N)</label>
                    <input type="number" step="0.1" min="0.1" class="form-control" id="txtGrade" name="txtGrade"
                        required>
                </div>
                <div class="col-md-2">
                    <label for="txtQty">Quantity (m<sup>3</sup>)</label>
                    <input type="number" step="0.1" min="0.1" class="form-control" id="txtQty" name="txtQty" required>
                </div>
                <div class="col-md-2">
                    <label for="txtPumpCar">Pump Car</label>
                    <input type="text" class="form-control" id="txtPumpCar" name="txtPumpCar">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="txtTrucks">Trucks</label>
                    <input type="text" class="form-control" id="txtTrucks" name="txtTrucks">
                </div>
                <div class="col-md-2">
                    <label for="txtSlump">Slump</label>
                    <input type="text" class="form-control" id="txtSlump" name="txtSlump">
                </div>
                <div class="col-md-2">
                    <label for="txtSlumpTest">Slump Test</label>
                    <input type="text" class="form-control" id="txtSlumpTest" name="txtSlumpTest">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="txtMolds">C. Molds</label>
                    <input type="text" class="form-control" id="txtMolds" name="txtMolds">
                </div>
                <div class="col-md-2">
                    <label for="txtLaying">Laying*</label>
                    <input type="text" class="form-control" id="txtLaying" name="txtLaying">
                </div>
                <div class="col-md-2">
                    <label for="txtPolythene">Polythene*</label>
                    <input type="text" class="form-control" id="txtPolythene" name="txtPolythene">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="txtJobType">Type of Job</label>
                    <input type="text" class="form-control" id="txtJobType" name="txtJobType" required>
                </div>
            </div>

            <label><u>Rates</u></label>
            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="txtRateConcrete">Concrete</label>
                    <input type="text" class="form-control" id="txtRateConcrete" name="txtRateConcrete">
                </div>
                <div class="col-md-2">
                    <label for="txtRatePumpCar">Pump Car</label>
                    <input type="text" class="form-control" id="txtRatePumpCar" name="txtRatePumpCar">
                </div>
                <div class="col-md-2">
                    <label for="txtRateLaying">Laying*</label>
                    <input type="text" class="form-control" id="txtRateLaying" name="txtRateLaying">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="txtCustRegNo">Customer Registration No.</label>
                    <input type="text" class="form-control" id="txtCustRegNo">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="txtPO">PO/Request Letter.</label>
                    <input type="text" class="form-control" id="txtPO" name="txtPO">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="txtPayReceiptNo">Payment Receipt No.</label>
                    <input type="text" class="form-control" id="txtPayReceiptNo" name="txtPayReceiptNo" required>
                </div>
                <div class="col-md-2">
                    <label for="txtPayMode">Pay Mode</label>
                    <input type="text" class="form-control" id="txtPayMode" name="txtPayMode" required>
                </div>
                <div class="col-md-2">
                    <label for="txtPaymentDate">Payment Date</label>
                    <input type="date" class="form-control" id="txtPaymentDate" name="txtPaymentDate" required>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary" name="btnSubmit">Submit</button>
                </div>
            </div>
        </form>
    </div>

</body>

</html>