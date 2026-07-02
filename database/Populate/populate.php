<?php

require __DIR__ . '/../../config/bootstrap.php';

use Core\Database\Database;
use Database\Populate\UserPopulate;
use Database\Populate\CattlePopulate;
use Database\Populate\VaccinePopulate;
use Database\Populate\TagPopulate;

Database::migrate();
UserPopulate::populate();
CattlePopulate::populate();
VaccinePopulate::populate();
TagPopulate::populate();