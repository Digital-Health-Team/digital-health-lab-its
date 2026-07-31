import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
import orderCenterC775ed from './order-center'
import events735790 from './events'
import teams from './teams'
import trainingsA8c742 from './trainings'
import inventoryEd84cf from './inventory'
import cms from './cms'
/**
* @see \App\Livewire\Admin\GlobalSearch\Index::__invoke
* @see app/Livewire/Admin/GlobalSearch/Index.php:7
* @route '/admin/search'
*/
export const search = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

search.definition = {
    methods: ["get","head"],
    url: '/admin/search',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\GlobalSearch\Index::__invoke
* @see app/Livewire/Admin/GlobalSearch/Index.php:7
* @route '/admin/search'
*/
search.url = (options?: RouteQueryOptions) => {
    return search.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\GlobalSearch\Index::__invoke
* @see app/Livewire/Admin/GlobalSearch/Index.php:7
* @route '/admin/search'
*/
search.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\GlobalSearch\Index::__invoke
* @see app/Livewire/Admin/GlobalSearch/Index.php:7
* @route '/admin/search'
*/
search.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: search.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\GlobalSearch\Index::__invoke
* @see app/Livewire/Admin/GlobalSearch/Index.php:7
* @route '/admin/search'
*/
const searchForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\GlobalSearch\Index::__invoke
* @see app/Livewire/Admin/GlobalSearch/Index.php:7
* @route '/admin/search'
*/
searchForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\GlobalSearch\Index::__invoke
* @see app/Livewire/Admin/GlobalSearch/Index.php:7
* @route '/admin/search'
*/
searchForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

search.form = searchForm

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
* @see \App\Livewire\Admin\Publication\Index::__invoke
* @see app/Livewire/Admin/Publication/Index.php:7
* @route '/admin/publications'
*/
export const publications = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: publications.url(options),
    method: 'get',
})

publications.definition = {
    methods: ["get","head"],
    url: '/admin/publications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Publication\Index::__invoke
* @see app/Livewire/Admin/Publication/Index.php:7
* @route '/admin/publications'
*/
publications.url = (options?: RouteQueryOptions) => {
    return publications.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Publication\Index::__invoke
* @see app/Livewire/Admin/Publication/Index.php:7
* @route '/admin/publications'
*/
publications.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: publications.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Publication\Index::__invoke
* @see app/Livewire/Admin/Publication/Index.php:7
* @route '/admin/publications'
*/
publications.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: publications.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\Publication\Index::__invoke
* @see app/Livewire/Admin/Publication/Index.php:7
* @route '/admin/publications'
*/
const publicationsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: publications.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Publication\Index::__invoke
* @see app/Livewire/Admin/Publication/Index.php:7
* @route '/admin/publications'
*/
publicationsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: publications.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Publication\Index::__invoke
* @see app/Livewire/Admin/Publication/Index.php:7
* @route '/admin/publications'
*/
publicationsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: publications.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

publications.form = publicationsForm

/**
* @see \App\Livewire\Admin\Training\Index::__invoke
* @see app/Livewire/Admin/Training/Index.php:7
* @route '/admin/trainings'
*/
export const trainings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: trainings.url(options),
    method: 'get',
})

trainings.definition = {
    methods: ["get","head"],
    url: '/admin/trainings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Training\Index::__invoke
* @see app/Livewire/Admin/Training/Index.php:7
* @route '/admin/trainings'
*/
trainings.url = (options?: RouteQueryOptions) => {
    return trainings.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Training\Index::__invoke
* @see app/Livewire/Admin/Training/Index.php:7
* @route '/admin/trainings'
*/
trainings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: trainings.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Training\Index::__invoke
* @see app/Livewire/Admin/Training/Index.php:7
* @route '/admin/trainings'
*/
trainings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: trainings.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\Training\Index::__invoke
* @see app/Livewire/Admin/Training/Index.php:7
* @route '/admin/trainings'
*/
const trainingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: trainings.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Training\Index::__invoke
* @see app/Livewire/Admin/Training/Index.php:7
* @route '/admin/trainings'
*/
trainingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: trainings.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Training\Index::__invoke
* @see app/Livewire/Admin/Training/Index.php:7
* @route '/admin/trainings'
*/
trainingsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: trainings.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

trainings.form = trainingsForm

/**
* @see \App\Livewire\Admin\Inventory\Index::__invoke
* @see app/Livewire/Admin/Inventory/Index.php:7
* @route '/admin/inventory'
*/
export const inventory = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: inventory.url(options),
    method: 'get',
})

