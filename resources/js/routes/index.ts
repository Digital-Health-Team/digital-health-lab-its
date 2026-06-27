import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../wayfinder'
/**
* @see \App\Livewire\Auth\Login::__invoke
* @see app/Livewire/Auth/Login.php:7
* @route '/login'
*/
export const login = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: login.url(options),
    method: 'get',
})

login.definition = {
    methods: ["get","head"],
    url: '/login',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Auth\Login::__invoke
* @see app/Livewire/Auth/Login.php:7
* @route '/login'
*/
login.url = (options?: RouteQueryOptions) => {
    return login.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Auth\Login::__invoke
* @see app/Livewire/Auth/Login.php:7
* @route '/login'
*/
login.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: login.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Auth\Login::__invoke
* @see app/Livewire/Auth/Login.php:7
* @route '/login'
*/
login.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: login.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Auth\Login::__invoke
* @see app/Livewire/Auth/Login.php:7
* @route '/login'
*/
const loginForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: login.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Auth\Login::__invoke
* @see app/Livewire/Auth/Login.php:7
* @route '/login'
*/
loginForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: login.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Auth\Login::__invoke
* @see app/Livewire/Auth/Login.php:7
* @route '/login'
*/
loginForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: login.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

login.form = loginForm

/**
* @see \Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::logout
* @see vendor/laravel/fortify/src/Http/Controllers/AuthenticatedSessionController.php:100
* @route '/logout'
*/
export const logout = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(options),
    method: 'post',
})

logout.definition = {
    methods: ["post"],
    url: '/logout',
} satisfies RouteDefinition<["post"]>

/**
* @see \Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::logout
* @see vendor/laravel/fortify/src/Http/Controllers/AuthenticatedSessionController.php:100
* @route '/logout'
*/
logout.url = (options?: RouteQueryOptions) => {
    return logout.definition.url + queryParams(options)
}

/**
* @see \Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::logout
* @see vendor/laravel/fortify/src/Http/Controllers/AuthenticatedSessionController.php:100
* @route '/logout'
*/
logout.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(options),
    method: 'post',
})

/**
* @see \Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::logout
* @see vendor/laravel/fortify/src/Http/Controllers/AuthenticatedSessionController.php:100
* @route '/logout'
*/
const logoutForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: logout.url(options),
    method: 'post',
})

/**
* @see \Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::logout
* @see vendor/laravel/fortify/src/Http/Controllers/AuthenticatedSessionController.php:100
* @route '/logout'
*/
logoutForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: logout.url(options),
    method: 'post',
})

logout.form = logoutForm

/**
* @see \App\Livewire\Auth\Register::__invoke
* @see app/Livewire/Auth/Register.php:7
* @route '/register'
*/
export const register = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})

register.definition = {
    methods: ["get","head"],
    url: '/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Auth\Register::__invoke
* @see app/Livewire/Auth/Register.php:7
* @route '/register'
*/
register.url = (options?: RouteQueryOptions) => {
    return register.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Auth\Register::__invoke
* @see app/Livewire/Auth/Register.php:7
* @route '/register'
*/
register.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Auth\Register::__invoke
* @see app/Livewire/Auth/Register.php:7
* @route '/register'
*/
register.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: register.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Auth\Register::__invoke
* @see app/Livewire/Auth/Register.php:7
* @route '/register'
*/
const registerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: register.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Auth\Register::__invoke
* @see app/Livewire/Auth/Register.php:7
* @route '/register'
*/
registerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: register.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Auth\Register::__invoke
* @see app/Livewire/Auth/Register.php:7
* @route '/register'
*/
registerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: register.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

register.form = registerForm

/**
* @see \App\Http\Controllers\LandingPageController::home
* @see app/Http/Controllers/LandingPageController.php:14
* @route '/'
*/
export const home = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: home.url(options),
    method: 'get',
})

