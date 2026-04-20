import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../wayfinder'
/**
* @see \App\Livewire\Auth\Register::__invoke
* @see app/Livewire/Auth/Register.php:7
* @route '/register'
*/
const Register = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Register.url(options),
    method: 'get',
})

Register.definition = {
    methods: ["get","head"],
    url: '/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Auth\Register::__invoke
* @see app/Livewire/Auth/Register.php:7
* @route '/register'
*/
Register.url = (options?: RouteQueryOptions) => {
    return Register.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Auth\Register::__invoke
* @see app/Livewire/Auth/Register.php:7
* @route '/register'
*/
Register.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Register.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Auth\Register::__invoke
* @see app/Livewire/Auth/Register.php:7
* @route '/register'
*/
Register.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Register.url(options),
    method: 'head',
})

export default Register