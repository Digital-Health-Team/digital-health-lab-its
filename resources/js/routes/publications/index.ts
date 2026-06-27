import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\PublicationsController::show
* @see app/Http/Controllers/PublicationsController.php:20
* @route '/publications/{publication}'
*/
export const show = (args: { publication: string | number } | [publication: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/publications/{publication}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PublicationsController::show
* @see app/Http/Controllers/PublicationsController.php:20
* @route '/publications/{publication}'
*/
show.url = (args: { publication: string | number } | [publication: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { publication: args }
    }

    if (Array.isArray(args)) {
        args = {
            publication: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        publication: args.publication,
    }

    return show.definition.url
            .replace('{publication}', parsedArgs.publication.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PublicationsController::show
* @see app/Http/Controllers/PublicationsController.php:20
* @route '/publications/{publication}'
*/
show.get = (args: { publication: string | number } | [publication: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PublicationsController::show
* @see app/Http/Controllers/PublicationsController.php:20
* @route '/publications/{publication}'
*/
show.head = (args: { publication: string | number } | [publication: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\PublicationsController::show
* @see app/Http/Controllers/PublicationsController.php:20
* @route '/publications/{publication}'
*/
const showForm = (args: { publication: string | number } | [publication: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PublicationsController::show
* @see app/Http/Controllers/PublicationsController.php:20
* @route '/publications/{publication}'
*/
showForm.get = (args: { publication: string | number } | [publication: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PublicationsController::show
* @see app/Http/Controllers/PublicationsController.php:20
* @route '/publications/{publication}'
*/
showForm.head = (args: { publication: string | number } | [publication: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

const publications = {
    show: Object.assign(show, show),
}

export default publications