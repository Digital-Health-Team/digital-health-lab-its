import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\TrainingController::show
* @see app/Http/Controllers/TrainingController.php:17
* @route '/training/{training}'
*/
export const show = (args: { training: string | { slug: string } } | [training: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/training/{training}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\TrainingController::show
* @see app/Http/Controllers/TrainingController.php:17
* @route '/training/{training}'
*/
show.url = (args: { training: string | { slug: string } } | [training: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { training: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'slug' in args) {
        args = { training: args.slug }
    }

    if (Array.isArray(args)) {
        args = {
            training: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        training: typeof args.training === 'object'
        ? args.training.slug
        : args.training,
    }

    return show.definition.url
            .replace('{training}', parsedArgs.training.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TrainingController::show
* @see app/Http/Controllers/TrainingController.php:17
* @route '/training/{training}'
*/
show.get = (args: { training: string | { slug: string } } | [training: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\TrainingController::show
* @see app/Http/Controllers/TrainingController.php:17
* @route '/training/{training}'
*/
show.head = (args: { training: string | { slug: string } } | [training: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\TrainingController::show
* @see app/Http/Controllers/TrainingController.php:17
* @route '/training/{training}'
*/
const showForm = (args: { training: string | { slug: string } } | [training: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\TrainingController::show
* @see app/Http/Controllers/TrainingController.php:17
* @route '/training/{training}'
*/
showForm.get = (args: { training: string | { slug: string } } | [training: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\TrainingController::show
* @see app/Http/Controllers/TrainingController.php:17
* @route '/training/{training}'
*/
showForm.head = (args: { training: string | { slug: string } } | [training: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\TrainingController::register
* @see app/Http/Controllers/TrainingController.php:62
* @route '/training/{training}/register'
*/
export const register = (args: { training: string | { slug: string } } | [training: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: register.url(args, options),
    method: 'post',
})

register.definition = {
    methods: ["post"],
    url: '/training/{training}/register',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\TrainingController::register
* @see app/Http/Controllers/TrainingController.php:62
* @route '/training/{training}/register'
*/
register.url = (args: { training: string | { slug: string } } | [training: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { training: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'slug' in args) {
        args = { training: args.slug }
    }

    if (Array.isArray(args)) {
        args = {
            training: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        training: typeof args.training === 'object'
        ? args.training.slug
        : args.training,
    }

    return register.definition.url
            .replace('{training}', parsedArgs.training.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TrainingController::register
* @see app/Http/Controllers/TrainingController.php:62
* @route '/training/{training}/register'
*/
register.post = (args: { training: string | { slug: string } } | [training: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: register.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\TrainingController::register
* @see app/Http/Controllers/TrainingController.php:62
* @route '/training/{training}/register'
*/
const registerForm = (args: { training: string | { slug: string } } | [training: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: register.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\TrainingController::register
* @see app/Http/Controllers/TrainingController.php:62
* @route '/training/{training}/register'
*/
registerForm.post = (args: { training: string | { slug: string } } | [training: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: register.url(args, options),
    method: 'post',
})

register.form = registerForm

/**
* @see \App\Http\Controllers\TrainingController::uploadPaymentProof
* @see app/Http/Controllers/TrainingController.php:92
* @route '/training/{training}/upload-proof'
*/
export const uploadPaymentProof = (args: { training: string | { slug: string } } | [training: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: uploadPaymentProof.url(args, options),
    method: 'post',
})

uploadPaymentProof.definition = {
    methods: ["post"],
    url: '/training/{training}/upload-proof',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\TrainingController::uploadPaymentProof
* @see app/Http/Controllers/TrainingController.php:92
* @route '/training/{training}/upload-proof'
*/
uploadPaymentProof.url = (args: { training: string | { slug: string } } | [training: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { training: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'slug' in args) {
        args = { training: args.slug }
    }

    if (Array.isArray(args)) {
        args = {
            training: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        training: typeof args.training === 'object'
        ? args.training.slug
        : args.training,
    }

    return uploadPaymentProof.definition.url
            .replace('{training}', parsedArgs.training.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TrainingController::uploadPaymentProof
* @see app/Http/Controllers/TrainingController.php:92
* @route '/training/{training}/upload-proof'
*/
uploadPaymentProof.post = (args: { training: string | { slug: string } } | [training: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: uploadPaymentProof.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\TrainingController::uploadPaymentProof
* @see app/Http/Controllers/TrainingController.php:92
* @route '/training/{training}/upload-proof'
*/
const uploadPaymentProofForm = (args: { training: string | { slug: string } } | [training: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: uploadPaymentProof.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\TrainingController::uploadPaymentProof
* @see app/Http/Controllers/TrainingController.php:92
* @route '/training/{training}/upload-proof'
*/
uploadPaymentProofForm.post = (args: { training: string | { slug: string } } | [training: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: uploadPaymentProof.url(args, options),
    method: 'post',
})

uploadPaymentProof.form = uploadPaymentProofForm

const TrainingController = { show, register, uploadPaymentProof }

export default TrainingController