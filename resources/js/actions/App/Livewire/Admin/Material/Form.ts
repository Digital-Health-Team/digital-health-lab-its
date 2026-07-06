import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/create'
*/
const Forme69a6512d2f73a3aac36576ef2aa40df = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Forme69a6512d2f73a3aac36576ef2aa40df.url(options),
    method: 'get',
})

Forme69a6512d2f73a3aac36576ef2aa40df.definition = {
    methods: ["get","head"],
    url: '/admin/inventory/materials/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/create'
*/
Forme69a6512d2f73a3aac36576ef2aa40df.url = (options?: RouteQueryOptions) => {
    return Forme69a6512d2f73a3aac36576ef2aa40df.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/create'
*/
Forme69a6512d2f73a3aac36576ef2aa40df.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Forme69a6512d2f73a3aac36576ef2aa40df.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/create'
*/
Forme69a6512d2f73a3aac36576ef2aa40df.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Forme69a6512d2f73a3aac36576ef2aa40df.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/create'
*/
const Forme69a6512d2f73a3aac36576ef2aa40dfForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Forme69a6512d2f73a3aac36576ef2aa40df.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/create'
*/
Forme69a6512d2f73a3aac36576ef2aa40dfForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Forme69a6512d2f73a3aac36576ef2aa40df.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/create'
*/
Forme69a6512d2f73a3aac36576ef2aa40dfForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Forme69a6512d2f73a3aac36576ef2aa40df.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

Forme69a6512d2f73a3aac36576ef2aa40df.form = Forme69a6512d2f73a3aac36576ef2aa40dfForm
/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/{material}/edit'
*/
const Formd9f52819383f144a4a3b1ecdbf4e460e = (args: { material: string | number } | [material: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Formd9f52819383f144a4a3b1ecdbf4e460e.url(args, options),
    method: 'get',
})

Formd9f52819383f144a4a3b1ecdbf4e460e.definition = {
    methods: ["get","head"],
    url: '/admin/inventory/materials/{material}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/{material}/edit'
*/
Formd9f52819383f144a4a3b1ecdbf4e460e.url = (args: { material: string | number } | [material: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return Formd9f52819383f144a4a3b1ecdbf4e460e.definition.url
            .replace('{material}', parsedArgs.material.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/{material}/edit'
*/
Formd9f52819383f144a4a3b1ecdbf4e460e.get = (args: { material: string | number } | [material: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Formd9f52819383f144a4a3b1ecdbf4e460e.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/{material}/edit'
*/
Formd9f52819383f144a4a3b1ecdbf4e460e.head = (args: { material: string | number } | [material: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Formd9f52819383f144a4a3b1ecdbf4e460e.url(args, options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/{material}/edit'
*/
const Formd9f52819383f144a4a3b1ecdbf4e460eForm = (args: { material: string | number } | [material: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Formd9f52819383f144a4a3b1ecdbf4e460e.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/{material}/edit'
*/
Formd9f52819383f144a4a3b1ecdbf4e460eForm.get = (args: { material: string | number } | [material: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Formd9f52819383f144a4a3b1ecdbf4e460e.url(args, options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Material\Form::__invoke
* @see app/Livewire/Admin/Material/Form.php:7
* @route '/admin/inventory/materials/{material}/edit'
*/
Formd9f52819383f144a4a3b1ecdbf4e460eForm.head = (args: { material: string | number } | [material: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Formd9f52819383f144a4a3b1ecdbf4e460e.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

Formd9f52819383f144a4a3b1ecdbf4e460e.form = Formd9f52819383f144a4a3b1ecdbf4e460eForm

const Form = {
    '/admin/inventory/materials/create': Forme69a6512d2f73a3aac36576ef2aa40df,
    '/admin/inventory/materials/{material}/edit': Formd9f52819383f144a4a3b1ecdbf4e460e,
}

export default Form