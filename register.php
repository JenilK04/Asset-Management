<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Registration</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(120deg, #e0f7fa, #e3f2fd);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            width: 100%;
            max-width: 900px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }

        .register-card h3 {
            font-weight: 600;
            color: #0d6efd;
        }

        label {
            font-weight: 500;
        }

        .input-group-text {
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="register-card">
    <h3 class="text-center mb-4">Client Registration</h3>

    <!-- Alert for password mismatch -->
    <div class="alert alert-danger d-none" id="passwordAlert">
        Password and Confirm Password do not match!
    </div>

    <form action="register_process.php" method="POST" class="needs-validation" novalidate onsubmit="return checkPasswordMatch();">

        <!-- Row 1 -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control" required>
                <div class="invalid-feedback">Please enter your full name.</div>
            </div>
            <div class="col-md-6">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" required>
                <div class="invalid-feedback">Enter a valid email.</div>
            </div>
        </div>

        <!-- Row 2 -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Phone Number</label>
                <input type="tel" name="phone" class="form-control" pattern="[0-9]{10}" required>
                <div class="invalid-feedback">Enter a valid 10-digit phone number.</div>
            </div>
            <div class="col-md-6">
                <label>Date of Birth</label>
                <input type="date" name="dob" class="form-control" required>
                <div class="invalid-feedback">Select your date of birth.</div>
            </div>
        </div>

        <!-- Row 3 -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Aadhaar / PAN</label>
                <input type="text" name="aadhaar_pan" class="form-control" required>
                <div class="invalid-feedback">Enter Aadhaar or PAN.</div>
            </div>
            <div class="col-md-6">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="2" required></textarea>
                <div class="invalid-feedback">Enter your address.</div>
            </div>
        </div>

        <!-- Row 4 -->
        <div class="row mb-4">
            <div class="col-md-6">
                <label>Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" minlength="6" required>
                    <span class="input-group-text" onclick="togglePassword('password', this)">
                        <i class="bi bi-eye"></i>
                    </span>
                    <div class="invalid-feedback">Password must be at least 6 characters.</div>
                </div>
            </div>

            <div class="col-md-6">
                <label>Confirm Password</label>
                <div class="input-group">
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" minlength="6" required>
                    <span class="input-group-text" onclick="togglePassword('confirm_password', this)">
                        <i class="bi bi-eye"></i>
                    </span>
                    <div class="invalid-feedback">Please confirm your password.</div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Register
        </button>

        <p class="text-center mt-3">
            Already have an account?
            <a href="login.php" class="text-decoration-none">Login</a>
        </p>

    </form>
</div>

<!-- Bootstrap Validation -->
<script>
(function () {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

<!-- Password Toggle + Match Check -->
<script>
function togglePassword(fieldId, el) {
    const input = document.getElementById(fieldId);
    const icon = el.querySelector('i');

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
    }
}

function checkPasswordMatch() {
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm_password").value;
    const alertBox = document.getElementById("passwordAlert");

    if (password !== confirmPassword) {
        alertBox.classList.remove("d-none");
        return false;
    } else {
        alertBox.classList.add("d-none");
        return true;
    }
}
</script>

</body>
</html>
