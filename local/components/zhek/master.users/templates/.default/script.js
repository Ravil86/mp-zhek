function setUser(id) {
  //   console.log("dsdf", id);
  const form = document.getElementById("addUser");
  form.querySelector("#org_id").value = id;
}

function selectUser(id) {
  console.log("dsdf", id);

  //   var myModal = new bootstrap.Modal(document.getElementById("selectUser"));
  //   myModal.show();

  var modalToggle = document.getElementById("selectUser"); // relatedTarget
  var myModal = new bootstrap.Modal(modalToggle);
  myModal.show();

  const form = modalToggle.querySelector("form");

  form.querySelector("#org_id").value = id;

  //   myModal.show(modalToggle);
}

document.addEventListener("DOMContentLoaded", function () {
  document.addEventListener("hide.bs.modal", function (event) {
    if (document.activeElement) {
      document.activeElement.blur();
      event.target.querySelector("#org_id").value = "";
    }
  });
});

function clearUser(id) {
  $.post(
    document.URL,
    {
      CLEAR_USER: "Y",
      ORG_ID: id,
      sessid: window.bitrixSessid,
    },
    function (data) {
      location.reload();
    },
  );
}