home.definition = {
    methods: ["get","head"],
    url: '/',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\LandingPageController::home
* @see app/Http/Controllers/LandingPageController.php:14
* @route '/'
*/
home.url = (options?: RouteQueryOptions) => {
    return home.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\LandingPageController::home
* @see app/Http/Controllers/LandingPageController.php:14
* @route '/'
*/
home.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: home.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LandingPageController::home
* @see app/Http/Controllers/LandingPageController.php:14
* @route '/'
*/
home.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: home.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\LandingPageController::home
* @see app/Http/Controllers/LandingPageController.php:14
* @route '/'
*/
const homeForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: home.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LandingPageController::home
* @see app/Http/Controllers/LandingPageController.php:14
* @route '/'
*/
homeForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: home.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LandingPageController::home
* @see app/Http/Controllers/LandingPageController.php:14
* @route '/'
*/
homeForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: home.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

home.form = homeForm

/**
* @see \App\Http\Controllers\PameranController::exhibition
* @see app/Http/Controllers/PameranController.php:10
* @route '/exhibition/{exhibition_name}'
*/
export const exhibition = (args: { exhibition_name: string | number } | [exhibition_name: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exhibition.url(args, options),
    method: 'get',
})

exhibition.definition = {
    methods: ["get","head"],
    url: '/exhibition/{exhibition_name}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PameranController::exhibition
* @see app/Http/Controllers/PameranController.php:10
* @route '/exhibition/{exhibition_name}'
*/
exhibition.url = (args: { exhibition_name: string | number } | [exhibition_name: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { exhibition_name: args }
    }

    if (Array.isArray(args)) {
        args = {
            exhibition_name: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        exhibition_name: args.exhibition_name,
    }

    return exhibition.definition.url
            .replace('{exhibition_name}', parsedArgs.exhibition_name.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PameranController::exhibition
* @see app/Http/Controllers/PameranController.php:10
* @route '/exhibition/{exhibition_name}'
*/
exhibition.get = (args: { exhibition_name: string | number } | [exhibition_name: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exhibition.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PameranController::exhibition
* @see app/Http/Controllers/PameranController.php:10
* @route '/exhibition/{exhibition_name}'
*/
exhibition.head = (args: { exhibition_name: string | number } | [exhibition_name: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exhibition.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\PameranController::exhibition
* @see app/Http/Controllers/PameranController.php:10
* @route '/exhibition/{exhibition_name}'
*/
const exhibitionForm = (args: { exhibition_name: string | number } | [exhibition_name: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exhibition.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PameranController::exhibition
* @see app/Http/Controllers/PameranController.php:10
* @route '/exhibition/{exhibition_name}'
*/
exhibitionForm.get = (args: { exhibition_name: string | number } | [exhibition_name: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exhibition.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PameranController::exhibition
* @see app/Http/Controllers/PameranController.php:10
* @route '/exhibition/{exhibition_name}'
*/
exhibitionForm.head = (args: { exhibition_name: string | number } | [exhibition_name: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exhibition.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

exhibition.form = exhibitionForm

/**
* @see \App\Livewire\Settings::__invoke
* @see app/Livewire/Settings.php:7
* @route '/settings'
*/
export const settings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: settings.url(options),
    method: 'get',
})

settings.definition = {
    methods: ["get","head"],
    url: '/settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Livewire\Settings::__invoke
* @see app/Livewire/Settings.php:7
* @route '/settings'
*/
settings.url = (options?: RouteQueryOptions) => {
    return settings.definition.url + queryParams(options)
}

/**
* @see \App\Livewire\Settings::__invoke
* @see app/Livewire/Settings.php:7
* @route '/settings'
*/
settings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: settings.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Settings::__invoke
* @see app/Livewire/Settings.php:7
* @route '/settings'
*/
settings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: settings.url(options),
    method: 'head',
})

/**
* @see \App\Livewire\Settings::__invoke
* @see app/Livewire/Settings.php:7
* @route '/settings'
*/
const settingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: settings.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Settings::__invoke
* @see app/Livewire/Settings.php:7
* @route '/settings'
*/
settingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: settings.url(options),
    method: 'get',
})

/**
* @see \App\Livewire\Settings::__invoke
* @see app/Livewire/Settings.php:7
* @route '/settings'
*/
settingsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: settings.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

settings.form = settingsForm

/**
* @see \App\Http\Controllers\GlobalSearchController::search
* @see app/Http/Controllers/GlobalSearchController.php:16
* @route '/search'
*/
export const search = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

search.definition = {
    methods: ["get","head"],
    url: '/search',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\GlobalSearchController::search
* @see app/Http/Controllers/GlobalSearchController.php:16
* @route '/search'
*/
search.url = (options?: RouteQueryOptions) => {
    return search.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\GlobalSearchController::search
* @see app/Http/Controllers/GlobalSearchController.php:16
* @route '/search'
*/
search.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GlobalSearchController::search
* @see app/Http/Controllers/GlobalSearchController.php:16
* @route '/search'
*/
search.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: search.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\GlobalSearchController::search
* @see app/Http/Controllers/GlobalSearchController.php:16
* @route '/search'
*/
const searchForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GlobalSearchController::search
* @see app/Http/Controllers/GlobalSearchController.php:16
* @route '/search'
*/
searchForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GlobalSearchController::search
* @see app/Http/Controllers/GlobalSearchController.php:16
* @route '/search'
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
* @see \App\Http\Controllers\TrainingController::training
* @see app/Http/Controllers/TrainingController.php:17
* @route '/training'
*/
export const training = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: training.url(options),
    method: 'get',
})

training.definition = {
    methods: ["get","head"],
    url: '/training',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\TrainingController::training
* @see app/Http/Controllers/TrainingController.php:17
* @route '/training'
*/
training.url = (options?: RouteQueryOptions) => {
    return training.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\TrainingController::training
* @see app/Http/Controllers/TrainingController.php:17
* @route '/training'
*/
training.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: training.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\TrainingController::training
* @see app/Http/Controllers/TrainingController.php:17
* @route '/training'
*/
training.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: training.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\TrainingController::training
* @see app/Http/Controllers/TrainingController.php:17
* @route '/training'
*/
const trainingForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: training.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\TrainingController::training
* @see app/Http/Controllers/TrainingController.php:17
* @route '/training'
*/
trainingForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: training.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\TrainingController::training
* @see app/Http/Controllers/TrainingController.php:17
* @route '/training'
*/
trainingForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: training.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

training.form = trainingForm

/**
* @see \App\Http\Controllers\ProjectsController::projects
* @see app/Http/Controllers/ProjectsController.php:10
* @route '/projects'
*/
export const projects = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: projects.url(options),
    method: 'get',
})

projects.definition = {
    methods: ["get","head"],
    url: '/projects',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProjectsController::projects
* @see app/Http/Controllers/ProjectsController.php:10
* @route '/projects'
*/
projects.url = (options?: RouteQueryOptions) => {
    return projects.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProjectsController::projects
* @see app/Http/Controllers/ProjectsController.php:10
* @route '/projects'
*/
projects.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: projects.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProjectsController::projects
* @see app/Http/Controllers/ProjectsController.php:10
* @route '/projects'
*/
projects.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: projects.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProjectsController::projects
* @see app/Http/Controllers/ProjectsController.php:10
* @route '/projects'
*/
const projectsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: projects.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProjectsController::projects
* @see app/Http/Controllers/ProjectsController.php:10
* @route '/projects'
*/
projectsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: projects.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProjectsController::projects
* @see app/Http/Controllers/ProjectsController.php:10
* @route '/projects'
*/
projectsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: projects.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

projects.form = projectsForm

/**
* @see \App\Http\Controllers\ServicesController::services
* @see app/Http/Controllers/ServicesController.php:11
* @route '/services'
*/
export const services = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: services.url(options),
    method: 'get',
})

services.definition = {
    methods: ["get","head"],
    url: '/services',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ServicesController::services
* @see app/Http/Controllers/ServicesController.php:11
* @route '/services'
*/
services.url = (options?: RouteQueryOptions) => {
    return services.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ServicesController::services
* @see app/Http/Controllers/ServicesController.php:11
* @route '/services'
*/
services.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: services.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ServicesController::services
* @see app/Http/Controllers/ServicesController.php:11
* @route '/services'
*/
services.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: services.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ServicesController::services
* @see app/Http/Controllers/ServicesController.php:11
* @route '/services'
*/
const servicesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: services.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ServicesController::services
* @see app/Http/Controllers/ServicesController.php:11
* @route '/services'
*/
servicesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: services.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ServicesController::services
* @see app/Http/Controllers/ServicesController.php:11
* @route '/services'
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
* @see \App\Http\Controllers\ProductsController::products
* @see app/Http/Controllers/ProductsController.php:10
* @route '/products'
*/
export const products = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: products.url(options),
    method: 'get',
})

products.definition = {
    methods: ["get","head"],
    url: '/products',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProductsController::products
* @see app/Http/Controllers/ProductsController.php:10
* @route '/products'
*/
products.url = (options?: RouteQueryOptions) => {
    return products.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProductsController::products
* @see app/Http/Controllers/ProductsController.php:10
* @route '/products'
*/
products.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: products.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProductsController::products
* @see app/Http/Controllers/ProductsController.php:10
* @route '/products'
*/
products.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: products.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProductsController::products
* @see app/Http/Controllers/ProductsController.php:10
* @route '/products'
*/
const productsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: products.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProductsController::products
* @see app/Http/Controllers/ProductsController.php:10
* @route '/products'
*/
productsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: products.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProductsController::products
* @see app/Http/Controllers/ProductsController.php:10
* @route '/products'
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
* @see \App\Http\Controllers\PublicationsController::publications
* @see app/Http/Controllers/PublicationsController.php:11
* @route '/publications'
*/
export const publications = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: publications.url(options),
    method: 'get',
})

publications.definition = {
    methods: ["get","head"],
    url: '/publications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PublicationsController::publications
* @see app/Http/Controllers/PublicationsController.php:11
* @route '/publications'
*/
publications.url = (options?: RouteQueryOptions) => {
    return publications.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PublicationsController::publications
* @see app/Http/Controllers/PublicationsController.php:11
* @route '/publications'
*/
publications.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: publications.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PublicationsController::publications
* @see app/Http/Controllers/PublicationsController.php:11
* @route '/publications'
*/
publications.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: publications.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\PublicationsController::publications
* @see app/Http/Controllers/PublicationsController.php:11
* @route '/publications'
*/
const publicationsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: publications.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PublicationsController::publications
* @see app/Http/Controllers/PublicationsController.php:11
* @route '/publications'
*/
publicationsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: publications.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PublicationsController::publications
* @see app/Http/Controllers/PublicationsController.php:11
* @route '/publications'
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
