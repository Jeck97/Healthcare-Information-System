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

function onRefresh() {
  const form = document.getElementById("form");
  const inputSearch = document.getElementById("input-search");
  inputSearch.value = null;
  form.submit();
}

const Dialog = {
  onAdd() {
    const html = `
        <div class="modal-dialog">
            <form class="dialog-container" method="post" action="manage-drug-controller.php">
                <div class="dialog-header">
                    <span class="title">Add New Drug</span>
                    <button class="button-close" type="button">&times;</button>
                </div>
                <div class="dialog-content">
                    <div class="input-text">
                        <label for="drug-name" style="width: 8rem;">Drug Name: </label>
                        <input id="drug-name" name="drug-name" type="text" required>
                    </div>
                    <div class="input-number">
                        <label for="drug-price" style="width: 12rem;">Price / Unit:&nbsp;&nbsp;RM</label>
                        <input id="drug-price" name="drug-price" type="number" min="0.01" step="0.01" required>
                    </div>
                    <div class="input-textarea">
                        <label for="drug-description">Description:</label>
                        <textarea id="drug-description" name="drug-description" rows="5" placeholder="Description of the drug..." required></textarea>
                    </div>
                </div>
                <div class="dialog-buttons">
                    <button class="button-cancel" type="button">CANCEL</button>
                    <button class="button-submit" type="submit" name="submit" value="add">ADD</button>
                </div>
            </form>
        </div>
        `;
    this._createDialog(html);
  },

  onEdit(id, name, desc, price) {
    price = price.toFixed(2);
    const html = `
        <div class="modal-dialog">
            <form class="dialog-container" method="post" action="manage-drug-controller.php">
                <div class="dialog-header">
                    <span class="title">Update Drug</span>
                    <button class="button-close" type="button">&times;</button>
                </div>
                <div class="dialog-content">
                    <input name="drug-id" type="hidden" value="${id}">
                    <div class="input-text">
                        <label for="drug-name" style="width: 8rem;">Drug Name: </label>
                        <input id="drug-name" name="drug-name" type="text" required value="${name}">
                    </div>
                    <div class="input-number">
                        <label for="drug-price" style="width: 12rem;">Price / Unit:&nbsp;&nbsp;RM</label>
                        <input id="drug-price" name="drug-price" type="number" min="0.01" step="0.01" required value="${price}">
                    </div>
                    <div class="input-textarea">
                        <label for="drug-description">Description:</label>
                        <textarea id="drug-description" name="drug-description" rows="5" placeholder="Description of the drug..." required>${desc}</textarea>
                    </div>
                </div>
                <div class="dialog-buttons">
                    <button class="button-cancel" type="button">CANCEL</button>
                    <button class="button-submit" type="submit" name="submit" value="update">UPDATE</button>
                </div>
            </form>
        </div>
        `;
    this._createDialog(html);
  },

  _createDialog(html) {
    const template = document.createElement("template");
    template.innerHTML = html;

    const dialog = template.content.querySelector(".modal-dialog");
    const btnClose = template.content.querySelector(".button-close");
    const btnCancel = template.content.querySelector(".button-cancel");

    [btnCancel, btnClose].forEach((btn) => {
      btn.addEventListener("click", () => this._close(dialog));
    });

    document.body.appendChild(template.content);
  },

  _close(dialog) {
    dialog.classList.add("modal-dialog-close");
    dialog.addEventListener("animationend", () => {
      document.body.removeChild(dialog);
    });
  },
};
