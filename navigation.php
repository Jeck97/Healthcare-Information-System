<?php ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HIS</title>
</head>

<body>

    <?php include "header.php"; ?>
    <div class="container mt-5">
        <div class="card rounded shadow">
            <div class="text-center my-3">
                <h1>Healthcare Information System</h1>
            </div>
            <hr>
            <div class="d-flex justify-content-around flex-wrap align-content-around">
                <a href="patient/view/patient.html">
                    <div class="btn btn-primary p-5 m-3">
                        <div class="card-body">
                            <h1>Register Patient</h1>
                        </div>
                    </div>
                </a>
                <a href="consultation/consultation.html">
                    <div class="btn btn-primary p-5 m-3">
                        <div class="card-body">
                            <h1>Consultation</h1>
                        </div>
                    </div>
                </a>
                <a href="pharmacy/home-page.php">
                    <div class="btn btn-primary p-5 m-3">
                        <div class="card-body">
                            <h1>Pharmacy</h1>
                        </div>
                    </div>
                </a>
                <a href="billing_system/billing_system.html">
                    <div class="btn btn-primary p-5 m-3">
                        <div class="card-body">
                            <h1>Billings</h1>
                        </div>
                    </div>
                </a>
                <a href="ward_system/index.php">
                    <div class="btn btn-primary p-5 m-3">
                        <div class="card-body">
                            <h1>Manage Ward</h1>
                        </div>
                    </div>
                </a>
            </div>
            <a href="logout.php" class="btn btn-danger mt-5 p-3 text-uppercase">Logout</a>
        </div>
    </div>
</body>

</html>