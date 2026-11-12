<?php
session_start();

// Create a CSRF token for this form session.
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];
$submitted = false;
$data = [
    'full_name' => '',
    'email' => '',
    'phone' => '',
    'dob' => '',
    'gender' => '',
    'course' => '',
    'address' => '',
    'terms' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($data as $key => $value) {
        if ($key === 'terms') {
            $data[$key] = isset($_POST[$key]) ? 'yes' : '';
        } else {
            $data[$key] = trim((string)($_POST[$key] ?? ''));
        }
    }

    if (!hash_equals($_SESSION['csrf_token'], (string)($_POST['csrf_token'] ?? ''))) {
        $errors[] = 'Your session expired. Please refresh the page and submit again.';
    }

    if ($data['full_name'] === '') {
        $errors[] = 'Full name is required.';
    } elseif (mb_strlen($data['full_name']) > 100) {
        $errors[] = 'Full name must be 100 characters or fewer.';
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    }

    if (!preg_match('/^[0-9+\-\s()]{7,20}$/', $data['phone'])) {
        $errors[] = 'Enter a valid phone number (7–20 characters).';
    }

    if ($data['dob'] === '') {
        $errors[] = 'Date of birth is required.';
    } else {
        $date = DateTime::createFromFormat('Y-m-d', $data['dob']);
        if (!$date || $date->format('Y-m-d') !== $data['dob'] || $date > new DateTime('today')) {
            $errors[] = 'Enter a valid date of birth that is not in the future.';
        }
    }

    if (!in_array($data['gender'], ['Female', 'Male', 'Other', 'Prefer not to say'], true)) {
        $errors[] = 'Please select a gender option.';
    }

    if (!in_array($data['course'], ['Computer Science', 'Information Science', 'Electronics', 'Mechanical', 'Civil', 'Other'], true)) {
        $errors[] = 'Please select a course.';
    }

    if ($data['address'] === '') {
        $errors[] = 'Address is required.';
    } elseif (mb_strlen($data['address']) > 500) {
        $errors[] = 'Address must be 500 characters or fewer.';
    }

    if ($data['terms'] !== 'yes') {
        $errors[] = 'You must confirm that the information is correct.';
    }

    if (!$errors) {
        $submitted = true;
        // Rotate the token after successful submission.
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Application Registration</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/script.js" defer></script>
</head>
<body>
<main class="page-shell">
    <header class="hero">
        <div class="brand-mark" aria-hidden="true">OA</div>
        <p class="eyebrow">ONLINE PORTAL</p>
        <h1>Application Registration</h1>
        <p class="hero-copy">Complete the form below to submit your application.</p>
    </header>

    <?php if ($submitted): ?>
        <section class="result-card success-card" aria-live="polite">
            <div class="success-icon" aria-hidden="true">✓</div>
            <p class="eyebrow">SUBMISSION SUCCESSFUL</p>
            <h2>Thank you, <?= e($data['full_name']) ?>!</h2>
            <p class="muted">Your application information has been received and is displayed below.</p>
            <div class="details-grid">
                <div class="detail"><span>Full name</span><strong><?= e($data['full_name']) ?></strong></div>
                <div class="detail"><span>Email address</span><strong><?= e($data['email']) ?></strong></div>
                <div class="detail"><span>Phone number</span><strong><?= e($data['phone']) ?></strong></div>
                <div class="detail"><span>Date of birth</span><strong><?= e($data['dob']) ?></strong></div>
                <div class="detail"><span>Gender</span><strong><?= e($data['gender']) ?></strong></div>
                <div class="detail"><span>Selected course</span><strong><?= e($data['course']) ?></strong></div>
                <div class="detail full-width"><span>Address</span><strong><?= nl2br(e($data['address'])) ?></strong></div>
            </div>
            <button class="button button-secondary" type="button" onclick="window.print()">Print application</button>
            <a class="text-link" href="index.php">Submit another application</a>
        </section>
    <?php else: ?>
        <?php if ($errors): ?>
            <section class="alert" role="alert">
                <strong>Please correct the following:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <section class="form-card">
            <div class="form-heading">
                <div>
                    <p class="eyebrow">APPLICATION FORM</p>
                    <h2>Personal information</h2>
                </div>
                <span class="required-note">* Required fields</span>
            </div>

            <form id="registrationForm" method="POST" action="index.php" novalidate>
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

                <div class="form-grid">
                    <div class="field full-width">
                        <label for="full_name">Full name <span>*</span></label>
                        <input type="text" id="full_name" name="full_name" maxlength="100"
                               placeholder="Enter your full name" value="<?= e($data['full_name']) ?>" required>
                        <small class="field-error"></small>
                    </div>

                    <div class="field">
                        <label for="email">Email address <span>*</span></label>
                        <input type="email" id="email" name="email"
                               placeholder="name@example.com" value="<?= e($data['email']) ?>" required>
                        <small class="field-error"></small>
                    </div>

                    <div class="field">
                        <label for="phone">Phone number <span>*</span></label>
                        <input type="tel" id="phone" name="phone" maxlength="20"
                               placeholder="+91 98765 43210" value="<?= e($data['phone']) ?>" required>
                        <small class="field-error"></small>
                    </div>

                    <div class="field">
                        <label for="dob">Date of birth <span>*</span></label>
                        <input type="date" id="dob" name="dob" max="<?= date('Y-m-d') ?>"
                               value="<?= e($data['dob']) ?>" required>
                        <small class="field-error"></small>
                    </div>

                    <div class="field">
                        <label for="gender">Gender <span>*</span></label>
                        <select id="gender" name="gender" required>
                            <option value="">Select an option</option>
                            <?php foreach (['Female', 'Male', 'Other', 'Prefer not to say'] as $option): ?>
                                <option value="<?= e($option) ?>" <?= $data['gender'] === $option ? 'selected' : '' ?>><?= e($option) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="field-error"></small>
                    </div>

                    <div class="field full-width">
                        <label for="course">Course applying for <span>*</span></label>
                        <select id="course" name="course" required>
                            <option value="">Select a course</option>
                            <?php foreach (['Computer Science', 'Information Science', 'Electronics', 'Mechanical', 'Civil', 'Other'] as $option): ?>
                                <option value="<?= e($option) ?>" <?= $data['course'] === $option ? 'selected' : '' ?>><?= e($option) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="field-error"></small>
                    </div>

                    <div class="field full-width">
                        <label for="address">Address <span>*</span></label>
                        <textarea id="address" name="address" rows="4" maxlength="500"
                                  placeholder="Enter your complete address" required><?= e($data['address']) ?></textarea>
                        <small class="field-error"></small>
                    </div>
                </div>

                <label class="check-row">
                    <input type="checkbox" id="terms" name="terms" value="yes" <?= $data['terms'] === 'yes' ? 'checked' : '' ?>>
                    <span>I confirm that the information provided above is correct. <b>*</b></span>
                </label>
                <small class="field-error terms-error"></small>

                <button class="button" type="submit">Submit application <span aria-hidden="true">→</span></button>
                <p class="privacy-note">Demo application form. This version validates and displays the submitted information; it does not store applications in a database or send email.</p>
            </form>
        </section>
    <?php endif; ?>

    <footer>Online Application Portal · Built with HTML, CSS, JavaScript, jQuery and PHP</footer>
</main>
</body>
</html>