inventory.definition = {
    methods: ["get","head"],
    url: '/admin/inventory',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Inventory\Index::__invoke
* @see app/Livewire/Admin/Inventory/Index.php:7
* @route '/admin/inventory'
*/
inventory.url = (options?: RouteQueryOptions) => {
    return inventory.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Inventory\Index::__invoke
* @see app/Livewire/Admin/Inventory/Index.php:7
* @route '/admin/inventory'
*/
inventory.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: inventory.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Inventory\Index::__invoke
* @see app/Livewire/Admin/Inventory/Index.php:7
* @route '/admin/inventory'
*/
inventory.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: inventory.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\Inventory\Index::__invoke
* @see app/Livewire/Admin/Inventory/Index.php:7
* @route '/admin/inventory'
*/
const inventoryForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: inventory.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Inventory\Index::__invoke
* @see app/Livewire/Admin/Inventory/Index.php:7
* @route '/admin/inventory'
*/
inventoryForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: inventory.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Inventory\Index::__invoke
* @see app/Livewire/Admin/Inventory/Index.php:7
* @route '/admin/inventory'
*/
inventoryForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: inventory.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

inventory.form = inventoryForm

/**
* @see \App\Livewire\Admin\Tool\Index::__invoke
* @see app/Livewire/Admin/Tool/Index.php:7
* @route '/admin/tools'
*/
export const tools = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: tools.url(options),
    method: 'get',
})

