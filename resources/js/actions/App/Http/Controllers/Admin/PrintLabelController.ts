import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\PrintLabelController::__invoke
* @see app/Http/Controllers/Admin/PrintLabelController.php:15
* @route '/admin/print-label/{type}/{id}'
*/
const PrintLabelController = (args: { type: string | number, id: string | number } | [type: string | number, id: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: PrintLabelController.url(args, options),
    method: 'get',
})

PrintLabelController.definition = {
    methods: ["get","head"],
    url: '/admin/print-label/{type}/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\PrintLabelController::__invoke
* @see app/Http/Controllers/Admin/PrintLabelController.php:15
* @route '/admin/print-label/{type}/{id}'
*/
PrintLabelController.url = (args: { type: string | number, id: string | number } | [type: string | number, id: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            type: args[0],
            id: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        type: args.type,
        id: args.id,
    }

    return PrintLabelController.definition.url
            .replace('{type}', parsedArgs.type.toString())
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\PrintLabelController::__invoke
* @see app/Http/Controllers/Admin/PrintLabelController.php:15
* @route '/admin/print-label/{type}/{id}'
*/
PrintLabelController.get = (args: { type: string | number, id: string | number } | [type: string | number, id: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: PrintLabelController.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\PrintLabelController::__invoke
* @see app/Http/Controllers/Admin/PrintLabelController.php:15
* @route '/admin/print-label/{type}/{id}'
*/
PrintLabelController.head = (args: { type: string | number, id: string | number } | [type: string | number, id: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: PrintLabelController.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\PrintLabelController::__invoke
* @see app/Http/Controllers/Admin/PrintLabelController.php:15
* @route '/admin/print-label/{type}/{id}'
*/
const PrintLabelControllerForm = (args: { type: string | number, id: string | number } | [type: string | number, id: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: PrintLabelController.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\PrintLabelController::__invoke
* @see app/Http/Controllers/Admin/PrintLabelController.php:15
* @route '/admin/print-label/{type}/{id}'
*/
PrintLabelControllerForm.get = (args: { type: string | number, id: string | number } | [type: string | number, id: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: PrintLabelController.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\PrintLabelController::__invoke
* @see app/Http/Controllers/Admin/PrintLabelController.php:15
* @route '/admin/print-label/{type}/{id}'
*/
PrintLabelControllerForm.head = (args: { type: string | number, id: string | number } | [type: string | number, id: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: PrintLabelController.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

PrintLabelController.form = PrintLabelControllerForm

export default PrintLabelController