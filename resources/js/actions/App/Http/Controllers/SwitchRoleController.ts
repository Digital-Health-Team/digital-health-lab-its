import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\SwitchRoleController::__invoke
* @see app/Http/Controllers/SwitchRoleController.php:10
* @route '/switch-role'
*/
const SwitchRoleController = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: SwitchRoleController.url(options),
    method: 'post',
})

SwitchRoleController.definition = {
    methods: ["post"],
    url: '/switch-role',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\SwitchRoleController::__invoke
* @see app/Http/Controllers/SwitchRoleController.php:10
* @route '/switch-role'
*/
SwitchRoleController.url = (options?: RouteQueryOptions) => {
    return SwitchRoleController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\SwitchRoleController::__invoke
* @see app/Http/Controllers/SwitchRoleController.php:10
* @route '/switch-role'
*/
SwitchRoleController.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: SwitchRoleController.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\SwitchRoleController::__invoke
* @see app/Http/Controllers/SwitchRoleController.php:10
* @route '/switch-role'
*/
const SwitchRoleControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: SwitchRoleController.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\SwitchRoleController::__invoke
* @see app/Http/Controllers/SwitchRoleController.php:10
* @route '/switch-role'
*/
SwitchRoleControllerForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: SwitchRoleController.url(options),
    method: 'post',
})

SwitchRoleController.form = SwitchRoleControllerForm

export default SwitchRoleController