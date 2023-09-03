<?php

require __DIR__ . '/vendor/autoload.php';

$engine = new Fabric\FabricEngine();

// Template Layout
$template = <<<EOT
<html>
<head>
    <script src="https://unpkg.com/htmx.org@1.9.4"></script>
</head>
<body>
  <div hx-target="this" hx-swap="outerHTML">
    <label>Email:
      <input id="email" type="email" name="email" hx-post="/email" value="">
    </label>
    <div id="message"></div>
  </div>
</body>
</html>
EOT;

// Execute the template
$engine->run($template);

// Get the email from the POST and validate for gmail only accounts
$emailError = !preg_match("/@gmail\.com$/i", isset($_POST['email']) ? $_POST['email'] : '');

// Construct the HTML template, with conditional error message
$engine->query('//div[@id="message"]')
    ->item(0)
    ->nodeValue = $emailError ? 'Only Gmail addresses accepted!' : '';

// Query call after run function call
$engine->query('//input[@id="email"]')
    ->item(0)
    ->setAttribute('value', filter_var($email, FILTER_VALIDATE_EMAIL));


echo $engine->saveFabric();


