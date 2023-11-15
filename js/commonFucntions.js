var our_costs_rows = document.getElementById("our_costs_row");

var btnRow = document.getElementById("btnRow");

function addFields() {
  var divElmNewRow = document.createElement("div");
  divElmNewRow.setAttribute("class", "row");

  var divElmField = document.createElement("div");
  divElmField.setAttribute("class", "col-md-5");

  var divElmField1 = document.createElement("div");
  divElmField1.setAttribute("class", "col-md-5");

  var divElmFieldSymbol = document.createElement("div");
  divElmFieldSymbol.setAttribute("class", "col-md-2");

  var add_more_fields = document.getElementById("add_more_fields");

  var newField = document.createElement("input");
  newField.setAttribute("type", "text");
  newField.setAttribute("name", "our_costs_desc[]");
  newField.setAttribute("class", "form-control");
  newField.setAttribute("placeholder", "Description (ANP Costs - XYZ)");
  divElmField.appendChild(newField);

  var newField1 = document.createElement("input");
  newField1.setAttribute("type", "currency");
  newField1.setAttribute("name", "our_costs[]");
  newField1.setAttribute("class", "form-control");
  newField1.setAttribute("placeholder", "£0.00");
  divElmField1.appendChild(newField1);

  var minusSign = document.createElement("span");
  minusSign.setAttribute("type", "button");
  minusSign.setAttribute("onclick", "removeField(this);");
  minusSign.setAttribute("class", "btn btn-danger");
  minusSign.appendChild(document.createTextNode("-"));

  var plusSign = document.createElement("span");
  plusSign.setAttribute("type", "button");
  plusSign.setAttribute("onclick", "addFields();");
  plusSign.setAttribute("class", "btn btn-primary");
  plusSign.setAttribute("id", "add_more_fields");
  plusSign.appendChild(document.createTextNode("+"));
  divElmFieldSymbol.appendChild(plusSign);

  parentNode = add_more_fields.parentNode;
  parentNode.replaceChild(minusSign, add_more_fields);

  divElmNewRow.appendChild(divElmField);
  divElmNewRow.appendChild(divElmField1);
  divElmNewRow.appendChild(divElmFieldSymbol);
  our_costs_rows.appendChild(divElmNewRow);
}

function removeField(minusElm) {
  minusElm.parentNode.parentNode.remove();
}
var undertakingsrows = document.getElementById("our_costs_row");
function addFieldsUndertakings(text) {
  var divElmNewRow = document.createElement("div");
  divElmNewRow.setAttribute("class", "row");

  var divElmField = document.createElement("div");
  divElmField.setAttribute("class", "col-md-5");

  var divElmFieldSymbol = document.createElement("div");
  divElmFieldSymbol.setAttribute("class", "col-md-2");

  var add_more_fields = document.getElementById("add_more_fields");

  var newField = document.createElement("input");
  newField.setAttribute("type", "text");
  newField.setAttribute("name", "undertakings[]");
  newField.setAttribute("class", "form-control");
  newField.setAttribute("placeholder", text);
  divElmField.appendChild(newField);

  var minusSign = document.createElement("span");
  minusSign.setAttribute("type", "button");
  minusSign.setAttribute("onclick", "removeField(this);");
  minusSign.setAttribute("class", "btn btn-danger");
  minusSign.appendChild(document.createTextNode("-"));

  var plusSign = document.createElement("span");
  plusSign.setAttribute("type", "button");
  plusSign.setAttribute("onclick", "addFields();");
  plusSign.setAttribute("class", "btn btn-primary");
  plusSign.setAttribute("id", "add_more_fields");
  plusSign.appendChild(document.createTextNode("+"));
  divElmFieldSymbol.appendChild(plusSign);

  parentNode = add_more_fields.parentNode;
  parentNode.replaceChild(minusSign, add_more_fields);

  divElmNewRow.appendChild(divElmField);
  divElmNewRow.appendChild(divElmFieldSymbol);
  our_costs_rows.appendChild(divElmNewRow);
}

