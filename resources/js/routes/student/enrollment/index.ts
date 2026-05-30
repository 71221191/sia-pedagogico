import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\EnrollmentController::pdf
 * @see app/Http/Controllers/EnrollmentController.php:124
 * @route '/estudiante/matricula/descargar-pdf/{enrollment}'
 */
export const pdf = (args: { enrollment: string | number } | [enrollment: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pdf.url(args, options),
    method: 'get',
})

pdf.definition = {
    methods: ["get","head"],
    url: '/estudiante/matricula/descargar-pdf/{enrollment}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\EnrollmentController::pdf
 * @see app/Http/Controllers/EnrollmentController.php:124
 * @route '/estudiante/matricula/descargar-pdf/{enrollment}'
 */
pdf.url = (args: { enrollment: string | number } | [enrollment: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { enrollment: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    enrollment: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        enrollment: args.enrollment,
                }

    return pdf.definition.url
            .replace('{enrollment}', parsedArgs.enrollment.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\EnrollmentController::pdf
 * @see app/Http/Controllers/EnrollmentController.php:124
 * @route '/estudiante/matricula/descargar-pdf/{enrollment}'
 */
pdf.get = (args: { enrollment: string | number } | [enrollment: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pdf.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\EnrollmentController::pdf
 * @see app/Http/Controllers/EnrollmentController.php:124
 * @route '/estudiante/matricula/descargar-pdf/{enrollment}'
 */
pdf.head = (args: { enrollment: string | number } | [enrollment: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: pdf.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\EnrollmentController::pdf
 * @see app/Http/Controllers/EnrollmentController.php:124
 * @route '/estudiante/matricula/descargar-pdf/{enrollment}'
 */
    const pdfForm = (args: { enrollment: string | number } | [enrollment: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: pdf.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\EnrollmentController::pdf
 * @see app/Http/Controllers/EnrollmentController.php:124
 * @route '/estudiante/matricula/descargar-pdf/{enrollment}'
 */
        pdfForm.get = (args: { enrollment: string | number } | [enrollment: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: pdf.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\EnrollmentController::pdf
 * @see app/Http/Controllers/EnrollmentController.php:124
 * @route '/estudiante/matricula/descargar-pdf/{enrollment}'
 */
        pdfForm.head = (args: { enrollment: string | number } | [enrollment: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: pdf.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    pdf.form = pdfForm
const enrollment = {
    pdf: Object.assign(pdf, pdf),
}

export default enrollment