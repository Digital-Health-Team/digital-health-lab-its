import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
const RedirectControllere9bf734ea3b998f11813daeea6aceb60 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllere9bf734ea3b998f11813daeea6aceb60.url(options),
    method: 'get',
})

RedirectControllere9bf734ea3b998f11813daeea6aceb60.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/admin/labs',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
RedirectControllere9bf734ea3b998f11813daeea6aceb60.url = (options?: RouteQueryOptions) => {
    return RedirectControllere9bf734ea3b998f11813daeea6aceb60.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
RedirectControllere9bf734ea3b998f11813daeea6aceb60.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllere9bf734ea3b998f11813daeea6aceb60.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
RedirectControllere9bf734ea3b998f11813daeea6aceb60.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectControllere9bf734ea3b998f11813daeea6aceb60.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
RedirectControllere9bf734ea3b998f11813daeea6aceb60.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectControllere9bf734ea3b998f11813daeea6aceb60.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
RedirectControllere9bf734ea3b998f11813daeea6aceb60.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectControllere9bf734ea3b998f11813daeea6aceb60.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
RedirectControllere9bf734ea3b998f11813daeea6aceb60.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectControllere9bf734ea3b998f11813daeea6aceb60.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
RedirectControllere9bf734ea3b998f11813daeea6aceb60.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectControllere9bf734ea3b998f11813daeea6aceb60.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
RedirectControllere9bf734ea3b998f11813daeea6aceb60.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectControllere9bf734ea3b998f11813daeea6aceb60.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
const RedirectControllere9bf734ea3b998f11813daeea6aceb60Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllere9bf734ea3b998f11813daeea6aceb60.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
RedirectControllere9bf734ea3b998f11813daeea6aceb60Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllere9bf734ea3b998f11813daeea6aceb60.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
RedirectControllere9bf734ea3b998f11813daeea6aceb60Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllere9bf734ea3b998f11813daeea6aceb60.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
RedirectControllere9bf734ea3b998f11813daeea6aceb60Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllere9bf734ea3b998f11813daeea6aceb60.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
RedirectControllere9bf734ea3b998f11813daeea6aceb60Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllere9bf734ea3b998f11813daeea6aceb60.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
RedirectControllere9bf734ea3b998f11813daeea6aceb60Form.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllere9bf734ea3b998f11813daeea6aceb60.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
RedirectControllere9bf734ea3b998f11813daeea6aceb60Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllere9bf734ea3b998f11813daeea6aceb60.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
RedirectControllere9bf734ea3b998f11813daeea6aceb60Form.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllere9bf734ea3b998f11813daeea6aceb60.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RedirectControllere9bf734ea3b998f11813daeea6aceb60.form = RedirectControllere9bf734ea3b998f11813daeea6aceb60Form
/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
const RedirectControllerfc38fa489c3b7d4df79f87069f8ee334 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.url(options),
    method: 'get',
})

RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/admin/raw-materials',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.url = (options?: RouteQueryOptions) => {
    return RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
const RedirectControllerfc38fa489c3b7d4df79f87069f8ee334Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
RedirectControllerfc38fa489c3b7d4df79f87069f8ee334Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
RedirectControllerfc38fa489c3b7d4df79f87069f8ee334Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
RedirectControllerfc38fa489c3b7d4df79f87069f8ee334Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
RedirectControllerfc38fa489c3b7d4df79f87069f8ee334Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
RedirectControllerfc38fa489c3b7d4df79f87069f8ee334Form.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
RedirectControllerfc38fa489c3b7d4df79f87069f8ee334Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
RedirectControllerfc38fa489c3b7d4df79f87069f8ee334Form.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RedirectControllerfc38fa489c3b7d4df79f87069f8ee334.form = RedirectControllerfc38fa489c3b7d4df79f87069f8ee334Form
/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
const RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.url(options),
    method: 'get',
})

RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/admin/master-data',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.url = (options?: RouteQueryOptions) => {
    return RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
const RedirectControllere154f04fe1e4fa37d7d3adef9b91b8ccForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
RedirectControllere154f04fe1e4fa37d7d3adef9b91b8ccForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
RedirectControllere154f04fe1e4fa37d7d3adef9b91b8ccForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
RedirectControllere154f04fe1e4fa37d7d3adef9b91b8ccForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
RedirectControllere154f04fe1e4fa37d7d3adef9b91b8ccForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
RedirectControllere154f04fe1e4fa37d7d3adef9b91b8ccForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
RedirectControllere154f04fe1e4fa37d7d3adef9b91b8ccForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
RedirectControllere154f04fe1e4fa37d7d3adef9b91b8ccForm.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc.form = RedirectControllere154f04fe1e4fa37d7d3adef9b91b8ccForm
/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/training'
*/
const RedirectController39f6b82f0f89cb68e876b6d89c5db2e6 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.url(options),
    method: 'get',
})

RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/training',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/training'
*/
RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.url = (options?: RouteQueryOptions) => {
    return RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/training'
*/
RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/training'
*/
RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/training'
*/
RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/training'
*/
RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/training'
*/
RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/training'
*/
RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/training'
*/
RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/training'
*/
const RedirectController39f6b82f0f89cb68e876b6d89c5db2e6Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/training'
*/
RedirectController39f6b82f0f89cb68e876b6d89c5db2e6Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/training'
*/
RedirectController39f6b82f0f89cb68e876b6d89c5db2e6Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/training'
*/
RedirectController39f6b82f0f89cb68e876b6d89c5db2e6Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/training'
*/
RedirectController39f6b82f0f89cb68e876b6d89c5db2e6Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/training'
*/
RedirectController39f6b82f0f89cb68e876b6d89c5db2e6Form.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/training'
*/
RedirectController39f6b82f0f89cb68e876b6d89c5db2e6Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/training'
*/
RedirectController39f6b82f0f89cb68e876b6d89c5db2e6Form.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RedirectController39f6b82f0f89cb68e876b6d89c5db2e6.form = RedirectController39f6b82f0f89cb68e876b6d89c5db2e6Form

const RedirectController = {
    '/admin/labs': RedirectControllere9bf734ea3b998f11813daeea6aceb60,
    '/admin/raw-materials': RedirectControllerfc38fa489c3b7d4df79f87069f8ee334,
    '/admin/master-data': RedirectControllere154f04fe1e4fa37d7d3adef9b91b8cc,
    '/training': RedirectController39f6b82f0f89cb68e876b6d89c5db2e6,
}

export default RedirectController