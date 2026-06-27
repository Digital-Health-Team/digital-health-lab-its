import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Livewire\Admin\OrderCenter\Show::__invoke
* @see app/Livewire/Admin/OrderCenter/Show.php:7
* @route '/admin/order-center/{booking}'
*/
const Show = (args: { booking: string | number } | [booking: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Show.url(args, options),
    method: 'get',
})

Show.definition = {
    methods: ["get","head"],
    url: '/admin/order-center/{booking}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\OrderCenter\Show::__invoke
* @see app/Livewire/Admin/OrderCenter/Show.php:7
* @route '/admin/order-center/{booking}'
*/
Show.url = (args: { booking: string | number } | [booking: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return Show.definition.url
            .replace('{booking}', parsedArgs.booking.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Livewire\Admin\OrderCenter\Show::__invoke
* @see app/Livewire/Admin/OrderCenter/Show.php:7
* @route '/admin/order-center/{booking}'
*/
Show.get = (args: { booking: string | number } | [booking: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Show.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\OrderCenter\Show::__invoke
* @see app/Livewire/Admin/OrderCenter/Show.php:7
* @route '/admin/order-center/{booking}'
*/
Show.head = (args: { booking: string | number } | [booking: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Show.url(args, options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\OrderCenter\Show::__invoke
* @see app/Livewire/Admin/OrderCenter/Show.php:7
* @route '/admin/order-center/{booking}'
*/
const ShowForm = (args: { booking: string | number } | [booking: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Show.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\OrderCenter\Show::__invoke
* @see app/Livewire/Admin/OrderCenter/Show.php:7
* @route '/admin/order-center/{booking}'
*/
ShowForm.get = (args: { booking: string | number } | [booking: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Show.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\OrderCenter\Show::__invoke
* @see app/Livewire/Admin/OrderCenter/Show.php:7
* @route '/admin/order-center/{booking}'
*/
ShowForm.head = (args: { booking: string | number } | [booking: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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