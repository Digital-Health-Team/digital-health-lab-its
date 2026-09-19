import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ChatbotController::ask
* @see app/Http/Controllers/ChatbotController.php:14
* @route '/chatbot/ask'
*/
export const ask = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: ask.url(options),
    method: 'post',
})

ask.definition = {
    methods: ["post"],
    url: '/chatbot/ask',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ChatbotController::ask
* @see app/Http/Controllers/ChatbotController.php:14
* @route '/chatbot/ask'
*/
ask.url = (options?: RouteQueryOptions) => {
    return ask.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ChatbotController::ask
* @see app/Http/Controllers/ChatbotController.php:14
* @route '/chatbot/ask'
*/
ask.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: ask.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ChatbotController::ask
* @see app/Http/Controllers/ChatbotController.php:14
* @route '/chatbot/ask'
*/
const askForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: ask.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ChatbotController::ask
* @see app/Http/Controllers/ChatbotController.php:14
* @route '/chatbot/ask'
*/
askForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: ask.url(options),
    method: 'post',
})

ask.form = askForm

const ChatbotController = { ask }

export default ChatbotController