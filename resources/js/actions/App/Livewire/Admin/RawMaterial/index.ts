import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Livewire\Admin\RawMaterial\Index::__invoke
* @see app/Livewire/Admin/RawMaterial/Index.php:7
* @route '/admin/raw-materials'
*/
const Index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(options),
    method: 'get',
})

Index.definition = {
    methods: ["get","head"],
    url: '/admin/raw-materials',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\RawMaterial\Index::__invoke
* @see app/Livewire/Admin/RawMaterial/Index.php:7
* @route '/admin/raw-materials'
*/
Index.url = (options?: RouteQueryOptions) => {
    return Index.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\RawMaterial\Index::__invoke
* @see app/Livewire/Admin/RawMaterial/Index.php:7
* @route '/admin/raw-materials'
*/
Index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\RawMaterial\Index::__invoke
* @see app/Livewire/Admin/RawMaterial/Index.php:7
* @route '/admin/raw-materials'
*/
Index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Index.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\RawMaterial\Index::__invoke
* @see app/Livewire/Admin/RawMaterial/Index.php:7
* @route '/admin/raw-materials'
*/
const IndexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Index.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\RawMaterial\Index::__invoke
* @see app/Livewire/Admin/RawMaterial/Index.php:7
* @route '/admin/raw-materials'
*/
IndexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Index.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\RawMaterial\Index::__invoke
* @see app/Livewire/Admin/RawMaterial/Index.php:7
* @route '/admin/raw-materials'
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