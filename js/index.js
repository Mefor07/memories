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
