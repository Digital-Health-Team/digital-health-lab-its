import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\User\OrderController::catalog
* @see app/Http/Controllers/User/OrderController.php:25
* @route '/services'
*/
export const catalog = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: catalog.url(options),
    method: 'get',
})

catalog.definition = {
    methods: ["get","head"],
    url: '/services',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\User\OrderController::catalog
* @see app/Http/Controllers/User/OrderController.php:25
* @route '/services'
*/
catalog.url = (options?: RouteQueryOptions) => {
    return catalog.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\User\OrderController::catalog
* @see app/Http/Controllers/User/OrderController.php:25
* @route '/services'
*/
catalog.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: catalog.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\OrderController::catalog
* @see app/Http/Controllers/User/OrderController.php:25
* @route '/services'
*/
catalog.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: catalog.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\User\OrderController::catalog
* @see app/Http/Controllers/User/OrderController.php:25
* @route '/services'
*/
const catalogForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: catalog.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\OrderController::catalog
* @see app/Http/Controllers/User/OrderController.php:25
* @route '/services'
*/
catalogForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: catalog.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\OrderController::catalog
* @see app/Http/Controllers/User/OrderController.php:25
* @route '/services'
*/
catalogForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: catalog.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

catalog.form = catalogForm

/**
* @see \App\Http\Controllers\User\OrderController::index
* @see app/Http/Controllers/User/OrderController.php:66
* @route '/orders'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/orders',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\User\OrderController::index
* @see app/Http/Controllers/User/OrderController.php:66
* @route '/orders'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\User\OrderController::index
* @see app/Http/Controllers/User/OrderController.php:66
* @route '/orders'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\OrderController::index
* @see app/Http/Controllers/User/OrderController.php:66
* @route '/orders'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\User\OrderController::index
* @see app/Http/Controllers/User/OrderController.php:66
* @route '/orders'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\OrderController::index
* @see app/Http/Controllers/User/OrderController.php:66
* @route '/orders'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\OrderController::index
* @see app/Http/Controllers/User/OrderController.php:66
* @route '/orders'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\User\OrderController::store
* @see app/Http/Controllers/User/OrderController.php:44
* @route '/orders'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/orders',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\User\OrderController::store
* @see app/Http/Controllers/User/OrderController.php:44
* @route '/orders'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\User\OrderController::store
* @see app/Http/Controllers/User/OrderController.php:44
* @route '/orders'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\User\OrderController::store
* @see app/Http/Controllers/User/OrderController.php:44
* @route '/orders'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\User\OrderController::store
* @see app/Http/Controllers/User/OrderController.php:44
* @route '/orders'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\User\OrderController::show
* @see app/Http/Controllers/User/OrderController.php:94
* @route '/orders/{booking}'
*/
export const show = (args: { booking: number | { id: number } } | [booking: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/orders/{booking}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\User\OrderController::show
* @see app/Http/Controllers/User/OrderController.php:94
* @route '/orders/{booking}'
*/
show.url = (args: { booking: number | { id: number } } | [booking: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { booking: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { booking: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            booking: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        booking: typeof args.booking === 'object'
        ? args.booking.id
        : args.booking,
    }

    return show.definition.url
            .replace('{booking}', parsedArgs.booking.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\User\OrderController::show
* @see app/Http/Controllers/User/OrderController.php:94
* @route '/orders/{booking}'
*/
show.get = (args: { booking: number | { id: number } } | [booking: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\OrderController::show
* @see app/Http/Controllers/User/OrderController.php:94
* @route '/orders/{booking}'
*/
show.head = (args: { booking: number | { id: number } } | [booking: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\User\OrderController::show
* @see app/Http/Controllers/User/OrderController.php:94
* @route '/orders/{booking}'
*/
const showForm = (args: { booking: number | { id: number } } | [booking: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\OrderController::show
* @see app/Http/Controllers/User/OrderController.php:94
* @route '/orders/{booking}'
*/
showForm.get = (args: { booking: number | { id: number } } | [booking: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\OrderController::show
* @see app/Http/Controllers/User/OrderController.php:94
* @route '/orders/{booking}'
*/
showForm.head = (args: { booking: number | { id: number } } | [booking: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\User\OrderController::uploadPaymentProof
* @see app/Http/Controllers/User/OrderController.php:192
* @route '/orders/{booking}/payments/{payment}/proof'
*/
export const uploadPaymentProof = (args: { booking: number | { id: number }, payment: number | { id: number } } | [booking: number | { id: number }, payment: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: uploadPaymentProof.url(args, options),
    method: 'post',
})

uploadPaymentProof.definition = {
    methods: ["post"],
    url: '/orders/{booking}/payments/{payment}/proof',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\User\OrderController::uploadPaymentProof
* @see app/Http/Controllers/User/OrderController.php:192
* @route '/orders/{booking}/payments/{payment}/proof'
*/
uploadPaymentProof.url = (args: { booking: number | { id: number }, payment: number | { id: number } } | [booking: number | { id: number }, payment: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            booking: args[0],
            payment: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        booking: typeof args.booking === 'object'
        ? args.booking.id
        : args.booking,
        payment: typeof args.payment === 'object'
        ? args.payment.id
        : args.payment,
    }

    return uploadPaymentProof.definition.url
            .replace('{booking}', parsedArgs.booking.toString())
            .replace('{payment}', parsedArgs.payment.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\User\OrderController::uploadPaymentProof
* @see app/Http/Controllers/User/OrderController.php:192
* @route '/orders/{booking}/payments/{payment}/proof'
*/
uploadPaymentProof.post = (args: { booking: number | { id: number }, payment: number | { id: number } } | [booking: number | { id: number }, payment: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: uploadPaymentProof.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\User\OrderController::uploadPaymentProof
* @see app/Http/Controllers/User/OrderController.php:192
* @route '/orders/{booking}/payments/{payment}/proof'
*/
const uploadPaymentProofForm = (args: { booking: number | { id: number }, payment: number | { id: number } } | [booking: number | { id: number }, payment: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: uploadPaymentProof.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\User\OrderController::uploadPaymentProof
* @see app/Http/Controllers/User/OrderController.php:192
* @route '/orders/{booking}/payments/{payment}/proof'
*/
uploadPaymentProofForm.post = (args: { booking: number | { id: number }, payment: number | { id: number } } | [booking: number | { id: number }, payment: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: uploadPaymentProof.url(args, options),
    method: 'post',
})

uploadPaymentProof.form = uploadPaymentProofForm

/**
* @see \App\Http\Controllers\User\OrderController::sendMessage
* @see app/Http/Controllers/User/OrderController.php:172
* @route '/orders/{booking}/messages'
*/
export const sendMessage = (args: { booking: number | { id: number } } | [booking: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sendMessage.url(args, options),
    method: 'post',
})

sendMessage.definition = {
    methods: ["post"],
    url: '/orders/{booking}/messages',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\User\OrderController::sendMessage
* @see app/Http/Controllers/User/OrderController.php:172
* @route '/orders/{booking}/messages'
*/
sendMessage.url = (args: { booking: number | { id: number } } | [booking: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { booking: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { booking: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            booking: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        booking: typeof args.booking === 'object'
        ? args.booking.id
        : args.booking,
    }

    return sendMessage.definition.url
            .replace('{booking}', parsedArgs.booking.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\User\OrderController::sendMessage
* @see app/Http/Controllers/User/OrderController.php:172
* @route '/orders/{booking}/messages'
*/
sendMessage.post = (args: { booking: number | { id: number } } | [booking: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sendMessage.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\User\OrderController::sendMessage
* @see app/Http/Controllers/User/OrderController.php:172
* @route '/orders/{booking}/messages'
*/
const sendMessageForm = (args: { booking: number | { id: number } } | [booking: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: sendMessage.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\User\OrderController::sendMessage
* @see app/Http/Controllers/User/OrderController.php:172
* @route '/orders/{booking}/messages'
*/
sendMessageForm.post = (args: { booking: number | { id: number } } | [booking: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: sendMessage.url(args, options),
    method: 'post',
})

sendMessage.form = sendMessageForm

const OrderController = { catalog, index, store, show, uploadPaymentProof, sendMessage }

export default OrderController