import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ScanMaterialController::__invoke
* @see app/Http/Controllers/ScanMaterialController.php:11
* @route '/scan/bahan/{unique_code}'
*/
const ScanMaterialController = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ScanMaterialController.url(args, options),
    method: 'get',
})

ScanMaterialController.definition = {
    methods: ["get","head"],
    url: '/scan/bahan/{unique_code}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ScanMaterialController::__invoke
* @see app/Http/Controllers/ScanMaterialController.php:11
* @route '/scan/bahan/{unique_code}'
*/
ScanMaterialController.url = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return ScanMaterialController.definition.url
            .replace('{unique_code}', parsedArgs.unique_code.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ScanMaterialController::__invoke
* @see app/Http/Controllers/ScanMaterialController.php:11
* @route '/scan/bahan/{unique_code}'
*/
ScanMaterialController.get = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ScanMaterialController.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ScanMaterialController::__invoke
* @see app/Http/Controllers/ScanMaterialController.php:11
* @route '/scan/bahan/{unique_code}'
*/
ScanMaterialController.head = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ScanMaterialController.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ScanMaterialController::__invoke
* @see app/Http/Controllers/ScanMaterialController.php:11
* @route '/scan/bahan/{unique_code}'
*/
const ScanMaterialControllerForm = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ScanMaterialController.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ScanMaterialController::__invoke
* @see app/Http/Controllers/ScanMaterialController.php:11
* @route '/scan/bahan/{unique_code}'
*/
ScanMaterialControllerForm.get = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ScanMaterialController.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ScanMaterialController::__invoke
* @see app/Http/Controllers/ScanMaterialController.php:11
* @route '/scan/bahan/{unique_code}'
*/
ScanMaterialControllerForm.head = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ScanMaterialController.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ScanMaterialController.form = ScanMaterialControllerForm

export default ScanMaterialController