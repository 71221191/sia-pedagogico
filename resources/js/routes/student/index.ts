import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import progress from './progress'
import courses from './courses'
import schedule7539d9 from './schedule'
import thesis from './thesis'
import enrollment from './enrollment'
/**
* @see \App\Http\Controllers\Student\ProgressController::schedule
 * @see app/Http/Controllers/Student/ProgressController.php:124
 * @route '/estudiante/mi-horario'
 */
export const schedule = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: schedule.url(options),
    method: 'get',
})

schedule.definition = {
    methods: ["get","head"],
    url: '/estudiante/mi-horario',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Student\ProgressController::schedule
 * @see app/Http/Controllers/Student/ProgressController.php:124
 * @route '/estudiante/mi-horario'
 */
schedule.url = (options?: RouteQueryOptions) => {
    return schedule.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\ProgressController::schedule
 * @see app/Http/Controllers/Student/ProgressController.php:124
 * @route '/estudiante/mi-horario'
 */
schedule.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: schedule.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Student\ProgressController::schedule
 * @see app/Http/Controllers/Student/ProgressController.php:124
 * @route '/estudiante/mi-horario'
 */
schedule.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: schedule.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Student\ProgressController::schedule
 * @see app/Http/Controllers/Student/ProgressController.php:124
 * @route '/estudiante/mi-horario'
 */
    const scheduleForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: schedule.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Student\ProgressController::schedule
 * @see app/Http/Controllers/Student/ProgressController.php:124
 * @route '/estudiante/mi-horario'
 */
        scheduleForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: schedule.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Student\ProgressController::schedule
 * @see app/Http/Controllers/Student/ProgressController.php:124
 * @route '/estudiante/mi-horario'
 */
        scheduleForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: schedule.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    schedule.form = scheduleForm
const student = {
    progress: Object.assign(progress, progress),
courses: Object.assign(courses, courses),
schedule: Object.assign(schedule, schedule7539d9),
thesis: Object.assign(thesis, thesis),
enrollment: Object.assign(enrollment, enrollment),
}

export default student