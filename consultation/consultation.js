$(document).ready(function () {
  $("#medical_history_table").DataTable();
  //   $("#order_drug_table").DataTable();

  $.ajax({
    url: "retrieve_drug.php",
    type: "post",
    success: function (result) {
      if (result[0] == "Found") {
        for (let counter = 1; counter < result.length; counter++) {
          $("#consultation_order_drug").append(
            new Option(result[counter][1], result[counter][0])
          );
        }
      } else if (result[0] == "NotFound") {
        console.log("There is no drug retrieved");
      }
    },
  });

  $.ajax({
    url: "retrieve_bed.php",
    type: "post",
    success: function (result) {
      if (result[0] == "Found") {
        for (let counter = 1; counter < result.length; counter++) {
          $("#consultation_order_wardbed").append(
            new Option(
              "Bed " + result[counter][1] + " in ward " + result[counter][2],
              result[counter][0]
            )
          );
        }
      } else if (result[0] == "NotFound") {
        console.log("There is no more available");
      }
    },
  });

  $.ajax({
    url: "retrieve_services.php",
    type: "post",
    success: function (result) {
      if (result[0] == "Found") {
        for (let counter = 1; counter < result.length; counter++) {
          var appendRow = "<div id='checkbox-row'>";
          appendRow +=
            "<input type='checkbox' name='" +
            result[counter][1] +
            "' class = 'services_checklist' id='" +
            result[counter][0] +
            "' title='" +
            result[counter][1] +
            "'>";
          appendRow +=
            "<label class='checkbox-label for='" +
            result[counter][1] +
            "' title='" +
            result[counter][1] +
            "'>" +
            result[counter][1] +
            "</label>";
          appendRow += "</div>";

          $("#grid-item-container").append(appendRow);
        }
      } else {
      }
    },
  });

  $("#close_quantity_button").click(function () {
    $("#drug_modal").removeClass("bg-active");
    $("body").css("overflow", "scroll");
  });

  $("#confirm-quantity-button").click(function () {
    $("#drug_modal").removeClass("bg-active");
    $("body").css("overflow", "scroll");

    var newQuantity = $("#edit_drug_quantity").val();
    console.log("New Quantity: " + newQuantity);
    var drugID = $("#edit_drug_quantity_drug_id").val();
    $("#drug_" + drugID)
      .children(".order_drug_quantity")
      .html(newQuantity);
  });

  $("#confirm-button").click(function () {
    $("#service_modal").removeClass("bg-active");
    $("body").css("overflow", "scroll");

    // Get the categories data
    var serviceLists = document.getElementsByClassName("services_checklist");
    var selectedServices = [];
    for (let counter = 0; counter < serviceLists.length; counter++) {
      if (serviceLists[counter].checked) {
        selectedServices.push(serviceLists[counter].getAttribute("name"));
      }
    }

    if (selectedServices.length != 0) {
      $("#service_chosen").val(selectedServices.join(","));
    } else {
      $("#service_chosen").val("No service chosen");
    }
  });

  $("#consultation_order_drug").change(function () {
    var selectedOption = $("#consultation_order_drug option:selected").text();
    if (selectedOption != "None") {
      $("#drug_quantity").attr("readonly", false);
    } else {
      $("#drug_quantity").attr("readonly", true);
    }
  });

  $("#confirmQuantity").click(function () {
    // Check the select field and input quantity field
    var selectedOption = $("#consultation_order_drug option:selected").text();
    var selectedValue = $("#consultation_order_drug option:selected").val();
    if (selectedOption != "None") {
      // Check the quantity first
      if ($("#drug_quantity").val() != "") {
        $("#order_drug_table").css("display", "block");

        // Check whether there is an id in the table already
        if ($("#drug_" + selectedValue).length == 0) {
          var appendString =
            "<tr id='drug_" +
            selectedValue +
            "'><td value='" +
            selectedValue +
            "'>" +
            selectedOption +
            "</td><td id='drug_quantity" +
            selectedValue +
            "'>" +
            $("#drug_quantity").val() +
            "</td>" +
            "<td><button id='edit_order_drug' class='btn btn-primary' onclick='editOrder(" +
            selectedValue +
            ")'>Edit</button></td>" +
            "<td><button id='delete_order_drug' class='btn btn-danger' onclick='deleteOrder(" +
            selectedValue +
            ")'>Delete</button></td>" +
            "</tr>";
          // Add the item to the checklist
          $("#order_drug_table tbody").append(appendString);
          $("#take_medicine_quantity_div").css("display", "block");
        } else {
          // Append the value of the drug
          var currentQuantity = $("#drug_quantity" + selectedValue).text();
          var updatedQuantity =
            parseInt(currentQuantity) + parseInt($("#drug_quantity").val());
          $("#drug_quantity" + selectedValue).text(updatedQuantity);
        }
      } else {
        alert("Please enter the quantity of " + selectedOption);
        $("#drug_quantity").focus();
      }
    } else {
      alert("Please choose a drug and enter its quantity to continue");
    }
  });

  $("#choose_service_button").click(function () {
    $("#service_modal").addClass("bg-active");
  });

  $("#close_modal_button").click(function () {
    $("#service_modal").removeClass("bg-active");
  });

  $("#submit_button").click(function () {
    var queueID = $("#queue_id").val();

    // Consultation reason refers to the text area in consultation page
    if ($("#consultation_reason").val() == 0) {
      alert("Please fill in the medical description");
      $("#consultation_reason").focus();
    } else {
      if (
        $("#order_drug_table tbody").find("tr").length != 0 &&
        ($("#take_medicine_quantity").val() == "" ||
          $("#take_medicine_description").val() == "")
      ) {
        // If order drug but there is no required information
        if ($("#take_medicine_quantity").val() == "") {
          alert("Please fill in the order quantity for drug first");
          $("#take_medicine_quantity").focus();
        } else if ($("#take_medicine_description").val() == "") {
          alert("Please fill in the order description for drug first");
          $("#take_medicine_description").focus();
        }
      } else {
        $("#next_queue_button").prop("disabled", false);
        $("#submit_button").prop("disabled", true);

        // In case doctor orders either drug or bed or both
        var gotOrderDrug = false,
          gotOrderBed = false,
          drugOrderID = 0;

        if ($("#order_drug_table tbody").find("tr").length != 0) {
          gotOrderDrug = true;

          // There is an order of drug
          var drugID = [],
            drugQuantity = [];
          var orderQuantity = parseInt($("#take_medicine_quantity").val());
          var orderDescription = $("#take_medicine_description").val();
          for (
            let counter = 0;
            counter < $(".order_quantity_class").length;
            counter++
          ) {
            var nameRow = document
              .getElementsByClassName("order_drug_name")
              [counter].getAttribute("value");
            var quantityRow = document.getElementsByClassName(
              "order_drug_quantity"
            )[counter].innerHTML;
            drugID.push(nameRow);
            drugQuantity.push(quantityRow);
          }

          $.ajax({
            url: "insert_order_drug.php",
            type: "post",
            async: false,
            data: {
              orderQuantity,
              orderDescription,
              drugID: JSON.stringify(drugID),
              drugQuantity: JSON.stringify(drugQuantity),
            },
            success: function (result) {
              drugOrderID = result;
            },
          });
        }

        if (
          $("#consultation_order_wardbed").children("option:selected").val() !=
          "None"
        ) {
          gotOrderBed = true;
          // Get the patient id and bed id selected
          var patientID = $("#patient_id").val();
          var bedID = $("#consultation_order_wardbed")
            .children("option:selected")
            .val();

          $.ajax({
            url: "insert_bed.php",
            type: "post",
            data: {
              patientID,
              bedID,
            },
            success: function (result) {
              console.log(result);
            },
          });
        }

        // Retrieve all the necessary information
        var consultationReason = $("#consultation_reason").val();
        var revisitStatus = $("#consultation_revisit")
          .children("option:selected")
          .val();
        // USER ID AND QUEUE ID is hardcoded
        var userID = 1;
        // Will be passed later on
        var consultationID;

        $.ajax({
          url: "retrieve_consultation_id.php",
          type: "post",
          async: false,
          data: {
            queue_id: queueID,
          },
          success: function (result) {
            if (result[0] == "Found") {
              consultationID = result[1];
            } else {
              console.log(result[0]);
            }
          },
        });

        if (gotOrderDrug) {
          var onhold = gotOrderBed == true ? 1 : 0;
          $.ajax({
            url: "insert_consultation.php",
            type: "post",
            data: {
              revisitStatus,
              consultationID,
              orderID: drugOrderID,
              onhold,
            },
            success: function (result) {
              if (result == "Success") {
                console.log("Consultation updated! Line 251");
              }
            },
          });
        } else {
          var onhold = gotOrderBed == true ? 1 : 0;
          $.ajax({
            url: "insert_consultation.php",
            type: "post",
            data: {
              revisitStatus,
              onhold,
              consultationID,
            },
            success: function (result) {
              console.log(result);
            },
          });
        }

        // Insert service_consultation table
        var services = [];
        $(".services_checklist").each(function () {
          if ($(this).is(":checked")) {
            services.push($(this).attr("id"));
          }
        });

        services.push(1);
        services = services.toString();

        $.ajax({
          url: "insert_service_consultation.php",
          type: "post",
          data: {
            consultationID,
            services,
          },
          success: function (result) {
            console.log(result);
          },
        });

        // Insert medical history
        var patientMedical = $("#patient_id").val();
        var consultationMedical = $("#consultation_reason").val();

        $.ajax({
          url: "insert_medical_history.php",
          type: "post",
          data: {
            patientMedical,
            consultationMedical,
          },
          success: function (result) {
            console.log(result);
          },
        });
      }
    }
  });
});

