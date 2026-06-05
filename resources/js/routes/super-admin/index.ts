import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Livewire\SuperAdmin\Dashboard\Index::__invoke
* @see app/Livewire/SuperAdmin/Dashboard/Index.php:7
* @route '/super-admin/dashboard'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/super-admin/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\SuperAdmin\Dashboard\Index::__invoke
* @see app/Livewire/SuperAdmin/Dashboard/Index.php:7
* @route '/super-admin/dashboard'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\SuperAdmin\Dashboard\Index::__invoke
* @see app/Livewire/SuperAdmin/Dashboard/Index.php:7
* @route '/super-admin/dashboard'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\SuperAdmin\Dashboard\Index::__invoke
* @see app/Livewire/SuperAdmin/Dashboard/Index.php:7
* @route '/super-admin/dashboard'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\SuperAdmin\Dashboard\Index::__invoke
* @see app/Livewire/SuperAdmin/Dashboard/Index.php:7
* @route '/super-admin/dashboard'
*/
const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\SuperAdmin\Dashboard\Index::__invoke
* @see app/Livewire/SuperAdmin/Dashboard/Index.php:7
* @route '/super-admin/dashboard'
*/
dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\SuperAdmin\Dashboard\Index::__invoke
* @see app/Livewire/SuperAdmin/Dashboard/Index.php:7
* @route '/super-admin/dashboard'
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

const superAdmin = {
    dashboard: Object.assign(dashboard, dashboard),
}

export default superAdmin