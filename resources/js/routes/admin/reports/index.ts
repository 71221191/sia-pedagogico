import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\ReportController::nomina
 * @see app/Http/Controllers/ReportController.php:165
 * @route '/admin/reportes/nomina-matricula/{courseSection}'
 */
export const nomina = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: nomina.url(args, options),
    method: 'get',
})

nomina.definition = {
    methods: ["get","head"],
    url: '/admin/reportes/nomina-matricula/{courseSection}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ReportController::nomina
 * @see app/Http/Controllers/ReportController.php:165
 * @route '/admin/reportes/nomina-matricula/{courseSection}'
 */
nomina.url = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { courseSection: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { courseSection: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    courseSection: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        courseSection: typeof args.courseSection === 'object'
                ? args.courseSection.id
                : args.courseSection,
                }

    return nomina.definition.url
            .replace('{courseSection}', parsedArgs.courseSection.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReportController::nomina
 * @see app/Http/Controllers/ReportController.php:165
 * @route '/admin/reportes/nomina-matricula/{courseSection}'
 */
nomina.get = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: nomina.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ReportController::nomina
 * @see app/Http/Controllers/ReportController.php:165
 * @route '/admin/reportes/nomina-matricula/{courseSection}'
 */
nomina.head = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: nomina.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ReportController::nomina
 * @see app/Http/Controllers/ReportController.php:165
 * @route '/admin/reportes/nomina-matricula/{courseSection}'
 */
    const nominaForm = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: nomina.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ReportController::nomina
 * @see app/Http/Controllers/ReportController.php:165
 * @route '/admin/reportes/nomina-matricula/{courseSection}'
 */
        nominaForm.get = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: nomina.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ReportController::nomina
 * @see app/Http/Controllers/ReportController.php:165
 * @route '/admin/reportes/nomina-matricula/{courseSection}'
 */
        nominaForm.head = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: nomina.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    nomina.form = nominaForm
/**
* @see \App\Http\Controllers\ReportController::cuadroEstadistico
 * @see app/Http/Controllers/ReportController.php:22
 * @route '/admin/reportes/cuadro-estadistico'
 */
export const cuadroEstadistico = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: cuadroEstadistico.url(options),
    method: 'get',
})

cuadroEstadistico.definition = {
    methods: ["get","head"],
    url: '/admin/reportes/cuadro-estadistico',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ReportController::cuadroEstadistico
 * @see app/Http/Controllers/ReportController.php:22
 * @route '/admin/reportes/cuadro-estadistico'
 */
cuadroEstadistico.url = (options?: RouteQueryOptions) => {
    return cuadroEstadistico.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReportController::cuadroEstadistico
 * @see app/Http/Controllers/ReportController.php:22
 * @route '/admin/reportes/cuadro-estadistico'
 */
cuadroEstadistico.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: cuadroEstadistico.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ReportController::cuadroEstadistico
 * @see app/Http/Controllers/ReportController.php:22
 * @route '/admin/reportes/cuadro-estadistico'
 */
cuadroEstadistico.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: cuadroEstadistico.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ReportController::cuadroEstadistico
 * @see app/Http/Controllers/ReportController.php:22
 * @route '/admin/reportes/cuadro-estadistico'
 */
    const cuadroEstadisticoForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: cuadroEstadistico.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ReportController::cuadroEstadistico
 * @see app/Http/Controllers/ReportController.php:22
 * @route '/admin/reportes/cuadro-estadistico'
 */
        cuadroEstadisticoForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: cuadroEstadistico.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ReportController::cuadroEstadistico
 * @see app/Http/Controllers/ReportController.php:22
 * @route '/admin/reportes/cuadro-estadistico'
 */
        cuadroEstadisticoForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: cuadroEstadistico.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    cuadroEstadistico.form = cuadroEstadisticoForm
const reports = {
    nomina: Object.assign(nomina, nomina),
cuadroEstadistico: Object.assign(cuadroEstadistico, cuadroEstadistico),
}

export default reports