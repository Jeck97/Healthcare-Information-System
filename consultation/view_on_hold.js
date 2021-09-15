$(document).ready(function () {

    $.ajax({
        url: 'retrieve_user_department.php',
        type: 'post',
        data: {
            user_id: 1
        },
        success: function (result) {
            if (result[0] == "Found") {
                $('#doctor_name_department').text(result[1] + " " + result[2] + " in Department " + result[3]);
            }
        }
    })

    $.ajax({
        url: 'retrieve_on_hold.php',
        type: 'post',
        success: function (result) {
            if (result[0] == "Found") {

                for (let counter = 1; counter < result.length; counter++) {

                    var appendRow = "<tr><td>" + result[counter][0] + "</td>";
                    appendRow += "<td>" + result[counter][1] + "</td>";
                    appendRow += "<td>" + result[counter][2] + "</td>";
                    appendRow += "<td>" + result[counter][3] + "</td>";
                    appendRow += "<td>" + result[counter][4] + "</td>";
                    appendRow += "<td><button onclick=discharge('" + result[counter][5] + "', '" + result[counter][6] + "')>Discharge</button></td>";
                    appendRow += "</tr>";

                    $('#on_hold_patient_table tbody').append(appendRow);
                }
            } else {

            }
        }
    })
})

function discharge(patientBedID, bedID) {
    $.ajax({
        url: 'discharge_patient.php',
        type: 'post',
        data: {
            patientBedID,
            bedID
        },
        success: function (result) {
            console.log(result);
        }
    })
}