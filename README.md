# Online Application Registration Form

A responsive PHP web application built using **HTML, CSS, JavaScript, jQuery and PHP**. It collects applicant information, validates it in the browser and on the server, and displays a styled confirmation page after a successful submission.

## Features

- Responsive registration form
- Full name, email, phone, date of birth, gender, course and address fields
- jQuery client-side validation
- PHP server-side validation
- CSRF token protection
- Safe HTML output escaping
- Styled success page with submitted details
- Print application button
- Dockerfile for cloud deployment

## Project structure

```text
online-registration-app/
├── index.php
├── assets/
│   ├── style.css
│   └── script.js
├── Dockerfile
├── .gitignore
└── README.md
```

## Run locally

### Option 1: PHP built-in server

1. Install PHP 8.1 or later.
2. Extract this ZIP file.
3. Open a terminal in the `online-registration-app` folder.
4. Run:

   ```bash
   php -S localhost:8000
   ```

5. Open `http://localhost:8000` in your browser.
6. Fill in the form and click **Submit application**.

### Option 2: XAMPP

1. Install and open XAMPP.
2. Copy the `online-registration-app` folder into `xampp/htdocs/`.
3. Start **Apache** from the XAMPP Control Panel.
4. Open `http://localhost/online-registration-app/` in your browser.

> PHP must run through a PHP-enabled server. Opening `index.php` directly as a file will not execute PHP.

## Deploy to the cloud using Render (Docker)

You need a GitHub account and a Render account.

1. Create a new GitHub repository, for example `online-registration-app`.
2. Extract this ZIP and upload all project files and folders to the repository. Keep `Dockerfile` in the repository root.
3. Push/commit the files to GitHub.
4. Sign in to [Render](https://render.com/).
5. Choose **New + → Web Service** and connect your GitHub repository.
6. Select **Docker** as the runtime/environment if prompted. Render will build using the included `Dockerfile`.
7. Create the web service and wait for the deployment to finish.
8. Open the public `onrender.com` URL shown in the Render dashboard.

Cloud providers can change their free-tier and deployment policies. Check the current plan and pricing before deploying.

## Alternative hosting

You can also upload the project files to any PHP 8.1+ web host that supports Apache or another PHP-capable web server, using that provider's file manager or FTP.

## How it works

1. The browser loads `index.php`, which outputs the HTML form.
2. `style.css` controls the layout, colors and responsive design.
3. `script.js` uses jQuery to validate fields before the form is submitted.
4. The form sends its values to `index.php` using the HTTP POST method.
5. PHP validates the submitted values again on the server.
6. If validation succeeds, PHP safely escapes the values and displays them on a formatted confirmation page. If validation fails, error messages are shown.

## Important note

This is a demonstration/academic project. Submitted details are validated and displayed, but **are not saved to a database and are not emailed**. Do not enter real sensitive personal information in a public demo. To make it a production application, add a database, privacy notice, access controls, HTTPS and appropriate data-retention/security practices.

## Technologies used

- HTML5
- CSS3
- JavaScript
- jQuery 3.7.1 (loaded from the official jQuery CDN)
- PHP 8.1+
- Docker (for cloud deployment)
