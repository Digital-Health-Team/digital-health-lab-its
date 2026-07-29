import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\EventController::show
* @see app/Http/Controllers/EventController.php:32
* @route '/events/{event}'
*/
export const show = (args: { event: string | { slug: string } } | [event: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/events/{event}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\EventController::show
* @see app/Http/Controllers/EventController.php:32
* @route '/events/{event}'
*/
show.url = (args: { event: string | { slug: string } } | [event: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { event: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'slug' in args) {
        args = { event: args.slug }
    }

    if (Array.isArray(args)) {
        args = {
            event: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        event: typeof args.event === 'object'
        ? args.event.slug
        : args.event,
    }

    return show.definition.url
            .replace('{event}', parsedArgs.event.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\EventController::show
* @see app/Http/Controllers/EventController.php:32
* @route '/events/{event}'
*/
show.get = (args: { event: string | { slug: string } } | [event: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EventController::show
* @see app/Http/Controllers/EventController.php:32
* @route '/events/{event}'
*/
show.head = (args: { event: string | { slug: string } } | [event: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\EventController::show
* @see app/Http/Controllers/EventController.php:32
* @route '/events/{event}'
*/
const showForm = (args: { event: string | { slug: string } } | [event: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EventController::show
* @see app/Http/Controllers/EventController.php:32
* @route '/events/{event}'
*/
showForm.get = (args: { event: string | { slug: string } } | [event: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EventController::show
* @see app/Http/Controllers/EventController.php:32
* @route '/events/{event}'
*/
showForm.head = (args: { event: string | { slug: string } } | [event: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

const events = {
    show: Object.assign(show, show),
}

export default events