function deleteOrder(drugID) {
  $("#drug_" + drugID).remove();
  if ($("#order_drug_table tbody").children().length == 0) {
    $("#order_drug_table").css("display", "none");
    $("#take_medicine_quantity_div").css("display", "none");
  }
}

function editOrder(drugID) {
  // Open up modal and edit here

  $("#drug_modal").addClass("bg-active");

  $("#edit_drug_name_label").html(
    $("#drug_" + drugID)
      .children(".order_drug_name")
      .html()
  );
  $("#edit_drug_quantity").val(
    parseInt(
      $("#drug_" + drugID)
        .children(".order_drug_quantity")
        .html(),
      10
    )
  );
  $("#edit_drug_quantity_drug_id").val(
    $("#drug_" + drugID)
      .children(".order_drug_name")
      .attr("value")
  );
}

function nextQueue() {
  var form = document.getElementById("nextQueueForm");

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "queueManager.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        var responseArray = JSON.parse(xhr.response);
        var patientID = responseArray[0];

        $("#queue_id").val(responseArray[1]);

        $.ajax({
          url: "retrieve_next_patient.php",
          type: "post",
          data: {
            patient_id: patientID,
          },
          success: function (result) {
            if (result[0] == "Found") {
              $("#patient_fname").val(result[1]);
              $("#patient_lname").val(result[2]);
              $("#patient_birthday").val(result[3]);
              $("#patient_identification_number").val(result[4]);
              $("#patient_email").val(result[5]);
              $("#patient_phone").val(result[6]);
              $("#patient_address1").val(result[7]);
              $("#patient_address2").val(result[8]);
              $("#patient_city").val(result[9]);
              $("#patient_postcode").val(result[10]);
              $("#patient_state").val(result[11]);
              $("#patient_id").val(result[12]);
              $(".patient_data").attr("readonly", "readonly");
              $("#submit_button").removeAttr("disabled");
              $("#next_queue_button").prop("disabled", true);
            } else if (result[0] == "NotFound") {
              // Ask them later on
              alert("Patient record not found!");
            }
          },
        });

        $.ajax({
          url: "retrieve_medical_history.php",
          type: "post",
          data: {
            patient_id: patientID,
          },
          success: function (result) {
            if (result[0] == "Found") {
              var append = "";
              // Value is displayed
              for (let counter = 1; counter < result.length; counter++) {
                append +=
                  "<tr><td>" +
                  result[counter][1] +
                  "</td><td>" +
                  result[counter][2] +
                  "</td></tr>";
              }

              $("#medical_history_table").append(append);
            } else if (result[0] == "NotFound") {
              // Display no medical record
              $("#medical_history_table").append(
                "<tr colspan='2'><td>There is no past medical record found!</td></tr>"
              );
            }
          },
        });
      }
    }
  };
  let formData = new FormData(form);
  xhr.send(formData);
}
