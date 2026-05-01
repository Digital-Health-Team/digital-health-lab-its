import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Livewire\Admin\Event\Show\Index::__invoke
* @see app/Livewire/Admin/Event/Show/Index.php:7
* @route '/admin/events/{event}'
*/
const Index = (args: { event: string | number } | [event: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(args, options),
    method: 'get',
})

Index.definition = {
    methods: ["get","head"],
    url: '/admin/events/{event}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Event\Show\Index::__invoke
* @see app/Livewire/Admin/Event/Show/Index.php:7
* @route '/admin/events/{event}'
*/
Index.url = (args: { event: string | number } | [event: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return Index.definition.url
            .replace('{event}', parsedArgs.event.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Event\Show\Index::__invoke
* @see app/Livewire/Admin/Event/Show/Index.php:7
* @route '/admin/events/{event}'
*/
Index.get = (args: { event: string | number } | [event: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Event\Show\Index::__invoke
* @see app/Livewire/Admin/Event/Show/Index.php:7
* @route '/admin/events/{event}'
*/
Index.head = (args: { event: string | number } | [event: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Index.url(args, options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\Event\Show\Index::__invoke
* @see app/Livewire/Admin/Event/Show/Index.php:7
* @route '/admin/events/{event}'
*/
const IndexForm = (args: { event: string | number } | [event: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Index.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Event\Show\Index::__invoke
* @see app/Livewire/Admin/Event/Show/Index.php:7
* @route '/admin/events/{event}'
*/
IndexForm.get = (args: { event: string | number } | [event: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Index.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Event\Show\Index::__invoke
* @see app/Livewire/Admin/Event/Show/Index.php:7
* @route '/admin/events/{event}'
*/
IndexForm.head = (args: { event: string | number } | [event: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Index.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

Index.form = IndexForm

export default Index