import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Livewire\Admin\Training\Show::__invoke
* @see app/Livewire/Admin/Training/Show.php:7
* @route '/admin/trainings/{training}'
*/
const Show = (args: { training: string | number } | [training: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Show.url(args, options),
    method: 'get',
})

Show.definition = {
    methods: ["get","head"],
    url: '/admin/trainings/{training}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Training\Show::__invoke
* @see app/Livewire/Admin/Training/Show.php:7
* @route '/admin/trainings/{training}'
*/
Show.url = (args: { training: string | number } | [training: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return Show.definition.url
            .replace('{training}', parsedArgs.training.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Training\Show::__invoke
* @see app/Livewire/Admin/Training/Show.php:7
* @route '/admin/trainings/{training}'
*/
Show.get = (args: { training: string | number } | [training: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Show.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Training\Show::__invoke
* @see app/Livewire/Admin/Training/Show.php:7
* @route '/admin/trainings/{training}'
*/
Show.head = (args: { training: string | number } | [training: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Show.url(args, options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\Training\Show::__invoke
* @see app/Livewire/Admin/Training/Show.php:7
* @route '/admin/trainings/{training}'
*/
const ShowForm = (args: { training: string | number } | [training: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Show.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Training\Show::__invoke
* @see app/Livewire/Admin/Training/Show.php:7
* @route '/admin/trainings/{training}'
*/
ShowForm.get = (args: { training: string | number } | [training: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Show.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Training\Show::__invoke
* @see app/Livewire/Admin/Training/Show.php:7
* @route '/admin/trainings/{training}'
*/
ShowForm.head = (args: { training: string | number } | [training: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

Show.form = ShowForm

export default Show