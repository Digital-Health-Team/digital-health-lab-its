import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Livewire\Admin\CMS\LandingContent\Index::__invoke
* @see app/Livewire/Admin/CMS/LandingContent/Index.php:7
* @route '/admin/cms/landing-content'
*/
export const landingContent = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: landingContent.url(options),
    method: 'get',
})

landingContent.definition = {
    methods: ["get","head"],
    url: '/admin/cms/landing-content',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\CMS\LandingContent\Index::__invoke
* @see app/Livewire/Admin/CMS/LandingContent/Index.php:7
* @route '/admin/cms/landing-content'
*/
landingContent.url = (options?: RouteQueryOptions) => {
    return landingContent.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\CMS\LandingContent\Index::__invoke
* @see app/Livewire/Admin/CMS/LandingContent/Index.php:7
* @route '/admin/cms/landing-content'
*/
landingContent.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: landingContent.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\CMS\LandingContent\Index::__invoke
* @see app/Livewire/Admin/CMS/LandingContent/Index.php:7
* @route '/admin/cms/landing-content'
*/
landingContent.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: landingContent.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\CMS\LandingContent\Index::__invoke
* @see app/Livewire/Admin/CMS/LandingContent/Index.php:7
* @route '/admin/cms/landing-content'
*/
const landingContentForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: landingContent.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\CMS\LandingContent\Index::__invoke
* @see app/Livewire/Admin/CMS/LandingContent/Index.php:7
* @route '/admin/cms/landing-content'
*/
landingContentForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: landingContent.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\CMS\LandingContent\Index::__invoke
* @see app/Livewire/Admin/CMS/LandingContent/Index.php:7
* @route '/admin/cms/landing-content'
*/
landingContentForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: landingContent.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

landingContent.form = landingContentForm

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

/**
* @see \App\Livewire\Admin\CMS\TeamSection\Index::__invoke
* @see app/Livewire/Admin/CMS/TeamSection/Index.php:7
* @route '/admin/cms/team-sections'
*/
export const teamSections = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: teamSections.url(options),
    method: 'get',
})

teamSections.definition = {
    methods: ["get","head"],
    url: '/admin/cms/team-sections',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\CMS\TeamSection\Index::__invoke
* @see app/Livewire/Admin/CMS/TeamSection/Index.php:7
* @route '/admin/cms/team-sections'
*/
teamSections.url = (options?: RouteQueryOptions) => {
    return teamSections.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\CMS\TeamSection\Index::__invoke
* @see app/Livewire/Admin/CMS/TeamSection/Index.php:7
* @route '/admin/cms/team-sections'
*/
teamSections.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: teamSections.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\CMS\TeamSection\Index::__invoke
* @see app/Livewire/Admin/CMS/TeamSection/Index.php:7
* @route '/admin/cms/team-sections'
*/
teamSections.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: teamSections.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\CMS\TeamSection\Index::__invoke
* @see app/Livewire/Admin/CMS/TeamSection/Index.php:7
* @route '/admin/cms/team-sections'
*/
const teamSectionsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: teamSections.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\CMS\TeamSection\Index::__invoke
* @see app/Livewire/Admin/CMS/TeamSection/Index.php:7
* @route '/admin/cms/team-sections'
*/
teamSectionsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: teamSections.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\CMS\TeamSection\Index::__invoke
* @see app/Livewire/Admin/CMS/TeamSection/Index.php:7
* @route '/admin/cms/team-sections'
*/
teamSectionsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: teamSections.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

teamSections.form = teamSectionsForm

const cms = {
    landingContent: Object.assign(landingContent, landingContent),
    pageSections: Object.assign(pageSections, pageSections),
    structuralMembers: Object.assign(structuralMembers, structuralMembers),
    teamSections: Object.assign(teamSections, teamSections),
}

export default cms