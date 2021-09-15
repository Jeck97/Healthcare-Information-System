<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Display</title>
</head>

<body style="background: url(https://wallpaperaccess.com/full/624111.jpg)">
    <?php include "header.php" ?>

    <div>
        <div id="pharmacy"></div>
    </div>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    setInterval(() => {
        xhr2 = new XMLHttpRequest();
        xhr2.open("POST", "queueManager.php", true);
        xhr2.onload = () => {
            if (xhr2.readyState === XMLHttpRequest.DONE) {
                if (xhr2.status === 200) {
                    let data2 = xhr2.response;
                    document.getElementById("pharmacy").innerHTML = data2;
                }
            }
        };
        xhr2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr2.send("getQueueNumberPharmacy");

    }, 500);
</script>

</html>