import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Livewire\Admin\GlobalSearch::__invoke
* @see app/Livewire/Admin/GlobalSearch.php:7
* @route '/admin/global-search'
*/
export const globalSearch = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: globalSearch.url(options),
    method: 'get',
})

globalSearch.definition = {
    methods: ["get","head"],
    url: '/admin/global-search',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\GlobalSearch::__invoke
* @see app/Livewire/Admin/GlobalSearch.php:7
* @route '/admin/global-search'
*/
globalSearch.url = (options?: RouteQueryOptions) => {
    return globalSearch.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\GlobalSearch::__invoke
* @see app/Livewire/Admin/GlobalSearch.php:7
* @route '/admin/global-search'
*/
globalSearch.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: globalSearch.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\GlobalSearch::__invoke
* @see app/Livewire/Admin/GlobalSearch.php:7
* @route '/admin/global-search'
*/
globalSearch.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: globalSearch.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\GlobalSearch::__invoke
* @see app/Livewire/Admin/GlobalSearch.php:7
* @route '/admin/global-search'
*/
const globalSearchForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: globalSearch.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\GlobalSearch::__invoke
* @see app/Livewire/Admin/GlobalSearch.php:7
* @route '/admin/global-search'
*/
globalSearchForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: globalSearch.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\GlobalSearch::__invoke
* @see app/Livewire/Admin/GlobalSearch.php:7
* @route '/admin/global-search'
*/
globalSearchForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: globalSearch.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

globalSearch.form = globalSearchForm

/**
* @see \App\Livewire\Admin\Dashboard::__invoke
* @see app/Livewire/Admin/Dashboard.php:7
* @route '/admin/dashboard'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/admin/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Dashboard::__invoke
* @see app/Livewire/Admin/Dashboard.php:7
* @route '/admin/dashboard'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Dashboard::__invoke
* @see app/Livewire/Admin/Dashboard.php:7
* @route '/admin/dashboard'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Dashboard::__invoke
* @see app/Livewire/Admin/Dashboard.php:7
* @route '/admin/dashboard'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\Dashboard::__invoke
* @see app/Livewire/Admin/Dashboard.php:7
* @route '/admin/dashboard'
*/
const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Dashboard::__invoke
* @see app/Livewire/Admin/Dashboard.php:7
* @route '/admin/dashboard'
*/
dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Dashboard::__invoke
* @see app/Livewire/Admin/Dashboard.php:7
* @route '/admin/dashboard'
*/
dashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

dashboard.form = dashboardForm

/**
* @see \App\Livewire\Admin\User\Index::__invoke
* @see app/Livewire/Admin/User/Index.php:7
* @route '/admin/users'
*/
export const users = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: users.url(options),
    method: 'get',
})

users.definition = {
    methods: ["get","head"],
    url: '/admin/users',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\User\Index::__invoke
* @see app/Livewire/Admin/User/Index.php:7
* @route '/admin/users'
*/
users.url = (options?: RouteQueryOptions) => {
    return users.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\User\Index::__invoke
* @see app/Livewire/Admin/User/Index.php:7
* @route '/admin/users'
*/
users.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: users.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\User\Index::__invoke
* @see app/Livewire/Admin/User/Index.php:7
* @route '/admin/users'
*/
users.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: users.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\User\Index::__invoke
* @see app/Livewire/Admin/User/Index.php:7
* @route '/admin/users'
*/
const usersForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: users.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\User\Index::__invoke
* @see app/Livewire/Admin/User/Index.php:7
* @route '/admin/users'
*/
usersForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: users.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\User\Index::__invoke
* @see app/Livewire/Admin/User/Index.php:7
* @route '/admin/users'
*/
usersForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: users.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

users.form = usersForm

const admin = {
    globalSearch: Object.assign(globalSearch, globalSearch),
    dashboard: Object.assign(dashboard, dashboard),
    users: Object.assign(users, users),
}

export default admin