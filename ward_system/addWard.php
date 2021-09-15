<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Ward</title>
</head>

<body>
    <?php include "conn.php"; ?>
    <?php include "../header.php"; ?>
    <?php include "navbar.php"; ?>

    <div class="container mt-5">
        <div class="card rounded shadow">
            <div class="card-body">
                <a href="index.php" class="float-left h1 mx-3"><i class="fas fa-arrow-left"></i></a>
                <h1 class="text-uppercase">Add Ward</h1>
                <hr>
                <form action="wardManager.php" method="post">
                    <div class="form-group">
                        <label for="wardName">Ward Name:</label>
                        <input type="text" name="wardName" id="wardName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="wardLocation">Ward Location:</label>
                        <select name="wardLocation" id="wardLocation" class="form-control" required>
                            <option value="">Please choose one</option>
                            <option value="Left Wing">Left Wing</option>
                            <option value="Right Wing">Right Wing</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="wardType">Ward Type:</label>
                        <input type="text" name="wardType" id="wardType" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="departmentID">Department:</label>
                        <select name="departmentID" id="departmentID" class="form-control" required>
                            <option value="">Please choose one</option>
                            <?php
                            $sql = "SELECT * FROM department";
                            $query = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_array($query)) {
                            ?>
                                <option value="<?= $row["department_id"] ?>"><?= $row["department_name"] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="add ward" name="addWard" class="btn btn-primary text-uppercase">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>