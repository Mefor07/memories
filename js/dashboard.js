function toggleMenu(id) {
  //$("#feedback").removeClass("alert-danger");
  //$("#feedback").addClass("alert-success");

  var menus = [
    "memorial-plans",
    "create-memorial",
    "my-memorials",
    "about",
    "settings",
    "logout",
  ];
  for (let i = 0; i < menus.length; i++) {
    if (id === menus[i]) {
      $("#" + id).addClass("active");
      switchViews("#" + id);
    } else {
      $("#" + menus[i]).removeClass("active");
    }
  }

  console.log(id);
}

toggleMenu("memorial-plans");

//async function for getting first name
async function getFirstName(first_name_data) {
  let result;

  try {
    result = await $.ajax({
      url: "./api/",
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify(first_name_data),
      beforeSend: () => {
        $("#loader").show();
        console.log("About to initiate request");
      },

      complete: function (first_name_data) {
        // Hide image container
        $("#loader").hide();
      },
    });

    return result;
  } catch (error) {
    console.error(error);
  }
}

//actual function called when logged into dashboard.
function invokegetFirstName() {
  const first_name_data = {
    service: "getCreatorFirstName",
    email_address: localStorage.getItem("creator_email"),
  };

  //make network call

  getFirstName(first_name_data).then((result) => {
    console.log(result.response);

    document.getElementById("creator_name").innerHTML =
      "Hi " + result.response.result.first_name;
  });
}

invokegetFirstName();

//section-plans-container, form-section(button-container), memories-container

function switchViews(id) {
  switch (id) {
    case "#memorial-plans":
      $("#section-plans-container").css("display", "flex");
      $("#form-section").css("display", "none");
      $("#button-container").css("display", "none");
      $("#memories-container").css("display", "none");
      break;

    case "#create-memorial":
      $("#section-plans-container").css("display", "none");
      $("#form-section").css("display", "flex");
      $("#button-container").css("display", "flex");
      $("#memories-container").css("display", "none");
      break;

    case "#my-memorials":
      $("#section-plans-container").css("display", "none");
      $("#form-section").css("display", "none");
      $("#button-container").css("display", "none");
      $("#memories-container").css("display", "flex");
      break;

    case "#logout":
      localStorage.removeItem("creator_email");
      logout();
      break;
  }
}

function logout() {
  window.location.replace("index.html");
}

function validateUser() {
  const user = localStorage.getItem("creator_email");

  if (user === null || user === "") {
    //log user out
    logout();
  }
}
