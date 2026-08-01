import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\DevDocumentationController::__invoke
* @see app/Http/Controllers/DevDocumentationController.php:9
* @route '/dev/documentations'
*/
export const documentation = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: documentation.url(options),
    method: 'get',
})

documentation.definition = {
    methods: ["get","head"],
    url: '/dev/documentations',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DevDocumentationController::__invoke
* @see app/Http/Controllers/DevDocumentationController.php:9
* @route '/dev/documentations'
*/
documentation.url = (options?: RouteQueryOptions) => {
    return documentation.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DevDocumentationController::__invoke
* @see app/Http/Controllers/DevDocumentationController.php:9
* @route '/dev/documentations'
*/
documentation.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: documentation.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DevDocumentationController::__invoke
* @see app/Http/Controllers/DevDocumentationController.php:9
* @route '/dev/documentations'
*/
documentation.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: documentation.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DevDocumentationController::__invoke
* @see app/Http/Controllers/DevDocumentationController.php:9
* @route '/dev/documentations'
*/
const documentationForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: documentation.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DevDocumentationController::__invoke
* @see app/Http/Controllers/DevDocumentationController.php:9
* @route '/dev/documentations'
*/
documentationForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: documentation.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DevDocumentationController::__invoke
* @see app/Http/Controllers/DevDocumentationController.php:9
* @route '/dev/documentations'
*/
documentationForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: documentation.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

documentation.form = documentationForm

const dev = {
    documentation: Object.assign(documentation, documentation),
}

export default dev