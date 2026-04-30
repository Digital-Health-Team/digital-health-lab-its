import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import events735790 from './events'
import teams from './teams'
import cms from './cms'
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
* @see \App\Livewire\Admin\GlobalSearch::__invoke
* @see app/Livewire/Admin/GlobalSearch.php:7
* @route '/admin/global-search'
*/
const globalSearchForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: globalSearch.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\GlobalSearch::__invoke
* @see app/Livewire/Admin/GlobalSearch.php:7
* @route '/admin/global-search'
*/
globalSearchForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: globalSearch.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\GlobalSearch::__invoke
* @see app/Livewire/Admin/GlobalSearch.php:7
* @route '/admin/global-search'
*/
globalSearchForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: globalSearch.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

globalSearch.form = globalSearchForm

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
* @see \App\Livewire\Admin\Dashboard::__invoke
* @see app/Livewire/Admin/Dashboard.php:7
* @route '/admin/dashboard'
*/
const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Dashboard::__invoke
* @see app/Livewire/Admin/Dashboard.php:7
* @route '/admin/dashboard'
*/
dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Dashboard::__invoke
* @see app/Livewire/Admin/Dashboard.php:7
* @route '/admin/dashboard'
*/
dashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

dashboard.form = dashboardForm

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
* @see \App\Livewire\Admin\User\Index::__invoke
* @see app/Livewire/Admin/User/Index.php:7
* @route '/admin/users'
*/
const usersForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: users.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\User\Index::__invoke
* @see app/Livewire/Admin/User/Index.php:7
* @route '/admin/users'
*/
usersForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: users.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\User\Index::__invoke
* @see app/Livewire/Admin/User/Index.php:7
* @route '/admin/users'
*/
usersForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: users.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

users.form = usersForm

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
* @see \App\Livewire\Admin\RawMaterial\Index::__invoke
* @see app/Livewire/Admin/RawMaterial/Index.php:7
* @route '/admin/raw-materials'
*/
const rawMaterialsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: rawMaterials.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\RawMaterial\Index::__invoke
* @see app/Livewire/Admin/RawMaterial/Index.php:7
* @route '/admin/raw-materials'
*/
rawMaterialsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: rawMaterials.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\RawMaterial\Index::__invoke
* @see app/Livewire/Admin/RawMaterial/Index.php:7
* @route '/admin/raw-materials'
*/
rawMaterialsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: rawMaterials.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

rawMaterials.form = rawMaterialsForm

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
* @see \App\Livewire\Admin\Service\Index::__invoke
* @see app/Livewire/Admin/Service/Index.php:7
* @route '/admin/services'
*/
const servicesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: services.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Service\Index::__invoke
* @see app/Livewire/Admin/Service/Index.php:7
* @route '/admin/services'
*/
servicesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: services.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Service\Index::__invoke
* @see app/Livewire/Admin/Service/Index.php:7
* @route '/admin/services'
*/
servicesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: services.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

services.form = servicesForm

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
* @see \App\Livewire\Admin\Product\Index::__invoke
* @see app/Livewire/Admin/Product/Index.php:7
* @route '/admin/products'
*/
const productsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: products.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Product\Index::__invoke
* @see app/Livewire/Admin/Product/Index.php:7
* @route '/admin/products'
*/
productsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: products.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Product\Index::__invoke
* @see app/Livewire/Admin/Product/Index.php:7
* @route '/admin/products'
*/
productsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: products.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

products.form = productsForm

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

/**
* @see \App\Livewire\Admin\Event\Index::__invoke
* @see app/Livewire/Admin/Event/Index.php:7
* @route '/admin/events'
*/
const eventsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: events.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Event\Index::__invoke
* @see app/Livewire/Admin/Event/Index.php:7
* @route '/admin/events'
*/
eventsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: events.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Event\Index::__invoke
* @see app/Livewire/Admin/Event/Index.php:7
* @route '/admin/events'
*/
eventsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: events.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

events.form = eventsForm

