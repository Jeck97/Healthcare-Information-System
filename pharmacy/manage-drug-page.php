<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="manage-drug-page.css">
    <script type="text/javascript" src="manage-drug-page.js"></script>
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
            <a href="manage-drug-page.php" class="selected">
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
    <form id="form" method="post" action="manage-drug-page.php">
        <div class="header">
            <div class="search-bar">
                <input id="input-search" type="text" name="search" placeholder="Search drug ID or name..." value="<?php echo isset($_POST['search']) ? $_POST['search'] : '' ?>">
                <button class="button-search" type="submit">Search</button>
            </div>
            <button type="button" class="button-add-drug" onclick="Dialog.onAdd();">
                <i class="fas fa-plus fa-1x">&nbsp;&nbsp;ADD DRUG</i>
            </button>
            <button type="button" class="button-refresh" title="Refresh" onclick="onRefresh();">
                <i class="fas fa-redo-alt fa-lg"></i>
            </button>
        </div>
        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th>Drug ID</th>
                        <th>Name</th>
                        <th style="width: 99%;">Description</th>
                        <th>Price/Unit</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php include "manage-drug-table-body.php"; ?>
                </tbody>
            </table>
        </div>
    </form>
    <button onclick="gotoTop()" id="btn-top" title="Go to top"><i class="fas fa-chevron-up"></i></button>
</body>