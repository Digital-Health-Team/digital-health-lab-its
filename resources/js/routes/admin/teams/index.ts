import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Livewire\Admin\Event\Team\Index::__invoke
* @see app/Livewire/Admin/Event/Team/Index.php:7
* @route '/admin/events/teams/{team}'
*/
export const show = (args: { team: string | number } | [team: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/events/teams/{team}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Event\Team\Index::__invoke
* @see app/Livewire/Admin/Event/Team/Index.php:7
* @route '/admin/events/teams/{team}'
*/
show.url = (args: { team: string | number } | [team: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { team: args }
    }

    if (Array.isArray(args)) {
        args = {
            team: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        team: args.team,
    }

    return show.definition.url
            .replace('{team}', parsedArgs.team.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Event\Team\Index::__invoke
* @see app/Livewire/Admin/Event/Team/Index.php:7
* @route '/admin/events/teams/{team}'
*/
show.get = (args: { team: string | number } | [team: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Event\Team\Index::__invoke
* @see app/Livewire/Admin/Event/Team/Index.php:7
* @route '/admin/events/teams/{team}'
*/
show.head = (args: { team: string | number } | [team: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\Event\Team\Index::__invoke
* @see app/Livewire/Admin/Event/Team/Index.php:7
* @route '/admin/events/teams/{team}'
*/
const showForm = (args: { team: string | number } | [team: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Event\Team\Index::__invoke
* @see app/Livewire/Admin/Event/Team/Index.php:7
* @route '/admin/events/teams/{team}'
*/
showForm.get = (args: { team: string | number } | [team: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Event\Team\Index::__invoke
* @see app/Livewire/Admin/Event/Team/Index.php:7
* @route '/admin/events/teams/{team}'
*/
showForm.head = (args: { team: string | number } | [team: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

const teams = {
    show: Object.assign(show, show),
}

export default teams