import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Livewire\Gudang\Dashboard\Index::__invoke
* @see app/Livewire/Gudang/Dashboard/Index.php:7
* @route '/gudang/dashboard'
*/
const Index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(options),
    method: 'get',
})

Index.definition = {
    methods: ["get","head"],
    url: '/gudang/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Gudang\Dashboard\Index::__invoke
* @see app/Livewire/Gudang/Dashboard/Index.php:7
* @route '/gudang/dashboard'
*/
Index.url = (options?: RouteQueryOptions) => {
    return Index.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Gudang\Dashboard\Index::__invoke
* @see app/Livewire/Gudang/Dashboard/Index.php:7
* @route '/gudang/dashboard'
*/
Index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Gudang\Dashboard\Index::__invoke
* @see app/Livewire/Gudang/Dashboard/Index.php:7
* @route '/gudang/dashboard'
*/
Index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Index.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Gudang\Dashboard\Index::__invoke
* @see app/Livewire/Gudang/Dashboard/Index.php:7
* @route '/gudang/dashboard'
*/
const IndexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Index.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Gudang\Dashboard\Index::__invoke
* @see app/Livewire/Gudang/Dashboard/Index.php:7
* @route '/gudang/dashboard'
*/
IndexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Index.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Gudang\Dashboard\Index::__invoke
* @see app/Livewire/Gudang/Dashboard/Index.php:7
* @route '/gudang/dashboard'
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