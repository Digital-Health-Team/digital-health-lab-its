import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Livewire\Admin\MasterData\Index::__invoke
* @see app/Livewire/Admin/MasterData/Index.php:7
* @route '/admin/master-data'
*/
const Index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(options),
    method: 'get',
})

Index.definition = {
    methods: ["get","head"],
    url: '/admin/master-data',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\MasterData\Index::__invoke
* @see app/Livewire/Admin/MasterData/Index.php:7
* @route '/admin/master-data'
*/
Index.url = (options?: RouteQueryOptions) => {
    return Index.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\MasterData\Index::__invoke
* @see app/Livewire/Admin/MasterData/Index.php:7
* @route '/admin/master-data'
*/
Index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\MasterData\Index::__invoke
* @see app/Livewire/Admin/MasterData/Index.php:7
* @route '/admin/master-data'
*/
Index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Index.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\MasterData\Index::__invoke
* @see app/Livewire/Admin/MasterData/Index.php:7
* @route '/admin/master-data'
*/
const IndexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Index.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\MasterData\Index::__invoke
* @see app/Livewire/Admin/MasterData/Index.php:7
* @route '/admin/master-data'
*/
IndexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Index.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\MasterData\Index::__invoke
* @see app/Livewire/Admin/MasterData/Index.php:7
* @route '/admin/master-data'
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