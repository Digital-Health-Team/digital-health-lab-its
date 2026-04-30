import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Livewire\Admin\Cms\PageSection\Index::__invoke
* @see app/Livewire/Admin/Cms/PageSection/Index.php:7
* @route '/admin/cms/page-sections'
*/
const Index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(options),
    method: 'get',
})

Index.definition = {
    methods: ["get","head"],
    url: '/admin/cms/page-sections',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Cms\PageSection\Index::__invoke
* @see app/Livewire/Admin/Cms/PageSection/Index.php:7
* @route '/admin/cms/page-sections'
*/
Index.url = (options?: RouteQueryOptions) => {
    return Index.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Cms\PageSection\Index::__invoke
* @see app/Livewire/Admin/Cms/PageSection/Index.php:7
* @route '/admin/cms/page-sections'
*/
Index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Cms\PageSection\Index::__invoke
* @see app/Livewire/Admin/Cms/PageSection/Index.php:7
* @route '/admin/cms/page-sections'
*/
Index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Index.url(options),
    method: 'head',
})

export default Index