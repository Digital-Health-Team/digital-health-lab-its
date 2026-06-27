import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\User\PortfolioController::index
* @see app/Http/Controllers/User/PortfolioController.php:15
* @route '/portfolio'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/portfolio',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\User\PortfolioController::index
* @see app/Http/Controllers/User/PortfolioController.php:15
* @route '/portfolio'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\User\PortfolioController::index
* @see app/Http/Controllers/User/PortfolioController.php:15
* @route '/portfolio'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\PortfolioController::index
* @see app/Http/Controllers/User/PortfolioController.php:15
* @route '/portfolio'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\User\PortfolioController::index
* @see app/Http/Controllers/User/PortfolioController.php:15
* @route '/portfolio'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\PortfolioController::index
* @see app/Http/Controllers/User/PortfolioController.php:15
* @route '/portfolio'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\PortfolioController::index
* @see app/Http/Controllers/User/PortfolioController.php:15
* @route '/portfolio'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

const portfolio = {
    index: Object.assign(index, index),
}

export default portfolio