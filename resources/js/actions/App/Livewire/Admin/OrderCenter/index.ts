import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Livewire\Admin\OrderCenter\Index::__invoke
* @see app/Livewire/Admin/OrderCenter/Index.php:7
* @route '/admin/order-center'
*/
const Index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(options),
    method: 'get',
})

Index.definition = {
    methods: ["get","head"],
    url: '/admin/order-center',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\OrderCenter\Index::__invoke
* @see app/Livewire/Admin/OrderCenter/Index.php:7
* @route '/admin/order-center'
*/
Index.url = (options?: RouteQueryOptions) => {
    return Index.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\OrderCenter\Index::__invoke
* @see app/Livewire/Admin/OrderCenter/Index.php:7
* @route '/admin/order-center'
*/
Index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\OrderCenter\Index::__invoke
* @see app/Livewire/Admin/OrderCenter/Index.php:7
* @route '/admin/order-center'
*/
Index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Index.url(options),
    method: 'head',
})

export default Index