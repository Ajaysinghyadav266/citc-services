/* ==========================================================================
   VM Request Application — client-side validation
   Path in Laravel app: resources/js/vm-request.js
   Mirrors the server-side rules in App\Http\Controllers\VmRequestController
   ========================================================================== */

(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", init);

  function init() {
    var form = document.getElementById("vmRequestForm");
    if (!form) return;

    var statusPill  = document.getElementById("vmreqStatus");
    var confirmWrap = document.getElementById("confirmWrap");
    var confirmBox  = document.getElementById("i_confirm");
    var saveBtn     = document.getElementById("saveBtn");
    var toast       = document.getElementById("vmreqToast");

    // Declarative validation rules per field name.
    var rules = {
      institute_email: {
        required: true,
        test: function (v) { return /^[^\s@]+@[^\s@]+\.[a-zA-Z]{2,}$/.test(v); },
        message: "Enter a valid institute email address."
      },
      department_name: {
        required: true,
        message: "Department / Section / Centre name is required."
      },
      owner_name: {
        required: true,
        test: function (v) { return v.trim().length >= 3; },
        message: "Enter the full name of the owner (min 3 characters)."
      },
      mobile_number: {
        required: true,
        test: function (v) { return /^[6-9]\d{9}$/.test(v); },
        message: "Enter a valid 10-digit mobile number."
      },
      employee_category: {
        required: true,
        message: "Select an employee category."
      },
      operating_system: {
        required: true,
        message: "Select an operating system."
      },
      vm_expiry_date: {
        required: true,
        test: function (v) {
          var picked = new Date(v + "T00:00:00");
          var today  = new Date(); today.setHours(0, 0, 0, 0);
          return !isNaN(picked) && picked > today;
        },
        message: "Choose a valid expiry date in the future."
      },
      os_type: {
        required: true,
        message: "Select an OS type."
      },
      purpose_usage: {
        required: true,
        test: function (v) { return v.trim().length >= 10; },
        message: "Describe the purpose in at least 10 characters."
      },
      cpu_cores: {
        required: true,
        test: function (v) { return Number(v) >= 1 && Number(v) <= 64; },
        message: "Cores must be a number between 1 and 64."
      },
      ram_gb: {
        required: true,
        test: function (v) { return Number(v) >= 1 && Number(v) <= 512; },
        message: "RAM must be a number between 1 and 512 GB."
      },
      justification: {
        required: true,
        test: function (v) { return v.trim().length >= 10; },
        message: "Provide a justification of at least 10 characters."
      },
      hard_disk_gb: {
        required: true,
        test: function (v) { return Number(v) >= 1 && Number(v) <= 10000; },
        message: "Hard disk must be a number between 1 and 10000 GB."
      },
      license_type: {
        required: true,
        message: "Select a license type."
      },
      software_list: {
        required: true,
        test: function (v) { return v.trim().length >= 3; },
        message: "List at least one software/package to install."
      },
      sub_domain: {
        required: false,
        test: function (v) { return v === "" || /^[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*$/.test(v); },
        message: "Enter a valid sub domain (letters, numbers, dots, hyphens)."
      },
      ssl_configuration: {
        required: true,
        message: "Select whether SSL configuration is required."
      }
    };

    // Wire up live validation.
    Object.keys(rules).forEach(function (name) {
      var input = form.elements[name];
      if (!input) return;
      var evt = (input.tagName === "SELECT") ? "change" : "input";
      input.addEventListener(evt, function () { validateField(name); });
      input.addEventListener("blur", function () { validateField(name); });
    });

    confirmBox.addEventListener("change", function () {
      confirmWrap.classList.remove("is-invalid");
    });

    form.addEventListener("submit", function (e) {
      setUnsaved();

      var allValid = true;
      Object.keys(rules).forEach(function (name) {
        if (!validateField(name)) allValid = false;
      });

      if (!confirmBox.checked) {
        confirmWrap.classList.add("is-invalid");
        allValid = false;
      }

      if (!allValid) {
        e.preventDefault();
        showToast("Please fix the highlighted fields.", "error");
        var firstError = form.querySelector(".field.is-invalid, .vmreq-confirm.is-invalid");
        if (firstError) firstError.scrollIntoView({ behavior: "smooth", block: "center" });
        return;
      }

      // Valid: let the browser POST natively to VmRequestController@store
      // (Laravel handles CSRF + authoritative server-side validation there).
      document.querySelectorAll('button[type="submit"]').forEach(function(btn){
      btn.disabled = true;
      btn.innerHTML = "Saving...";
    });

showToast("⏳ Saving your VM Request...", "loading");
    });

    function validateField(name) {
      var rule  = rules[name];
      var input = form.elements[name];
      if (!rule || !input) return true;

      var fieldWrap = input.closest(".field");
      var errorEl   = fieldWrap ? fieldWrap.querySelector(".field-error") : null;
      var value     = (input.value || "").trim();

      var isValid = true;
      var message = "";

      if (rule.required && value === "") {
        isValid = false;
        message = rule.message || "This field is required.";
      } else if (value !== "" && rule.test && !rule.test(value)) {
        isValid = false;
        message = rule.message || "This value is not valid.";
      }

      if (fieldWrap) {
        fieldWrap.classList.toggle("is-invalid", !isValid);
      }
      if (errorEl) {
        errorEl.textContent = isValid ? "" : message;
      }

      return isValid;
    }

    function setUnsaved() {
      statusPill.textContent = "• Not Saved";
      statusPill.classList.remove("is-saved");
    }
  }
   window.showToast = function(message, type) {

      var toast = document.getElementById("vmreqToast");

      toast.textContent = message;

      toast.className = "vmreq-toast";

      if(type === "success"){
          toast.classList.add("is-success");
      }
      else if(type === "error"){
          toast.classList.add("is-error");
      }
      else{
          toast.classList.add("is-loading");
      }

      toast.classList.add("is-visible");

      if(type !== "loading"){
          clearTimeout(window.showToast._t);

          window.showToast._t = setTimeout(function () {
              toast.classList.remove("is-visible");
          },3000);
      }
  };
})();
