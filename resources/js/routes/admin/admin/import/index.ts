import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\ImportController::paymentsLegacy
 * @see app/Http/Controllers/Admin/ImportController.php:107
 * @route '/admin/importar-pagos-legacy'
 */
export const paymentsLegacy = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: paymentsLegacy.url(options),
    method: 'post',
})

paymentsLegacy.definition = {
    methods: ["post"],
    url: '/admin/importar-pagos-legacy',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ImportController::paymentsLegacy
 * @see app/Http/Controllers/Admin/ImportController.php:107
 * @route '/admin/importar-pagos-legacy'
 */
paymentsLegacy.url = (options?: RouteQueryOptions) => {
    return paymentsLegacy.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ImportController::paymentsLegacy
 * @see app/Http/Controllers/Admin/ImportController.php:107
 * @route '/admin/importar-pagos-legacy'
 */
paymentsLegacy.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: paymentsLegacy.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\ImportController::paymentsLegacy
 * @see app/Http/Controllers/Admin/ImportController.php:107
 * @route '/admin/importar-pagos-legacy'
 */
    const paymentsLegacyForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: paymentsLegacy.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ImportController::paymentsLegacy
 * @see app/Http/Controllers/Admin/ImportController.php:107
 * @route '/admin/importar-pagos-legacy'
 */
        paymentsLegacyForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: paymentsLegacy.url(options),
            method: 'post',
        })
    
    paymentsLegacy.form = paymentsLegacyForm
const importMethod = {
    paymentsLegacy: Object.assign(paymentsLegacy, paymentsLegacy),
}

export default importMethod