window.onscroll = function () {
  const btnTop = document.getElementById("btn-top");
  document.body.scrollTop > 20 || document.documentElement.scrollTop > 20
    ? (btnTop.style.display = "block")
    : (btnTop.style.display = "none");
};

function gotoTop() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}

function showHideRow(normalRowId, hidableRowId) {
  const normalRow = document.getElementById(normalRowId);
  const hidableRow = document.getElementById(hidableRowId);

  if (hidableRow.className == "hidable-row hide") {
    hidableRow.className = "hidable-row";
    normalRow.className = "normal-row selected";
  } else {
    hidableRow.className = "hidable-row hide";
    normalRow.className = "normal-row";
  }
}

function onStatusSelected() {
  const form = document.getElementById("form");
  form.submit();
}

function onRefresh() {
  const form = document.getElementById("form");
  const inputSearch = document.getElementById("input-search");
  const selectStatus = document.getElementById("select-status");
  inputSearch.value = null;
  selectStatus.value = "all";
  form.submit();
}

const Dialog = {
  open(id, status, queueNo) {
    const statusFrom = status;
    const statusTo =
      status == "Ordered" || status == "Revisit" ? "Prepared" : "Completed";

    const message = `Update order status from "${statusFrom}" to "${statusTo}"?`;

    const html = `
      <div class="modal-dialog">
          <div class="dialog-container">
              <div class="dialog-header">
                  <button class="button-close">&times;</button>
              </div>
              <div class="dialog-content">${message}</div>
              <div class="dialog-buttons">
                  <button class="button-no">NO</button>
                  <button class="button-yes">YES</button>
              </div>
          </div>
      </div>`;

    const template = document.createElement("template");
    template.innerHTML = html;

    const dialog = template.content.querySelector(".modal-dialog");
    const btnClose = template.content.querySelector(".button-close");
    const btnYes = template.content.querySelector(".button-yes");
    const btnNo = template.content.querySelector(".button-no");

    btnYes.addEventListener("click", () => {
      const inputOrderId = document.getElementById("input-order-id");
      inputOrderId.value = id;

      const inputOrderStatus = document.getElementById("input-order-status-to");
      inputOrderStatus.value = statusTo;
      const form = document.getElementById("form");
      form.submit();
      inputOrderId.value = null;
      inputOrderStatus.value = null;
      this._close(dialog);

      if (statusTo === "Prepared") {
        $.ajax({
          url: "queueManager.php",
          method: "POST",
          data: {
            nextPharmacy: true,
            queueNumber: queueNo,
          },
          success: function (data) {
            console.log(data);
          },
        });
      }
    });

    [btnNo, btnClose].forEach((btn) =>
      btn.addEventListener("click", () => this._close(dialog))
    );

    document.body.appendChild(template.content);
  },

  _close(dialog) {
    dialog.classList.add("modal-dialog-close");
    dialog.addEventListener("animationend", () => {
      document.body.removeChild(dialog);
    });
  },
};
