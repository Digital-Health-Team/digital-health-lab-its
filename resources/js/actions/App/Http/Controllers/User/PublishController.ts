import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\User\PublishController::index
* @see app/Http/Controllers/User/PublishController.php:110
* @route '/publish'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/publish',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\User\PublishController::index
* @see app/Http/Controllers/User/PublishController.php:110
* @route '/publish'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\User\PublishController::index
* @see app/Http/Controllers/User/PublishController.php:110
* @route '/publish'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\PublishController::index
* @see app/Http/Controllers/User/PublishController.php:110
* @route '/publish'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\User\PublishController::index
* @see app/Http/Controllers/User/PublishController.php:110
* @route '/publish'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\PublishController::index
* @see app/Http/Controllers/User/PublishController.php:110
* @route '/publish'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\PublishController::index
* @see app/Http/Controllers/User/PublishController.php:110
* @route '/publish'
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
* @see \App\Http\Controllers\User\PublishController::create
* @see app/Http/Controllers/User/PublishController.php:130
* @route '/publish/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/publish/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\User\PublishController::create
* @see app/Http/Controllers/User/PublishController.php:130
* @route '/publish/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\User\PublishController::create
* @see app/Http/Controllers/User/PublishController.php:130
* @route '/publish/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\PublishController::create
* @see app/Http/Controllers/User/PublishController.php:130
* @route '/publish/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\User\PublishController::create
* @see app/Http/Controllers/User/PublishController.php:130
* @route '/publish/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\PublishController::create
* @see app/Http/Controllers/User/PublishController.php:130
* @route '/publish/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\PublishController::create
* @see app/Http/Controllers/User/PublishController.php:130
* @route '/publish/create'
*/
createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

create.form = createForm

/**
* @see \App\Http\Controllers\User\PublishController::store
* @see app/Http/Controllers/User/PublishController.php:154
* @route '/publish'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/publish',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\User\PublishController::store
* @see app/Http/Controllers/User/PublishController.php:154
* @route '/publish'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\User\PublishController::store
* @see app/Http/Controllers/User/PublishController.php:154
* @route '/publish'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\User\PublishController::store
* @see app/Http/Controllers/User/PublishController.php:154
* @route '/publish'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\User\PublishController::store
* @see app/Http/Controllers/User/PublishController.php:154
* @route '/publish'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\User\PublishController::edit
* @see app/Http/Controllers/User/PublishController.php:140
* @route '/publish/{kind}/{id}/edit'
*/
export const edit = (args: { kind: string | number, id: string | number } | [kind: string | number, id: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/publish/{kind}/{id}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\User\PublishController::edit
* @see app/Http/Controllers/User/PublishController.php:140
* @route '/publish/{kind}/{id}/edit'
*/
edit.url = (args: { kind: string | number, id: string | number } | [kind: string | number, id: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            kind: args[0],
            id: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        kind: args.kind,
        id: args.id,
    }

    return edit.definition.url
            .replace('{kind}', parsedArgs.kind.toString())
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\User\PublishController::edit
* @see app/Http/Controllers/User/PublishController.php:140
* @route '/publish/{kind}/{id}/edit'
*/
edit.get = (args: { kind: string | number, id: string | number } | [kind: string | number, id: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\PublishController::edit
* @see app/Http/Controllers/User/PublishController.php:140
* @route '/publish/{kind}/{id}/edit'
*/
edit.head = (args: { kind: string | number, id: string | number } | [kind: string | number, id: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\User\PublishController::edit
* @see app/Http/Controllers/User/PublishController.php:140
* @route '/publish/{kind}/{id}/edit'
*/
const editForm = (args: { kind: string | number, id: string | number } | [kind: string | number, id: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\PublishController::edit
* @see app/Http/Controllers/User/PublishController.php:140
* @route '/publish/{kind}/{id}/edit'
*/
editForm.get = (args: { kind: string | number, id: string | number } | [kind: string | number, id: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\PublishController::edit
* @see app/Http/Controllers/User/PublishController.php:140
* @route '/publish/{kind}/{id}/edit'
*/
editForm.head = (args: { kind: string | number, id: string | number } | [kind: string | number, id: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

/**
* @see \App\Http\Controllers\User\PublishController::update
* @see app/Http/Controllers/User/PublishController.php:197
* @route '/publish/{kind}/{id}'
*/
export const update = (args: { kind: string | number, id: string | number } | [kind: string | number, id: string | number ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(args, options),
    method: 'post',
})

update.definition = {
    methods: ["post"],
    url: '/publish/{kind}/{id}',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\User\PublishController::update
* @see app/Http/Controllers/User/PublishController.php:197
* @route '/publish/{kind}/{id}'
*/
update.url = (args: { kind: string | number, id: string | number } | [kind: string | number, id: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            kind: args[0],
            id: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        kind: args.kind,
        id: args.id,
    }

    return update.definition.url
            .replace('{kind}', parsedArgs.kind.toString())
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\User\PublishController::update
* @see app/Http/Controllers/User/PublishController.php:197
* @route '/publish/{kind}/{id}'
*/
update.post = (args: { kind: string | number, id: string | number } | [kind: string | number, id: string | number ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\User\PublishController::update
* @see app/Http/Controllers/User/PublishController.php:197
* @route '/publish/{kind}/{id}'
*/
const updateForm = (args: { kind: string | number, id: string | number } | [kind: string | number, id: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\User\PublishController::update
* @see app/Http/Controllers/User/PublishController.php:197
* @route '/publish/{kind}/{id}'
*/
updateForm.post = (args: { kind: string | number, id: string | number } | [kind: string | number, id: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, options),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\User\PublishController::destroy
* @see app/Http/Controllers/User/PublishController.php:258
* @route '/publish/{kind}/{id}'
*/
export const destroy = (args: { kind: string | number, id: string | number } | [kind: string | number, id: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/publish/{kind}/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\User\PublishController::destroy
* @see app/Http/Controllers/User/PublishController.php:258
* @route '/publish/{kind}/{id}'
*/
destroy.url = (args: { kind: string | number, id: string | number } | [kind: string | number, id: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            kind: args[0],
            id: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        kind: args.kind,
        id: args.id,
    }

    return destroy.definition.url
            .replace('{kind}', parsedArgs.kind.toString())
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\User\PublishController::destroy
* @see app/Http/Controllers/User/PublishController.php:258
* @route '/publish/{kind}/{id}'
*/
destroy.delete = (args: { kind: string | number, id: string | number } | [kind: string | number, id: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\User\PublishController::destroy
* @see app/Http/Controllers/User/PublishController.php:258
* @route '/publish/{kind}/{id}'
*/
const destroyForm = (args: { kind: string | number, id: string | number } | [kind: string | number, id: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\User\PublishController::destroy
* @see app/Http/Controllers/User/PublishController.php:258
* @route '/publish/{kind}/{id}'
*/
destroyForm.delete = (args: { kind: string | number, id: string | number } | [kind: string | number, id: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const PublishController = { index, create, store, edit, update, destroy }

export default PublishController