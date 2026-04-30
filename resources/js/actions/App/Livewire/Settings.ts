import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Livewire\Settings::__invoke
* @see app/Livewire/Settings.php:7
* @route '/settings'
*/
const Settings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Settings.url(options),
    method: 'get',
})

Settings.definition = {
    methods: ["get","head"],
    url: '/settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Settings::__invoke
* @see app/Livewire/Settings.php:7
* @route '/settings'
*/
Settings.url = (options?: RouteQueryOptions) => {
    return Settings.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Settings::__invoke
* @see app/Livewire/Settings.php:7
* @route '/settings'
*/
Settings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Settings.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Settings::__invoke
* @see app/Livewire/Settings.php:7
* @route '/settings'
*/
Settings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Settings.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Settings::__invoke
* @see app/Livewire/Settings.php:7
* @route '/settings'
*/
const SettingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Settings.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Settings::__invoke
* @see app/Livewire/Settings.php:7
* @route '/settings'
*/
SettingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Settings.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Settings::__invoke
* @see app/Livewire/Settings.php:7
* @route '/settings'
*/
SettingsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Settings.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

Settings.form = SettingsForm

export default Settings