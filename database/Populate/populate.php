<?php

require __DIR__ . '/../../config/bootstrap.php';

use Core\Database\Database;
use Database\Populate\UserPopulate;
use Database\Populate\CattlePopulate;

Database::migrate();
UserPopulate::populate();
CattlePopulate::populate();