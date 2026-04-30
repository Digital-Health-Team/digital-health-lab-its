import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Livewire\Admin\Event\Show\Index::__invoke
* @see app/Livewire/Admin/Event/Show/Index.php:7
* @route '/admin/events/{event}'
*/
export const show = (args: { event: string | number } | [event: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/events/{event}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Event\Show\Index::__invoke
* @see app/Livewire/Admin/Event/Show/Index.php:7
* @route '/admin/events/{event}'
*/
show.url = (args: { event: string | number } | [event: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { event: args }
    }

    if (Array.isArray(args)) {
        args = {
            event: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        event: args.event,
    }

    return show.definition.url
            .replace('{event}', parsedArgs.event.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Event\Show\Index::__invoke
* @see app/Livewire/Admin/Event/Show/Index.php:7
* @route '/admin/events/{event}'
*/
show.get = (args: { event: string | number } | [event: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Event\Show\Index::__invoke
* @see app/Livewire/Admin/Event/Show/Index.php:7
* @route '/admin/events/{event}'
*/
show.head = (args: { event: string | number } | [event: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\Event\Show\Index::__invoke
* @see app/Livewire/Admin/Event/Show/Index.php:7
* @route '/admin/events/{event}'
*/
const showForm = (args: { event: string | number } | [event: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Event\Show\Index::__invoke
* @see app/Livewire/Admin/Event/Show/Index.php:7
* @route '/admin/events/{event}'
*/
showForm.get = (args: { event: string | number } | [event: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Event\Show\Index::__invoke
* @see app/Livewire/Admin/Event/Show/Index.php:7
* @route '/admin/events/{event}'
*/
showForm.head = (args: { event: string | number } | [event: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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