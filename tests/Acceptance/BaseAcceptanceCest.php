<?php

namespace Tests\Acceptance;

use Core\Database\Database;
use Core\Env\EnvLoader;
use Tests\Support\AcceptanceTester;
use Database\Populate\UserPopulate;

class BaseAcceptanceCest
{
    public function _before(AcceptanceTester $page): void
    {
        EnvLoader::init();
        Database::create();
        Database::migrate();
        UserPopulate::populate();
    }

    public function _after(AcceptanceTester $page): void
    {
        Database::drop();
    }
}
