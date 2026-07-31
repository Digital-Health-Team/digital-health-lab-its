import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Livewire\Admin\OrderCenter\Show::__invoke
* @see app/Livewire/Admin/OrderCenter/Show.php:7
* @route '/admin/order-center/{booking}'
*/
export const show = (args: { booking: string | number } | [booking: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/order-center/{booking}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\OrderCenter\Show::__invoke
* @see app/Livewire/Admin/OrderCenter/Show.php:7
* @route '/admin/order-center/{booking}'
*/
show.url = (args: { booking: string | number } | [booking: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { booking: args }
    }

    if (Array.isArray(args)) {
        args = {
            booking: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        booking: args.booking,
    }

    return show.definition.url
            .replace('{booking}', parsedArgs.booking.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Livewire\Admin\OrderCenter\Show::__invoke
* @see app/Livewire/Admin/OrderCenter/Show.php:7
* @route '/admin/order-center/{booking}'
*/
show.get = (args: { booking: string | number } | [booking: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\OrderCenter\Show::__invoke
* @see app/Livewire/Admin/OrderCenter/Show.php:7
* @route '/admin/order-center/{booking}'
*/
show.head = (args: { booking: string | number } | [booking: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\OrderCenter\Show::__invoke
* @see app/Livewire/Admin/OrderCenter/Show.php:7
* @route '/admin/order-center/{booking}'
*/
const showForm = (args: { booking: string | number } | [booking: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\OrderCenter\Show::__invoke
* @see app/Livewire/Admin/OrderCenter/Show.php:7
* @route '/admin/order-center/{booking}'
*/
showForm.get = (args: { booking: string | number } | [booking: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\OrderCenter\Show::__invoke
* @see app/Livewire/Admin/OrderCenter/Show.php:7
* @route '/admin/order-center/{booking}'
*/
showForm.head = (args: { booking: string | number } | [booking: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

const orderCenter = {
    show: Object.assign(show, show),
}

export default orderCenter