<?php session_start();?>
<?php include('server/databaseconn.php');
  $tools_result = $conn->query("SELECT Tool_ID, Name FROM tool");
?>
<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expert Registeration</title>
    <link rel="icon" href="./assets/img/icon.png">
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/icons/all.min.css">
    <link rel="stylesheet" href="./css/login.css">
<!-- For the multi-select tools section-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
     <style>
      .ex {
    background-color: var(--maincolor) !important;
    font-size: 25px !important;
    font-weight: bold !important;
    color: white !important;
    border: none !important;
    padding: 5px !important;
     }  
     .ex:hover{
      background-color:rgb(102, 78, 56) !important
     }
     .input-wrapper {
  position: relative;
  margin-bottom: 2rem; 
  width: 35%;
}
.bio-wrapper {
  width: 90% !important;  
  /* max-width: 100%; */
}
.cv-wrapper {
  width: 100%;
  max-width: 100%;
}

.error-message {
  position: absolute;
  bottom: -1.2rem;
  left: 0;
  font-size: 13px;
  color: red;
  visibility: hidden;
}

.error-message.active {
  visibility: visible;
}

input.invalid,
select.invalid,
textarea.invalid {
  border: 2px solid red;
}
     </style>
<!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</script>


</head>
<body>
  <!--To Display a user-friendly notification -->
  <?php
    if (isset($_SESSION['message'])) {
        $type = $_SESSION['message_type']; // success, error, etc.
        echo "<div class='alert $type'>{$_SESSION['message']}</div>";
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
  ?>
  <!-- To display javascript error message-->
  <div id="error-message" class="alert error" style="display: none;"></div>
  <div class="back-home">
    <a href="index.php"><i class="fa-solid fa-arrow-up-right-from-square fa-rotate-270"></i></a>
  </div>
  <!-- <h1>AI Nexus</h1> -->
  <div class="hero2">
    <h1 class="ms-5">
      Expert Registration
   </h1>
    <!-- <button> <a href="index.php">Back to home page</a></button> -->
  </div>
    <div class="container mt-4">
        <div class="col-lg-10">
            <form action= "server/server_1.php" method="post" enctype="multipart/form-data">
                <div class="d-lg-flex d-sm-inline" id="consult-input">
                    <input type="text" placeholder=" Name " class="col-lg-12 col-sm-12  mb-3" id="expert_name" name="expert_name">
                     <span class="error-message">This field is required</span>
                    <input type="number" placeholder=" National ID " class="col-lg-12 col-sm-12  mb-3 ms-3" id="regist-expertid" name="national_id" >
                    <span class="error-message">This field is required</span>
                  </div>
                  <div class="d-lg-flex d-sm-inline" id="consult-input">
                    <input type="text" placeholder=" Phone " class="col-lg-12 col-sm-12  mb-3" id="regist-expertphone" name="expert_phone" >
                     <span class="error-message">This field is required</span>
                    <input type="text" placeholder="2 Street Name, City, State  " class="col-lg-12 col-sm-12  mb-3 ms-3" id="regist-expertaddress" name="expert_address">
                     <span class="error-message">This field is required</span>
                  </div>
                  <div class="d-lg-flex d-sm-inline" id="consult-input">
                    <input type="password" placeholder=" Password " class="col-lg-12 col-sm-12  mb-3" id="expert_password" name="expert_password">
                     <span class="error-message">This field is required</span>
                    <input type="email" placeholder="Email" class=" col-lg-12 col-sm-12  mb-3 ms-3" id="regist-expertemail" name="expert_email" >
                     <span class="error-message">This field is required</span>
                  </div>
                  <div class="d-lg-flex d-sm-inline" id="consult-input">
                    <div class="col-lg-12 col-sm-12 mb-3 me-3">
                    <select name="expert_gender" class="form-control">
                       <option value="" disabled selected>Select Gender</option>
                       <option value="male">Male</option>
                       <option value="female">Female</option>
                     </select>
                    </div>
                  </div>

                  <!--To allow multi-selection from available tools -->

                  <label class="input-wrap"for="tools">You are an expert in:</label>
                  <select class="col-lg-12 col-sm-12"  name="tools[]" id="tools" multiple class="form-control">
                      <?php while ($tool = $tools_result->fetch_assoc()) { ?>
                          <option value="<?= $tool['Tool_ID']; ?>"><?= htmlspecialchars($tool['Name']); ?></option>
                      <?php } ?> 
                  </select>
                  <br>
                  <!-- Selecting schedule input-->
                   <div id="schedule-container">
                    <div class="schedule-slot">
                      <label>Day:</label>
                      <select name="day[]">
                        <option>Monday</option>
                        <option>Tuesday</option>
                        <option>Wednesday</option>
                        <option>Thursday</option>
                        <option>Friday</option>
                        <option>Saturday</option>
                        <option>Sunday</option>
                      </select>

                      <label>Start Time:</label>
                      <input type="time" name="start_time[]" required>

                      <label>End Time:</label>
                      <input type="time" name="end_time[]" required>
                      <button type="button" class="remove-slot" onclick="removeScheduleSlot(this)">Remove</button>
                    </div>
                  </div>
                  <button type="button" onclick="addScheduleSlot()">Add Another Day</button>
                  <br>
                  <br>

                  <div class="d-lg-10 " id="consult-input">
                    <textarea class="bio-input" placeholder="Add a brief bio" class="col-lg-12 col-sm-12 expert_discription" id="expert_discription" name="expert_discription" ></textarea>
                  </div>

                  <div class="d-lg-block d-sm-inline" id="cv">
                    <label class=" justify-content-center ms-3 cv-input">Upload Your CV</label>
                              <input type="file" class=" bio-input col-lg-3 col-sm-12 mb-3" name="expert_cv" >
                    </div>

                    <div class="regist2 col-lg-10">
                         <!-- <BUtton>Register</BUtton> -->
                        <button class="col-lg-8 justify-content-center ex" name="button_submit_expert" type="submit">Register</button> <br>
                        <label class=" justify-content-center log-expertlink">you have an account ?<a href="expertLOGIN.php">Log in</a></label>
                    </div>
            </form>
        </div>
    </div>
        <!--A Script to support the multi-select section for tools selection -->
    <!-- A Script to support adding/deleting time slots-->
    <script>
    function addScheduleSlot() {
      const container = document.getElementById('schedule-container');
      const firstSlot = container.children[0];
      const newSlot = firstSlot.cloneNode(true);

      // Clear the time fields in the new slot
      newSlot.querySelector('input[name="start_time[]"]').value = '';
      newSlot.querySelector('input[name="end_time[]"]').value = '';

      container.appendChild(newSlot); 
}

    function removeScheduleSlot(button) {
      const container = document.getElementById('schedule-container');
      const slot = button.closest('.schedule-slot');

      // Only remove if more than one slot remains
      if (container.children.length > 1) {
        container.removeChild(slot);
      } else {


        // Optionally show a styled message in the DOM
        const msgBox = document.getElementById('error-message');
        if (msgBox) {
          msgBox.textContent = "You need to select at least one time slot.";
          msgBox.style.display = 'block';
      }
    }
}
  $(document).ready(function() {
    $('#tools').select2({
      placeholder: "Select tools..",
      width: '50%',
      maximumSelectionLength: 3
    });
  });
 document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");

  const inputs = [
    { name: "expert_name", type: "text", required: true },
    { name: "national_id", type: "number", required: true, length: 14 },
    { name: "expert_phone", type: "text", required: true, maxLength: 11 },
    { name: "expert_address", type: "text", required: true },
    { name: "expert_password", type: "password", required: true },
    { name: "expert_email", type: "email", required: true },
    { name: "expert_gender", type: "select", required: true },
    { name: "tools[]", type: "multi", required: false },
    { name: "expert_discription", type: "textarea", required: true, minLength: 100 },
    { name: "expert_cv", type: "file", required: true }
  ];

  inputs.forEach(item => {
    const field = form.querySelector(`[name="${item.name}"]`) || form.querySelector(`[name="${item.name}[]"]`);
    if (field) {
      let wrapper = field.parentNode;

      if (!wrapper.classList.contains("input-wrapper")) {
        const newWrapper = document.createElement("div");
        newWrapper.classList.add("input-wrapper");

       if (item.name === "expert_discription") {
          newWrapper.classList.add("bio-wrapper");
          } else if (item.name === "expert_cv") {
           newWrapper.classList.add("cv-wrapper");
         }


        field.parentNode.insertBefore(newWrapper, field);
        newWrapper.appendChild(field);
        wrapper = newWrapper;
      }
       if (!wrapper.classList.contains("input-wrapper")) {
        const newWrapper = document.createElement("div");
        newWrapper.classList.add("input-wrapper");

        if (item.name === "expert_cv") {
          newWrapper.classList.add("bio-wrapper");
        }

        field.parentNode.insertBefore(newWrapper, field);
        newWrapper.appendChild(field);
        wrapper = newWrapper;
      }
      let errorSpan = wrapper.querySelector(".error-message");
      if (!errorSpan) {
        errorSpan = document.createElement("span");
        errorSpan.className = "error-message";
        wrapper.appendChild(errorSpan);
      }

      field.addEventListener("blur", () => validateField(field, item));
      field.addEventListener("input", () => validateField(field, item));
      if (item.type === "select" || item.type === "multi") {
        field.addEventListener("change", () => validateField(field, item));
      }
    }
  });

  form.addEventListener("submit", function (e) {
    let isValid = true;
    inputs.forEach(item => {
      const field = form.querySelector(`[name="${item.name}"]`) || form.querySelector(`[name="${item.name}[]"]`);
      if (field && !validateField(field, item)) {
        isValid = false;
      }
    });

    if (!isValid) {
      e.preventDefault();
      const msgBox = document.getElementById("error-message");
      msgBox.textContent = "Please fill all required fields correctly.";
      msgBox.style.display = "block";
    }
  });

  function validateField(field, rules) {
    const errorSpan = field.closest('.input-wrapper')?.querySelector('.error-message');
    let valid = true;
    const value = field.value?.trim();

    if (rules.required && (!value || (rules.type === "select" && field.selectedIndex === 0))) {
      setError("This field is required.");
    } else if (rules.type === "email" && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
      setError("Please enter a valid email.");
    } else if (rules.name === "national_id" && value.length !== 14) {
      setError("National ID must be 14 digits.");
    } else if (rules.name === "expert_phone" && value.length !== 11) {
      setError("Phone number must be exactly 11 digits.");
    } else if (rules.name === "expert_discription" && value.length < rules.minLength) {
      setError(`Bio must be at least ${rules.minLength} characters.`);
    } else {
      clearError();
    }

    function setError(message) {
      if (errorSpan) {
        errorSpan.textContent = message;
        errorSpan.classList.add("active");
      }
      field.classList.add("invalid");
      valid = false;
    }

    function clearError() {
      if (errorSpan) {
        errorSpan.textContent = "";
        errorSpan.classList.remove("active");
      }
      field.classList.remove("invalid");
    }

    return valid;
  }
});

</script>
</body>
</html>