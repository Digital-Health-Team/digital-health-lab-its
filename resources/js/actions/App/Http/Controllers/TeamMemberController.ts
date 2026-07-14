import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\TeamMemberController::show
* @see app/Http/Controllers/TeamMemberController.php:11
* @route '/team/{labTeamPerson}'
*/
export const show = (args: { labTeamPerson: string | { slug: string } } | [labTeamPerson: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/team/{labTeamPerson}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\TeamMemberController::show
* @see app/Http/Controllers/TeamMemberController.php:11
* @route '/team/{labTeamPerson}'
*/
show.url = (args: { labTeamPerson: string | { slug: string } } | [labTeamPerson: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { labTeamPerson: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'slug' in args) {
        args = { labTeamPerson: args.slug }
    }

    if (Array.isArray(args)) {
        args = {
            labTeamPerson: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        labTeamPerson: typeof args.labTeamPerson === 'object'
        ? args.labTeamPerson.slug
        : args.labTeamPerson,
    }

    return show.definition.url
            .replace('{labTeamPerson}', parsedArgs.labTeamPerson.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TeamMemberController::show
* @see app/Http/Controllers/TeamMemberController.php:11
* @route '/team/{labTeamPerson}'
*/
show.get = (args: { labTeamPerson: string | { slug: string } } | [labTeamPerson: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\TeamMemberController::show
* @see app/Http/Controllers/TeamMemberController.php:11
* @route '/team/{labTeamPerson}'
*/
show.head = (args: { labTeamPerson: string | { slug: string } } | [labTeamPerson: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\TeamMemberController::show
* @see app/Http/Controllers/TeamMemberController.php:11
* @route '/team/{labTeamPerson}'
*/
const showForm = (args: { labTeamPerson: string | { slug: string } } | [labTeamPerson: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\TeamMemberController::show
* @see app/Http/Controllers/TeamMemberController.php:11
* @route '/team/{labTeamPerson}'
*/
showForm.get = (args: { labTeamPerson: string | { slug: string } } | [labTeamPerson: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\TeamMemberController::show
* @see app/Http/Controllers/TeamMemberController.php:11
* @route '/team/{labTeamPerson}'
*/
showForm.head = (args: { labTeamPerson: string | { slug: string } } | [labTeamPerson: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

const TeamMemberController = { show }

export default TeamMemberController