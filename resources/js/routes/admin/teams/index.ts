import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Livewire\Admin\Event\Team\Index::__invoke
* @see app/Livewire/Admin/Event/Team/Index.php:7
* @route '/admin/teams/{team}'
*/
export const show = (args: { team: string | number } | [team: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/teams/{team}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Event\Team\Index::__invoke
* @see app/Livewire/Admin/Event/Team/Index.php:7
* @route '/admin/teams/{team}'
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
* @route '/admin/teams/{team}'
*/
show.get = (args: { team: string | number } | [team: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Event\Team\Index::__invoke
* @see app/Livewire/Admin/Event/Team/Index.php:7
* @route '/admin/teams/{team}'
*/
show.head = (args: { team: string | number } | [team: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

const teams = {
    show: Object.assign(show, show),
}

export default teams