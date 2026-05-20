import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Student\ProgressController::pdf
 * @see app/Http/Controllers/Student/ProgressController.php:0
 * @route '/estudiante/mi-horario/pdf'
 */
export const pdf = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pdf.url(options),
    method: 'get',
})

pdf.definition = {
    methods: ["get","head"],
    url: '/estudiante/mi-horario/pdf',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Student\ProgressController::pdf
 * @see app/Http/Controllers/Student/ProgressController.php:0
 * @route '/estudiante/mi-horario/pdf'
 */
pdf.url = (options?: RouteQueryOptions) => {
    return pdf.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\ProgressController::pdf
 * @see app/Http/Controllers/Student/ProgressController.php:0
 * @route '/estudiante/mi-horario/pdf'
 */
pdf.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pdf.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Student\ProgressController::pdf
 * @see app/Http/Controllers/Student/ProgressController.php:0
 * @route '/estudiante/mi-horario/pdf'
 */
pdf.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: pdf.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Student\ProgressController::pdf
 * @see app/Http/Controllers/Student/ProgressController.php:0
 * @route '/estudiante/mi-horario/pdf'
 */
    const pdfForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: pdf.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Student\ProgressController::pdf
 * @see app/Http/Controllers/Student/ProgressController.php:0
 * @route '/estudiante/mi-horario/pdf'
 */
        pdfForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: pdf.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Student\ProgressController::pdf
 * @see app/Http/Controllers/Student/ProgressController.php:0
 * @route '/estudiante/mi-horario/pdf'
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
/**
* @see \App\Http\Controllers\Student\ProgressController::excel
 * @see app/Http/Controllers/Student/ProgressController.php:220
 * @route '/estudiante/mi-horario/excel'
 */
export const excel = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: excel.url(options),
    method: 'get',
})

excel.definition = {
    methods: ["get","head"],
    url: '/estudiante/mi-horario/excel',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Student\ProgressController::excel
 * @see app/Http/Controllers/Student/ProgressController.php:220
 * @route '/estudiante/mi-horario/excel'
 */
excel.url = (options?: RouteQueryOptions) => {
    return excel.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\ProgressController::excel
 * @see app/Http/Controllers/Student/ProgressController.php:220
 * @route '/estudiante/mi-horario/excel'
 */
excel.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: excel.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Student\ProgressController::excel
 * @see app/Http/Controllers/Student/ProgressController.php:220
 * @route '/estudiante/mi-horario/excel'
 */
excel.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: excel.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Student\ProgressController::excel
 * @see app/Http/Controllers/Student/ProgressController.php:220
 * @route '/estudiante/mi-horario/excel'
 */
    const excelForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: excel.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Student\ProgressController::excel
 * @see app/Http/Controllers/Student/ProgressController.php:220
 * @route '/estudiante/mi-horario/excel'
 */
        excelForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: excel.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Student\ProgressController::excel
 * @see app/Http/Controllers/Student/ProgressController.php:220
 * @route '/estudiante/mi-horario/excel'
 */
        excelForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: excel.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    excel.form = excelForm
const schedule = {
    pdf: Object.assign(pdf, pdf),
excel: Object.assign(excel, excel),
}

export default schedule