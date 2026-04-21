import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../wayfinder'
/**
* @see \App\Livewire\Auth\ForgotPassword::__invoke
* @see app/Livewire/Auth/ForgotPassword.php:7
* @route '/forgot-password'
*/
const ForgotPassword = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ForgotPassword.url(options),
    method: 'get',
})

ForgotPassword.definition = {
    methods: ["get","head"],
    url: '/forgot-password',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Auth\ForgotPassword::__invoke
* @see app/Livewire/Auth/ForgotPassword.php:7
* @route '/forgot-password'
*/
ForgotPassword.url = (options?: RouteQueryOptions) => {
    return ForgotPassword.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Auth\ForgotPassword::__invoke
* @see app/Livewire/Auth/ForgotPassword.php:7
* @route '/forgot-password'
*/
ForgotPassword.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ForgotPassword.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Auth\ForgotPassword::__invoke
* @see app/Livewire/Auth/ForgotPassword.php:7
* @route '/forgot-password'
*/
ForgotPassword.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ForgotPassword.url(options),
    method: 'head',
})

export default ForgotPassword