<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue Manager</title>
</head>

<body style="background: url(https://wallpaperaccess.com/full/624111.jpg)">
    <?php include "header.php" ?>
    <div>
        <div id="queueContainer"></div>
    </div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    setInterval(() => {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "queueManager.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let data = xhr.response;
                    document.getElementById("queueContainer").innerHTML = data;
                }
            }
        };
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("getQueueNumber");
    }, 500);
</script>


</html>