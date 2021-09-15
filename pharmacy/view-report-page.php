<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="view-report-page.css">
    <script type="text/javascript" src="view-report-page.js"></script>
    <script src="https://kit.fontawesome.com/c4d66d84fb.js" crossorigin="anonymous"></script>
    <title>HIS | Pharmacy</title>
</head>

<body>
    <nav>
        <a href="home-page.php"><i class="fas fa-pills fa-3x">&nbsp;PHARMACY</i></a>
        <div>
            <a href="home-page.php">
                <span>HOME<br>PAGE</span>
            </a>
            <a href="manage-drug-page.php">
                <span>MANAGE<br>DRUG</span>
            </a>
            <a href="view-report-page.php" class="selected">
                <span>VIEW<br>REPORT</span>
            </a>
            <a href="../navigation.php">
                <span>BACK</span>
            </a>
        </div>
    </nav>
    <form id="form" action="view-report-page.php" method="post" class="header">
        <h2>Select Dispended Date of Order</h2>
        <div>
            <label for="input-date-from">From:&nbsp;</label>
            <input id="input-date-from" name="date-from" type="date" max="<?php echo date('Y-m-d', strtotime(isset($_POST['date-to']) ? $_POST['date-to'] : date("Y-m-d") . ' - 1 days')); ?>" value="<?php echo isset($_POST['date-from']) ? $_POST['date-from'] : ''; ?>" required>
            <label for="input-date-to">To:&nbsp;</label>
            <input id="input-date-to" name="date-to" type="date" onchange="onDateChanged();" max="<?php echo date("Y-m-d"); ?>" value="<?php echo isset($_POST['date-to']) ? $_POST['date-to'] : date("Y-m-d"); ?>" required>
            <button type="submit">GENERATE</button>
            <button type="button" onclick="window.print();;">PRINT</button>
        </div>
    </form>
    <hr style="margin: 0 1rem;">
    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>Date Dispense</th>
                    <th>Date Ordered</th>
                    <th>Order ID</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Drug Fee (RM)</th>
                </tr>
            </thead>
            <tbody>
                <?php include "view-report-table-body.php" ?>
            </tbody>
        </table>
    </div>
    <button onclick="gotoTop()" id="btn-top" title="Go to top"><i class="fas fa-chevron-up"></i></button>
</body>