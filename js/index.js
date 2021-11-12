function move() {
  const user = localStorage.getItem("creator_email");

  if (user === null || user === "") {
    showError("Oops please you need to be logged in :) ");
    $("html, body").animate({ scrollTop: 0 }, "fast");
  } else {
    window.location.replace("dashboard.html");
  }
}

//async function for registeration
async function register(data) {
  let result;

  try {
    result = await $.ajax({
      url: "./api/",
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify(data),
      beforeSend: () => {
        $("#loader").show();
        console.log("About to initiate request");
      },

      complete: function (data) {
        // Hide image container
        $("#loader").hide();
        console.log(data);
      },
    });

    return result;
  } catch (error) {
    console.error(error);
  }
}

//actual function called when register is clicked.
function invokeReg() {
  //get field values here
  const first_name = $("#first_name").val();
  const last_name = $("#last_name").val();
  const email_address = $("#email_address").val();
  const password = $("#password").val();
  const password2 = $("#password2").val();
  const contact_number = $("#contact_number").val();
  const referer = $("#referer").val();

  const data = {
    service: "signUp",
    first_name: first_name,
    last_name: last_name,
    email_address: email_address,
    password: password,
    password2: password2,
    contact_number: contact_number,
    referer: referer,
  };

  //check that values are not empty

  if (
    data.first_name === "" ||
    data.last_name === "" ||
    data.email_address === "" ||
    data.password === "" ||
    data.contact_number === ""
  ) {
    showError("Please fill out all fields.");
  } else {
    if (data.password === data.password2) {
      //make network call

      register(data).then((result) => {
        console.log(result.response.result);

        if (result.response.result !== "Inserted successfully.") {
          showError(result.response.result);
        } else if (result.response.result === "Inserted successfully.") {
          showSuccess("Registered successfully, you will be redirected soon.");
          localStorage.setItem("creator_email", email_address);
          move();
        }
      });
    } else {
      showError("Please passwords do not match");
    }
  }
}

//async function for login
async function login(login_data) {
  let result;

  try {
    result = await $.ajax({
      url: "./api/",
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify(login_data),
      beforeSend: () => {
        $("#loader").show();
        console.log("About to initiate login request");
      },

      complete: function (login_data) {
        // Hide image container
        $("#loader").hide();
      },
    });

    return result;
  } catch (error) {
    console.log(error);
  }
}

//actual function called when login is clicked.
function invokeLog() {
  //get field values here
  const login_email = $("#login_email").val();
  const login_password = $("#login_password").val();

  const login_data = {
    service: "signIn",
    email_address: login_email,
    password: login_password,
  };

  //check that values are not empty

  if (login_data.email_address === "" || login_data.password === "") {
    showError("Please fill out all fields.");
  } else {
    //make network call

    login(login_data).then((result) => {
      console.log(result.response.result);

      if (result.response.result !== "success") {
        showError(result.response.result);
      } else if (result.response.result === "success") {
        showSuccess("Logged in successfully, you will be redirected soon.");

        localStorage.setItem("creator_email", login_email);
        move();
      }
    });
  }
}

function showError(error) {
  $("#feedback").html(error);
  $("#feedback").show();
}

function showSuccess(success) {
  $("#feedback").removeClass("alert-danger");
  $("#feedback").addClass("alert-success");
  $("#feedback").html(success);
  $("#feedback").show();
}

function showPaymentDialogue() {
  //get the modal
  var modal = document.getElementById("paymentModal");

  //get the start buttons
  var proBtn = document.getElementById("start-pro");
  var premiumBtn = document.getElementById("start-premium");
  var basicBtn = document.getElementById("start-basic");
  var freeBtn = document.getElementById("start-free");

  // Get the <span> element that closes the modal
  var span = document.getElementsByClassName("close")[0];
  var cancel = document.getElementsByClassName("cancel")[0];

  // When the user clicks on the button, open the modal
  proBtn.onclick = function () {
    modal.style.display = "block";
  };

  premiumBtn.onclick = function () {
    modal.style.display = "block";
  };

  basicBtn.onclick = function () {
    modal.style.display = "block";
  };

  freeBtn.onclick = function () {
    modal.style.display = "block";
  };

  // When the user clicks on <span> (x), close the modal
  span.onclick = function () {
    modal.style.display = "none";
  };

  cancel.onclick = function () {
    modal.style.display = "none";
  };

  window.onclick = function (event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  };
}

showPaymentDialogue();
