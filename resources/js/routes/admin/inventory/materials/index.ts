import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin/inventory/materials/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/create'
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
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/{material}/edit'
*/
export const edit = (args: { material: string | number } | [material: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/inventory/materials/{material}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/{material}/edit'
*/
edit.url = (args: { material: string | number } | [material: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { material: args }
    }

    if (Array.isArray(args)) {
        args = {
            material: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        material: args.material,
    }

    return edit.definition.url
            .replace('{material}', parsedArgs.material.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/{material}/edit'
*/
edit.get = (args: { material: string | number } | [material: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/{material}/edit'
*/
edit.head = (args: { material: string | number } | [material: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/{material}/edit'
*/
const editForm = (args: { material: string | number } | [material: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/{material}/edit'
*/
editForm.get = (args: { material: string | number } | [material: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/{material}/edit'
*/
editForm.head = (args: { material: string | number } | [material: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

const materials = {
    create: Object.assign(create, create),
    edit: Object.assign(edit, edit),
}

export default materials