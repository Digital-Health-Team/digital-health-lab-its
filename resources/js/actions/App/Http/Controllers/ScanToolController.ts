import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ScanToolController::__invoke
* @see app/Http/Controllers/ScanToolController.php:11
* @route '/scan/alat/{unique_code}'
*/
const ScanToolController = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ScanToolController.url(args, options),
    method: 'get',
})

ScanToolController.definition = {
    methods: ["get","head"],
    url: '/scan/alat/{unique_code}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ScanToolController::__invoke
* @see app/Http/Controllers/ScanToolController.php:11
* @route '/scan/alat/{unique_code}'
*/
ScanToolController.url = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { unique_code: args }
    }

    if (Array.isArray(args)) {
        args = {
            unique_code: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        unique_code: args.unique_code,
    }

    return ScanToolController.definition.url
            .replace('{unique_code}', parsedArgs.unique_code.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ScanToolController::__invoke
* @see app/Http/Controllers/ScanToolController.php:11
* @route '/scan/alat/{unique_code}'
*/
ScanToolController.get = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ScanToolController.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ScanToolController::__invoke
* @see app/Http/Controllers/ScanToolController.php:11
* @route '/scan/alat/{unique_code}'
*/
ScanToolController.head = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ScanToolController.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ScanToolController::__invoke
* @see app/Http/Controllers/ScanToolController.php:11
* @route '/scan/alat/{unique_code}'
*/
const ScanToolControllerForm = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ScanToolController.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ScanToolController::__invoke
* @see app/Http/Controllers/ScanToolController.php:11
* @route '/scan/alat/{unique_code}'
*/
ScanToolControllerForm.get = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ScanToolController.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ScanToolController::__invoke
* @see app/Http/Controllers/ScanToolController.php:11
* @route '/scan/alat/{unique_code}'
*/
ScanToolControllerForm.head = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ScanToolController.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ScanToolController.form = ScanToolControllerForm

export default ScanToolController