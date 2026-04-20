import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
/**
* @see vendor/robsontenorio/mary/routes/web.php:7
* @route '/mary/toogle-sidebar'
*/
export const toogleSidebar = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: toogleSidebar.url(options),
    method: 'get',
})

toogleSidebar.definition = {
    methods: ["get","head"],
    url: '/mary/toogle-sidebar',
} satisfies RouteDefinition<["get","head"]>

/**
* @see vendor/robsontenorio/mary/routes/web.php:7
* @route '/mary/toogle-sidebar'
*/
toogleSidebar.url = (options?: RouteQueryOptions) => {
    return toogleSidebar.definition.url + queryParams(options)
}

/**
* @see vendor/robsontenorio/mary/routes/web.php:7
* @route '/mary/toogle-sidebar'
*/
toogleSidebar.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: toogleSidebar.url(options),
    method: 'get',
})

/**
* @see vendor/robsontenorio/mary/routes/web.php:7
* @route '/mary/toogle-sidebar'
*/
toogleSidebar.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: toogleSidebar.url(options),
    method: 'head',
})

/**
* @see vendor/robsontenorio/mary/routes/web.php:13
* @route '/mary/spotlight'
*/
export const spotlight = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: spotlight.url(options),
    method: 'get',
})

spotlight.definition = {
    methods: ["get","head"],
    url: '/mary/spotlight',
} satisfies RouteDefinition<["get","head"]>

/**
* @see vendor/robsontenorio/mary/routes/web.php:13
* @route '/mary/spotlight'
*/
spotlight.url = (options?: RouteQueryOptions) => {
    return spotlight.definition.url + queryParams(options)
}

/**
* @see vendor/robsontenorio/mary/routes/web.php:13
* @route '/mary/spotlight'
*/
spotlight.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: spotlight.url(options),
    method: 'get',
})

/**
* @see vendor/robsontenorio/mary/routes/web.php:13
* @route '/mary/spotlight'
*/
spotlight.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: spotlight.url(options),
    method: 'head',
})

/**
* @see vendor/robsontenorio/mary/routes/web.php:17
* @route '/mary/upload'
*/
export const upload = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: upload.url(options),
    method: 'post',
})

upload.definition = {
    methods: ["post"],
    url: '/mary/upload',
} satisfies RouteDefinition<["post"]>

/**
* @see vendor/robsontenorio/mary/routes/web.php:17
* @route '/mary/upload'
*/
upload.url = (options?: RouteQueryOptions) => {
    return upload.definition.url + queryParams(options)
}

/**
* @see vendor/robsontenorio/mary/routes/web.php:17
* @route '/mary/upload'
*/
upload.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: upload.url(options),
    method: 'post',
})

const mary = {
    toogleSidebar: Object.assign(toogleSidebar, toogleSidebar),
    spotlight: Object.assign(spotlight, spotlight),
    upload: Object.assign(upload, upload),
}

export default mary