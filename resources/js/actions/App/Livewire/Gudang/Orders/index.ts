import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Livewire\Gudang\Orders\Index::__invoke
* @see app/Livewire/Gudang/Orders/Index.php:7
* @route '/gudang/orders'
*/
const Index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(options),
    method: 'get',
})

Index.definition = {
    methods: ["get","head"],
    url: '/gudang/orders',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Gudang\Orders\Index::__invoke
* @see app/Livewire/Gudang/Orders/Index.php:7
* @route '/gudang/orders'
*/
Index.url = (options?: RouteQueryOptions) => {
    return Index.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Gudang\Orders\Index::__invoke
* @see app/Livewire/Gudang/Orders/Index.php:7
* @route '/gudang/orders'
*/
Index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Gudang\Orders\Index::__invoke
* @see app/Livewire/Gudang/Orders/Index.php:7
* @route '/gudang/orders'
*/
Index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Index.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Gudang\Orders\Index::__invoke
* @see app/Livewire/Gudang/Orders/Index.php:7
* @route '/gudang/orders'
*/
const IndexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Index.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Gudang\Orders\Index::__invoke
* @see app/Livewire/Gudang/Orders/Index.php:7
* @route '/gudang/orders'
*/
IndexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Index.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Gudang\Orders\Index::__invoke
* @see app/Livewire/Gudang/Orders/Index.php:7
* @route '/gudang/orders'
*/
IndexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

Index.form = IndexForm

export default Index