import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../wayfinder'
/**
* @see \App\Livewire\Admin\GlobalSearch::__invoke
* @see app/Livewire/Admin/GlobalSearch.php:7
* @route '/admin/global-search'
*/
const GlobalSearch = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: GlobalSearch.url(options),
    method: 'get',
})

GlobalSearch.definition = {
    methods: ["get","head"],
    url: '/admin/global-search',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\GlobalSearch::__invoke
* @see app/Livewire/Admin/GlobalSearch.php:7
* @route '/admin/global-search'
*/
GlobalSearch.url = (options?: RouteQueryOptions) => {
    return GlobalSearch.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\GlobalSearch::__invoke
* @see app/Livewire/Admin/GlobalSearch.php:7
* @route '/admin/global-search'
*/
GlobalSearch.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: GlobalSearch.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\GlobalSearch::__invoke
* @see app/Livewire/Admin/GlobalSearch.php:7
* @route '/admin/global-search'
*/
GlobalSearch.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: GlobalSearch.url(options),
    method: 'head',
})

export default GlobalSearch