function test() {
  var idleTimeThreshold = 60 * 1000;

  var idleTimer;

  // Function to reset the idle timer
  function resetIdleTimer() {
    clearInterval(idleTimer);
    idleTimer = setTimeout(expireSession, idleTimeThreshold);
  }

  // Function to handle user activity
  function handleUserActivity() {
    resetIdleTimer();
  }

  // Function to extend the session
  function extendSession() {
    handleUserActivity();
    hideExtendButton(); // Hide the button after extending the session
  }

  // Function to expire the session
  function expireSession() {
    console.log("sessionexpired");
    clearTimeout(idleTimer);
    showExtendButton();
  }

  function logOut() {
    window.location.href = "logout.php?SessionExpired=1";
  }

  function showExtendButton() {
    console.log("extendButtonCalled");

    // Create a div for the container
    var containerDiv = document.createElement("div");
    containerDiv.id = "extendContainer";
    containerDiv.className = "shadow p-3 mb-5 bg-white rounded";
    containerDiv.style.position = "fixed";
    containerDiv.style.top = "50%";
    containerDiv.style.left = "50%";
    containerDiv.style.transform = "translate(-50%, -50%)";
    containerDiv.style.zIndex = "9999";
    containerDiv.style.display = "flex";
    containerDiv.style.flexDirection = "column";
    containerDiv.style.alignItems = "center";
    containerDiv.style.textAlign = "center";

    // information div
    var infoDiv = document.createElement("div");
    infoDiv.id = "infoDiv";
    infoDiv.style.color = "black";
    infoDiv.style.fontSize = "18px";
    infoDiv.style.fontFamily = "Arial";
    infoDiv.className = "p-3";
    infoDiv.textContent =
      "On reload of this page your profile will be automatically logged out";

    // Create the timer div
    var timerDiv = document.createElement("div");
    timerDiv.id = "timerDiv";
    timerDiv.style.color = "black";
    timerDiv.style.fontSize = "18px";
    timerDiv.style.fontFamily = "Arial";
    timerDiv.className = "mb-3";

    // Create a container for the buttons
    var buttonsContainer = document.createElement("div");
    buttonsContainer.style.display = "flex";

    // Create the extend button
    var extendButton = document.createElement("button");
    extendButton.textContent = "Extend";
    extendButton.className = "m-1 btn btn-primary";
    extendButton.addEventListener("click", extendSession);

    // Create the log out button
    var logOutButton = document.createElement("button");
    logOutButton.textContent = "Log Out";
    logOutButton.className = "m-1 btn btn-primary";
    logOutButton.addEventListener("click", logOut);

    // Append the timer div and buttons to the container
    containerDiv.appendChild(infoDiv);
    containerDiv.appendChild(timerDiv);
    buttonsContainer.appendChild(extendButton);
    buttonsContainer.appendChild(logOutButton);
    containerDiv.appendChild(buttonsContainer);

    document.body.appendChild(containerDiv);

    // Apply inline CSS styles to create the blurred background
    var blurredBackground = document.createElement("div");
    blurredBackground.className = "blurred-background";
    blurredBackground.style.position = "fixed";
    blurredBackground.style.top = "0";
    blurredBackground.style.left = "0";
    blurredBackground.style.width = "100%";
    blurredBackground.style.height = "100%";
    blurredBackground.style.backgroundColor = "rgba(0, 0, 0, 0.5)";
    blurredBackground.style.backdropFilter = "blur(5px)";

    document.body.appendChild(blurredBackground);

    // Start the timer for an additional 15 minutes
    var timerDuration = 15 * 60 * 1000; // 15 minutes in milliseconds
    var endTime = Date.now() + timerDuration;

    var timerInterval = setInterval(function () {
      var remainingTime = endTime - Date.now();
      var minutes = Math.floor(remainingTime / 60000);
      var seconds = Math.floor((remainingTime % 60000) / 1000);

      // Display the timer in the timerDiv
      timerDiv.textContent =
        "Extend session for " + minutes + "m " + seconds + "s";

      if (remainingTime <= 0) {
        clearInterval(timerInterval);
        logOut();
      }
    }, 1000); // Update the timer every second
  }

  function hideExtendButton() {
    resetIdleTimer();

    var extendContainer = document.getElementById("extendContainer");
    if (extendContainer) {
      extendContainer.parentNode.removeChild(extendContainer);

      var blurredBackground = document.querySelector(".blurred-background");
      if (blurredBackground) {
        blurredBackground.parentNode.removeChild(blurredBackground);
      }

      // Remove the blur background style using JavaScript
      document.body.style.filter = "none";
    }
  }

  // Attach event listeners for mouse movement and keyboard input
  document.addEventListener("mousemove", handleUserActivity);
  document.addEventListener("keypress", handleUserActivity);

  window.onbeforeunload = function () {
    var extendContainer = document.getElementById("extendContainer");
    if (extendContainer) {
      window.setTimeout(function () {
        window.location = "Logout.php";
      }, 0);
      window.onbeforeunload = null;
    }
  };

  // Start the idle timer
  resetIdleTimer();
}
