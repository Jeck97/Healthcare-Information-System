<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HIS</title>
</head>

<body>
    <?php include "header.php" ?>
    <div class="container text-center mt-5">
        <h1>Healthcare Information System</h1>
    </div>
    <div class="container mt-5">
        <div class="card rounded shadow">
            <div class="card-body">
                <div class="text-center">
                    <h1 class="text-uppercase">Login</h1>
                </div>
                <hr>
                <form action="authenticateManager.php" method="post">
                    <div class="form-group">
                        <label for="email">Email: </label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="Enter email.." required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password: </label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter password.." required>
                    </div>
                    <div class="form-group text-center">
                        <input type="submit" value="Log in" name="login" class=" btn btn-primary text-uppercase px-5">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>