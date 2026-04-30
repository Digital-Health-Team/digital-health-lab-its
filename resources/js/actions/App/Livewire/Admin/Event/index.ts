import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Livewire\Admin\Event\Index::__invoke
* @see app/Livewire/Admin/Event/Index.php:7
* @route '/admin/events'
*/
const Index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(options),
    method: 'get',
})

Index.definition = {
    methods: ["get","head"],
    url: '/admin/events',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Event\Index::__invoke
* @see app/Livewire/Admin/Event/Index.php:7
* @route '/admin/events'
*/
Index.url = (options?: RouteQueryOptions) => {
    return Index.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Event\Index::__invoke
* @see app/Livewire/Admin/Event/Index.php:7
* @route '/admin/events'
*/
Index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Event\Index::__invoke
* @see app/Livewire/Admin/Event/Index.php:7
* @route '/admin/events'
*/
Index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Index.url(options),
    method: 'head',
})

export default Index