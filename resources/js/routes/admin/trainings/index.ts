import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Livewire\Admin\Training\Show::__invoke
* @see app/Livewire/Admin/Training/Show.php:7
* @route '/admin/trainings/{training}'
*/
export const show = (args: { training: string | number } | [training: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/trainings/{training}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Training\Show::__invoke
* @see app/Livewire/Admin/Training/Show.php:7
* @route '/admin/trainings/{training}'
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
* @see \App\Livewire\Admin\Training\Show::__invoke
* @see app/Livewire/Admin/Training/Show.php:7
* @route '/admin/trainings/{training}'
*/
show.get = (args: { training: string | number } | [training: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Training\Show::__invoke
* @see app/Livewire/Admin/Training/Show.php:7
* @route '/admin/trainings/{training}'
*/
show.head = (args: { training: string | number } | [training: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\Training\Show::__invoke
* @see app/Livewire/Admin/Training/Show.php:7
* @route '/admin/trainings/{training}'
*/
const showForm = (args: { training: string | number } | [training: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Training\Show::__invoke
* @see app/Livewire/Admin/Training/Show.php:7
* @route '/admin/trainings/{training}'
*/
showForm.get = (args: { training: string | number } | [training: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Training\Show::__invoke
* @see app/Livewire/Admin/Training/Show.php:7
* @route '/admin/trainings/{training}'
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

const trainings = {
    show: Object.assign(show, show),
}

export default trainings