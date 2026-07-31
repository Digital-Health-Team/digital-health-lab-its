import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\DevDocumentationController::__invoke
* @see app/Http/Controllers/DevDocumentationController.php:9
* @route '/dev/documentations'
*/
const DevDocumentationController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: DevDocumentationController.url(options),
    method: 'get',
})

DevDocumentationController.definition = {
    methods: ["get","head"],
    url: '/dev/documentations',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DevDocumentationController::__invoke
* @see app/Http/Controllers/DevDocumentationController.php:9
* @route '/dev/documentations'
*/
DevDocumentationController.url = (options?: RouteQueryOptions) => {
    return DevDocumentationController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DevDocumentationController::__invoke
* @see app/Http/Controllers/DevDocumentationController.php:9
* @route '/dev/documentations'
*/
DevDocumentationController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: DevDocumentationController.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DevDocumentationController::__invoke
* @see app/Http/Controllers/DevDocumentationController.php:9
* @route '/dev/documentations'
*/
DevDocumentationController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: DevDocumentationController.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DevDocumentationController::__invoke
* @see app/Http/Controllers/DevDocumentationController.php:9
* @route '/dev/documentations'
*/
const DevDocumentationControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: DevDocumentationController.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DevDocumentationController::__invoke
* @see app/Http/Controllers/DevDocumentationController.php:9
* @route '/dev/documentations'
*/
DevDocumentationControllerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: DevDocumentationController.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DevDocumentationController::__invoke
* @see app/Http/Controllers/DevDocumentationController.php:9
* @route '/dev/documentations'
*/
DevDocumentationControllerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: DevDocumentationController.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

DevDocumentationController.form = DevDocumentationControllerForm

export default DevDocumentationController