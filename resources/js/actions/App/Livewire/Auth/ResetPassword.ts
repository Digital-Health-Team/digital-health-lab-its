import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Livewire\Auth\ResetPassword::__invoke
* @see app/Livewire/Auth/ResetPassword.php:7
* @route '/reset-password/{token}'
*/
const ResetPassword = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ResetPassword.url(args, options),
    method: 'get',
})

ResetPassword.definition = {
    methods: ["get","head"],
    url: '/reset-password/{token}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Auth\ResetPassword::__invoke
* @see app/Livewire/Auth/ResetPassword.php:7
* @route '/reset-password/{token}'
*/
ResetPassword.url = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { token: args }
    }

    if (Array.isArray(args)) {
        args = {
            token: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        token: args.token,
    }

    return ResetPassword.definition.url
            .replace('{token}', parsedArgs.token.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Livewire\Auth\ResetPassword::__invoke
* @see app/Livewire/Auth/ResetPassword.php:7
* @route '/reset-password/{token}'
*/
ResetPassword.get = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ResetPassword.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Auth\ResetPassword::__invoke
* @see app/Livewire/Auth/ResetPassword.php:7
* @route '/reset-password/{token}'
*/
ResetPassword.head = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ResetPassword.url(args, options),
    method: 'head',
})

export default ResetPassword