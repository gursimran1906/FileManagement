<?php
include('Connect.php');



// <div class="p-2 bd-highlight">            
//     <a class="btn btn-primary nav-item p-2 bd-highlight" href="Reports.php" role="button" id="ReportsButton" target="_blank">Reports</a>
// </div>

function writeNavBar()
{
    $navBarHtml = '
    <div>
   
    <nav class="  d-flex flex-row bg-light">
            
            <div class="d-flex justify-content-end highlight mb-3">
                <a class="p-2 " href="index.php" style="background-color: transparent !important;">
                    <img src="images/NewLogo.png" alt="ANP Logo" width="200" height="75" >
                </a>
                
                <div class="p-2 bd-highlight">            
                    <a class="btn btn-primary nav-item p-2 bd-highlight" href="OpenFilePage.php" role="button" id="OpenNewFileButton" target="_blank">Open New File</a>
                </div>
                
                <div class="p-2 bd-highlight">            
                    <a class="btn btn-primary nav-item p-2 bd-highlight" href="PendingSlipsPage.php" role="button" target="_blank">Download Pending Slips</a>
                </div>
                
                ';

    if (isset($_SESSION['userInitials'])) {
        $userInitials = $_SESSION['userInitials'];

        if ($userInitials == 'ND' || $userInitials == 'JP' || $userInitials == 'GB') {
            $navBarHtml = $navBarHtml . ' <div class="p-2 bd-highlight">            
                                <a class="btn btn-primary nav-item p-2 bd-highlight" href="MonthlySlipsReport.php" role="button">Audit Monthly Slips</a>
                            </div>';
        }
        $navBarHtml = $navBarHtml . ' <div class="p-2 bd-highlight">            
                                <a class="btn btn-primary nav-item p-2 bd-highlight" href="Logout.php" role="button">Logout</a>
                            </div>';
    }
    $navBarHtml = $navBarHtml . '
                
            </div>
        </nav>
    </div>';
    echo $navBarHtml;
}
function writeFooter()
{
    $footerHTML = ' <div class=" mt-auto p-3 mb-2 bg-light text-dark">
                    <footer class="mt-auto footer ">
                    <div class="footer-copyright">
                    <div class="container">
                    <div class="row">
                    <div class="col-md-12 text-center">
                    <p>Copyright ANP Solicitors Limited © 2023. All rights reserved.</p>
                    </div>
                    </div>
                    </div>
                    </div>

                    </footer>
                    </div>';
    echo $footerHTML;
}

