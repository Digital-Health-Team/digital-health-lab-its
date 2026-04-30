import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Livewire\Auth\Login::__invoke
* @see app/Livewire/Auth/Login.php:7
* @route '/login'
*/
const Login = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Login.url(options),
    method: 'get',
})

Login.definition = {
    methods: ["get","head"],
    url: '/login',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Auth\Login::__invoke
* @see app/Livewire/Auth/Login.php:7
* @route '/login'
*/
Login.url = (options?: RouteQueryOptions) => {
    return Login.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Auth\Login::__invoke
* @see app/Livewire/Auth/Login.php:7
* @route '/login'
*/
Login.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Login.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Auth\Login::__invoke
* @see app/Livewire/Auth/Login.php:7
* @route '/login'
*/
Login.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Login.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Auth\Login::__invoke
* @see app/Livewire/Auth/Login.php:7
* @route '/login'
*/
const LoginForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Login.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Auth\Login::__invoke
* @see app/Livewire/Auth/Login.php:7
* @route '/login'
*/
LoginForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Login.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Auth\Login::__invoke
* @see app/Livewire/Auth/Login.php:7
* @route '/login'
*/
LoginForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Login.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

Login.form = LoginForm

export default Login