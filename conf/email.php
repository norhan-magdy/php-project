<?php
// config/email.php

return [
    'host'       => 'smtp.gmail.com',      // Using Gmail SMTP for a free option
    'username'   => 'your-email@gmail.com',  // Replace with your email
    'password'   => 'your-email-password',   // Replace with your email password (or an app password)
    'port'       => 587,
    'encryption' => 'tls',
    'from_email' => 'your-email@gmail.com',
    'from_name'  => 'Your Restaurant Name'
];
