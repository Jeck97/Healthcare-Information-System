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

function onDateChanged() {
  const inputDateFrom = document.getElementById("input-date-from");
  const inputDateTo = document.getElementById("input-date-to");

  const splitTo = inputDateTo.value.split("-");
  const dateTo = Date.parse(`${splitTo[0]}-${splitTo[1]}-${splitTo[2]}`);

  inputDateFrom.max = formatDate(dateTo - 86400000);

  if (inputDateFrom.value) {
    const splitFrom = inputDateFrom.value.split("-");
    const dateFrom = Date.parse(
      `${splitFrom[0]}-${splitFrom[1]}-${splitFrom[2]}`
    );
    if (dateFrom >= dateTo) {
      inputDateFrom.value = null;
    }
  }
}

function formatDate(date) {
  let d = new Date(date),
    month = "" + (d.getMonth() + 1),
    day = "" + d.getDate(),
    year = d.getFullYear();

  if (month.length < 2) month = "0" + month;
  if (day.length < 2) day = "0" + day;

  return [year, month, day].join("-");
}
