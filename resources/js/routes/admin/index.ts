import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
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

const admin = {
    globalSearch: Object.assign(globalSearch, globalSearch),
    dashboard: Object.assign(dashboard, dashboard),
    users: Object.assign(users, users),
}

export default admin