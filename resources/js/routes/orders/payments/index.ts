import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\User\OrderController::proof
* @see app/Http/Controllers/User/OrderController.php:229
* @route '/orders/{booking}/payments/{payment}/proof'
*/
export const proof = (args: { booking: number | { id: number }, payment: number | { id: number } } | [booking: number | { id: number }, payment: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: proof.url(args, options),
    method: 'post',
})

proof.definition = {
    methods: ["post"],
    url: '/orders/{booking}/payments/{payment}/proof',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\User\OrderController::proof
* @see app/Http/Controllers/User/OrderController.php:229
* @route '/orders/{booking}/payments/{payment}/proof'
*/
proof.url = (args: { booking: number | { id: number }, payment: number | { id: number } } | [booking: number | { id: number }, payment: number | { id: number } ], options?: RouteQueryOptions) => {
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

    return proof.definition.url
            .replace('{booking}', parsedArgs.booking.toString())
            .replace('{payment}', parsedArgs.payment.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\User\OrderController::proof
* @see app/Http/Controllers/User/OrderController.php:229
* @route '/orders/{booking}/payments/{payment}/proof'
*/
proof.post = (args: { booking: number | { id: number }, payment: number | { id: number } } | [booking: number | { id: number }, payment: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: proof.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\User\OrderController::proof
* @see app/Http/Controllers/User/OrderController.php:229
* @route '/orders/{booking}/payments/{payment}/proof'
*/
const proofForm = (args: { booking: number | { id: number }, payment: number | { id: number } } | [booking: number | { id: number }, payment: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: proof.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\User\OrderController::proof
* @see app/Http/Controllers/User/OrderController.php:229
* @route '/orders/{booking}/payments/{payment}/proof'
*/
proofForm.post = (args: { booking: number | { id: number }, payment: number | { id: number } } | [booking: number | { id: number }, payment: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: proof.url(args, options),
    method: 'post',
})

proof.form = proofForm

const payments = {
    proof: Object.assign(proof, proof),
}

export default payments