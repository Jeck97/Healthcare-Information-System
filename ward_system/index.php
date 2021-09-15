<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ward List</title>
</head>

<body>
    <?php include "conn.php"; ?>
    <?php include "../header.php"; ?>
    <?php include "navbar.php"; ?>

    <div class="container mt-5">
        <div class="card rounded shadow">
            <div class="card-body">
                <a href="addWard.php" class="float-right"><button class="btn btn-primary text-uppercase"><i class="fas fa-plus"></i> Add Ward</button></a>
                <h1 class="text-uppercase">Manage Ward</h1>
                <form action="wardManager.php" method="post">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <td>Ward Name</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $sql = "SELECT * FROM ward";
                            $query = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_array($query)) {
                            ?>
                                <tr>
                                    <td><?= $row["ward_name"] ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-primary text-uppercase" data-bs-toggle="modal" data-bs-target="#editWard<?= $row["ward_id"] ?>">
                                                Edit
                                            </button>
                                            <div class="modal fade" id="editWard<?= $row["ward_id"] ?>" tabindex="-1" aria-labelledby="editWardLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editWardLabel">Edit Ward</h5>
                                                            <span class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></span>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="wardManager.php" method="post">
                                                                <div class="form-group">
                                                                    <label for="wardEditName<?= $row["ward_id"] ?>">
                                                                        Ward Name:
                                                                    </label>
                                                                    <input type="hidden" name="wardID" value="<?= $row["ward_id"] ?>">
                                                                    <input type="text" name="wardName" id="wardEditName<?= $row["ward_id"] ?>" class="form-control" value="<?= $row["ward_name"] ?>" required>
                                                                </div>
                                                                <div class="modal-footer mt-3">
                                                                    <button type="button" class="btn btn-danger text-uppercase" data-bs-dismiss="modal">Close</button>
                                                                    <input type="submit" class="btn btn-primary text-uppercase" value="Edit Ward" name="editWard">
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</body>

</html>