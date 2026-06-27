import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\TrainingController::show
* @see app/Http/Controllers/TrainingController.php:15
* @route '/training/{training}'
*/
export const show = (args: { training: string | number } | [training: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/training/{training}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\TrainingController::show
* @see app/Http/Controllers/TrainingController.php:15
* @route '/training/{training}'
*/
show.url = (args: { training: string | number } | [training: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { training: args }
    }

    if (Array.isArray(args)) {
        args = {
            training: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        training: args.training,
    }

    return show.definition.url
            .replace('{training}', parsedArgs.training.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TrainingController::show
* @see app/Http/Controllers/TrainingController.php:15
* @route '/training/{training}'
*/
show.get = (args: { training: string | number } | [training: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\TrainingController::show
* @see app/Http/Controllers/TrainingController.php:15
* @route '/training/{training}'
*/
show.head = (args: { training: string | number } | [training: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\TrainingController::show
* @see app/Http/Controllers/TrainingController.php:15
* @route '/training/{training}'
*/
const showForm = (args: { training: string | number } | [training: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\TrainingController::show
* @see app/Http/Controllers/TrainingController.php:15
* @route '/training/{training}'
*/
showForm.get = (args: { training: string | number } | [training: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\TrainingController::show
* @see app/Http/Controllers/TrainingController.php:15
* @route '/training/{training}'
*/
showForm.head = (args: { training: string | number } | [training: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

const training = {
    show: Object.assign(show, show),
}

export default training