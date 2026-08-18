<?php
/** logout.php – meldet den Benutzer ab. */

require_once dirname(__DIR__) . '/lib/bootstrap.php';

Auth::logout();
Util::redirect('login.php');
