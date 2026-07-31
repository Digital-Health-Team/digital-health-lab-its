import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\ScanMaterialController::__invoke
* @see app/Http/Controllers/ScanMaterialController.php:11
* @route '/scan/bahan/{unique_code}'
*/
export const material = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: material.url(args, options),
    method: 'get',
})

material.definition = {
    methods: ["get","head"],
    url: '/scan/bahan/{unique_code}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ScanMaterialController::__invoke
* @see app/Http/Controllers/ScanMaterialController.php:11
* @route '/scan/bahan/{unique_code}'
*/
material.url = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return material.definition.url
            .replace('{unique_code}', parsedArgs.unique_code.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ScanMaterialController::__invoke
* @see app/Http/Controllers/ScanMaterialController.php:11
* @route '/scan/bahan/{unique_code}'
*/
material.get = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: material.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ScanMaterialController::__invoke
* @see app/Http/Controllers/ScanMaterialController.php:11
* @route '/scan/bahan/{unique_code}'
*/
material.head = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: material.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ScanMaterialController::__invoke
* @see app/Http/Controllers/ScanMaterialController.php:11
* @route '/scan/bahan/{unique_code}'
*/
const materialForm = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: material.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ScanMaterialController::__invoke
* @see app/Http/Controllers/ScanMaterialController.php:11
* @route '/scan/bahan/{unique_code}'
*/
materialForm.get = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: material.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ScanMaterialController::__invoke
* @see app/Http/Controllers/ScanMaterialController.php:11
* @route '/scan/bahan/{unique_code}'
*/
materialForm.head = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: material.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

material.form = materialForm

/**
* @see \App\Http\Controllers\ScanToolController::__invoke
* @see app/Http/Controllers/ScanToolController.php:11
* @route '/scan/alat/{unique_code}'
*/
export const tool = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: tool.url(args, options),
    method: 'get',
})

tool.definition = {
    methods: ["get","head"],
    url: '/scan/alat/{unique_code}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ScanToolController::__invoke
* @see app/Http/Controllers/ScanToolController.php:11
* @route '/scan/alat/{unique_code}'
*/
tool.url = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return tool.definition.url
            .replace('{unique_code}', parsedArgs.unique_code.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ScanToolController::__invoke
* @see app/Http/Controllers/ScanToolController.php:11
* @route '/scan/alat/{unique_code}'
*/
tool.get = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: tool.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ScanToolController::__invoke
* @see app/Http/Controllers/ScanToolController.php:11
* @route '/scan/alat/{unique_code}'
*/
tool.head = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: tool.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ScanToolController::__invoke
* @see app/Http/Controllers/ScanToolController.php:11
* @route '/scan/alat/{unique_code}'
*/
const toolForm = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: tool.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ScanToolController::__invoke
* @see app/Http/Controllers/ScanToolController.php:11
* @route '/scan/alat/{unique_code}'
*/
toolForm.get = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: tool.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ScanToolController::__invoke
* @see app/Http/Controllers/ScanToolController.php:11
* @route '/scan/alat/{unique_code}'
*/
toolForm.head = (args: { unique_code: string | number } | [unique_code: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: tool.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

tool.form = toolForm

const scan = {
    material: Object.assign(material, material),
    tool: Object.assign(tool, tool),
}

export default scan