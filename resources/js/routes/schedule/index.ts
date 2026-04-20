import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Student\ProgressController::pdf
 * @see app/Http/Controllers/Student/ProgressController.php:192
 * @route '/mi-horario/descargar'
 */
export const pdf = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pdf.url(options),
    method: 'get',
})

pdf.definition = {
    methods: ["get","head"],
    url: '/mi-horario/descargar',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Student\ProgressController::pdf
 * @see app/Http/Controllers/Student/ProgressController.php:192
 * @route '/mi-horario/descargar'
 */
pdf.url = (options?: RouteQueryOptions) => {
    return pdf.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\ProgressController::pdf
 * @see app/Http/Controllers/Student/ProgressController.php:192
 * @route '/mi-horario/descargar'
 */
pdf.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pdf.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Student\ProgressController::pdf
 * @see app/Http/Controllers/Student/ProgressController.php:192
 * @route '/mi-horario/descargar'
 */
pdf.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: pdf.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Student\ProgressController::pdf
 * @see app/Http/Controllers/Student/ProgressController.php:192
 * @route '/mi-horario/descargar'
 */
    const pdfForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: pdf.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Student\ProgressController::pdf
 * @see app/Http/Controllers/Student/ProgressController.php:192
 * @route '/mi-horario/descargar'
 */
        pdfForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: pdf.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Student\ProgressController::pdf
 * @see app/Http/Controllers/Student/ProgressController.php:192
 * @route '/mi-horario/descargar'
 */
        pdfForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: pdf.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    pdf.form = pdfForm
const schedule = {
    pdf: Object.assign(pdf, pdf),
}

export default schedule