<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Bed</title>
</head>

<body>
    <?php include "conn.php"; ?>
    <?php include "../header.php"; ?>
    <?php include "navbar.php"; ?>

    <div class="container">
        <div class="card rounded shadow">
            <div class="card-body">
                <a href="index.php" class="float-left h1 mx-3"><i class="fas fa-arrow-left"></i></a>
                <h1 class="text-uppercase">Add Bed</h1>
                <form action="wardManager.php" method="post">
                    <div class="form-group">
                        <label for="ward">Ward:</label>
                        <select name="wardID" id="ward" class="form-control" required>
                            <option value="">Please choose one</option>
                            <?php
                            $sql = "SELECT * FROM ward";
                            $query = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_array($query)) {
                            ?>
                                <option value="<?= $row["ward_id"] ?>"><?= $row["ward_name"] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="bedNumber">Number of Bed:</label>
                        <select name="bedNumber" id="bedNumber" class="form-control" required>
                            <option value="">Please choose one</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="add bed" name="addBed" class="btn btn-primary text-uppercase">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>