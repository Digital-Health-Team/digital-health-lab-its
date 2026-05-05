import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Livewire\Admin\CMS\PageSection\Index::__invoke
* @see app/Livewire/Admin/CMS/PageSection/Index.php:7
* @route '/admin/cms/page-sections'
*/
export const pageSections = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pageSections.url(options),
    method: 'get',
})

pageSections.definition = {
    methods: ["get","head"],
    url: '/admin/cms/page-sections',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\CMS\PageSection\Index::__invoke
* @see app/Livewire/Admin/CMS/PageSection/Index.php:7
* @route '/admin/cms/page-sections'
*/
pageSections.url = (options?: RouteQueryOptions) => {
    return pageSections.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\CMS\PageSection\Index::__invoke
* @see app/Livewire/Admin/CMS/PageSection/Index.php:7
* @route '/admin/cms/page-sections'
*/
pageSections.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pageSections.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\CMS\PageSection\Index::__invoke
* @see app/Livewire/Admin/CMS/PageSection/Index.php:7
* @route '/admin/cms/page-sections'
*/
pageSections.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: pageSections.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\CMS\PageSection\Index::__invoke
* @see app/Livewire/Admin/CMS/PageSection/Index.php:7
* @route '/admin/cms/page-sections'
*/
const pageSectionsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: pageSections.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\CMS\PageSection\Index::__invoke
* @see app/Livewire/Admin/CMS/PageSection/Index.php:7
* @route '/admin/cms/page-sections'
*/
pageSectionsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: pageSections.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\CMS\PageSection\Index::__invoke
* @see app/Livewire/Admin/CMS/PageSection/Index.php:7
* @route '/admin/cms/page-sections'
*/
pageSectionsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: pageSections.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

pageSections.form = pageSectionsForm

/**
* @see \App\Livewire\Admin\CMS\StructuralMember\Index::__invoke
* @see app/Livewire/Admin/CMS/StructuralMember/Index.php:7
* @route '/admin/cms/structural-members'
*/
export const structuralMembers = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: structuralMembers.url(options),
    method: 'get',
})

structuralMembers.definition = {
    methods: ["get","head"],
    url: '/admin/cms/structural-members',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\CMS\StructuralMember\Index::__invoke
* @see app/Livewire/Admin/CMS/StructuralMember/Index.php:7
* @route '/admin/cms/structural-members'
*/
structuralMembers.url = (options?: RouteQueryOptions) => {
    return structuralMembers.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\CMS\StructuralMember\Index::__invoke
* @see app/Livewire/Admin/CMS/StructuralMember/Index.php:7
* @route '/admin/cms/structural-members'
*/
structuralMembers.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: structuralMembers.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\CMS\StructuralMember\Index::__invoke
* @see app/Livewire/Admin/CMS/StructuralMember/Index.php:7
* @route '/admin/cms/structural-members'
*/
structuralMembers.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: structuralMembers.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\CMS\StructuralMember\Index::__invoke
* @see app/Livewire/Admin/CMS/StructuralMember/Index.php:7
* @route '/admin/cms/structural-members'
*/
const structuralMembersForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: structuralMembers.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\CMS\StructuralMember\Index::__invoke
* @see app/Livewire/Admin/CMS/StructuralMember/Index.php:7
* @route '/admin/cms/structural-members'
*/
structuralMembersForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: structuralMembers.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\CMS\StructuralMember\Index::__invoke
* @see app/Livewire/Admin/CMS/StructuralMember/Index.php:7
* @route '/admin/cms/structural-members'
*/
structuralMembersForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: structuralMembers.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

structuralMembers.form = structuralMembersForm

const cms = {
    pageSections: Object.assign(pageSections, pageSections),
    structuralMembers: Object.assign(structuralMembers, structuralMembers),
}

export default cms