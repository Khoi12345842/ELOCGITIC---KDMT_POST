<?php

/**
 * Laravel IDE Helper File
 * 
 * This file helps IDEs understand Laravel's magic methods and helper functions.
 * It should NOT be included in your application code.
 * 
 * @see https://laravel.com/docs/11.x/helpers
 * @see https://laravel.com/docs/11.x/facades
 */

namespace Illuminate\Support\Facades {
    
    /**
     * @method static \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard guard(string|null $name = null)
     * @method static void shouldUse(string $name)
     * @method static bool check()
     * @method static bool guest()
     * @method static \App\Models\User|null user()
     * @method static int|string|null id()
     * @method static bool validate(array $credentials = [])
     * @method static void setUser(\Illuminate\Contracts\Auth\Authenticatable $user)
     * @method static bool attempt(array $credentials = [], bool $remember = false)
     * @method static bool once(array $credentials = [])
     * @method static void login(\Illuminate\Contracts\Auth\Authenticatable $user, bool $remember = false)
     * @method static \Illuminate\Contracts\Auth\Authenticatable loginUsingId(mixed $id, bool $remember = false)
     * @method static bool onceUsingId(mixed $id)
     * @method static bool viaRemember()
     * @method static void logout()
     *
     * @see \Illuminate\Auth\AuthManager
     * @see \Illuminate\Contracts\Auth\Guard
     * @see \Illuminate\Contracts\Auth\StatefulGuard
     */
    class Auth {}
}

namespace {
    
    use Illuminate\Contracts\Auth\Factory as AuthFactory;
    use Illuminate\Contracts\Auth\Guard;
    use Illuminate\Contracts\Auth\StatefulGuard;
    
    /**
     * Get the available auth instance.
     *
     * @param  string|null  $guard
     * @return AuthFactory|Guard|StatefulGuard
     */
    function auth($guard = null) {
        if (is_null($guard)) {
            return app(AuthFactory::class);
        }
        return app(AuthFactory::class)->guard($guard);
    }
    
    /**
     * Get the available cache instance.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return mixed|\Illuminate\Cache\CacheManager
     */
    function cache($key = null, $default = null) {}
    
    /**
     * Get / set the specified configuration value.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return mixed|\Illuminate\Config\Repository
     */
    function config($key = null, $default = null) {}
    
    /**
     * Get the available request instance.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return \Illuminate\Http\Request|string|array|null
     */
    function request($key = null, $default = null) {}
    
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $data
     * @param  array  $mergeData
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    function view($view = null, $data = [], $mergeData = []) {}
    
    /**
     * Create a new redirect response to the given path.
     *
     * @param  string|null  $to
     * @param  int  $status
     * @param  array  $headers
     * @param  bool|null  $secure
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    function redirect($to = null, $status = 302, $headers = [], $secure = null) {}
    
    /**
     * Get an instance of the current route or return a route by name.
     *
     * @param  array|string|null  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return \Illuminate\Routing\Route|string|null
     */
    function route($name = null, $parameters = [], $absolute = true) {}
    
    /**
     * Generate a url for the application.
     *
     * @param  string|null  $path
     * @param  mixed  $parameters
     * @param  bool|null  $secure
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    function url($path = null, $parameters = [], $secure = null) {}
    
    /**
     * Get the available session instance.
     *
     * @param  string|array|null  $key
     * @param  mixed  $default
     * @return \Illuminate\Session\Store|\Illuminate\Session\SessionManager|mixed
     */
    function session($key = null, $default = null) {}
}
