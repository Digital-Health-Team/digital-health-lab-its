import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\LocaleController::update
* @see app/Http/Controllers/LocaleController.php:13
* @route '/locale'
*/
export const update = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(options),
    method: 'post',
})

update.definition = {
    methods: ["post"],
    url: '/locale',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\LocaleController::update
* @see app/Http/Controllers/LocaleController.php:13
* @route '/locale'
*/
update.url = (options?: RouteQueryOptions) => {
    return update.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\LocaleController::update
* @see app/Http/Controllers/LocaleController.php:13
* @route '/locale'
*/
update.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\LocaleController::update
* @see app/Http/Controllers/LocaleController.php:13
* @route '/locale'
*/
const updateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\LocaleController::update
* @see app/Http/Controllers/LocaleController.php:13
* @route '/locale'
*/
updateForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(options),
    method: 'post',
})

update.form = updateForm

const LocaleController = { update }

export default LocaleController