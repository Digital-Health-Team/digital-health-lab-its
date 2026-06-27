import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\User\OrderController::catalog
* @see app/Http/Controllers/User/OrderController.php:25
* @route '/services'
*/
export const catalog = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: catalog.url(options),
    method: 'get',
})

catalog.definition = {
    methods: ["get","head"],
    url: '/services',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\User\OrderController::catalog
* @see app/Http/Controllers/User/OrderController.php:25
* @route '/services'
*/
catalog.url = (options?: RouteQueryOptions) => {
    return catalog.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\User\OrderController::catalog
* @see app/Http/Controllers/User/OrderController.php:25
* @route '/services'
*/
catalog.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: catalog.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\OrderController::catalog
* @see app/Http/Controllers/User/OrderController.php:25
* @route '/services'
*/
catalog.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: catalog.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\User\OrderController::catalog
* @see app/Http/Controllers/User/OrderController.php:25
* @route '/services'
*/
const catalogForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: catalog.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\OrderController::catalog
* @see app/Http/Controllers/User/OrderController.php:25
* @route '/services'
*/
catalogForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: catalog.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\OrderController::catalog
* @see app/Http/Controllers/User/OrderController.php:25
* @route '/services'
*/
catalogForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: catalog.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

catalog.form = catalogForm

const services = {
    catalog: Object.assign(catalog, catalog),
}

export default services