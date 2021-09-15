<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home-page.css">
    <script type="text/javascript" src="home-page.js"></script>
    <script src="https://kit.fontawesome.com/c4d66d84fb.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>HIS | Pharmacy</title>
</head>

<body>
    <nav>
        <a href="home-page.php"><i class="fas fa-pills fa-3x">&nbsp;PHARMACY</i></a>
        <div>
            <a href="home-page.php" class="selected">
                <span>HOME<br>PAGE</span>
            </a>
            <a href="manage-drug-page.php">
                <span>MANAGE<br>DRUG</span>
            </a>
            <a href="view-report-page.php">
                <span>VIEW<br>REPORT</span>
            </a>
            <a href="../navigation.php">
                <span>BACK</span>
            </a>
        </div>
    </nav>
    <form id="form" method="post" action="home-page.php">
        <div class="header">
            <div class="search-bar">
                <input id="input-search" type="text" name="search" placeholder="Search queue number..." value="<?php echo isset($_POST['search']) ? $_POST['search'] : '' ?>">
                <button type="submit">Search</button>
            </div>
            <select id="select-status" name="status" onchange="onStatusSelected();">
                <?php $status = isset($_POST["status"]) ? $_POST["status"] : "all" ?>
                <option <?php if ($status == "all") echo "selected"; ?> value="all">ALL</option>
                <option <?php if ($status == "ordered") echo "selected"; ?> value="ordered">ORDERED</option>
                <option <?php if ($status == "revisit") echo "selected"; ?> value="revisit">REVISIT</option>
                <option <?php if ($status == "prepared") echo "selected"; ?> value="prepared">PREPARED</option>
                <option <?php if ($status == "completed") echo "selected"; ?> value="completed">COMPLETED</option>
            </select>
            <button type="button" title="Refresh" onclick="onRefresh();">
                <i class="fas fa-redo-alt fa-lg"></i>
            </button>
        </div>
        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th>Queue No.</th>
                        <th>Order ID</th>
                        <th>Status</th>
                        <th style="width: 99%;">Description</th>
                        <th>Qty</th>
                    </tr>
                </thead>
                <tbody>
                    <?php include "home-table-body.php"; ?>
                </tbody>
            </table>
        </div>
        <input type="hidden" id="input-order-id" name="order-id">
        <input type="hidden" id="input-order-status-to" name="order-status-to">
    </form>
    <button onclick="gotoTop()" id="btn-top" title="Go to top"><i class="fas fa-chevron-up"></i></button>
</body>

</html>