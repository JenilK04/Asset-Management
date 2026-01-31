<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

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

        .login-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }

        .login-card h3 {
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

<div class="login-card">
    <h3 class="text-center mb-4">Login</h3>

    <!-- Login error alert (shown if ?error=1) -->
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            Invalid email or password.
        </div>
    <?php endif; ?>

    <!-- Login Form -->
    <form action="login_process.php" method="POST" class="needs-validation" novalidate>

        <!-- Email -->
        <div class="mb-3">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" required>
            <div class="invalid-feedback">Please enter a valid email.</div>
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label>Password</label>
            <div class="input-group">
                <input type="password" id="password" name="password" class="form-control" required>
                <span class="input-group-text" onclick="togglePassword()">
                    <i class="bi bi-eye"></i>
                </span>
                <div class="invalid-feedback">Please enter your password.</div>
            </div>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-primary w-100">
            Login
        </button>

        <p class="text-center mt-3">
            Don’t have an account?
            <a href="register.php" class="text-decoration-none">Register</a>
        </p>
    </form>
</div>

<!-- Bootstrap Validation Script -->
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

<!-- Eye Toggle Script -->
<script>
function togglePassword() {
    const input = document.getElementById("password");
    const icon = event.currentTarget.querySelector("i");

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
</script>

</body>
</html>
