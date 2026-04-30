import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Livewire\Admin\Event\Team\Index::__invoke
* @see app/Livewire/Admin/Event/Team/Index.php:7
* @route '/admin/events/teams/{team}'
*/
const Index = (args: { team: string | number } | [team: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(args, options),
    method: 'get',
})

Index.definition = {
    methods: ["get","head"],
    url: '/admin/events/teams/{team}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Event\Team\Index::__invoke
* @see app/Livewire/Admin/Event/Team/Index.php:7
* @route '/admin/events/teams/{team}'
*/
Index.url = (args: { team: string | number } | [team: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return Index.definition.url
            .replace('{team}', parsedArgs.team.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Event\Team\Index::__invoke
* @see app/Livewire/Admin/Event/Team/Index.php:7
* @route '/admin/events/teams/{team}'
*/
Index.get = (args: { team: string | number } | [team: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Index.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Event\Team\Index::__invoke
* @see app/Livewire/Admin/Event/Team/Index.php:7
* @route '/admin/events/teams/{team}'
*/
Index.head = (args: { team: string | number } | [team: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Index.url(args, options),
    method: 'head',
})

export default Index