import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Livewire\Gudang\Dashboard\Index::__invoke
* @see app/Livewire/Gudang/Dashboard/Index.php:7
* @route '/gudang/dashboard'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/gudang/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Gudang\Dashboard\Index::__invoke
* @see app/Livewire/Gudang/Dashboard/Index.php:7
* @route '/gudang/dashboard'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Gudang\Dashboard\Index::__invoke
* @see app/Livewire/Gudang/Dashboard/Index.php:7
* @route '/gudang/dashboard'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Gudang\Dashboard\Index::__invoke
* @see app/Livewire/Gudang/Dashboard/Index.php:7
* @route '/gudang/dashboard'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Gudang\Dashboard\Index::__invoke
* @see app/Livewire/Gudang/Dashboard/Index.php:7
* @route '/gudang/dashboard'
*/
const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Gudang\Dashboard\Index::__invoke
* @see app/Livewire/Gudang/Dashboard/Index.php:7
* @route '/gudang/dashboard'
*/
dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Gudang\Dashboard\Index::__invoke
* @see app/Livewire/Gudang/Dashboard/Index.php:7
* @route '/gudang/dashboard'
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
* @see \App\Livewire\Gudang\Orders\Index::__invoke
* @see app/Livewire/Gudang/Orders/Index.php:7
* @route '/gudang/orders'
*/
export const orders = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: orders.url(options),
    method: 'get',
})

orders.definition = {
    methods: ["get","head"],
    url: '/gudang/orders',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Gudang\Orders\Index::__invoke
* @see app/Livewire/Gudang/Orders/Index.php:7
* @route '/gudang/orders'
*/
orders.url = (options?: RouteQueryOptions) => {
    return orders.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Gudang\Orders\Index::__invoke
* @see app/Livewire/Gudang/Orders/Index.php:7
* @route '/gudang/orders'
*/
orders.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: orders.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Gudang\Orders\Index::__invoke
* @see app/Livewire/Gudang/Orders/Index.php:7
* @route '/gudang/orders'
*/
orders.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: orders.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Gudang\Orders\Index::__invoke
* @see app/Livewire/Gudang/Orders/Index.php:7
* @route '/gudang/orders'
*/
const ordersForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: orders.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Gudang\Orders\Index::__invoke
* @see app/Livewire/Gudang/Orders/Index.php:7
* @route '/gudang/orders'
*/
ordersForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: orders.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Gudang\Orders\Index::__invoke
* @see app/Livewire/Gudang/Orders/Index.php:7
* @route '/gudang/orders'
*/
ordersForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: orders.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

orders.form = ordersForm

const gudang = {
    dashboard: Object.assign(dashboard, dashboard),
    orders: Object.assign(orders, orders),
}

export default gudang