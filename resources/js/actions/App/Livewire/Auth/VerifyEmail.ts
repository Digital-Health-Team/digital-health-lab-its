import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../wayfinder'
/**
* @see \App\Livewire\Auth\VerifyEmail::__invoke
* @see app/Livewire/Auth/VerifyEmail.php:7
* @route '/email/verify'
*/
const VerifyEmail = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: VerifyEmail.url(options),
    method: 'get',
})

VerifyEmail.definition = {
    methods: ["get","head"],
    url: '/email/verify',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Auth\VerifyEmail::__invoke
* @see app/Livewire/Auth/VerifyEmail.php:7
* @route '/email/verify'
*/
VerifyEmail.url = (options?: RouteQueryOptions) => {
    return VerifyEmail.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Auth\VerifyEmail::__invoke
* @see app/Livewire/Auth/VerifyEmail.php:7
* @route '/email/verify'
*/
VerifyEmail.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: VerifyEmail.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Auth\VerifyEmail::__invoke
* @see app/Livewire/Auth/VerifyEmail.php:7
* @route '/email/verify'
*/
VerifyEmail.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: VerifyEmail.url(options),
    method: 'head',
})

export default VerifyEmail