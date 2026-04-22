import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
import events735790 from './events'
import teams from './teams'
/**
* @see \App\Livewire\Admin\GlobalSearch::__invoke
* @see app/Livewire/Admin/GlobalSearch.php:7
* @route '/admin/global-search'
*/
export const globalSearch = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: globalSearch.url(options),
    method: 'get',
})

globalSearch.definition = {
    methods: ["get","head"],
    url: '/admin/global-search',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\GlobalSearch::__invoke
* @see app/Livewire/Admin/GlobalSearch.php:7
* @route '/admin/global-search'
*/
globalSearch.url = (options?: RouteQueryOptions) => {
    return globalSearch.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\GlobalSearch::__invoke
* @see app/Livewire/Admin/GlobalSearch.php:7
* @route '/admin/global-search'
*/
globalSearch.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: globalSearch.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\GlobalSearch::__invoke
* @see app/Livewire/Admin/GlobalSearch.php:7
* @route '/admin/global-search'
*/
globalSearch.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: globalSearch.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\Dashboard::__invoke
* @see app/Livewire/Admin/Dashboard.php:7
* @route '/admin/dashboard'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/admin/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Dashboard::__invoke
* @see app/Livewire/Admin/Dashboard.php:7
* @route '/admin/dashboard'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Dashboard::__invoke
* @see app/Livewire/Admin/Dashboard.php:7
* @route '/admin/dashboard'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Dashboard::__invoke
* @see app/Livewire/Admin/Dashboard.php:7
* @route '/admin/dashboard'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\User\Index::__invoke
* @see app/Livewire/Admin/User/Index.php:7
* @route '/admin/users'
*/
export const users = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: users.url(options),
    method: 'get',
})

users.definition = {
    methods: ["get","head"],
    url: '/admin/users',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\User\Index::__invoke
* @see app/Livewire/Admin/User/Index.php:7
* @route '/admin/users'
*/
users.url = (options?: RouteQueryOptions) => {
    return users.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\User\Index::__invoke
* @see app/Livewire/Admin/User/Index.php:7
* @route '/admin/users'
*/
users.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: users.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\User\Index::__invoke
* @see app/Livewire/Admin/User/Index.php:7
* @route '/admin/users'
*/
users.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: users.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\RawMaterial\Index::__invoke
* @see app/Livewire/Admin/RawMaterial/Index.php:7
* @route '/admin/raw-materials'
*/
export const rawMaterials = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: rawMaterials.url(options),
    method: 'get',
})

rawMaterials.definition = {
    methods: ["get","head"],
    url: '/admin/raw-materials',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\RawMaterial\Index::__invoke
* @see app/Livewire/Admin/RawMaterial/Index.php:7
* @route '/admin/raw-materials'
*/
rawMaterials.url = (options?: RouteQueryOptions) => {
    return rawMaterials.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\RawMaterial\Index::__invoke
* @see app/Livewire/Admin/RawMaterial/Index.php:7
* @route '/admin/raw-materials'
*/
rawMaterials.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: rawMaterials.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\RawMaterial\Index::__invoke
* @see app/Livewire/Admin/RawMaterial/Index.php:7
* @route '/admin/raw-materials'
*/
rawMaterials.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: rawMaterials.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\Service\Index::__invoke
* @see app/Livewire/Admin/Service/Index.php:7
* @route '/admin/services'
*/
export const services = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: services.url(options),
    method: 'get',
})

services.definition = {
    methods: ["get","head"],
    url: '/admin/services',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Service\Index::__invoke
* @see app/Livewire/Admin/Service/Index.php:7
* @route '/admin/services'
*/
services.url = (options?: RouteQueryOptions) => {
    return services.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Service\Index::__invoke
* @see app/Livewire/Admin/Service/Index.php:7
* @route '/admin/services'
*/
services.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: services.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Service\Index::__invoke
* @see app/Livewire/Admin/Service/Index.php:7
* @route '/admin/services'
*/
services.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: services.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\Product\Index::__invoke
* @see app/Livewire/Admin/Product/Index.php:7
* @route '/admin/products'
*/
export const products = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: products.url(options),
    method: 'get',
})

products.definition = {
    methods: ["get","head"],
    url: '/admin/products',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Product\Index::__invoke
* @see app/Livewire/Admin/Product/Index.php:7
* @route '/admin/products'
*/
products.url = (options?: RouteQueryOptions) => {
    return products.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Product\Index::__invoke
* @see app/Livewire/Admin/Product/Index.php:7
* @route '/admin/products'
*/
products.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: products.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Product\Index::__invoke
* @see app/Livewire/Admin/Product/Index.php:7
* @route '/admin/products'
*/
products.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: products.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\Event\Index::__invoke
* @see app/Livewire/Admin/Event/Index.php:7
* @route '/admin/events'
*/
export const events = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: events.url(options),
    method: 'get',
})

events.definition = {
    methods: ["get","head"],
    url: '/admin/events',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Event\Index::__invoke
* @see app/Livewire/Admin/Event/Index.php:7
* @route '/admin/events'
*/
events.url = (options?: RouteQueryOptions) => {
    return events.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Event\Index::__invoke
* @see app/Livewire/Admin/Event/Index.php:7
* @route '/admin/events'
*/
events.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: events.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Event\Index::__invoke
* @see app/Livewire/Admin/Event/Index.php:7
* @route '/admin/events'
*/
events.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: events.url(options),
    method: 'head',
})

const admin = {
    globalSearch: Object.assign(globalSearch, globalSearch),
    dashboard: Object.assign(dashboard, dashboard),
    users: Object.assign(users, users),
    rawMaterials: Object.assign(rawMaterials, rawMaterials),
    services: Object.assign(services, services),
    products: Object.assign(products, products),
    events: Object.assign(events, events735790),
    teams: Object.assign(teams, teams),
}

export default admin