tools.definition = {
    methods: ["get","head"],
    url: '/admin/tools',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Tool\Index::__invoke
* @see app/Livewire/Admin/Tool/Index.php:7
* @route '/admin/tools'
*/
tools.url = (options?: RouteQueryOptions) => {
    return tools.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Tool\Index::__invoke
* @see app/Livewire/Admin/Tool/Index.php:7
* @route '/admin/tools'
*/
tools.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: tools.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Tool\Index::__invoke
* @see app/Livewire/Admin/Tool/Index.php:7
* @route '/admin/tools'
*/
tools.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: tools.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\Tool\Index::__invoke
* @see app/Livewire/Admin/Tool/Index.php:7
* @route '/admin/tools'
*/
const toolsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: tools.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Tool\Index::__invoke
* @see app/Livewire/Admin/Tool/Index.php:7
* @route '/admin/tools'
*/
toolsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: tools.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Tool\Index::__invoke
* @see app/Livewire/Admin/Tool/Index.php:7
* @route '/admin/tools'
*/
toolsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: tools.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

tools.form = toolsForm

/**
* @see \App\Http\Controllers\Admin\PrintLabelController::__invoke
* @see app/Http/Controllers/Admin/PrintLabelController.php:15
* @route '/admin/print-label/{type}/{id}'
*/
export const printLabel = (args: { type: string | number, id: string | number } | [type: string | number, id: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: printLabel.url(args, options),
    method: 'get',
})

printLabel.definition = {
    methods: ["get","head"],
    url: '/admin/print-label/{type}/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\PrintLabelController::__invoke
* @see app/Http/Controllers/Admin/PrintLabelController.php:15
* @route '/admin/print-label/{type}/{id}'
*/
printLabel.url = (args: { type: string | number, id: string | number } | [type: string | number, id: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            type: args[0],
            id: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        type: args.type,
        id: args.id,
    }

    return printLabel.definition.url
            .replace('{type}', parsedArgs.type.toString())
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\PrintLabelController::__invoke
* @see app/Http/Controllers/Admin/PrintLabelController.php:15
* @route '/admin/print-label/{type}/{id}'
*/
printLabel.get = (args: { type: string | number, id: string | number } | [type: string | number, id: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: printLabel.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\PrintLabelController::__invoke
* @see app/Http/Controllers/Admin/PrintLabelController.php:15
* @route '/admin/print-label/{type}/{id}'
*/
printLabel.head = (args: { type: string | number, id: string | number } | [type: string | number, id: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: printLabel.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\PrintLabelController::__invoke
* @see app/Http/Controllers/Admin/PrintLabelController.php:15
* @route '/admin/print-label/{type}/{id}'
*/
const printLabelForm = (args: { type: string | number, id: string | number } | [type: string | number, id: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: printLabel.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\PrintLabelController::__invoke
* @see app/Http/Controllers/Admin/PrintLabelController.php:15
* @route '/admin/print-label/{type}/{id}'
*/
printLabelForm.get = (args: { type: string | number, id: string | number } | [type: string | number, id: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: printLabel.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\PrintLabelController::__invoke
* @see app/Http/Controllers/Admin/PrintLabelController.php:15
* @route '/admin/print-label/{type}/{id}'
*/
printLabelForm.head = (args: { type: string | number, id: string | number } | [type: string | number, id: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: printLabel.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

printLabel.form = printLabelForm

/**
* @see \App\Livewire\Admin\Report\Index::__invoke
* @see app/Livewire/Admin/Report/Index.php:7
* @route '/admin/reports'
*/
export const reports = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reports.url(options),
    method: 'get',
})

reports.definition = {
    methods: ["get","head"],
    url: '/admin/reports',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Admin\Report\Index::__invoke
* @see app/Livewire/Admin/Report/Index.php:7
* @route '/admin/reports'
*/
reports.url = (options?: RouteQueryOptions) => {
    return reports.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Admin\Report\Index::__invoke
* @see app/Livewire/Admin/Report/Index.php:7
* @route '/admin/reports'
*/
reports.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reports.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Report\Index::__invoke
* @see app/Livewire/Admin/Report/Index.php:7
* @route '/admin/reports'
*/
reports.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: reports.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Admin\Report\Index::__invoke
* @see app/Livewire/Admin/Report/Index.php:7
* @route '/admin/reports'
*/
const reportsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: reports.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Report\Index::__invoke
* @see app/Livewire/Admin/Report/Index.php:7
* @route '/admin/reports'
*/
reportsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: reports.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Admin\Report\Index::__invoke
* @see app/Livewire/Admin/Report/Index.php:7
* @route '/admin/reports'
*/
reportsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: reports.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

reports.form = reportsForm

/**
* @see \App\Http\Controllers\AdminDocumentationController::__invoke
* @see app/Http/Controllers/AdminDocumentationController.php:9
* @route '/admin/documentations'
*/
export const documentation = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: documentation.url(options),
    method: 'get',
})

documentation.definition = {
    methods: ["get","head"],
    url: '/admin/documentations',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AdminDocumentationController::__invoke
* @see app/Http/Controllers/AdminDocumentationController.php:9
* @route '/admin/documentations'
*/
documentation.url = (options?: RouteQueryOptions) => {
    return documentation.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AdminDocumentationController::__invoke
* @see app/Http/Controllers/AdminDocumentationController.php:9
* @route '/admin/documentations'
*/
documentation.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: documentation.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\AdminDocumentationController::__invoke
* @see app/Http/Controllers/AdminDocumentationController.php:9
* @route '/admin/documentations'
*/
documentation.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: documentation.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\AdminDocumentationController::__invoke
* @see app/Http/Controllers/AdminDocumentationController.php:9
* @route '/admin/documentations'
*/
const documentationForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: documentation.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\AdminDocumentationController::__invoke
* @see app/Http/Controllers/AdminDocumentationController.php:9
* @route '/admin/documentations'
*/
documentationForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: documentation.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\AdminDocumentationController::__invoke
* @see app/Http/Controllers/AdminDocumentationController.php:9
* @route '/admin/documentations'
*/
documentationForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: documentation.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

documentation.form = documentationForm

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
export const labs = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: labs.url(options),
    method: 'get',
})

labs.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/admin/labs',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
labs.url = (options?: RouteQueryOptions) => {
    return labs.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
labs.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: labs.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
labs.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: labs.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
labs.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: labs.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
labs.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: labs.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
labs.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: labs.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
labs.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: labs.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
labs.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: labs.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
const labsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: labs.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
labsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: labs.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
labsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: labs.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
labsForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: labs.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
labsForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: labs.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
labsForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: labs.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
labsForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: labs.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/labs'
*/
labsForm.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: labs.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

labs.form = labsForm

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
export const rawMaterials = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: rawMaterials.url(options),
    method: 'get',
})

rawMaterials.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/admin/raw-materials',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
rawMaterials.url = (options?: RouteQueryOptions) => {
    return rawMaterials.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
rawMaterials.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: rawMaterials.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
rawMaterials.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: rawMaterials.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
rawMaterials.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: rawMaterials.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
rawMaterials.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: rawMaterials.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
rawMaterials.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: rawMaterials.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
rawMaterials.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: rawMaterials.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
rawMaterials.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: rawMaterials.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
const rawMaterialsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: rawMaterials.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
rawMaterialsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: rawMaterials.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
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

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
rawMaterialsForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: rawMaterials.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
rawMaterialsForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: rawMaterials.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
rawMaterialsForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: rawMaterials.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
rawMaterialsForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: rawMaterials.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/raw-materials'
*/
rawMaterialsForm.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: rawMaterials.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

rawMaterials.form = rawMaterialsForm

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
export const masterData = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: masterData.url(options),
    method: 'get',
})

masterData.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/admin/master-data',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
masterData.url = (options?: RouteQueryOptions) => {
    return masterData.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
masterData.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: masterData.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
masterData.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: masterData.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
masterData.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: masterData.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
masterData.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: masterData.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
masterData.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: masterData.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
masterData.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: masterData.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
masterData.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: masterData.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
const masterDataForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: masterData.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
masterDataForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: masterData.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
masterDataForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: masterData.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
masterDataForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: masterData.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
masterDataForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: masterData.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
masterDataForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: masterData.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
masterDataForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: masterData.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin/master-data'
*/
masterDataForm.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: masterData.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

masterData.form = masterDataForm

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

const admin = {
    search: Object.assign(search, search),
    dashboard: Object.assign(dashboard, dashboard),
    orderCenter: Object.assign(orderCenter, orderCenterC775ed),
    services: Object.assign(services, services),
    products: Object.assign(products, products),
    events: Object.assign(events, events735790),
    teams: Object.assign(teams, teams),
    openSourceProjects: Object.assign(openSourceProjects, openSourceProjects),
    publications: Object.assign(publications, publications),
    trainings: Object.assign(trainings, trainingsA8c742),
    inventory: Object.assign(inventory, inventoryEd84cf),
    tools: Object.assign(tools, tools),
    printLabel: Object.assign(printLabel, printLabel),
    reports: Object.assign(reports, reports),
    documentation: Object.assign(documentation, documentation),
    labs: Object.assign(labs, labs),
    rawMaterials: Object.assign(rawMaterials, rawMaterials),
    masterData: Object.assign(masterData, masterData),
    users: Object.assign(users, users),
    cms: Object.assign(cms, cms),
}

export default admin