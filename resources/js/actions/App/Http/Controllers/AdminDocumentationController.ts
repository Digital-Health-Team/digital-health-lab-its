import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\AdminDocumentationController::__invoke
* @see app/Http/Controllers/AdminDocumentationController.php:9
* @route '/admin/documentations'
*/
const AdminDocumentationController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: AdminDocumentationController.url(options),
    method: 'get',
})

AdminDocumentationController.definition = {
    methods: ["get","head"],
    url: '/admin/documentations',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AdminDocumentationController::__invoke
* @see app/Http/Controllers/AdminDocumentationController.php:9
* @route '/admin/documentations'
*/
AdminDocumentationController.url = (options?: RouteQueryOptions) => {
    return AdminDocumentationController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AdminDocumentationController::__invoke
* @see app/Http/Controllers/AdminDocumentationController.php:9
* @route '/admin/documentations'
*/
AdminDocumentationController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: AdminDocumentationController.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\AdminDocumentationController::__invoke
* @see app/Http/Controllers/AdminDocumentationController.php:9
* @route '/admin/documentations'
*/
AdminDocumentationController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: AdminDocumentationController.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\AdminDocumentationController::__invoke
* @see app/Http/Controllers/AdminDocumentationController.php:9
* @route '/admin/documentations'
*/
const AdminDocumentationControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: AdminDocumentationController.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\AdminDocumentationController::__invoke
* @see app/Http/Controllers/AdminDocumentationController.php:9
* @route '/admin/documentations'
*/
AdminDocumentationControllerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: AdminDocumentationController.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\AdminDocumentationController::__invoke
* @see app/Http/Controllers/AdminDocumentationController.php:9
* @route '/admin/documentations'
*/
AdminDocumentationControllerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: AdminDocumentationController.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

AdminDocumentationController.form = AdminDocumentationControllerForm

export default AdminDocumentationController