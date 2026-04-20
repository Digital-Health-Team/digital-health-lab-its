import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
/**
* @see \App\Livewire\User\Dashboard::__invoke
* @see app/Livewire/User/Dashboard.php:7
* @route '/user/dashboard'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/user/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\User\Dashboard::__invoke
* @see app/Livewire/User/Dashboard.php:7
* @route '/user/dashboard'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\User\Dashboard::__invoke
* @see app/Livewire/User/Dashboard.php:7
* @route '/user/dashboard'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\User\Dashboard::__invoke
* @see app/Livewire/User/Dashboard.php:7
* @route '/user/dashboard'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

const user = {
    dashboard: Object.assign(dashboard, dashboard),
}

export default user