<?php

namespace Workbench\Database\Factories;

use Workbench\App\Models\User;

/**
 * @template TModel of \Workbench\App\Models\User
 */
class UserFactory extends \Orchestra\Testbench\Factories\UserFactory
{
    /**
     * Get the name of the model that is generated by the factory.
     *
     * @return class-string<\QuantaQuirk\Database\Eloquent\Model|TModel>
     */
    public function modelName()
    {
        return User::class;
    }
}