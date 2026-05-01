import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Livewire\Admin\Cms\StructuralMember\Index::__invoke
* @see app/Livewire/Admin/Cms/StructuralMember/Index.php:7
* @route '/admin/cms/structural-members'
*/
const Index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(options),
    method: 'get',
})

Index.definition = {
    methods: ["get","head"],
    url: '/admin/cms/structural-members',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Cms\StructuralMember\Index::__invoke
* @see app/Livewire/Admin/Cms/StructuralMember/Index.php:7
* @route '/admin/cms/structural-members'
*/
Index.url = (options?: RouteQueryOptions) => {
    return Index.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Cms\StructuralMember\Index::__invoke
* @see app/Livewire/Admin/Cms/StructuralMember/Index.php:7
* @route '/admin/cms/structural-members'
*/
Index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Cms\StructuralMember\Index::__invoke
* @see app/Livewire/Admin/Cms/StructuralMember/Index.php:7
* @route '/admin/cms/structural-members'
*/
Index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Index.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\Cms\StructuralMember\Index::__invoke
* @see app/Livewire/Admin/Cms/StructuralMember/Index.php:7
* @route '/admin/cms/structural-members'
*/
const IndexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Index.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Cms\StructuralMember\Index::__invoke
* @see app/Livewire/Admin/Cms/StructuralMember/Index.php:7
* @route '/admin/cms/structural-members'
*/
IndexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Index.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Cms\StructuralMember\Index::__invoke
* @see app/Livewire/Admin/Cms/StructuralMember/Index.php:7
* @route '/admin/cms/structural-members'
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