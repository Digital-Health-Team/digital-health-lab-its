import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \Livewire\Features\SupportFileUploads\FileUploadController::handle
* @see vendor/livewire/livewire/src/Features/SupportFileUploads/FileUploadController.php:27
* @route '/livewire-10670b7f/upload-file'
*/
export const handle = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: handle.url(options),
    method: 'post',
})

handle.definition = {
    methods: ["post"],
    url: '/livewire-10670b7f/upload-file',
} satisfies RouteDefinition<["post"]>

/**
* @see \Livewire\Features\SupportFileUploads\FileUploadController::handle
* @see vendor/livewire/livewire/src/Features/SupportFileUploads/FileUploadController.php:27
* @route '/livewire-10670b7f/upload-file'
*/
handle.url = (options?: RouteQueryOptions) => {
    return handle.definition.url + queryParams(options)
}

/**
* @see \Livewire\Features\SupportFileUploads\FileUploadController::handle
* @see vendor/livewire/livewire/src/Features/SupportFileUploads/FileUploadController.php:27
* @route '/livewire-10670b7f/upload-file'
*/
handle.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: handle.url(options),
    method: 'post',
})

/**
* @see \Livewire\Features\SupportFileUploads\FileUploadController::handle
* @see vendor/livewire/livewire/src/Features/SupportFileUploads/FileUploadController.php:27
* @route '/livewire-aad6c32d/upload-file'
*/
const handleForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: handle.url(options),
    method: 'post',
})

/**
* @see \Livewire\Features\SupportFileUploads\FileUploadController::handle
* @see vendor/livewire/livewire/src/Features/SupportFileUploads/FileUploadController.php:27
* @route '/livewire-aad6c32d/upload-file'
*/
handleForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: handle.url(options),
    method: 'post',
})

handle.form = handleForm

const FileUploadController = { handle }

export default FileUploadController