import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Livewire\Admin\Product\Index::__invoke
* @see app/Livewire/Admin/Product/Index.php:7
* @route '/admin/products'
*/
const Index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(options),
    method: 'get',
})

Index.definition = {
    methods: ["get","head"],
    url: '/admin/products',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Product\Index::__invoke
* @see app/Livewire/Admin/Product/Index.php:7
* @route '/admin/products'
*/
Index.url = (options?: RouteQueryOptions) => {
    return Index.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Product\Index::__invoke
* @see app/Livewire/Admin/Product/Index.php:7
* @route '/admin/products'
*/
Index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Product\Index::__invoke
* @see app/Livewire/Admin/Product/Index.php:7
* @route '/admin/products'
*/
Index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Index.url(options),
    method: 'head',
})

export default Index