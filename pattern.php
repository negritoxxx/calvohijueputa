<?php

    $variable = '456789asdsadA%#';

    $pattern = '[a-zA-Z0-9!@#$&%*()\\.+,\/"]';

    if(!preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/', $variable)) {
        echo 'the password does not meet the requirements!';
    }
?>