/**
* @see \App\Livewire\Admin\OpenSourceProject\Index::__invoke
* @see app/Livewire/Admin/OpenSourceProject/Index.php:7
* @route '/admin/open-source-projects'
*/
export const openSourceProjects = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: openSourceProjects.url(options),
    method: 'get',
})

openSourceProjects.definition = {
    methods: ["get","head"],
    url: '/admin/open-source-projects',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\OpenSourceProject\Index::__invoke
* @see app/Livewire/Admin/OpenSourceProject/Index.php:7
* @route '/admin/open-source-projects'
*/
openSourceProjects.url = (options?: RouteQueryOptions) => {
    return openSourceProjects.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\OpenSourceProject\Index::__invoke
* @see app/Livewire/Admin/OpenSourceProject/Index.php:7
* @route '/admin/open-source-projects'
*/
openSourceProjects.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: openSourceProjects.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\OpenSourceProject\Index::__invoke
* @see app/Livewire/Admin/OpenSourceProject/Index.php:7
* @route '/admin/open-source-projects'
*/
openSourceProjects.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: openSourceProjects.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\OpenSourceProject\Index::__invoke
* @see app/Livewire/Admin/OpenSourceProject/Index.php:7
* @route '/admin/open-source-projects'
*/
const openSourceProjectsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: openSourceProjects.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\OpenSourceProject\Index::__invoke
* @see app/Livewire/Admin/OpenSourceProject/Index.php:7
* @route '/admin/open-source-projects'
*/
openSourceProjectsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: openSourceProjects.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\OpenSourceProject\Index::__invoke
* @see app/Livewire/Admin/OpenSourceProject/Index.php:7
* @route '/admin/open-source-projects'
*/
openSourceProjectsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: openSourceProjects.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

openSourceProjects.form = openSourceProjectsForm

/**
* @see \App\Livewire\Admin\OrderCenter\Index::__invoke
* @see app/Livewire/Admin/OrderCenter/Index.php:7
* @route '/admin/order-center'
*/
export const orderCenter = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: orderCenter.url(options),
    method: 'get',
})

orderCenter.definition = {
    methods: ["get","head"],
    url: '/admin/order-center',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\OrderCenter\Index::__invoke
* @see app/Livewire/Admin/OrderCenter/Index.php:7
* @route '/admin/order-center'
*/
orderCenter.url = (options?: RouteQueryOptions) => {
    return orderCenter.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\OrderCenter\Index::__invoke
* @see app/Livewire/Admin/OrderCenter/Index.php:7
* @route '/admin/order-center'
*/
orderCenter.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: orderCenter.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\OrderCenter\Index::__invoke
* @see app/Livewire/Admin/OrderCenter/Index.php:7
* @route '/admin/order-center'
*/
orderCenter.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: orderCenter.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\OrderCenter\Index::__invoke
* @see app/Livewire/Admin/OrderCenter/Index.php:7
* @route '/admin/order-center'
*/
const orderCenterForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: orderCenter.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\OrderCenter\Index::__invoke
* @see app/Livewire/Admin/OrderCenter/Index.php:7
* @route '/admin/order-center'
*/
orderCenterForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: orderCenter.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\OrderCenter\Index::__invoke
* @see app/Livewire/Admin/OrderCenter/Index.php:7
* @route '/admin/order-center'
*/
orderCenterForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: orderCenter.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

orderCenter.form = orderCenterForm

const admin = {
    globalSearch: Object.assign(globalSearch, globalSearch),
    dashboard: Object.assign(dashboard, dashboard),
    users: Object.assign(users, users),
    rawMaterials: Object.assign(rawMaterials, rawMaterials),
    services: Object.assign(services, services),
    products: Object.assign(products, products),
    events: Object.assign(events, events735790),
    teams: Object.assign(teams, teams),
    openSourceProjects: Object.assign(openSourceProjects, openSourceProjects),
    orderCenter: Object.assign(orderCenter, orderCenter),
    cms: Object.assign(cms, cms),
}

export default admin