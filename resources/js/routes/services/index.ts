import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\ServicesController::consultation
* @see app/Http/Controllers/ServicesController.php:38
* @route '/services/consultation'
*/
export const consultation = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: consultation.url(options),
    method: 'get',
})

consultation.definition = {
    methods: ["get","head"],
    url: '/services/consultation',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::consultation
* @see app/Http/Controllers/ServicesController.php:38
* @route '/services/consultation'
*/
consultation.url = (options?: RouteQueryOptions) => {
    return consultation.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::consultation
* @see app/Http/Controllers/ServicesController.php:38
* @route '/services/consultation'
*/
consultation.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: consultation.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ServicesController::consultation
* @see app/Http/Controllers/ServicesController.php:38
* @route '/services/consultation'
*/
consultation.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: consultation.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ServicesController::consultation
* @see app/Http/Controllers/ServicesController.php:38
* @route '/services/consultation'
*/
const consultationForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: consultation.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ServicesController::consultation
* @see app/Http/Controllers/ServicesController.php:38
* @route '/services/consultation'
*/
consultationForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: consultation.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ServicesController::consultation
* @see app/Http/Controllers/ServicesController.php:38
* @route '/services/consultation'
*/
consultationForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: consultation.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

consultation.form = consultationForm

/**
* @see \App\Http\Controllers\ServicesController::show
* @see app/Http/Controllers/ServicesController.php:87
* @route '/services/{service}'
*/
export const show = (args: { service: string | number } | [service: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/services/{service}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::show
* @see app/Http/Controllers/ServicesController.php:87
* @route '/services/{service}'
*/
show.url = (args: { service: string | number } | [service: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { service: args }
    }

    if (Array.isArray(args)) {
        args = {
            service: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        service: args.service,
    }

    return show.definition.url
            .replace('{service}', parsedArgs.service.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::show
* @see app/Http/Controllers/ServicesController.php:87
* @route '/services/{service}'
*/
show.get = (args: { service: string | number } | [service: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ServicesController::show
* @see app/Http/Controllers/ServicesController.php:87
* @route '/services/{service}'
*/
show.head = (args: { service: string | number } | [service: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ServicesController::show
* @see app/Http/Controllers/ServicesController.php:87
* @route '/services/{service}'
*/
const showForm = (args: { service: string | number } | [service: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ServicesController::show
* @see app/Http/Controllers/ServicesController.php:87
* @route '/services/{service}'
*/
showForm.get = (args: { service: string | number } | [service: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ServicesController::show
* @see app/Http/Controllers/ServicesController.php:87
* @route '/services/{service}'
*/
showForm.head = (args: { service: string | number } | [service: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

const services = {
    consultation: Object.assign(consultation, consultation),
    show: Object.assign(show, show),
}

export default services