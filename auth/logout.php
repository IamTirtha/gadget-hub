<?php
session_start();
session_destroy();

header("Location: /GadgetHub/loginform.html");
exit();
