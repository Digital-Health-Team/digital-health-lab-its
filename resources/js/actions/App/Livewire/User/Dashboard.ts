import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Livewire\User\Dashboard::__invoke
* @see app/Livewire/User/Dashboard.php:7
* @route '/user/dashboard'
*/
const Dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboard.url(options),
    method: 'get',
})

Dashboard.definition = {
    methods: ["get","head"],
    url: '/user/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\User\Dashboard::__invoke
* @see app/Livewire/User/Dashboard.php:7
* @route '/user/dashboard'
*/
Dashboard.url = (options?: RouteQueryOptions) => {
    return Dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\User\Dashboard::__invoke
* @see app/Livewire/User/Dashboard.php:7
* @route '/user/dashboard'
*/
Dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\User\Dashboard::__invoke
* @see app/Livewire/User/Dashboard.php:7
* @route '/user/dashboard'
*/
Dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Dashboard.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\User\Dashboard::__invoke
* @see app/Livewire/User/Dashboard.php:7
* @route '/user/dashboard'
*/
const DashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\User\Dashboard::__invoke
* @see app/Livewire/User/Dashboard.php:7
* @route '/user/dashboard'
*/
DashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\User\Dashboard::__invoke
* @see app/Livewire/User/Dashboard.php:7
* @route '/user/dashboard'
*/
DashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboard.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

Dashboard.form = DashboardForm

export default Dashboard