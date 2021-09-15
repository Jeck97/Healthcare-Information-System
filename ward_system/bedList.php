<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bed List</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/73d902c54d.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>

</head>

<body>
    <?php include "conn.php"; ?>
    <?php include "navbar.php"; ?>

    <div class="container mt-5">
        <div class="card rounded shadow">
            <div class="card-body">
                <h1 class="text-uppercase">Search Ward</h1>
                <label for="search">Search Ward:</label>
                <input type="text" name="searchName" class="form-control" id="searchName">
                <div class="list-group" id="ward-list" class="form-control" style="overflow:auto;display:none;position: absolute;z-index:100"></div>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="card rounded shadow">
            <div class="card-body">
                <a href="addBed.php" class="float-right"><button class="btn btn-primary text-uppercase"><i class="fas fa-plus"></i> Add Bed</button></a>
                <h1 class="text-uppercase">Bed List</h1>
                <hr>
                <table id="myTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Ward Name</th>
                            <th>Bed Number</th>
                            <th>Bed Status</th>
                            <th>Bed Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="bed-list">
                        <?php

                        $sql = "SELECT * FROM bed JOIN ward ON bed.ward_id=ward.ward_id";
                        $query = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_array($query)) {
                            echo '
                    <tr>
                        <td>' . $row["ward_name"] . '</td>
                        <td>' . $row["bed_number"] . '</td>
                        <td>' . $row["bed_status"] . '</td>
                        <td>' . $row["bed_status"] . '</td>';
                            if ($row["bed_status"] == "Unavailable") {
                                echo '<td>  <a href="wardManager.php?discharge&bedID=' . $row["bed_id"] . '"><button class="btn btn-primary text-uppercase">Discharge</button></a></td>';
                            } else if ($row["bed_status"] == "Discharged") {
                                echo '<td>  <a href="wardManager.php?makeAvailable&bedID=' . $row["bed_id"] . '"><button class="btn btn-primary text-uppercase">Make Available</button></a></td>';
                            } else {
                                echo "<td></td>";
                            }
                            echo '</tr>';
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<script>
    $(document).ready(function() {
        $('#myTable').dataTable();

        $("#searchName").keyup(function() {
            $("#ward-list").css('display', "block")
            let searchText = $(this).val();
            if (searchText != "") {
                $.ajax({
                    url: "wardManager.php",
                    method: "post",
                    contentType: "application/x-www-form-urlencoded",
                    data: {
                        getWard: true,
                        query: searchText
                    },
                    success: function(response) {
                        $("#ward-list").html(response);
                    }
                });

            } else {
                $("#ward-list").html("");
            }
        });

        $(document).on("click", ".ward", function() {
            $("#searchName").val("");
            let searchText = $(this).attr("href");
            $.ajax({
                url: "wardManager.php",
                method: "post",
                contentType: "application/x-www-form-urlencoded",
                data: {
                    getBed: true,
                    query: searchText
                },
                success: function(response) {
                    document.cookie = "table=" + response;
                    $("#bed-list").html(response);
                    $("#ward-list").css('display', "none");
                }
            });
        });

    });
</script>

</html>