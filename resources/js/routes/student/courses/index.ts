import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Student\CourseController::index
 * @see app/Http/Controllers/Student/CourseController.php:20
 * @route '/estudiante/cursos'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/estudiante/cursos',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Student\CourseController::index
 * @see app/Http/Controllers/Student/CourseController.php:20
 * @route '/estudiante/cursos'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\CourseController::index
 * @see app/Http/Controllers/Student/CourseController.php:20
 * @route '/estudiante/cursos'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Student\CourseController::index
 * @see app/Http/Controllers/Student/CourseController.php:20
 * @route '/estudiante/cursos'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Student\CourseController::index
 * @see app/Http/Controllers/Student/CourseController.php:20
 * @route '/estudiante/cursos'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Student\CourseController::index
 * @see app/Http/Controllers/Student/CourseController.php:20
 * @route '/estudiante/cursos'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Student\CourseController::index
 * @see app/Http/Controllers/Student/CourseController.php:20
 * @route '/estudiante/cursos'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Http\Controllers\Student\CourseController::show
 * @see app/Http/Controllers/Student/CourseController.php:57
 * @route '/estudiante/cursos/{section}'
 */
export const show = (args: { section: string | number } | [section: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/estudiante/cursos/{section}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Student\CourseController::show
 * @see app/Http/Controllers/Student/CourseController.php:57
 * @route '/estudiante/cursos/{section}'
 */
show.url = (args: { section: string | number } | [section: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { section: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    section: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        section: args.section,
                }

    return show.definition.url
            .replace('{section}', parsedArgs.section.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\CourseController::show
 * @see app/Http/Controllers/Student/CourseController.php:57
 * @route '/estudiante/cursos/{section}'
 */
show.get = (args: { section: string | number } | [section: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Student\CourseController::show
 * @see app/Http/Controllers/Student/CourseController.php:57
 * @route '/estudiante/cursos/{section}'
 */
show.head = (args: { section: string | number } | [section: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Student\CourseController::show
 * @see app/Http/Controllers/Student/CourseController.php:57
 * @route '/estudiante/cursos/{section}'
 */
    const showForm = (args: { section: string | number } | [section: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Student\CourseController::show
 * @see app/Http/Controllers/Student/CourseController.php:57
 * @route '/estudiante/cursos/{section}'
 */
        showForm.get = (args: { section: string | number } | [section: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Student\CourseController::show
 * @see app/Http/Controllers/Student/CourseController.php:57
 * @route '/estudiante/cursos/{section}'
 */
        showForm.head = (args: { section: string | number } | [section: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
const courses = {
    index: Object.assign(index, index),
show: Object.assign(show, show),
}

export default courses