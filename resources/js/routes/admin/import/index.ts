import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\ImportController::paymentsLegacy
 * @see app/Http/Controllers/Admin/ImportController.php:107
 * @route '/admin/importar-pagos-historial'
 */
export const paymentsLegacy = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: paymentsLegacy.url(options),
    method: 'post',
})

paymentsLegacy.definition = {
    methods: ["post"],
    url: '/admin/importar-pagos-historial',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ImportController::paymentsLegacy
 * @see app/Http/Controllers/Admin/ImportController.php:107
 * @route '/admin/importar-pagos-historial'
 */
paymentsLegacy.url = (options?: RouteQueryOptions) => {
    return paymentsLegacy.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ImportController::paymentsLegacy
 * @see app/Http/Controllers/Admin/ImportController.php:107
 * @route '/admin/importar-pagos-historial'
 */
paymentsLegacy.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: paymentsLegacy.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\ImportController::paymentsLegacy
 * @see app/Http/Controllers/Admin/ImportController.php:107
 * @route '/admin/importar-pagos-historial'
 */
    const paymentsLegacyForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: paymentsLegacy.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ImportController::paymentsLegacy
 * @see app/Http/Controllers/Admin/ImportController.php:107
 * @route '/admin/importar-pagos-historial'
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