function sessionCheck()
{
    echo "<script> 
    // Set the idle time threshold in milliseconds (15 minutes)
    var idleTimeThreshold = 30 * 60 * 1000;
    
    var idleTimer;
    var lastActivityTime = new Date().getTime();
   
    // Function to reset the idle timer
    function resetIdleTimer() {
        var extendContainer = document.getElementById('extendContainer'); 
        if (extendContainer == null) {
        clearTimeout(idleTimer);
        idleTimer = setTimeout(expireSession, idleTimeThreshold);}
    }
    
    // Function to handle user activity
    function handleUserActivity() {
        lastActivityTime = new Date().getTime();
        resetIdleTimer();
        
    }
    
    // Function to extend the session
    function extendSession() {
        handleUserActivity();
        hideExtendButton(); // Hide the button after extending the session
    }
    
    // Function to expire the session
    function expireSession() {
        showExtendButton();
    }
    
   function logOut(){
    window.location.href = 'Logout.php';
   }
   
    function showExtendButton() {
        
        clearTimeout(idleTimer);

        // Create a div for the container
        var containerDiv = document.createElement('div');
        containerDiv.id = 'extendContainer';
        containerDiv.className = 'shadow p-3 mb-5 bg-white rounded';
        containerDiv.style.position = 'fixed';
        containerDiv.style.top = '50%';
        containerDiv.style.left = '50%';
        containerDiv.style.transform = 'translate(-50%, -50%)';
        containerDiv.style.zIndex = '9999';
        containerDiv.style.display = 'flex';
        containerDiv.style.flexDirection = 'column';
        containerDiv.style.alignItems = 'center';
        containerDiv.style.textAlign = 'center';
        
        // information div
        // var infoDiv = document.createElement('div');
        // infoDiv.id = 'infoDiv';
        // infoDiv.style.color = 'black';
        // infoDiv.style.fontSize = '18px';
        // infoDiv.style.fontFamily = 'Arial';
        // infoDiv.className = 'p-3';
        // infoDiv.textContent = 'On reload of this page, your profile will be automatically logged out';

        // Create the timer div
        var timerDiv = document.createElement('div');
        timerDiv.id = 'timerDiv';
        timerDiv.style.color = 'black';
        timerDiv.style.fontSize = '18px';
        timerDiv.style.fontFamily = 'Arial';
        timerDiv.className = 'mb-3';
        
        // Create a container for the buttons
        var buttonsContainer = document.createElement('div');
        buttonsContainer.style.display = 'flex';
        
        // Create the extend button
        var extendButton = document.createElement('button');
        extendButton.textContent = 'Extend';
        extendButton.className = 'm-1 btn btn-primary';
        extendButton.addEventListener('click', extendSession);
        
        // Create the log out button
        var logOutButton = document.createElement('button');
        logOutButton.textContent = 'Log Out';
        logOutButton.className = 'm-1 btn btn-primary';
        logOutButton.addEventListener('click', logOut);
        
        // Append the timer div and buttons to the container
        // containerDiv.appendChild(infoDiv);
        containerDiv.appendChild(timerDiv);
        buttonsContainer.appendChild(extendButton);
        buttonsContainer.appendChild(logOutButton);
        containerDiv.appendChild(buttonsContainer);
        
        document.body.appendChild(containerDiv);
        
        
        // Apply inline CSS styles to create the blurred background
        var blurredBackground = document.createElement('div');
        blurredBackground.className = 'blurred-background';
        blurredBackground.style.position = 'fixed';
        blurredBackground.style.top = '0';
        blurredBackground.style.left = '0';
        blurredBackground.style.width = '100%';
        blurredBackground.style.height = '100%';
        blurredBackground.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
        blurredBackground.style.backdropFilter = 'blur(5px)';
      
        document.body.appendChild(blurredBackground);
      
        // Start the timer for an additional 15 minutes
        var timerDuration =  15 * 60 * 1000; // 15 minutes in milliseconds
        var endTime = Date.now() + timerDuration;
      
        var timerInterval = setInterval(function() {
          var remainingTime = endTime - Date.now();
          var minutes = Math.floor(remainingTime / 60000);
          var seconds = Math.floor((remainingTime % 60000) / 1000);
      
          // Display the timer in the timerDiv
          timerDiv.textContent = 'Session expires in ' + minutes + 'm ' + seconds + 's';
      
          if (remainingTime <= 0) {
            clearInterval(timerInterval);
           logOut();
          }
        }, 1000); // Update the timer every second


      }
      
      
      
    

      function hideExtendButton() {
        var extendContainer = document.getElementById('extendContainer'); 
        if (extendContainer) {
          extendContainer.parentNode.removeChild(extendContainer);
      
          var blurredBackground = document.querySelector('.blurred-background');
          if (blurredBackground) {
            blurredBackground.parentNode.removeChild(blurredBackground);
          }
      
          // Remove the blur background style using JavaScript
          document.body.style.filter = 'none';
        }
        resetIdleTimer();
      }
      
    
    // Attach event listeners for mouse movement and keyboard input
    document.addEventListener('mousemove', handleUserActivity);
    document.addEventListener('keypress', handleUserActivity);
    
   
    window.onbeforeunload = function() { 
        var extendContainer = document.getElementById('extendContainer'); 
        if (extendContainer) {
        window.setTimeout(function () { 
            window.location = 'Logout.php';
        }, 0); 
            window.onbeforeunload = null; }
    }

    // Start the idle timer
    resetIdleTimer();
    </script>
    ";
}

function checkLogin()
{
    session_start();

    if (!isset($_SESSION['userInitials'])) {
        header("Location: Login.php");
    }
}
function writeNavBarErrPage()
{
    $navBarHtml = '
    <div>
    <nav class="  d-flex flex-row bg-none">
            
            <div class="d-flex justify-content-end highlight mb-3">
                <a class="p-2 " href="index.php" style="background-color: transparent !important;">
                    <img src="images/FinalLogo.png" alt="ANP Logo" width="200" height="75">
                </a>
            </div>
        </nav>
    </div>';
    echo $navBarHtml;
}

function initialsList($con, $selected, $showNewRate)
{


    $sql = 'SELECT * from users';
    $data = $con->query($sql);

    $users = array();


    if ($data->num_rows > 0) {

        while ($row = $data->fetch_assoc()) {
            $users[] = $row;
        }
    }

    foreach ($users as $u) {

        echo "<option ";
        if ($u['userInitials'] == $selected) {
            echo "selected";
        }
        echo  " >" . $u['userInitials'] . "</option>";
    }
    if ($showNewRate) {
        echo "<option value='TRN'>TR New</option>";
        echo "<option value='JPN'>JP New</option>";
    }
}
