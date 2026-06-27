import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\PameranController::index
* @see app/Http/Controllers/PameranController.php:10
* @route '/exhibition/{exhibition_name}'
*/
export const index = (args: { exhibition_name: string | number } | [exhibition_name: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/exhibition/{exhibition_name}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PameranController::index
* @see app/Http/Controllers/PameranController.php:10
* @route '/exhibition/{exhibition_name}'
*/
index.url = (args: { exhibition_name: string | number } | [exhibition_name: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { exhibition_name: args }
    }

    if (Array.isArray(args)) {
        args = {
            exhibition_name: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        exhibition_name: args.exhibition_name,
    }

    return index.definition.url
            .replace('{exhibition_name}', parsedArgs.exhibition_name.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PameranController::index
* @see app/Http/Controllers/PameranController.php:10
* @route '/exhibition/{exhibition_name}'
*/
index.get = (args: { exhibition_name: string | number } | [exhibition_name: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PameranController::index
* @see app/Http/Controllers/PameranController.php:10
* @route '/exhibition/{exhibition_name}'
*/
index.head = (args: { exhibition_name: string | number } | [exhibition_name: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\PameranController::index
* @see app/Http/Controllers/PameranController.php:10
* @route '/exhibition/{exhibition_name}'
*/
const indexForm = (args: { exhibition_name: string | number } | [exhibition_name: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PameranController::index
* @see app/Http/Controllers/PameranController.php:10
* @route '/exhibition/{exhibition_name}'
*/
indexForm.get = (args: { exhibition_name: string | number } | [exhibition_name: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PameranController::index
* @see app/Http/Controllers/PameranController.php:10
* @route '/exhibition/{exhibition_name}'
*/
indexForm.head = (args: { exhibition_name: string | number } | [exhibition_name: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

const PameranController = { index }

export default PameranController