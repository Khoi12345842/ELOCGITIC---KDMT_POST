<?php

namespace PHPSTORM_META {

    override(\Illuminate\Support\Facades\Auth::guard(0), map([
        '' => \Illuminate\Contracts\Auth\StatefulGuard::class,
    ]));

    override(\auth(0), map([
        '' => \Illuminate\Contracts\Auth\StatefulGuard::class,
    ]));

    expectedArguments(
        \auth(),
        0,
        'web',
        'api'
    );

    registerArgumentsSet(
        'auth_guards',
        'web',
        'api'
    );

    expectedArguments(\Illuminate\Support\Facades\Auth::guard(), 0, argumentsSet('auth_guards'));
    expectedArguments(\auth(), 0, argumentsSet('auth_guards'));

    // Auth helper return types
    override(\auth(), type(0));
    
    // When auth() is called without parameters, it returns the auth factory
    // which has methods like check(), user(), id(), etc.
    
    exitPoint(\abort());
    exitPoint(\dd());
    exitPoint(\die());
    exitPoint(\exit());

}
